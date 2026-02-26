<?php
// WHY: Releases CMS allows authenticated admins to create, edit, and delete releases
// Similar to blog CMS but tailored for release management with version tracking

$path = $_SERVER['DOCUMENT_ROOT'];
$host = $_SERVER['HTTP_HOST'];

if ($host === "localhost") {
    $dir = '/wheelder';
    require_once $path . $dir . '/apps/edu/controllers/ReleaseController.php';
} else {
    require_once $path . '/apps/edu/controllers/ReleaseController.php';
}

$releaseController = new ReleaseController();
$releaseController->check_auth();

$action = $_GET['action'] ?? 'list';
$editId = $_GET['edit'] ?? null;
$deleteId = $_GET['delete'] ?? null;
$message = '';
$messageType = '';

// WHY: handle delete action
if ($deleteId) {
    $result = $releaseController->deleteRelease($deleteId);
    $message = $result['message'];
    $messageType = $result['success'] ? 'success' : 'danger';
    $action = 'list';
}

// WHY: handle form submission for create/update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $content = $_POST['content'] ?? '';
    $version = $_POST['version'] ?? '';
    $images = isset($_POST['images']) ? array_filter(explode("\n", $_POST['images'])) : [];
    $videos = isset($_POST['videos']) ? array_filter(explode("\n", $_POST['videos'])) : [];

    if ($editId) {
        $result = $releaseController->updateRelease($editId, $title, $description, $content, $version, $images, $videos);
    } else {
        $result = $releaseController->createRelease($title, $description, $content, $version, $images, $videos);
    }

    $message = $result['message'];
    $messageType = $result['success'] ? 'success' : 'danger';
    
    if ($result['success']) {
        $action = 'list';
    }
}

// WHY: get release data if editing
$release = null;
if ($editId && $action === 'edit') {
    $release = $releaseController->getReleaseById($editId);
    if (!$release) {
        $message = 'Release not found.';
        $messageType = 'danger';
        $action = 'list';
    }
}

// WHY: get all releases for list view
$releases = $releaseController->getAllReleasesForCMS();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Releases CMS - Wheelder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar {
            background-color: #212529;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .sidebar {
            background-color: #fff;
            border-right: 1px solid #dee2e6;
            min-height: 100vh;
        }

        .sidebar .nav-link {
            color: #212529;
            padding: 0.75rem 1.5rem;
            border-left: 3px solid transparent;
        }

        .sidebar .nav-link:hover {
            background-color: #f8f9fa;
            border-left-color: #0d6efd;
        }

        .sidebar .nav-link.active {
            background-color: #e7f1ff;
            border-left-color: #0d6efd;
            color: #0d6efd;
            font-weight: 600;
        }

        .content {
            padding: 2rem;
        }

        .form-section {
            background-color: #fff;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .releases-table {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .releases-table table {
            margin-bottom: 0;
        }

        .releases-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .badge-published {
            background-color: #28a745;
        }

        .badge-draft {
            background-color: #6c757d;
        }

        .btn-action {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
        }

        .note-editor.note-frame {
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }

        .form-text {
            font-size: 0.85rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <i class="fas fa-rocket"></i> Wheelder Releases CMS
            </a>
            <div>
                <a href="/releases" class="btn btn-sm btn-outline-light">
                    <i class="fas fa-eye"></i> View Releases
                </a>
                <a href="/logout" class="btn btn-sm btn-outline-light ms-2">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 sidebar">
                <div class="nav flex-column mt-3">
                    <a class="nav-link <?php echo $action === 'list' ? 'active' : ''; ?>" href="?action=list">
                        <i class="fas fa-list"></i> All Releases
                    </a>
                    <a class="nav-link <?php echo $action === 'create' ? 'active' : ''; ?>" href="?action=create">
                        <i class="fas fa-plus"></i> New Release
                    </a>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-10 content">
                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($action === 'list'): ?>
                    <!-- List all releases -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2><i class="fas fa-list"></i> All Releases</h2>
                        <a href="?action=create" class="btn btn-primary">
                            <i class="fas fa-plus"></i> New Release
                        </a>
                    </div>

                    <?php if (empty($releases)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No releases yet. <a href="?action=create">Create one now</a>.
                        </div>
                    <?php else: ?>
                        <div class="releases-table">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Title</th>
                                        <th>Version</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($releases as $rel): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($rel['title']); ?></strong>
                                            </td>
                                            <td>
                                                <?php echo $rel['version'] ? htmlspecialchars($rel['version']) : '—'; ?>
                                            </td>
                                            <td>
                                                <?php echo date('M d, Y', strtotime($rel['created_at'])); ?>
                                            </td>
                                            <td>
                                                <span class="badge <?php echo $rel['is_published'] ? 'badge-published' : 'badge-draft'; ?>">
                                                    <?php echo $rel['is_published'] ? 'Published' : 'Draft'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="?action=edit&edit=<?php echo $rel['id']; ?>" class="btn btn-sm btn-primary btn-action">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="?delete=<?php echo $rel['id']; ?>" class="btn btn-sm btn-danger btn-action" onclick="return confirm('Delete this release?');">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                <?php elseif ($action === 'create' || $action === 'edit'): ?>
                    <!-- Create/Edit form -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>
                            <i class="fas <?php echo $action === 'create' ? 'fa-plus' : 'fa-edit'; ?>"></i>
                            <?php echo $action === 'create' ? 'New Release' : 'Edit Release'; ?>
                        </h2>
                        <a href="?action=list" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>

                    <div class="form-section">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Release Title *</label>
                                        <input type="text" class="form-control" id="title" name="title" 
                                               value="<?php echo $release ? htmlspecialchars($release['title']) : ''; ?>" 
                                               required>
                                        <small class="form-text">e.g., "New Spinning Prompt Modal"</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="2"
                                                  placeholder="Brief summary of this release..."><?php echo $release ? htmlspecialchars($release['description']) : ''; ?></textarea>
                                        <small class="form-text">Short summary that appears in the releases list</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="content" class="form-label">Content *</label>
                                        <textarea class="form-control summernote" id="content" name="content" required><?php echo $release ? $release['content'] : ''; ?></textarea>
                                        <small class="form-text">Full release notes with rich formatting, images, and videos</small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="version" class="form-label">Version</label>
                                        <input type="text" class="form-control" id="version" name="version" 
                                               value="<?php echo $release ? htmlspecialchars($release['version']) : ''; ?>"
                                               placeholder="e.g., 1.2.0">
                                        <small class="form-text">Semantic versioning (optional)</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="images" class="form-label">Image URLs</label>
                                        <textarea class="form-control" id="images" name="images" rows="3" 
                                                  placeholder="One URL per line&#10;https://example.com/image1.jpg&#10;https://example.com/image2.jpg"><?php echo $release ? implode("\n", $release['images']) : ''; ?></textarea>
                                        <small class="form-text">One image URL per line</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="videos" class="form-label">Video URLs</label>
                                        <textarea class="form-control" id="videos" name="videos" rows="3"
                                                  placeholder="One URL per line&#10;https://example.com/video1.mp4&#10;https://example.com/video2.mp4"><?php echo $release ? implode("\n", $release['videos']) : ''; ?></textarea>
                                        <small class="form-text">One video URL per line (MP4 format recommended)</small>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> 
                                    <?php echo $action === 'create' ? 'Create Release' : 'Update Release'; ?>
                                </button>
                                <a href="?action=list" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        // WHY: initialize Summernote rich text editor for content field
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 400,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
</body>
</html>
