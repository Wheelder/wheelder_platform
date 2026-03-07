<?php
/**
 * Vackup - Edit Project
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/VackupEngine.php';

$engine = new VackupEngine();
$db = VackupDatabase::getInstance();

$projectId = (int)($_GET['id'] ?? 0);
$project = $engine->getProject($projectId);

if (!$project) {
    header('Location: /vackup/projects');
    exit;
}

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $db->prepare("
        UPDATE projects SET
            name = :name,
            project_path = :project_path,
            description = :description,
            local_storage_path = :local_storage_path,
            onedrive_path = :onedrive_path,
            google_drive_path = :google_drive_path,
            github_repo = :github_repo,
            github_token = :github_token,
            auto_push_github = :auto_push_github,
            auto_copy_onedrive = :auto_copy_onedrive,
            auto_copy_gdrive = :auto_copy_gdrive,
            exclude_patterns = :exclude_patterns,
            updated_at = datetime('now')
        WHERE id = :id
    ");

    $excludePatterns = array_filter(array_map('trim', explode("\n", $_POST['exclude_patterns'] ?? '')));

    $stmt->bindValue(':id', $projectId, SQLITE3_INTEGER);
    $stmt->bindValue(':name', $_POST['name'], SQLITE3_TEXT);
    $stmt->bindValue(':project_path', $_POST['project_path'], SQLITE3_TEXT);
    $stmt->bindValue(':description', $_POST['description'] ?? '', SQLITE3_TEXT);
    $stmt->bindValue(':local_storage_path', $_POST['local_storage_path'], SQLITE3_TEXT);
    $stmt->bindValue(':onedrive_path', $_POST['onedrive_path'] ?? '', SQLITE3_TEXT);
    $stmt->bindValue(':google_drive_path', $_POST['google_drive_path'] ?? '', SQLITE3_TEXT);
    $stmt->bindValue(':github_repo', $_POST['github_repo'] ?? '', SQLITE3_TEXT);
    $stmt->bindValue(':github_token', $_POST['github_token'] ?? '', SQLITE3_TEXT);
    $stmt->bindValue(':auto_push_github', isset($_POST['auto_push_github']) ? 1 : 0, SQLITE3_INTEGER);
    $stmt->bindValue(':auto_copy_onedrive', isset($_POST['auto_copy_onedrive']) ? 1 : 0, SQLITE3_INTEGER);
    $stmt->bindValue(':auto_copy_gdrive', isset($_POST['auto_copy_gdrive']) ? 1 : 0, SQLITE3_INTEGER);
    $stmt->bindValue(':exclude_patterns', json_encode($excludePatterns), SQLITE3_TEXT);

    if ($stmt->execute()) {
        $message = 'Project updated successfully';
        $messageType = 'success';
        $project = $engine->getProject($projectId);
    } else {
        $message = 'Failed to update project';
        $messageType = 'danger';
    }
}

$excludePatterns = json_decode($project['exclude_patterns'] ?? '[]', true) ?: [];
$excludePatternsText = implode("\n", $excludePatterns);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project — Vackup</title>
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
                    <a class="nav-link active" href="/vackup/projects"><i class="fas fa-folder"></i> Projects</a>
                    <a class="nav-link" href="/vackup/history"><i class="fas fa-history"></i> History</a>
                    <a class="nav-link" href="/vackup/settings"><i class="fas fa-cog"></i> Settings</a>
                </nav>
            </div>

            <div class="col-md-10 main-content">
                <?php if ($message): ?>
                <div class="alert alert-<?= $messageType ?> alert-dismissible fade show">
                    <?= htmlspecialchars($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-edit me-2"></i>Edit: <?= htmlspecialchars($project['name']) ?></h2>
                    <a href="/vackup/projects" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </div>

                <form method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-info-circle me-2"></i>Project Information
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Project Name *</label>
                                        <input type="text" class="form-control" name="name" required
                                               value="<?= htmlspecialchars($project['name']) ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Project Path *</label>
                                        <input type="text" class="form-control" name="project_path" required
                                               value="<?= htmlspecialchars($project['project_path']) ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" rows="2"><?= htmlspecialchars($project['description']) ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fab fa-github me-2"></i>GitHub Integration
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">GitHub Repository</label>
                                        <input type="text" class="form-control" name="github_repo"
                                               value="<?= htmlspecialchars($project['github_repo']) ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">GitHub Token</label>
                                        <input type="password" class="form-control" name="github_token"
                                               value="<?= htmlspecialchars($project['github_token']) ?>">
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="auto_push_github" 
                                               id="auto_push_github" <?= $project['auto_push_github'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="auto_push_github">
                                            Auto-create GitHub release
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-cloud me-2"></i>Storage Destinations
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Local Storage Path</label>
                                        <input type="text" class="form-control" name="local_storage_path"
                                               value="<?= htmlspecialchars($project['local_storage_path']) ?>">
                                    </div>
                                    <hr>
                                    <div class="mb-3">
                                        <label class="form-label">OneDrive Path</label>
                                        <input type="text" class="form-control" name="onedrive_path"
                                               value="<?= htmlspecialchars($project['onedrive_path']) ?>">
                                    </div>
                                    <div class="form-check mb-3">
                                        <input type="checkbox" class="form-check-input" name="auto_copy_onedrive" 
                                               id="auto_copy_onedrive" <?= $project['auto_copy_onedrive'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="auto_copy_onedrive">Auto-copy to OneDrive</label>
                                    </div>
                                    <hr>
                                    <div class="mb-3">
                                        <label class="form-label">Google Drive Path</label>
                                        <input type="text" class="form-control" name="google_drive_path"
                                               value="<?= htmlspecialchars($project['google_drive_path']) ?>">
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="auto_copy_gdrive" 
                                               id="auto_copy_gdrive" <?= $project['auto_copy_gdrive'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="auto_copy_gdrive">Auto-copy to Google Drive</label>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-filter me-2"></i>Exclude Patterns
                                </div>
                                <div class="card-body">
                                    <textarea class="form-control" name="exclude_patterns" rows="6"><?= htmlspecialchars($excludePatternsText) ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-vackup btn-lg">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
