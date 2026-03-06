<?php
// WHY: Releases home page displays all published releases with sidebar navigation
// Users can view release notes, features, and innovations with rich media support
// Similar to /center layout with sidebar for quick navigation and main panel for content

// WHY: only start session if one isn't already active — the router may have started it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// WHY: wrap initialization in try-catch to catch and log any errors during controller setup
try {
    // WHY: use __DIR__ instead of $_SERVER['DOCUMENT_ROOT'] for reliable relative paths
    // This works regardless of how the script is called (localhost, production, subdirectory)
    // Path: apps/edu/ui/views/releases/app.php → go up 3 levels to apps/edu/controllers/
    $controllerPath = __DIR__ . '/../../../controllers/ReleaseController.php';
    
    if (!file_exists($controllerPath)) {
        throw new Exception("ReleaseController not found at: $controllerPath");
    }
    
    require_once $controllerPath;
    
    if (!class_exists('ReleaseController')) {
        throw new Exception("ReleaseController class not defined after requiring file");
    }
    
    $releaseController = new ReleaseController();
    
    // WHY: get all published releases ordered by newest first
    $releases = $releaseController->getAllReleases();
    error_log('Releases app: Found ' . count($releases) . ' published releases');
    
    // WHY: also check all releases (including unpublished) for debugging
    $allReleases = $releaseController->getAllReleasesForCMS();
    error_log('Releases app: Found ' . count($allReleases) . ' total releases (including unpublished)');
    
    // WHY: determine which release to display (latest by default, or specific by ID)
    $displayRelease = null;
    if (!empty($_GET['id'])) {
        $releaseId = intval($_GET['id']);
        $displayRelease = $releaseController->getReleaseById($releaseId);
        // WHY: only show if published or user is admin
        if ($displayRelease && !$displayRelease['is_published'] && empty($_SESSION['user_id'])) {
            $displayRelease = null;
        }
    } elseif (!empty($releases)) {
        // WHY: show latest release by default
        $displayRelease = $releases[0];
    }
} catch (Exception $e) {
    // WHY: log the error for debugging and show a user-friendly message
    error_log('Releases app initialization error: ' . $e->getMessage());
    $releaseController = null;
    $releases = [];
    $displayRelease = null;
    $initError = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Releases & Changelog - Wheelder</title>

    <!-- Open Graph -->
    <meta property="og:title" content="Wheelder Releases & Changelog">
    <meta property="og:description" content="Latest features, updates, and innovations from the Wheelder research platform. Innovating since 2023.">
    <meta property="og:image" content="https://wheelder.com/pool/assets/og-image.php">
    <meta property="og:url" content="https://wheelder.com/releases">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Wheelder">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Wheelder Releases & Changelog">
    <meta name="twitter:description" content="Latest features, updates, and innovations from the Wheelder research platform. Innovating since 2023.">
    <meta name="twitter:image" content="https://wheelder.com/pool/assets/og-image.php">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #212529;
            --secondary-color: #6c757d;
            --accent-color: #0d6efd;
        }

        body {
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        .dark-mode {
            background-color: #1a1a1a;
            color: #e0e0e0;
        }

        .dark-mode .navbar {
            background-color: #0d0d0d !important;
        }

        .dark-mode .card {
            background-color: #2a2a2a;
            color: #e0e0e0;
            border-color: #444;
        }

        .dark-mode .release-item {
            background-color: #2a2a2a;
            border-color: #444;
        }

        .dark-mode .release-content {
            background-color: #1e1e1e;
            color: #e0e0e0;
        }

        /* Navbar styling */
        .navbar {
            background-color: var(--primary-color);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        /* Release list sidebar */
        .releases-sidebar {
            max-height: 80vh;
            overflow-y: auto;
        }

        .release-item {
            padding: 1rem;
            margin-bottom: 0.5rem;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: #fff;
        }

        .release-item:hover {
            background-color: #f8f9fa;
            border-color: var(--accent-color);
            transform: translateX(4px);
        }

        .release-item.active {
            background-color: var(--accent-color);
            color: white;
            border-color: var(--accent-color);
        }

        .release-item .version-badge {
            display: inline-block;
            background-color: #e9ecef;
            color: #212529;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-right: 0.5rem;
        }

        .release-item.active .version-badge {
            background-color: rgba(255,255,255,0.3);
            color: white;
        }

        .release-date {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .release-item.active .release-date {
            color: rgba(255,255,255,0.8);
        }

        /* Release content area */
        .release-content {
            background-color: #fff;
            border-radius: 8px;
            padding: 2rem;
            min-height: 500px;
        }

        .release-header {
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 1.5rem;
            margin-bottom: 2rem;
        }

        .release-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .release-meta {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .release-version {
            background-color: var(--accent-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 600;
        }

        .release-timestamp {
            color: var(--secondary-color);
            font-size: 0.95rem;
        }

        .release-description {
            font-size: 1.1rem;
            color: var(--secondary-color);
            margin-bottom: 2rem;
            font-style: italic;
        }

        .release-body {
            line-height: 1.8;
        }

        .release-body img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1.5rem 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .release-body video {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1.5rem 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .release-body h2 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .release-body h3 {
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .release-body p {
            margin-bottom: 1rem;
        }

        .release-body ul, .release-body ol {
            margin-bottom: 1rem;
            padding-left: 2rem;
        }

        .release-body li {
            margin-bottom: 0.5rem;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--secondary-color);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Admin controls */
        .admin-controls {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #dee2e6;
        }

        .btn-sm {
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
        }

        @media (max-width: 768px) {
            .release-title {
                font-size: 1.75rem;
            }

            .release-content {
                padding: 1rem;
            }

            .releases-sidebar {
                max-height: 300px;
                margin-bottom: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <i class="fas fa-rocket"></i> Wheelder Releases
            </a>
            <div class="d-flex gap-2">
                <a href="/center" class="btn btn-sm btn-outline-light" title="Back to Ask to Learn">
                    <i class="fas fa-arrow-left"></i> Back to Center
                </a>
                <?php if (!empty($_SESSION['user_id'])): ?>
                    <a href="/releases/cms" class="btn btn-sm btn-outline-light">
                        <i class="fas fa-edit"></i> Manage
                    </a>
                <?php endif; ?>
                <button class="btn btn-sm btn-outline-light" id="darkModeToggle">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <!-- WHY: display initialization errors if any occurred during controller setup -->
        <?php if (!empty($initError)): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <strong>Error loading releases:</strong> <?php echo htmlspecialchars($initError); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Releases list sidebar -->
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="fas fa-list"></i> All Releases</h5>
                    </div>
                    <div class="releases-sidebar">
                        <?php if (empty($releases)): ?>
                            <div class="p-3 text-center text-muted">
                                <p>No releases yet.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($releases as $release): ?>
                                <div class="release-item <?php echo ($displayRelease && $displayRelease['id'] === $release['id']) ? 'active' : ''; ?>"
                                     onclick="window.location.href='?id=<?php echo $release['id']; ?>'">
                                    <?php if ($release['version']): ?>
                                        <span class="version-badge"><?php echo htmlspecialchars($release['version']); ?></span>
                                    <?php endif; ?>
                                    <div class="fw-bold"><?php echo htmlspecialchars($release['title']); ?></div>
                                    <div class="release-date">
                                        <i class="fas fa-calendar"></i>
                                        <?php echo date('M d, Y', strtotime($release['created_at'])); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Release content area -->
            <div class="col-lg-9">
                <?php if ($displayRelease): ?>
                    <div class="release-content">
                        <div class="release-header">
                            <h1 class="release-title"><?php echo htmlspecialchars($displayRelease['title']); ?></h1>
                            <div class="release-meta">
                                <?php if ($displayRelease['version']): ?>
                                    <span class="release-version">v<?php echo htmlspecialchars($displayRelease['version']); ?></span>
                                <?php endif; ?>
                                <span class="release-timestamp">
                                    <i class="fas fa-calendar-alt"></i>
                                    <?php echo date('F d, Y', strtotime($displayRelease['created_at'])); ?>
                                </span>
                            </div>
                        </div>

                        <?php if ($displayRelease['description']): ?>
                            <p class="release-description"><?php echo htmlspecialchars($displayRelease['description']); ?></p>
                        <?php endif; ?>

                        <div class="release-body">
                            <?php echo $displayRelease['content']; ?>
                        </div>

                        <!-- Display images if any -->
                        <?php if (!empty($displayRelease['images'])): ?>
                            <div class="mt-4">
                                <h3>Images</h3>
                                <div class="row">
                                    <?php foreach ($displayRelease['images'] as $image): ?>
                                        <div class="col-md-6 mb-3">
                                            <img src="<?php echo htmlspecialchars($image); ?>" alt="Release image" class="img-fluid">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Display videos if any -->
                        <?php if (!empty($displayRelease['videos'])): ?>
                            <div class="mt-4">
                                <h3>Videos</h3>
                                <div class="row">
                                    <?php foreach ($displayRelease['videos'] as $video): ?>
                                        <div class="col-md-6 mb-3">
                                            <video width="100%" controls style="border-radius: 8px;">
                                                <source src="<?php echo htmlspecialchars($video); ?>" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Admin controls -->
                        <?php if (!empty($_SESSION['user_id'])): ?>
                            <div class="admin-controls">
                                <a href="/releases/cms?edit=<?php echo $displayRelease['id']; ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="/releases/cms?delete=<?php echo $displayRelease['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this release?');">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="release-content">
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h3>No Releases Yet</h3>
                            <p>Check back soon for updates and new features!</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // WHY: dark mode toggle persists in localStorage
        const darkModeToggle = document.getElementById('darkModeToggle');
        const htmlElement = document.documentElement;

        // Check for saved dark mode preference
        if (localStorage.getItem('darkMode') === 'enabled') {
            htmlElement.classList.add('dark-mode');
        }

        darkModeToggle.addEventListener('click', function() {
            htmlElement.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', htmlElement.classList.contains('dark-mode') ? 'enabled' : 'disabled');
        });
    </script>
</body>
</html>
