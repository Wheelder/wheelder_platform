<?php
/**
 * Vackup Dashboard - Version Control + Backup Platform
 * Progressive Development System
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/VackupEngine.php';

$engine = new VackupEngine();
$projects = $engine->getAllProjects();

// Get selected project
$selectedProjectId = $_GET['project'] ?? null;
$selectedProject = null;
$vackupHistory = [];
$nextVersion = '1.0';

if ($selectedProjectId) {
    $selectedProject = $engine->getProject($selectedProjectId);
    if ($selectedProject) {
        $vackupHistory = $engine->getVackupHistory($selectedProjectId, 20);
        $nextVersion = $engine->getNextVersion($selectedProjectId);
    }
}

// Handle form submissions
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create_vackup' && $selectedProject) {
        $version = $_POST['version'] ?? $nextVersion;
        $label = $_POST['label'] ?? '';
        $description = $_POST['description'] ?? '';
        $notes = $_POST['notes'] ?? '';
        
        $result = $engine->createVackup($selectedProjectId, $version, $label, $description, $notes);
        
        if ($result['success']) {
            $message = "Vackup v{$version} created successfully! ({$result['zip_size']}, {$result['files_count']} files)";
            $messageType = 'success';
            // Refresh data
            $vackupHistory = $engine->getVackupHistory($selectedProjectId, 20);
            $nextVersion = $engine->getNextVersion($selectedProjectId);
            $selectedProject = $engine->getProject($selectedProjectId);
        } else {
            $message = "Error: " . ($result['error'] ?? 'Unknown error');
            $messageType = 'danger';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vackup — Version Control + Backup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        body { background-color: #f8f9fa; }
        .sidebar {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            min-height: 100vh;
            color: #fff;
        }
        .sidebar h4 { color: #fff; font-weight: 700; }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 0;
            transition: all 0.2s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: #fff;
        }
        .sidebar .nav-link i { margin-right: 10px; width: 20px; }
        .main-content { padding: 30px; }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border-radius: 12px 12px 0 0 !important;
            font-weight: 600;
        }
        .btn-vackup {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 8px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-vackup:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(17, 153, 142, 0.4);
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
        .history-item {
            border-left: 3px solid #667eea;
            padding: 15px;
            margin-bottom: 15px;
            background: #fff;
            border-radius: 0 8px 8px 0;
            transition: transform 0.2s;
        }
        .history-item:hover {
            transform: translateX(5px);
        }
        .storage-badge {
            font-size: 0.75em;
            padding: 3px 8px;
            border-radius: 12px;
            margin-right: 5px;
        }
        .storage-badge.local { background: #e3f2fd; color: #1976d2; }
        .storage-badge.onedrive { background: #e8f5e9; color: #388e3c; }
        .storage-badge.gdrive { background: #fff3e0; color: #f57c00; }
        .storage-badge.github { background: #f3e5f5; color: #7b1fa2; }
        .project-card {
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent;
        }
        .project-card:hover {
            border-color: #667eea;
            transform: translateY(-3px);
        }
        .project-card.selected {
            border-color: #11998e;
            background: linear-gradient(135deg, rgba(17,153,142,0.05) 0%, rgba(56,239,125,0.05) 100%);
        }
        .logo-text {
            font-size: 1.8em;
            font-weight: 800;
            color: #fff;
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
                    <a class="nav-link active" href="/vackup">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <a class="nav-link" href="/vackup/projects">
                        <i class="fas fa-folder"></i> Projects
                    </a>
                    <a class="nav-link" href="/vackup/history">
                        <i class="fas fa-history"></i> History
                    </a>
                    <a class="nav-link" href="/vackup/settings">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                </nav>

                <hr class="my-4" style="border-color: rgba(255,255,255,0.2);">
                
                <h6 class="text-white-50 mb-3">QUICK PROJECTS</h6>
                <?php foreach (array_slice($projects, 0, 5) as $proj): ?>
                <a class="nav-link <?= $selectedProjectId == $proj['id'] ? 'active' : '' ?>" 
                   href="?project=<?= $proj['id'] ?>">
                    <i class="fas fa-code-branch"></i> <?= htmlspecialchars($proj['name']) ?>
                </a>
                <?php endforeach; ?>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <?php if ($message): ?>
                <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if (!$selectedProject): ?>
                <!-- No Project Selected - Show All Projects -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-rocket me-2"></i>Welcome to Vackup</h2>
                    <a href="/vackup/projects/new" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>New Project
                    </a>
                </div>

                <?php if (empty($projects)): ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-folder-open fa-4x text-muted mb-4"></i>
                        <h4>No Projects Yet</h4>
                        <p class="text-muted">Create your first project to start using Vackup</p>
                        <a href="/vackup/projects/new" class="btn btn-vackup">
                            <i class="fas fa-plus me-2"></i>Create First Project
                        </a>
                    </div>
                </div>
                <?php else: ?>
                <div class="row">
                    <?php foreach ($projects as $proj): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card project-card" onclick="window.location='?project=<?= $proj['id'] ?>'">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-code-branch me-2 text-primary"></i>
                                    <?= htmlspecialchars($proj['name']) ?>
                                </h5>
                                <p class="text-muted small mb-2"><?= htmlspecialchars($proj['project_path']) ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="version-badge">v<?= htmlspecialchars($proj['current_version']) ?></span>
                                    <small class="text-muted">
                                        <?= date('M j, Y', strtotime($proj['updated_at'])) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php else: ?>
                <!-- Project Selected - Show Vackup Form -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2>
                            <i class="fas fa-code-branch me-2"></i>
                            <?= htmlspecialchars($selectedProject['name']) ?>
                        </h2>
                        <small class="text-muted"><?= htmlspecialchars($selectedProject['project_path']) ?></small>
                    </div>
                    <span class="version-badge fs-5">Current: v<?= htmlspecialchars($selectedProject['current_version']) ?></span>
                </div>

                <div class="row">
                    <!-- Create Vackup Form -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-archive me-2"></i>Create New Vackup
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <input type="hidden" name="action" value="create_vackup">
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Version</label>
                                            <input type="text" class="form-control" name="version" 
                                                   value="<?= htmlspecialchars($nextVersion) ?>" required>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label">Label (Feature/Update Name)</label>
                                            <input type="text" class="form-control" name="label" 
                                                   placeholder="e.g., Login system completed" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <input type="text" class="form-control" name="description" 
                                               placeholder="Brief description of changes">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Release Notes (Markdown)</label>
                                        <textarea class="form-control" name="notes" rows="4" 
                                                  placeholder="- Added feature X&#10;- Fixed bug Y&#10;- Improved performance"></textarea>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-vackup btn-lg">
                                            <i class="fas fa-archive me-2"></i>Create Vackup v<?= htmlspecialchars($nextVersion) ?>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Storage Status -->
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-cloud me-2"></i>Storage Destinations
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-wrap gap-2">
                                    <?php if ($selectedProject['local_storage_path']): ?>
                                    <span class="badge bg-primary">
                                        <i class="fas fa-folder me-1"></i>Local
                                    </span>
                                    <?php endif; ?>
                                    <?php if ($selectedProject['auto_copy_onedrive'] && $selectedProject['onedrive_path']): ?>
                                    <span class="badge bg-info">
                                        <i class="fas fa-cloud me-1"></i>OneDrive
                                    </span>
                                    <?php endif; ?>
                                    <?php if ($selectedProject['auto_copy_gdrive'] && $selectedProject['google_drive_path']): ?>
                                    <span class="badge bg-warning text-dark">
                                        <i class="fab fa-google-drive me-1"></i>Google Drive
                                    </span>
                                    <?php endif; ?>
                                    <?php if ($selectedProject['auto_push_github'] && $selectedProject['github_repo']): ?>
                                    <span class="badge bg-dark">
                                        <i class="fab fa-github me-1"></i>GitHub
                                    </span>
                                    <?php endif; ?>
                                </div>
                                <hr>
                                <small class="text-muted">
                                    <strong>Local:</strong> <?= htmlspecialchars($selectedProject['local_storage_path'] ?: 'Not set') ?>
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Vackup History -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-history me-2"></i>Vackup History</span>
                                <span class="badge bg-light text-dark"><?= count($vackupHistory) ?> versions</span>
                            </div>
                            <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                                <?php if (empty($vackupHistory)): ?>
                                <p class="text-muted text-center py-4">
                                    <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                                    No vackups yet. Create your first one!
                                </p>
                                <?php else: ?>
                                <?php foreach ($vackupHistory as $vackup): ?>
                                <div class="history-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <span class="version-badge">v<?= htmlspecialchars($vackup['version']) ?></span>
                                            <strong class="ms-2"><?= htmlspecialchars($vackup['label']) ?></strong>
                                        </div>
                                        <small class="text-muted">
                                            <?= date('M j, Y g:ia', strtotime($vackup['created_at'])) ?>
                                        </small>
                                    </div>
                                    <?php if ($vackup['description']): ?>
                                    <p class="text-muted small mt-2 mb-2"><?= htmlspecialchars($vackup['description']) ?></p>
                                    <?php endif; ?>
                                    <div class="mt-2">
                                        <?php if ($vackup['local_copied']): ?>
                                        <span class="storage-badge local"><i class="fas fa-check"></i> Local</span>
                                        <?php endif; ?>
                                        <?php if ($vackup['onedrive_copied']): ?>
                                        <span class="storage-badge onedrive"><i class="fas fa-check"></i> OneDrive</span>
                                        <?php endif; ?>
                                        <?php if ($vackup['gdrive_copied']): ?>
                                        <span class="storage-badge gdrive"><i class="fas fa-check"></i> GDrive</span>
                                        <?php endif; ?>
                                        <?php if ($vackup['github_pushed']): ?>
                                        <span class="storage-badge github"><i class="fas fa-check"></i> GitHub</span>
                                        <?php endif; ?>
                                        <span class="text-muted small float-end">
                                            <?= $vackup['zip_size_formatted'] ?> • <?= $vackup['files_count'] ?> files
                                        </span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
