<?php
/**
 * Vackup - Create New Project
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/VackupEngine.php';

$engine = new VackupEngine();
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $_POST['name'] ?? '',
        'project_path' => $_POST['project_path'] ?? '',
        'description' => $_POST['description'] ?? '',
        'local_storage_path' => $_POST['local_storage_path'] ?? DEFAULT_LOCAL_STORAGE,
        'onedrive_path' => $_POST['onedrive_path'] ?? '',
        'google_drive_path' => $_POST['google_drive_path'] ?? '',
        'github_repo' => $_POST['github_repo'] ?? '',
        'github_token' => $_POST['github_token'] ?? '',
        'auto_push_github' => isset($_POST['auto_push_github']) ? 1 : 0,
        'auto_copy_onedrive' => isset($_POST['auto_copy_onedrive']) ? 1 : 0,
        'auto_copy_gdrive' => isset($_POST['auto_copy_gdrive']) ? 1 : 0,
        'exclude_patterns' => array_filter(array_map('trim', explode("\n", $_POST['exclude_patterns'] ?? '')))
    ];

    if (empty($data['name']) || empty($data['project_path'])) {
        $message = 'Project name and path are required';
        $messageType = 'danger';
    } else {
        $projectId = $engine->createProject($data);
        if ($projectId) {
            header('Location: /vackup?project=' . $projectId);
            exit;
        } else {
            $message = 'Failed to create project';
            $messageType = 'danger';
        }
    }
}

// Default exclude patterns
$defaultExcludes = implode("\n", [
    '.git',
    'node_modules',
    'vendor',
    '.env',
    '*.log',
    '.DS_Store',
    '__pycache__'
]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Project — Vackup</title>
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
        .btn-vackup:hover { color: #fff; transform: translateY(-2px); }
        .section-title {
            font-weight: 600;
            color: #667eea;
            border-bottom: 2px solid #667eea;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
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

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <?php if ($message): ?>
                <div class="alert alert-<?= $messageType ?> alert-dismissible fade show">
                    <?= htmlspecialchars($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-plus-circle me-2"></i>Create New Project</h2>
                    <a href="/vackup/projects" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Projects
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
                                               placeholder="e.g., autowork-beta">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Project Path *</label>
                                        <input type="text" class="form-control" name="project_path" required
                                               placeholder="e.g., C:\Projects\autowork">
                                        <small class="text-muted">Full path to the project directory</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" rows="2"
                                                  placeholder="Brief project description"></textarea>
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
                                               placeholder="e.g., username/repo-name">
                                        <small class="text-muted">Format: owner/repository</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">GitHub Personal Access Token</label>
                                        <input type="password" class="form-control" name="github_token"
                                               placeholder="ghp_xxxxxxxxxxxx">
                                        <small class="text-muted">Token with repo permissions</small>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="auto_push_github" id="auto_push_github">
                                        <label class="form-check-label" for="auto_push_github">
                                            Auto-create GitHub release on each Vackup
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
                                               value="<?= htmlspecialchars(DEFAULT_LOCAL_STORAGE) ?>">
                                        <small class="text-muted">Primary backup location</small>
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">OneDrive Folder Path</label>
                                        <input type="text" class="form-control" name="onedrive_path"
                                               placeholder="e.g., C:\Users\you\OneDrive\Vackups">
                                    </div>
                                    <div class="form-check mb-3">
                                        <input type="checkbox" class="form-check-input" name="auto_copy_onedrive" id="auto_copy_onedrive" checked>
                                        <label class="form-check-label" for="auto_copy_onedrive">
                                            Auto-copy to OneDrive
                                        </label>
                                    </div>

                                    <hr>

                                    <div class="mb-3">
                                        <label class="form-label">Google Drive Folder Path</label>
                                        <input type="text" class="form-control" name="google_drive_path"
                                               placeholder="e.g., G:\My Drive\Vackups">
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="auto_copy_gdrive" id="auto_copy_gdrive">
                                        <label class="form-check-label" for="auto_copy_gdrive">
                                            Auto-copy to Google Drive
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-filter me-2"></i>Exclude Patterns
                                </div>
                                <div class="card-body">
                                    <textarea class="form-control" name="exclude_patterns" rows="6"
                                              placeholder="One pattern per line"><?= htmlspecialchars($defaultExcludes) ?></textarea>
                                    <small class="text-muted">Files/folders to exclude from backup (one per line)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-vackup btn-lg">
                            <i class="fas fa-plus-circle me-2"></i>Create Project
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
