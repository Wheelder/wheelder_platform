<?php
// WHY: ReleaseController manages all release/changelog operations — create, read, update, delete
// Follows the same pattern as BlogController for consistency across the codebase
require_once __DIR__ . '/Controller.php';

class ReleaseController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->ensureReleasesTable();
    }

    // WHY: create releases table on first use if it doesn't exist
    // Uses SQLite-compatible syntax (AUTOINCREMENT, TEXT instead of LONGTEXT, INTEGER instead of BOOLEAN)
    // JSON fields are stored as TEXT in SQLite and decoded in PHP
    private function ensureReleasesTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS releases (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            description TEXT,
            content TEXT,
            images TEXT DEFAULT '[]',
            videos TEXT DEFAULT '[]',
            version TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            is_published INTEGER DEFAULT 1
        )";
        
        try {
            $this->run_query($sql);
        } catch (Exception $e) {
            error_log('ReleaseController: Failed to create releases table: ' . $e->getMessage());
        }
    }

    // WHY: check if user is authenticated before allowing CMS access
    public function check_auth()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
            header('Location: /');
            exit;
        }
    }

    // WHY: get all published releases ordered by date (newest first)
    public function getAllReleases($limit = null)
    {
        $sql = "SELECT * FROM releases WHERE is_published = 1 ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        
        $stmt = $this->run_query($sql);
        $releases = [];
        
        // WHY: check if query succeeded before trying to fetch results
        if ($stmt && is_object($stmt)) {
            while ($row = $stmt->fetch_assoc()) {
                // WHY: decode JSON fields for images and videos
                $row['images'] = json_decode($row['images'] ?? '[]', true);
                $row['videos'] = json_decode($row['videos'] ?? '[]', true);
                $releases[] = $row;
            }
        }
        return $releases;
    }

    // WHY: get a single release by ID
    public function getReleaseById($id)
    {
        $id = intval($id);
        $sql = "SELECT * FROM releases WHERE id = $id";
        $stmt = $this->run_query($sql);
        $release = null;
        
        // WHY: check if query succeeded before trying to fetch results
        if ($stmt && is_object($stmt)) {
            $release = $stmt->fetch_assoc();
            
            if ($release) {
                $release['images'] = json_decode($release['images'] ?? '[]', true);
                $release['videos'] = json_decode($release['videos'] ?? '[]', true);
            }
        }
        return $release;
    }

    // WHY: create a new release with validation
    // Uses PDO prepared statements to safely handle content with quotes and special characters
    public function createRelease($title, $description, $content, $version = null, $images = [], $videos = [])
    {
        // WHY: validate required fields
        if (empty($title) || empty($content)) {
            return ['success' => false, 'message' => 'Title and content are required.'];
        }

        // WHY: sanitize title/description to prevent XSS (content kept as HTML for rich formatting)
        $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
        $version = $version ? htmlspecialchars($version, ENT_QUOTES, 'UTF-8') : null;
        
        $images_json = json_encode($images);
        $videos_json = json_encode($videos);

        try {
            $pdo = $this->connectDbPDO();
            $sql = "INSERT INTO releases (title, description, content, version, images, videos) 
                    VALUES (:title, :description, :content, :version, :images, :videos)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':description' => $description,
                ':content' => $content,
                ':version' => $version,
                ':images' => $images_json,
                ':videos' => $videos_json
            ]);
            return ['success' => true, 'message' => 'Release created successfully.'];
        } catch (Exception $e) {
            error_log('ReleaseController: Failed to create release: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to create release. Please try again.'];
        }
    }

    // WHY: update an existing release
    // Uses PDO prepared statements to safely handle content with quotes and special characters
    public function updateRelease($id, $title, $description, $content, $version = null, $images = [], $videos = [])
    {
        $id = intval($id);
        
        if (empty($title) || empty($content)) {
            return ['success' => false, 'message' => 'Title and content are required.'];
        }

        $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
        $version = $version ? htmlspecialchars($version, ENT_QUOTES, 'UTF-8') : null;
        
        $images_json = json_encode($images);
        $videos_json = json_encode($videos);

        try {
            $pdo = $this->connectDbPDO();
            $sql = "UPDATE releases 
                    SET title = :title, description = :description, content = :content, 
                        version = :version, images = :images, videos = :videos
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':description' => $description,
                ':content' => $content,
                ':version' => $version,
                ':images' => $images_json,
                ':videos' => $videos_json,
                ':id' => $id
            ]);
            return ['success' => true, 'message' => 'Release updated successfully.'];
        } catch (Exception $e) {
            error_log('ReleaseController: Failed to update release: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to update release. Please try again.'];
        }
    }

    // WHY: delete a release by ID
    public function deleteRelease($id)
    {
        $id = intval($id);
        $sql = "DELETE FROM releases WHERE id = $id";
        
        try {
            $this->run_query($sql);
            return ['success' => true, 'message' => 'Release deleted successfully.'];
        } catch (Exception $e) {
            error_log('ReleaseController: Failed to delete release: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to delete release. Please try again.'];
        }
    }

    // WHY: toggle publish status of a release
    public function togglePublish($id)
    {
        $id = intval($id);
        $sql = "UPDATE releases SET is_published = NOT is_published WHERE id = $id";
        
        try {
            $this->run_query($sql);
            return ['success' => true, 'message' => 'Release status updated.'];
        } catch (Exception $e) {
            error_log('ReleaseController: Failed to toggle publish status: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to update status.'];
        }
    }

    // WHY: get all releases including unpublished (for CMS)
    public function getAllReleasesForCMS()
    {
        $sql = "SELECT id, title, version, created_at, is_published FROM releases ORDER BY created_at DESC";
        $stmt = $this->run_query($sql);
        $releases = [];
        
        // WHY: check if query succeeded before trying to fetch results
        if ($stmt && is_object($stmt)) {
            while ($row = $stmt->fetch_assoc()) {
                $releases[] = $row;
            }
        }
        return $releases;
    }
}
?>
