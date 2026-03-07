<?php
/**
 * Vackup - Global Settings
 * WHY: Manage global configuration for Vackup (storage paths, GitHub tokens)
 */

// WHY: Prevent session conflicts when included from router
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// WHY: Load database connection and config constants
require_once __DIR__ . '/config/config.php';

// WHY: Declare $db in global scope so getSetting/setSetting functions can access it
// When this file is require'd from a router closure, variables are local to that closure.
// The functions use 'global $db', which looks in the global scope — so we must put $db there.
global $db;
try {
    $db = VackupDatabase::getInstance();
} catch (Exception $e) {
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}

$message = '';
$messageType = '';

/**
 * Get setting value from database
 * WHY: Centralized settings retrieval with fallback to default
 * @param string $key Setting key
 * @param mixed $default Default value if not found
 * @return mixed Setting value or default
 */
function getSetting($key, $default = '') {
    global $db;
    try {
        $stmt = $db->prepare("SELECT value FROM settings WHERE key = :key");
        $stmt->bindValue(':key', $key, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['value'] : $default;
    } catch (Exception $e) {
        // WHY: Return default on error to prevent page break
        error_log("getSetting error for key '$key': " . $e->getMessage());
        return $default;
    }
}

/**
 * Set or update setting in database
 * WHY: Upsert pattern - update if exists, insert if new
 * @param string $key Setting key
 * @param string $value Setting value
 * @return bool Success status
 */
function setSetting($key, $value) {
    global $db;
    try {
        $existing = getSetting($key, null);
        if ($existing !== null) {
            $stmt = $db->prepare("UPDATE settings SET value = :value, updated_at = datetime('now') WHERE key = :key");
        } else {
            $stmt = $db->prepare("INSERT INTO settings (key, value) VALUES (:key, :value)");
        }
        $stmt->bindValue(':key', $key, PDO::PARAM_STR);
        $stmt->bindValue(':value', $value, PDO::PARAM_STR);
        return $stmt->execute();
    } catch (Exception $e) {
        error_log("setSetting error for key '$key': " . $e->getMessage());
        return false;
    }
}

// WHY: Handle form submission for saving settings
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = true;
    $success = $success && setSetting('default_local_storage', $_POST['default_local_storage'] ?? '');
    $success = $success && setSetting('default_onedrive_path', $_POST['default_onedrive_path'] ?? '');
    $success = $success && setSetting('default_gdrive_path', $_POST['default_gdrive_path'] ?? '');
    $success = $success && setSetting('github_default_token', $_POST['github_default_token'] ?? '');
    
    if ($success) {
        $message = 'Settings saved successfully';
        $messageType = 'success';
    } else {
        $message = 'Error saving some settings. Check error log.';
        $messageType = 'danger';
    }
}

// WHY: Load current settings for display in form
$settings = [
    'default_local_storage' => getSetting('default_local_storage', DEFAULT_LOCAL_STORAGE),
    'default_onedrive_path' => getSetting('default_onedrive_path', ''),
    'default_gdrive_path' => getSetting('default_gdrive_path', ''),
    'github_default_token' => getSetting('github_default_token', ''),
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings — Vackup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        body { background-color: #f8f9fa; }
        .sidebar {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            min-height: 100vh;
            color: #fff;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 0;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: #fff;
        }
        .sidebar .nav-link i { margin-right: 10px; width: 20px; }
        .main-content { padding: 30px; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border-radius: 12px 12px 0 0 !important;
            font-weight: 600;
        }
        .logo-text {
            font-size: 1.8em;
            font-weight: 800;
            color: #fff;
        }
        .btn-vackup {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
            color: #fff;
            font-weight: 600;
        }
        .btn-vackup:hover { color: #fff; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar p-4">
                <div class="mb-4">
                    <span class="logo-text">Vackup</span>
                    <small class="d-block text-white-50">Version Control + Backup</small>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="/vackup"><i class="fas fa-home"></i> Dashboard</a>
                    <a class="nav-link" href="/vackup/projects"><i class="fas fa-folder"></i> Projects</a>
                    <a class="nav-link" href="/vackup/history"><i class="fas fa-history"></i> History</a>
                    <a class="nav-link active" href="/vackup/settings"><i class="fas fa-cog"></i> Settings</a>
                </nav>
            </div>

            <div class="col-md-10 main-content">
                <?php if ($message): ?>
                <div class="alert alert-<?= $messageType ?> alert-dismissible fade show">
                    <?= htmlspecialchars($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <h2 class="mb-4"><i class="fas fa-cog me-2"></i>Global Settings</h2>

                <form method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-folder me-2"></i>Default Storage Paths
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Default Local Storage</label>
                                        <input type="text" class="form-control" name="default_local_storage"
                                               value="<?= htmlspecialchars($settings['default_local_storage']) ?>">
                                        <small class="text-muted">Used when creating new projects</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Default OneDrive Path</label>
                                        <input type="text" class="form-control" name="default_onedrive_path"
                                               value="<?= htmlspecialchars($settings['default_onedrive_path']) ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Default Google Drive Path</label>
                                        <input type="text" class="form-control" name="default_gdrive_path"
                                               value="<?= htmlspecialchars($settings['default_gdrive_path']) ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fab fa-github me-2"></i>GitHub Settings
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Default GitHub Token</label>
                                        <input type="password" class="form-control" name="github_default_token"
                                               value="<?= htmlspecialchars($settings['github_default_token']) ?>">
                                        <small class="text-muted">Used as default for new projects</small>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-database me-2"></i>Database
                                </div>
                                <div class="card-body">
                                    <p><strong>Database:</strong> SQLite</p>
                                    <p><strong>Location:</strong> <code>/vackup/config/vackup.db</code></p>
                                    <a href="/vackup/setup?action=cr" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-sync me-1"></i>Run Migrations
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-vackup btn-lg">
                            <i class="fas fa-save me-2"></i>Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
