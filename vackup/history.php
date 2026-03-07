<?php
/**
 * Vackup - Global History View
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/VackupEngine.php';

$db = VackupDatabase::getInstance();

// Get all vackups with project info
$result = $db->query("
    SELECT v.*, p.name as project_name, p.slug as project_slug
    FROM vackups v
    JOIN projects p ON p.id = v.project_id
    WHERE p.status = 'active'
    ORDER BY v.created_at DESC
    LIMIT 100
");

$vackups = [];
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $row['zip_size_formatted'] = formatBytes($row['zip_size']);
    $vackups[] = $row;
}

function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History — Vackup</title>
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
        .storage-badge {
            font-size: 0.7em;
            padding: 2px 6px;
            border-radius: 10px;
            margin-right: 3px;
        }
        .storage-badge.local { background: #e3f2fd; color: #1976d2; }
        .storage-badge.onedrive { background: #e8f5e9; color: #388e3c; }
        .storage-badge.gdrive { background: #fff3e0; color: #f57c00; }
        .storage-badge.github { background: #f3e5f5; color: #7b1fa2; }
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
                    <a class="nav-link active" href="/vackup/history"><i class="fas fa-history"></i> History</a>
                    <a class="nav-link" href="/vackup/settings"><i class="fas fa-cog"></i> Settings</a>
                </nav>
            </div>

            <div class="col-md-10 main-content">
                <h2 class="mb-4"><i class="fas fa-history me-2"></i>All Vackups</h2>

                <div class="card">
                    <div class="card-body">
                        <?php if (empty($vackups)): ?>
                        <p class="text-center text-muted py-4">No vackups yet.</p>
                        <?php else: ?>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Project</th>
                                    <th>Version</th>
                                    <th>Label</th>
                                    <th>Size</th>
                                    <th>Storage</th>
                                    <th>Commit</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($vackups as $v): ?>
                                <tr>
                                    <td>
                                        <a href="/vackup?project=<?= $v['project_id'] ?>">
                                            <?= htmlspecialchars($v['project_name']) ?>
                                        </a>
                                    </td>
                                    <td><span class="version-badge">v<?= htmlspecialchars($v['version']) ?></span></td>
                                    <td><?= htmlspecialchars($v['label']) ?></td>
                                    <td><small><?= $v['zip_size_formatted'] ?></small></td>
                                    <td>
                                        <?php if ($v['local_copied']): ?><span class="storage-badge local">Local</span><?php endif; ?>
                                        <?php if ($v['onedrive_copied']): ?><span class="storage-badge onedrive">OneDrive</span><?php endif; ?>
                                        <?php if ($v['gdrive_copied']): ?><span class="storage-badge gdrive">GDrive</span><?php endif; ?>
                                        <?php if ($v['github_pushed']): ?><span class="storage-badge github">GitHub</span><?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($v['github_commit_sha'])): ?>
                                        <code class="small"><?= substr($v['github_commit_sha'], 0, 7) ?></code>
                                        <?php else: ?>
                                        <small class="text-muted">-</small>
                                        <?php endif; ?>
                                    </td>
                                    <td><small><?= date('M j, Y g:ia', strtotime($v['created_at'])) ?></small></td>
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
