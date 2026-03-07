<?php
/**
 * Vackup Projects Management
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/VackupEngine.php';

$engine = new VackupEngine();
$projects = $engine->getAllProjects();

$message = '';
$messageType = '';

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $projectId = (int)$_POST['project_id'];
    $db = VackupDatabase::getInstance();
    $db->exec("UPDATE projects SET status = 'deleted' WHERE id = {$projectId}");
    $message = 'Project deleted successfully';
    $messageType = 'success';
    $projects = $engine->getAllProjects();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects — Vackup</title>
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
        .logo-text {
            font-size: 1.8em;
            font-weight: 800;
            color: #fff;
        }
        .version-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85em;
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
                    <h2><i class="fas fa-folder me-2"></i>All Projects</h2>
                    <a href="/vackup/projects/new" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>New Project
                    </a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <?php if (empty($projects)): ?>
                        <p class="text-center text-muted py-4">No projects yet.</p>
                        <?php else: ?>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Project</th>
                                    <th>Path</th>
                                    <th>Version</th>
                                    <th>Storage</th>
                                    <th>Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($projects as $proj): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($proj['name']) ?></strong>
                                    </td>
                                    <td><small class="text-muted"><?= htmlspecialchars($proj['project_path']) ?></small></td>
                                    <td><span class="version-badge">v<?= htmlspecialchars($proj['current_version']) ?></span></td>
                                    <td>
                                        <?php if ($proj['local_storage_path']): ?><i class="fas fa-folder text-primary me-1" title="Local"></i><?php endif; ?>
                                        <?php if ($proj['onedrive_path']): ?><i class="fas fa-cloud text-info me-1" title="OneDrive"></i><?php endif; ?>
                                        <?php if ($proj['google_drive_path']): ?><i class="fab fa-google-drive text-warning me-1" title="Google Drive"></i><?php endif; ?>
                                        <?php if ($proj['github_repo']): ?><i class="fab fa-github text-dark me-1" title="GitHub"></i><?php endif; ?>
                                    </td>
                                    <td><small><?= date('M j, Y', strtotime($proj['updated_at'])) ?></small></td>
                                    <td>
                                        <a href="/vackup?project=<?= $proj['id'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-archive"></i>
                                        </a>
                                        <a href="/vackup/projects/edit?id=<?= $proj['id'] ?>" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" class="d-inline" onsubmit="return confirm('Delete this project?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="project_id" value="<?= $proj['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
