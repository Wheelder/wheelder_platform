<?php
/**
 * StorageManager - Handles file storage to multiple destinations
 * Supports: Local filesystem, OneDrive (folder sync), Google Drive (folder sync)
 */

class StorageManager
{
    private $db;

    public function __construct()
    {
        $this->db = VackupDatabase::getInstance();
    }

    /**
     * Copy file to all configured storage locations
     */
    public function copyToAllStorages($sourcePath, $filename, $project, $vackupId)
    {
        $results = [];

        // Local storage (primary)
        if (!empty($project['local_storage_path'])) {
            $results['local'] = $this->copyToPath($sourcePath, $project['local_storage_path'], $filename);
            if ($results['local']['success']) {
                $this->updateVackupStatus($vackupId, 'local_copied', 1);
            }
        }

        // OneDrive (folder sync)
        if (!empty($project['onedrive_path'])) {
            $results['onedrive'] = $this->copyToPath($sourcePath, $project['onedrive_path'], $filename);
            if ($results['onedrive']['success']) {
                $this->updateVackupStatus($vackupId, 'onedrive_copied', 1);
            }
        }

        // Google Drive (folder sync)
        if (!empty($project['google_drive_path'])) {
            $results['gdrive'] = $this->copyToPath($sourcePath, $project['google_drive_path'], $filename);
            if ($results['gdrive']['success']) {
                $this->updateVackupStatus($vackupId, 'gdrive_copied', 1);
            }
        }

        return $results;
    }

    /**
     * Copy file to a specific path
     */
    public function copyToPath($sourcePath, $destDir, $filename)
    {
        // Normalize path separators
        $destDir = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $destDir);
        $destDir = rtrim($destDir, DIRECTORY_SEPARATOR);

        // Create directory if it doesn't exist
        if (!is_dir($destDir)) {
            if (!mkdir($destDir, 0755, true)) {
                return [
                    'success' => false,
                    'error' => "Cannot create directory: {$destDir}"
                ];
            }
        }

        // Check if source exists
        if (!file_exists($sourcePath)) {
            return [
                'success' => false,
                'error' => "Source file not found: {$sourcePath}"
            ];
        }

        $destPath = $destDir . DIRECTORY_SEPARATOR . $filename;

        // Copy file
        if (copy($sourcePath, $destPath)) {
            return [
                'success' => true,
                'path' => $destPath,
                'size' => filesize($destPath)
            ];
        }

        return [
            'success' => false,
            'error' => "Failed to copy file to: {$destPath}"
        ];
    }

    /**
     * Update vackup storage status
     */
    private function updateVackupStatus($vackupId, $column, $value)
    {
        $this->db->exec("UPDATE vackups SET {$column} = {$value} WHERE id = {$vackupId}");
    }

    /**
     * Get storage statistics for a project
     */
    public function getStorageStats($projectId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total_vackups,
                SUM(zip_size) as total_size,
                SUM(local_copied) as local_count,
                SUM(onedrive_copied) as onedrive_count,
                SUM(gdrive_copied) as gdrive_count,
                SUM(github_pushed) as github_count
            FROM vackups 
            WHERE project_id = :project_id
        ");
        $stmt->bindValue(':project_id', $projectId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        return $result->fetchArray(SQLITE3_ASSOC);
    }

    /**
     * Verify storage paths are accessible
     */
    public function verifyStoragePaths($paths)
    {
        $results = [];
        
        foreach ($paths as $name => $path) {
            if (empty($path)) {
                $results[$name] = ['accessible' => false, 'reason' => 'Path not configured'];
                continue;
            }

            $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
            
            if (is_dir($path)) {
                $results[$name] = [
                    'accessible' => true,
                    'writable' => is_writable($path),
                    'path' => $path
                ];
            } else {
                // Try to create it
                if (@mkdir($path, 0755, true)) {
                    $results[$name] = [
                        'accessible' => true,
                        'writable' => true,
                        'path' => $path,
                        'created' => true
                    ];
                } else {
                    $results[$name] = [
                        'accessible' => false,
                        'reason' => 'Directory does not exist and cannot be created'
                    ];
                }
            }
        }

        return $results;
    }

    /**
     * List files in a storage location
     */
    public function listStorageFiles($path, $pattern = '*.zip')
    {
        if (!is_dir($path)) {
            return [];
        }

        $files = glob(rtrim($path, '/\\') . DIRECTORY_SEPARATOR . $pattern);
        $result = [];

        foreach ($files as $file) {
            $result[] = [
                'name' => basename($file),
                'path' => $file,
                'size' => filesize($file),
                'modified' => filemtime($file)
            ];
        }

        // Sort by modified date, newest first
        usort($result, function($a, $b) {
            return $b['modified'] - $a['modified'];
        });

        return $result;
    }
}
