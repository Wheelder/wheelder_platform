<?php
/**
 * VackupEngine - Core backup and versioning logic
 * Handles zip creation, version management, and file operations
 */

require_once dirname(__DIR__) . '/config/config.php';

class VackupEngine
{
    private $db;
    private $projectPath;
    private $projectName;
    private $excludePatterns = [];

    public function __construct()
    {
        $this->db = VackupDatabase::getInstance();
    }

    /**
     * Create a new Vackup (Version + Backup)
     */
    public function createVackup($projectId, $version, $label, $description = '', $notes = '')
    {
        // WHY: Retrieve project configuration from database
        $project = $this->getProject($projectId);
        if (!$project) {
            return ['success' => false, 'error' => 'Project not found in database'];
        }

        $this->projectPath = $project['project_path'];
        $this->projectName = $project['name'];
        $this->excludePatterns = json_decode($project['exclude_patterns'] ?? '[]', true) ?: [];

        // WHY: Validate project directory exists before attempting backup
        if (!is_dir($this->projectPath)) {
            return [
                'success' => false, 
                'error' => "Project directory not found: {$this->projectPath}. Please update the project path in project settings."
            ];
        }

        // WHY: Validate project directory is readable
        if (!is_readable($this->projectPath)) {
            return [
                'success' => false,
                'error' => "Project directory is not readable: {$this->projectPath}. Check file permissions."
            ];
        }

        // WHY: Generate semantic filename following convention: {project}-v{version}-{label}.zip
        $safeLabel = $this->sanitizeFilename($label);
        $zipFilename = "{$this->projectName}-v{$version}-{$safeLabel}.zip";

        // WHY: Determine storage location with fallback to default
        $localPath = $project['local_storage_path'] ?: DEFAULT_LOCAL_STORAGE;
        $zipPath = rtrim($localPath, '/\\') . '/' . $zipFilename;

        // WHY: Create storage directory if it doesn't exist, with error handling
        if (!is_dir($localPath)) {
            if (!@mkdir($localPath, 0755, true)) {
                return [
                    'success' => false,
                    'error' => "Failed to create storage directory: {$localPath}. Check parent directory permissions."
                ];
            }
        }

        // WHY: Validate storage directory is writable
        if (!is_writable($localPath)) {
            return [
                'success' => false,
                'error' => "Storage directory is not writable: {$localPath}. Check directory permissions (needs 755 or 775)."
            ];
        }

        // Create the zip
        $result = $this->createZip($zipPath);
        if (!$result['success']) {
            return $result;
        }

        // Get zip info
        $zipSize = filesize($zipPath);
        $filesCount = $result['files_count'];

        // Save to database
        $stmt = $this->db->prepare("
            INSERT INTO vackups (project_id, version, label, description, notes, zip_filename, zip_size, zip_path, files_count, local_copied)
            VALUES (:project_id, :version, :label, :description, :notes, :zip_filename, :zip_size, :zip_path, :files_count, 1)
        ");
        $stmt->bindValue(':project_id', $projectId, PDO::PARAM_INT);
        $stmt->bindValue(':version', $version, PDO::PARAM_STR);
        $stmt->bindValue(':label', $label, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->bindValue(':notes', $notes, PDO::PARAM_STR);
        $stmt->bindValue(':zip_filename', $zipFilename, PDO::PARAM_STR);
        $stmt->bindValue(':zip_size', $zipSize, PDO::PARAM_INT);
        $stmt->bindValue(':zip_path', $zipPath, PDO::PARAM_STR);
        $stmt->bindValue(':files_count', $filesCount, PDO::PARAM_INT);
        $stmt->execute();

        $vackupId = $this->db->lastInsertRowID();

        // Update project's current version
        $this->db->exec("UPDATE projects SET current_version = '{$version}', updated_at = datetime('now') WHERE id = {$projectId}");

        // Copy to additional storage locations
        $copyResults = [];
        
        if ($project['auto_copy_onedrive'] && !empty($project['onedrive_path'])) {
            $copyResults['onedrive'] = $this->copyToStorage($zipPath, $project['onedrive_path'], $zipFilename, $vackupId, 'onedrive');
        }

        if ($project['auto_copy_gdrive'] && !empty($project['google_drive_path'])) {
            $copyResults['gdrive'] = $this->copyToStorage($zipPath, $project['google_drive_path'], $zipFilename, $vackupId, 'gdrive');
        }

        // Push to GitHub if enabled
        $githubResult = null;
        if ($project['auto_push_github'] && !empty($project['github_repo']) && !empty($project['github_token'])) {
            require_once __DIR__ . '/GitHubClient.php';
            $github = new GitHubClient($project['github_token']);
            $githubResult = $github->createRelease($project['github_repo'], $version, $label, $notes);
            
            if ($githubResult['success']) {
                $this->db->exec("UPDATE vackups SET github_pushed = 1, github_tag = 'v{$version}' WHERE id = {$vackupId}");
            }
        }

        // Save release notes
        if (!empty($notes)) {
            $stmt = $this->db->prepare("INSERT INTO release_notes (vackup_id, content) VALUES (:vackup_id, :content)");
            $stmt->bindValue(':vackup_id', $vackupId, PDO::PARAM_INT);
            $stmt->bindValue(':content', $notes, PDO::PARAM_STR);
            $stmt->execute();
        }

        return [
            'success' => true,
            'vackup_id' => $vackupId,
            'zip_filename' => $zipFilename,
            'zip_path' => $zipPath,
            'zip_size' => $this->formatBytes($zipSize),
            'files_count' => $filesCount,
            'copy_results' => $copyResults,
            'github_result' => $githubResult
        ];
    }

    /**
     * Create zip archive of the project
     */
    private function createZip($zipPath)
    {
        if (!is_dir($this->projectPath)) {
            return ['success' => false, 'error' => 'Project directory not found: ' . $this->projectPath];
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return ['success' => false, 'error' => 'Failed to create zip file'];
        }

        $filesCount = 0;
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->projectPath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $file) {
            if ($file->isDir()) continue;

            $filePath = $file->getPathname();
            $relativePath = substr($filePath, strlen($this->projectPath) + 1);

            // Check exclusions
            if ($this->shouldExclude($relativePath)) continue;

            $zip->addFile($filePath, $relativePath);
            $filesCount++;
        }

        $zip->close();

        return ['success' => true, 'files_count' => $filesCount];
    }

    /**
     * Check if file should be excluded
     */
    private function shouldExclude($relativePath)
    {
        global $VACKUP_EXCLUDE_PATTERNS;
        $patterns = array_merge($VACKUP_EXCLUDE_PATTERNS, $this->excludePatterns);

        foreach ($patterns as $pattern) {
            // Directory match
            if (strpos($relativePath, $pattern) !== false) {
                return true;
            }
            // Wildcard match
            if (fnmatch($pattern, $relativePath)) {
                return true;
            }
            if (fnmatch($pattern, basename($relativePath))) {
                return true;
            }
        }
        return false;
    }

    /**
     * Copy zip to additional storage location
     */
    private function copyToStorage($sourcePath, $destDir, $filename, $vackupId, $type)
    {
        if (!is_dir($destDir)) {
            if (!mkdir($destDir, 0755, true)) {
                return ['success' => false, 'error' => "Cannot create directory: $destDir"];
            }
        }

        $destPath = rtrim($destDir, '/\\') . '/' . $filename;
        
        if (copy($sourcePath, $destPath)) {
            $column = $type === 'onedrive' ? 'onedrive_copied' : 'gdrive_copied';
            $this->db->exec("UPDATE vackups SET {$column} = 1 WHERE id = {$vackupId}");
            return ['success' => true, 'path' => $destPath];
        }

        return ['success' => false, 'error' => "Failed to copy to $destDir"];
    }

    /**
     * Get project by ID
     */
    public function getProject($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM projects WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get all projects
     */
    public function getAllProjects()
    {
        $result = $this->db->query("SELECT * FROM projects WHERE status = 'active' ORDER BY updated_at DESC");
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new project
     */
    public function createProject($data)
    {
        $slug = $this->generateSlug($data['name']);
        
        $stmt = $this->db->prepare("
            INSERT INTO projects (name, slug, project_path, description, github_repo, github_token, 
                local_storage_path, onedrive_path, google_drive_path, exclude_patterns,
                auto_push_github, auto_copy_onedrive, auto_copy_gdrive)
            VALUES (:name, :slug, :project_path, :description, :github_repo, :github_token,
                :local_storage_path, :onedrive_path, :google_drive_path, :exclude_patterns,
                :auto_push_github, :auto_copy_onedrive, :auto_copy_gdrive)
        ");

        $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindValue(':slug', $slug, PDO::PARAM_STR);
        $stmt->bindValue(':project_path', $data['project_path'], PDO::PARAM_STR);
        $stmt->bindValue(':description', $data['description'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':github_repo', $data['github_repo'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':github_token', $data['github_token'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':local_storage_path', $data['local_storage_path'] ?? DEFAULT_LOCAL_STORAGE, PDO::PARAM_STR);
        $stmt->bindValue(':onedrive_path', $data['onedrive_path'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':google_drive_path', $data['google_drive_path'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':exclude_patterns', json_encode($data['exclude_patterns'] ?? []), PDO::PARAM_STR);
        $stmt->bindValue(':auto_push_github', $data['auto_push_github'] ?? 0, PDO::PARAM_INT);
        $stmt->bindValue(':auto_copy_onedrive', $data['auto_copy_onedrive'] ?? 1, PDO::PARAM_INT);
        $stmt->bindValue(':auto_copy_gdrive', $data['auto_copy_gdrive'] ?? 0, PDO::PARAM_INT);

        $stmt->execute();
        return $this->db->lastInsertRowID();
    }

    /**
     * Get vackup history for a project
     */
    public function getVackupHistory($projectId, $limit = 50)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM vackups 
            WHERE project_id = :project_id 
            ORDER BY created_at DESC 
            LIMIT :limit
        ");
        $stmt->bindValue(':project_id', $projectId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        $vackups = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['zip_size_formatted'] = $this->formatBytes($row['zip_size']);
            $vackups[] = $row;
        }
        return $vackups;
    }

    /**
     * Get next version number
     */
    public function getNextVersion($projectId, $type = 'minor')
    {
        $project = $this->getProject($projectId);
        $current = $project['current_version'] ?? '0.0';
        
        $parts = explode('.', $current);
        $major = (int)($parts[0] ?? 0);
        $minor = (int)($parts[1] ?? 0);

        if ($type === 'major') {
            $major++;
            $minor = 0;
        } else {
            $minor++;
        }

        return "$major.$minor";
    }

    /**
     * Helper: Generate URL-safe slug
     */
    private function generateSlug($name)
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }

    /**
     * Helper: Sanitize filename
     */
    private function sanitizeFilename($name)
    {
        $name = preg_replace('/[^a-zA-Z0-9\-_ ]/', '', $name);
        $name = str_replace(' ', '-', $name);
        return $name;
    }

    /**
     * Helper: Format bytes to human readable
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
