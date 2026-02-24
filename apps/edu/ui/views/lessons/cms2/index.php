<?php
// Use __DIR__ for reliable paths regardless of DOCUMENT_ROOT — same pattern as cms/list.php
include __DIR__ . '/../../../../controllers/LessonController.php';

$lesson = new LessonController();
// Auth gate — only logged-in users may access CMS2; check_auth() redirects via url() on failure
$lesson->check_auth();

// Generate a CSRF token once per session — sent with every AJAX POST
if (empty($_SESSION['cms2_csrf_token'])) {
    $_SESSION['cms2_csrf_token'] = bin2hex(random_bytes(32));
}

// Ensure lessons table exists in database before querying it
// This is critical for first-time setup or if database was reset
$db = $lesson->connectDb();
if ($db) {
    // Create lessons table if it doesn't exist
    $db->query("
        CREATE TABLE IF NOT EXISTS lessons (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            category TEXT,
            content TEXT,
            image_url TEXT,
            code_block TEXT,
            status TEXT DEFAULT 'draft',
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Migrate missing columns — the table may have been created before image_url,
    // code_block, and status were added. SQLite does not auto-alter existing tables,
    // so we check each column via PRAGMA and ADD it only if absent.
    // Same pattern as sqlite_setup.php::lessons_migrate_columns().
    $needed = [
        'image_url'  => 'TEXT',
        'code_block' => 'TEXT',
        'status'     => "TEXT DEFAULT 'draft'",
        'created_at' => "TEXT DEFAULT CURRENT_TIMESTAMP",
    ];
    $info = $db->query("PRAGMA table_info(lessons)");
    $existing = [];
    if ($info) {
        while ($row = $info->fetch_assoc()) {
            $existing[] = $row['name'];
        }
    }
    foreach ($needed as $col => $definition) {
        if (!in_array($col, $existing, true)) {
            // ADD COLUMN is safe — it never removes data from existing rows
            $db->query("ALTER TABLE lessons ADD COLUMN $col $definition");
        }
    }
}

// Load existing draft lessons for the sidebar (newest first)
$drafts = $lesson->list_drafts();

// If the user clicked a draft in the sidebar, pre-load it via ?id=
$viewLesson = null;
if (!empty($_GET['id'])) {
    $viewLesson = $lesson->get_lesson_edit((int)$_GET['id']);
    // Only show drafts in cms2 — published/archived lessons belong to /lesson
    if ($viewLesson && $viewLesson['status'] !== 'draft') {
        $viewLesson = null;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wheelder — Lesson CMS2</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        /* ── Answer panel — same style as /demo ── */
        .content {
            position: relative; width: 100%; padding: 10px;
            height: 580px; overflow-y: auto; overflow-x: hidden;
            background-color: #fff; white-space: normal;
            border: 2px solid #ccc; font-family: Verdana, sans-serif;
            font-size: 16px; font-weight: 400; border-radius: 10px;
            box-shadow: 8px 8px 8px #ccc;
        }
        /* Q&A blocks — same as /demo */
        .qa-question {
            font-weight: 700; font-size: 14px; color: #212529;
            margin-top: 12px; margin-bottom: 8px; padding: 10px 14px;
            border: 2px solid #212529; border-radius: 6px; background-color: #f8f9fa;
        }
        .qa-answer { margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px dashed #ddd; line-height: 1.7; }
        .qa-answer ol, .qa-answer ul { padding-left: 28px; margin: 8px 0; }
        .qa-answer li { margin-bottom: 4px; }
        .qa-answer p  { margin: 6px 0; }
        .qa-answer h3 { font-size: 1.1em; margin: 12px 0 6px; }
        .qa-answer h4 { font-size: 1.0em; margin: 10px 0 4px; }
        .qa-answer h5 { font-size: 0.9em; margin: 8px 0 4px; }
        .qa-answer code { background: #f0f0f0; padding: 1px 5px; border-radius: 3px; font-size: 0.9em; }
        .qa-answer pre  { background: #1e1e1e; color: #d4d4d4; padding: 12px 14px; border-radius: 6px; overflow-x: auto; margin: 10px 0; font-size: 0.86em; line-height: 1.5; }
        .qa-answer pre code { background: none; padding: 0; font-size: inherit; }
        .qa-answer hr  { border: none; border-top: 1px solid #ddd; margin: 12px 0; }
        .qa-depth-label { color: #888; font-size: 0.82em; margin-bottom: 4px; }
        /* ── Right image panel — narrower than /demo (col-md-4 vs col-md-6) ── */
        .contentImage {
            position: relative; width: 100%; height: 580px; overflow: hidden;
            border: 2px solid #ccc; border-radius: 10px; box-shadow: 8px 8px 8px #ccc;
            display: flex; align-items: center; justify-content: center;
            background-color: #f9f9f9;
        }
        .contentImage img { width: 100%; height: 100%; object-fit: cover; }
        /* Fullscreen button inside image panel */
        .img-fullscreen-btn {
            position: absolute; top: 10px; right: 10px; z-index: 10;
            background: rgba(0,0,0,.6); color: #fff; border: none;
            border-radius: 6px; padding: 6px 10px; cursor: pointer; font-size: 15px;
            transition: background .2s;
        }
        .img-fullscreen-btn:hover { background: rgba(0,0,0,.85); }
        /* Focus button inside answer panel */
        .text-fullscreen-btn {
            position: sticky; top: 10px; float: right; z-index: 10;
            background: rgba(0,0,0,.6); color: #fff; border: none;
            border-radius: 6px; padding: 6px 10px; cursor: pointer; font-size: 15px;
            transition: background .2s;
        }
        .text-fullscreen-btn:hover { background: rgba(0,0,0,.85); }
        /* ── Image fullscreen overlay ── */
        .img-overlay {
            display: none; position: fixed; inset: 0; width: 100vw; height: 100vh;
            z-index: 99999; background: rgba(0,0,0,.95);
            align-items: center; justify-content: center;
        }
        .img-overlay.active { display: flex; }
        .img-overlay img { max-width: 95vw; max-height: 90vh; object-fit: contain; border-radius: 8px; }
        .img-overlay-close {
            position: absolute; top: 18px; right: 24px; background: none; border: none;
            color: #fff; font-size: 32px; cursor: pointer; z-index: 100000;
            min-width: 44px; min-height: 44px;
        }
        /* ── Text focus overlay ── */
        .text-overlay {
            display: none; position: fixed; inset: 0; width: 100vw; height: 100vh;
            z-index: 99999; background: rgba(0,0,0,.95); overflow-y: auto;
        }
        .text-overlay.active { display: block; }
        .text-overlay-content {
            max-width: 800px; margin: 60px auto 40px; padding: 30px;
            color: #fff; font-family: Verdana, sans-serif; font-size: 17px; line-height: 1.7;
        }
        .text-overlay-content .qa-question { border: 2px solid #fff; padding: 8px 12px; margin-bottom: 16px; font-weight: 700; color: #fff; background: transparent; }
        .text-overlay-content .qa-answer pre { background: #333; color: #d4d4d4; padding: 12px; border-radius: 6px; overflow-x: auto; }
        .text-overlay-content .qa-answer code { background: #444; color: #fff; padding: 1px 5px; border-radius: 3px; }
        .text-overlay-content .qa-answer pre code { background: none; padding: 0; }
        .text-overlay-close {
            position: fixed; top: 18px; right: 24px; background: none; border: none;
            color: #fff; font-size: 32px; cursor: pointer; z-index: 100000;
            min-width: 44px; min-height: 44px;
        }
        /* ── Sidebar — same as /demo ── */
        .sidebar {
            position: fixed; top: 0; bottom: 0; left: 0; width: 260px;
            z-index: 100; padding: 40px 0 0; overflow-y: auto; overflow-x: hidden;
            background-color: #fff; color: #000; border: 1px solid #ccc;
            border-radius: 7px; box-shadow: 8px 8px 8px #ccc;
            scrollbar-width: thin; scrollbar-color: #999 #f0f0f0;
        }
        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-track { background: #f0f0f0; border-radius: 3px; }
        .sidebar::-webkit-scrollbar-thumb { background: #999; border-radius: 3px; }
        .sidebar::-webkit-scrollbar-thumb:hover { background: #666; }
        .sidebar a { color: #000; background-color: #fff; }
        .sidebar .nav-item {
            margin: 3px; border: 1px solid #ccc; border-radius: 5px;
            box-shadow: 3px 3px 3px #ccc; display: flex; align-items: center;
            max-width: 100%; overflow: hidden;
        }
        .sidebar .nav-link {
            color: #000; font-size: 13px; font-weight: 400;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            flex: 1; min-width: 0; padding: 6px 8px; line-height: 1.3;
        }
        .sidebar .nav-link:hover { background-color: #f0f0f0; }
        .sidebar .nav-link.active { background-color: #212529; color: #fff; border-radius: 5px; }
        .sidebar .conv-date { font-size: 10px; color: #999; display: block; white-space: normal; margin-top: 1px; }
        /* Action icons: Publish, Archive, Delete */
        .conv-actions { display: flex; align-items: center; gap: 5px; padding-right: 6px; flex-shrink: 0; margin-left: auto; }
        .conv-actions i { font-size: 11px; color: #999; cursor: pointer; transition: color .15s; }
        .conv-actions .conv-archive:hover { color: #0d6efd; }
        .conv-actions .conv-delete:hover  { color: #dc3545; }
        /* Publish icon turns green — signals "make live on /lesson" */
        .conv-actions .conv-publish:hover { color: #198754; }
        /* Draft badge */
        .draft-badge {
            font-size: 9px; background: #ffc107; color: #000;
            border-radius: 3px; padding: 1px 4px; margin-left: 3px;
            font-weight: 700; flex-shrink: 0;
        }
        /* ── Toolbar ── */
        .controls i { cursor: pointer; }
        .navbar .form-control { padding: .75rem 1rem; background-color: #212529; color: #fff; border-color: transparent; }
        /* ── Dark mode ── */
        .dark-mode { background-color: #000; color: #fff; }
        .dark-mode .content { background-color: #1a1a1a; color: #fff; border-color: #444; }
        .dark-mode .qa-question { color: #eee; border-color: #888; background-color: #2a2a2a; }
        .dark-mode .qa-answer { border-color: #444; }
        .dark-mode .contentImage { background-color: #1a1a1a; border-color: #444; }
        .dark-mode .form-control { background-color: #1a1a1a; color: #fff; border-color: #444; }
        .dark-mode .controls { background-color: #000; color: #fff; }
        .dark-mode .sidebar { color: #fff; background-color: #000; box-shadow: 10px 10px 10px #ccc; scrollbar-color: #555 #1a1a1a; }
        .dark-mode .sidebar a { color: #fff; background-color: #000; }
        .dark-mode #sidebarMenu { color: #fff; background-color: #000; }
        .dark-mode .conv-actions i { color: #666; }
        .dark-mode .conv-actions .conv-archive:hover { color: #6ea8fe; }
        .dark-mode .conv-actions .conv-delete:hover  { color: #ea868f; }
        .dark-mode .conv-actions .conv-publish:hover { color: #75b798; }
        .dark-mode .qa-answer pre  { background-color: #333; color: #fff; }
        .dark-mode .qa-answer code { background-color: #444; color: #fff; }
        /* ── Responsive ── */
        @media (max-width: 767.98px) {
            .sidebar { top: 3.5rem; bottom: auto; left: 0; right: 0; width: 100% !important; max-height: 60vh; border-radius: 0; position: fixed !important; }
            #sidebarMenu { flex: none !important; width: 100% !important; }
            main.col-md-10 { flex: 0 0 100% !important; max-width: 100% !important; margin-left: 0 !important; padding: 0 12px !important; }
            .content, .contentImage { height: 320px; font-size: 14px; }
            .controls { display: flex; flex-wrap: wrap; gap: 2px; padding: 6px 8px !important; font-size: 0; justify-content: center; align-items: center; }
            .controls i { font-size: 14px; padding: 4px; height: 22px; display: inline-flex; align-items: center; }
        }
        @media (min-width: 768px) and (max-width: 991.98px) {
            .sidebar { width: 200px; }
            .content, .contentImage { height: 420px; font-size: 15px; }
        }
        a { text-decoration: none; }
    </style>
</head>
<body>

    <!-- ── Top navbar (same structure as /demo) ── -->
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-2 col-lg-2 me-0 px-3 fs-6" href="<?php echo url('/lesson/cms2'); ?>">Wheelder — CMS2</a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button"
            data-bs-toggle="collapse" data-bs-target="#sidebarMenu"
            aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="controls form-control w-100 rounded-0 border-0">
            <i id="start"            class="fas fa-play"         title="Read aloud"></i>
            <i id="pause"            class="fas fa-pause"        title="Pause"></i>
            <i id="resume"           class="fas fa-step-forward" title="Resume"></i>
            |
            <i id="fullscreen"       class="fas fa-expand"       title="Fullscreen"></i>
            |
            <i id="copyToClipboard"  class="far fa-copy"         title="Copy content"></i>
            |
            <i id="printContent"     class="fas fa-print"        title="Print"></i>
            |
            <i id="shareButton"      class="fas fa-share-alt"    title="Share"></i>
            |
            <i id="increaseFontSize" class="fas fa-search-plus"  title="Increase font"></i>
            <i id="decreaseFontSize" class="fas fa-search-minus" title="Decrease font"></i>
            |
            <i id="darkMode"         class="fas fa-moon"         title="Dark mode"></i>
            <i id="lightMode"        class="fas fa-sun"          title="Light mode"></i>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">

            <!-- Left sidebar: draft lesson list -->
            <nav id="sidebarMenu" class="col-md-2 col-lg-2 d-md-block sidebar collapse">
                <div class="pt-3" style="flex:1 1 auto; overflow-y:auto; overflow-x:hidden; min-height:0;">
                    <ul class="nav flex-column">
                        <!-- New Lesson resets to fresh state -->
                        <li class="nav-item">
                            <a href="<?php echo url('/lesson/cms2'); ?>" class="nav-link <?php echo empty($_GET['id']) ? 'active' : ''; ?>">
                                <i class="fas fa-plus"></i> New Lesson
                            </a>
                        </li>
                        <?php foreach ($drafts as $draft): ?>
                        <?php
                            $label    = mb_substr($draft['title'], 0, 28);
                            if (mb_strlen($draft['title']) > 28) $label .= '...';
                            $isActive = (!empty($_GET['id']) && (int)$_GET['id'] === (int)$draft['id']) ? 'active' : '';
                        ?>
                        <li class="nav-item d-flex align-items-center" data-lesson-id="<?php echo (int)$draft['id']; ?>">
                            <a href="<?php echo url('/lesson/cms2'); ?>?id=<?php echo (int)$draft['id']; ?>"
                               class="nav-link flex-grow-1 <?php echo $isActive; ?>"
                               title="<?php echo htmlspecialchars($draft['title'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                                <span class="conv-date"><?php echo htmlspecialchars($draft['created_at'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                            </a>
                            <span class="draft-badge">draft</span>
                            <!-- Publish (green), Archive (blue), Delete (red) -->
                            <span class="conv-actions">
                                <i class="fas fa-upload conv-publish"      title="Publish to /lesson"  data-lesson-id="<?php echo (int)$draft['id']; ?>"></i>
                                <i class="fas fa-box-archive conv-archive" title="Archive this draft"  data-lesson-id="<?php echo (int)$draft['id']; ?>"></i>
                                <i class="fas fa-trash conv-delete"        title="Delete this draft"   data-lesson-id="<?php echo (int)$draft['id']; ?>"></i>
                            </span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </nav>

            <!-- Main content area -->
            <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
                <h2 class="text-center mb-3 pt-3">Generate Lesson with AI</h2>

                <div class="row justify-content-center">
                    <div class="col-md-9">
                        <div class="mb-3">
                            <textarea id="queryInput" class="form-control" rows="3" placeholder="Enter your question or topic"></textarea>
                        </div>
                        <div class="mb-3">
                            <input id="categoryInput" class="form-control" type="text" placeholder="Enter a category">
                        </div>
                    </div>
                </div>

                <!-- Action buttons -->
                <div class="row justify-content-center">
                    <div class="col-md-9">
                        <div class="d-flex gap-2 mb-3 flex-wrap">
                            <button type="button" id="askBtn"    class="btn btn-dark">Generate Lesson</button>
                            <button type="button" id="deepenBtn" class="btn btn-outline-dark" style="display:none;">Deepen / Go Deeper</button>
                            <button type="button" id="clearBtn"  class="btn btn-dark">Clear</button>
                            <span id="depthBadge" class="badge bg-dark fs-6 ms-auto align-self-center" style="display:none;"></span>
                        </div>
                    </div>
                </div>

                <!-- Spinner -->
                <div id="loadingSpinner" class="text-center mb-3" style="display:none;">
                    <div class="spinner-border text-dark" role="status"><span class="visually-hidden">Loading...</span></div>
                    <p class="mt-2 text-muted">Generating lesson...</p>
                </div>

                <!-- Error / success alerts -->
                <div id="errorMsg"   class="alert alert-danger  mb-3" style="display:none;" role="alert"></div>
                <div id="successMsg" class="alert alert-success mb-3" style="display:none;" role="alert"></div>

                <!-- Two panels: answer (col-md-8) + image (col-md-4, narrower than /demo) -->
                <div class="row mt-2" id="resultsRow">
                    <?php if ($viewLesson): ?>
                    <div class="col-md-8">
                        <div class="content" id="answerPanel">
                            <button class="text-fullscreen-btn" title="Focus view" onclick="openTextOverlay()">
                                <i class="fas fa-expand"></i>
                            </button>
                            <div class="qa-question"><?php echo htmlspecialchars($viewLesson['title'], ENT_QUOTES, 'UTF-8'); ?></div>
                            <div class="qa-answer"><?php echo nl2br(htmlspecialchars($viewLesson['content'], ENT_QUOTES, 'UTF-8')); ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="contentImage" id="imagePanel">
                            <?php if (!empty($viewLesson['image_url'])): ?>
                                <button class="img-fullscreen-btn" title="View fullscreen" onclick="openImageOverlay(this)">
                                    <i class="fas fa-expand"></i>
                                </button>
                                <img src="<?php echo htmlspecialchars($viewLesson['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="Lesson diagram"/>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

            </main>
        </div>
    </div>

    <!-- Image fullscreen overlay -->
    <div class="img-overlay" id="imgOverlay">
        <button class="img-overlay-close" onclick="closeImageOverlay()">&times;</button>
        <img id="imgOverlayImg" src="" alt="Fullscreen image"/>
    </div>

    <!-- Text focus overlay -->
    <div class="text-overlay" id="textOverlay">
        <button class="text-overlay-close" onclick="closeTextOverlay()">&times;</button>
        <div class="text-overlay-content" id="textOverlayContent"></div>
    </div>

    <div class="mb-5"></div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script>
        // ── AJAX state — tracks the current lesson being generated ──
        var ajaxState = {
            lessonId:         <?php echo $viewLesson ? (int)$viewLesson['id'] : 0; ?>,
            originalQuestion: <?php echo $viewLesson ? json_encode($viewLesson['title']) : '""'; ?>,
            prevAnswer:       <?php echo $viewLesson ? json_encode($viewLesson['content']) : '""'; ?>,
            depthLevel:       0
        };

        // CSRF token — generated server-side, sent with every AJAX POST
        var csrfToken = <?php echo json_encode($_SESSION['cms2_csrf_token']); ?>;

        // AJAX endpoint for this CMS
        var ajaxUrl = '<?php echo url('/lesson/cms2/ajax'); ?>';

        // ── DOM refs ──
        var askBtn         = document.getElementById('askBtn');
        var deepenBtn      = document.getElementById('deepenBtn');
        var clearBtn       = document.getElementById('clearBtn');
        var depthBadge     = document.getElementById('depthBadge');
        var queryInput     = document.getElementById('queryInput');
        var categoryInput  = document.getElementById('categoryInput');
        var resultsRow     = document.getElementById('resultsRow');
        var loadingSpinner = document.getElementById('loadingSpinner');
        var errorMsg       = document.getElementById('errorMsg');
        var successMsg     = document.getElementById('successMsg');

        // Show deepen button if a lesson is pre-loaded from the sidebar
        <?php if ($viewLesson): ?>
        deepenBtn.style.display = 'inline-block';
        <?php endif; ?>

        // ── Ensure both panels exist before writing to them ──
        function ensurePanels() {
            if (!document.getElementById('answerPanel')) {
                resultsRow.innerHTML =
                    '<div class="col-md-8">' +
                    '  <div class="content" id="answerPanel">' +
                    '    <button class="text-fullscreen-btn" title="Focus view" onclick="openTextOverlay()">' +
                    '      <i class="fas fa-expand"></i>' +
                    '    </button>' +
                    '  </div>' +
                    '</div>' +
                    '<div class="col-md-4">' +
                    '  <div class="contentImage" id="imagePanel"></div>' +
                    '</div>';
            }
        }

        function scrollAnswerPanel() {
            var panel = document.getElementById('answerPanel');
            if (!panel) return;
            var qs = panel.querySelectorAll('.qa-question');
            if (qs.length > 0) {
                qs[qs.length - 1].scrollIntoView({ block: 'start', behavior: 'smooth' });
            } else {
                panel.scrollTop = panel.scrollHeight;
            }
        }

        // ── Image overlay ──
        var _overlayScrollY = 0;
        function openImageOverlay(btn) {
            var panel = btn.closest('.contentImage');
            var img   = panel ? panel.querySelector('img') : null;
            if (!img || !img.src) return;
            document.getElementById('imgOverlayImg').src = img.src;
            document.getElementById('imgOverlay').classList.add('active');
            _overlayScrollY = window.scrollY;
            document.body.style.position = 'fixed';
            document.body.style.top      = '-' + _overlayScrollY + 'px';
            document.body.style.overflow = 'hidden';
        }
        function closeImageOverlay() {
            document.getElementById('imgOverlay').classList.remove('active');
            document.getElementById('imgOverlayImg').src = '';
            document.body.style.position = '';
            document.body.style.top      = '';
            document.body.style.overflow = '';
            window.scrollTo(0, _overlayScrollY);
        }

        // ── Text focus overlay ──
        function openTextOverlay() {
            var panel = document.getElementById('answerPanel');
            if (!panel) return;
            var oc = document.getElementById('textOverlayContent');
            oc.innerHTML = '';
            var children = panel.children;
            for (var i = 0; i < children.length; i++) {
                if (children[i].classList.contains('text-fullscreen-btn')) continue;
                oc.innerHTML += children[i].outerHTML;
            }
            document.getElementById('textOverlay').classList.add('active');
            _overlayScrollY = window.scrollY;
            document.body.style.position = 'fixed';
            document.body.style.top      = '-' + _overlayScrollY + 'px';
            document.body.style.overflow = 'hidden';
        }
        function closeTextOverlay() {
            document.getElementById('textOverlay').classList.remove('active');
            document.getElementById('textOverlayContent').innerHTML = '';
            document.body.style.position = '';
            document.body.style.top      = '';
            document.body.style.overflow = '';
            window.scrollTo(0, _overlayScrollY);
        }
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') { closeImageOverlay(); closeTextOverlay(); }
        });

        // ── Auto-resize textarea ──
        // Guard against null — if queryInput is missing, skip silently rather than crashing the whole script
        function autoResizeTextarea() {
            if (!queryInput) return;
            queryInput.style.height = 'auto';
            queryInput.style.height = Math.min(queryInput.scrollHeight, 200) + 'px';
        }
        if (queryInput) queryInput.addEventListener('input', autoResizeTextarea);
        autoResizeTextarea();

        // ── Add a new item to the sidebar after a lesson is generated ──
        function addSidebarItem(lessonId, title) {
            var ul = document.querySelector('#sidebarMenu .nav.flex-column');
            if (!ul) return;
            // Remove any existing item with this lesson ID to avoid duplicates
            var existing = ul.querySelector('li[data-lesson-id="' + lessonId + '"]');
            if (existing) existing.remove();
            
            var label = title.length > 28 ? title.substring(0, 28) + '...' : title;
            var li = document.createElement('li');
            li.className = 'nav-item d-flex align-items-center';
            li.setAttribute('data-lesson-id', lessonId);
            li.innerHTML =
                '<a href="' + '<?php echo url('/lesson/cms2'); ?>?id=' + lessonId + '" class="nav-link flex-grow-1" title="' + title.replace(/"/g, '&quot;') + '">' +
                    label +
                    '<span class="conv-date">just now</span>' +
                '</a>' +
                '<span class="draft-badge">draft</span>' +
                '<span class="conv-actions">' +
                    '<i class="fas fa-upload conv-publish" title="Publish to /lesson" data-lesson-id="' + lessonId + '"></i>' +
                    '<i class="fas fa-box-archive conv-archive" title="Archive this draft" data-lesson-id="' + lessonId + '"></i>' +
                    '<i class="fas fa-trash conv-delete" title="Delete this draft" data-lesson-id="' + lessonId + '"></i>' +
                '</span>';
            // Insert after the "New Lesson" item (first child)
            var firstItem = ul.querySelector('li.nav-item');
            if (firstItem && firstItem.nextSibling) {
                ul.insertBefore(li, firstItem.nextSibling);
            } else {
                ul.appendChild(li);
            }
        }

        // ── Core AJAX function — mirrors sendAjax in /demo ──
        function sendAjax(formData, questionLabel) {
            errorMsg.style.display   = 'none';
            successMsg.style.display = 'none';
            loadingSpinner.style.display = 'block';
            askBtn.disabled    = true;
            deepenBtn.disabled = true;

            // Always attach CSRF token
            formData.append('csrf_token', csrfToken);

            fetch(ajaxUrl, { method: 'POST', body: formData })
            .then(function (response) {
                return response.text().then(function (text) {
                    return { text: text, status: response.status, ct: response.headers.get('content-type') || '' };
                });
            })
            .then(function (res) {
                loadingSpinner.style.display = 'none';
                askBtn.disabled    = false;
                deepenBtn.disabled = false;

                if (!res.text || !res.text.trim()) {
                    throw new Error('Server returned an empty response (HTTP ' + res.status + ').');
                }
                if (res.ct.indexOf('application/json') === -1) {
                    throw new Error('Server returned non-JSON. Please reload the page.');
                }
                var data;
                try { data = JSON.parse(res.text); }
                catch (e) { throw new Error('Invalid JSON from server (HTTP ' + res.status + ').'); }


                if (data.error) {
                    errorMsg.textContent   = data.error;
                    errorMsg.style.display = 'block';
                    return;
                }

                // Update AJAX state with the returned values
                ajaxState.lessonId         = data.lesson_id   || ajaxState.lessonId;
                ajaxState.originalQuestion = data.original_question || ajaxState.originalQuestion;
                ajaxState.prevAnswer       = data.prev_answer || ajaxState.prevAnswer;
                ajaxState.depthLevel       = data.depth_level || 0;

                // Render answer into the left panel
                ensurePanels();
                var panel = document.getElementById('answerPanel');
                var qDiv  = document.createElement('div');
                qDiv.className   = 'qa-question';
                qDiv.textContent = questionLabel;
                if (ajaxState.depthLevel > 0) {
                    var dl = document.createElement('div');
                    dl.className   = 'qa-depth-label';
                    dl.textContent = 'Depth level ' + ajaxState.depthLevel + ' of 7';
                    panel.appendChild(dl);
                }
                panel.appendChild(qDiv);
                var aDiv = document.createElement('div');
                aDiv.className = 'qa-answer';
                aDiv.innerHTML = data.answer || '';
                panel.appendChild(aDiv);
                scrollAnswerPanel();

                // Render image into the right panel with retry button
                if (data.image) {
                    var imgPanel = document.getElementById('imagePanel');
                    if (imgPanel) {
                        imgPanel.innerHTML =
                            '<button class="img-fullscreen-btn" title="View fullscreen" onclick="openImageOverlay(this)">' +
                            '  <i class="fas fa-expand"></i>' +
                            '</button>' +
                            '<button class="img-fullscreen-btn" id="retryImageBtn" title="Regenerate image" style="right:60px;">' +
                            '  <i class="fas fa-redo"></i>' +
                            '</button>' +
                            '<img src="' + data.image + '" alt="Lesson diagram" ' +
                            '     onerror="this.src=\'https://placehold.co/1024x630?text=Image+failed+to+load\';" />';
                    }
                }

                // Attach click handler to retry button
                var retryBtn = document.getElementById('retryImageBtn');
                if (retryBtn) {
                    retryBtn.addEventListener('click', function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        if (!ajaxState.originalQuestion) {
                            alert('No lesson to regenerate image for.');
                            return;
                        }
                        // Regenerate image by calling a new AJAX action
                        var formData = new FormData();
                        formData.append('regenerate_image', '1');
                        formData.append('original_question', ajaxState.originalQuestion);
                        formData.append('lesson_id', ajaxState.lessonId);
                        formData.append('csrf_token', csrfToken);
                        
                        loadingSpinner.style.display = 'block';
                        fetch(ajaxUrl, { method: 'POST', body: formData })
                        .then(function (r) { return r.json(); })
                        .then(function (data) {
                            loadingSpinner.style.display = 'none';
                            if (data.error) {
                                alert('Image regeneration failed: ' + data.error);
                                return;
                            }
                            if (data.image) {
                                var imgEl = imgPanel.querySelector('img');
                                if (imgEl) {
                                    imgEl.src = data.image;
                                    imgEl.style.display = 'block';
                                }
                            }
                        })
                        .catch(function (err) {
                            loadingSpinner.style.display = 'none';
                            alert('Image regeneration request failed: ' + err.message);
                        });
                    }); // end retryBtn.addEventListener
                }

                // Show Deepen button now that we have content
                deepenBtn.style.display = 'inline-block';

                // Update depth badge
                if (ajaxState.depthLevel > 0) {
                    depthBadge.textContent = 'Depth ' + ajaxState.depthLevel + '/7';
                    depthBadge.style.display = 'inline-block';
                }

                // Add to sidebar if this was a fresh Ask (not a deepen)
                if (data.lesson_id && data.title && ajaxState.depthLevel === 0) {
                    addSidebarItem(data.lesson_id, data.title);
                    // Show retry image button now that we have an image
                    var retryBtn = document.getElementById('retryImageBtn');
                    if (retryBtn) retryBtn.style.display = 'block';
                }
            })
            .catch(function (err) {
                loadingSpinner.style.display = 'none';
                askBtn.disabled    = false;
                deepenBtn.disabled = false;
                errorMsg.textContent   = 'Request failed: ' + err.message;
                errorMsg.style.display = 'block';
            });
        }

        // ── Generate Lesson button ──
        // Guard: askBtn must exist before attaching listener
        if (askBtn) askBtn.addEventListener('click', function () {
            if (!queryInput) { alert('Input field not found. Please reload the page.'); return; }
            var q = queryInput.value.trim();
            if (!q) {
                errorMsg.textContent   = 'Please enter a topic or question.';
                errorMsg.style.display = 'block';
                return;
            }
            queryInput.value = '';
            autoResizeTextarea();

            var formData = new FormData();
            formData.append('ask', '1');
            formData.append('query', q);
            formData.append('category', categoryInput ? categoryInput.value.trim() : '');

            // Reset depth state for a fresh generation
            ajaxState.depthLevel = 0;
            depthBadge.style.display = 'none';

            sendAjax(formData, q);
        });

        // ── Deepen button ──
        // Guard: deepenBtn must exist before attaching listener
        if (deepenBtn) deepenBtn.addEventListener('click', function () {
            if (!ajaxState.originalQuestion) {
                errorMsg.textContent   = 'Generate a lesson first before deepening.';
                errorMsg.style.display = 'block';
                return;
            }
            var formData = new FormData();
            formData.append('deepen', '1');
            formData.append('depth_level',        ajaxState.depthLevel + 1);
            formData.append('original_question',  ajaxState.originalQuestion);
            formData.append('prev_answer',        ajaxState.prevAnswer);
            formData.append('lesson_id',          ajaxState.lessonId);

            sendAjax(formData, 'Deepening: ' + ajaxState.originalQuestion);
        });

        // ── Clear button ──
        if (clearBtn) clearBtn.addEventListener('click', function () {
            if (queryInput) { queryInput.value = ''; }
            autoResizeTextarea();
            resultsRow.innerHTML = '';
            deepenBtn.style.display  = 'none';
            depthBadge.style.display = 'none';
            errorMsg.style.display   = 'none';
            successMsg.style.display = 'none';
            loadingSpinner.style.display = 'none';
            ajaxState = { lessonId: 0, originalQuestion: '', prevAnswer: '', depthLevel: 0 };
        });

        // ── Enter key submits (Shift+Enter for newline) ──
        if (queryInput) queryInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); if (askBtn) askBtn.click(); }
        });

        // ── Sidebar: Publish / Archive / Delete — event delegation ──
        document.getElementById('sidebarMenu').addEventListener('click', function (e) {
            var target    = e.target.closest('i') || e.target;
            var isPublish = target.classList.contains('conv-publish');
            var isArchive = target.classList.contains('conv-archive');
            var isDelete  = target.classList.contains('conv-delete');
            if (!isPublish && !isArchive && !isDelete) return;

            e.preventDefault();
            e.stopPropagation();

            var lessonId = parseInt(target.getAttribute('data-lesson-id'), 10);
            if (!lessonId || lessonId <= 0) return;

            if (isDelete && !confirm('Delete this draft permanently? This cannot be undone.')) return;
            if (isPublish && !confirm('Publish this lesson? It will become visible on /lesson.')) return;

            var action = isPublish ? 'publish' : (isArchive ? 'archive' : 'delete');
            var formData = new FormData();
            formData.append(action, '1');
            formData.append('lesson_id',  lessonId);
            formData.append('csrf_token', csrfToken);

            fetch(ajaxUrl, { method: 'POST', body: formData })
            .then(function (r) {
                return r.text().then(function (t) {
                    return { text: t, status: r.status, ct: r.headers.get('content-type') || '' };
                });
            })
            .then(function (res) {
                if (!res.text || !res.text.trim()) {
                    throw new Error('Empty response (HTTP ' + res.status + ').');
                }
                if (res.ct.indexOf('application/json') === -1) {
                    throw new Error('Non-JSON response. Please reload the page.');
                }
                var data;
                try { data = JSON.parse(res.text); }
                catch (e) { throw new Error('Invalid JSON (HTTP ' + res.status + ').'); }

                if (data.error) { alert('Error: ' + data.error); return; }

                // Remove the sidebar item from the DOM
                var li = target.closest('li.nav-item');
                if (li) li.remove();

                // Show feedback
                if (isPublish) {
                    successMsg.textContent   = 'Lesson published! It is now visible on /lesson.';
                    successMsg.style.display = 'block';
                    setTimeout(function () { successMsg.style.display = 'none'; }, 4000);
                }

                // If we were viewing the affected lesson, reset the main area
                if (ajaxState.lessonId === lessonId) {
                    resultsRow.innerHTML     = '';
                    deepenBtn.style.display  = 'none';
                    depthBadge.style.display = 'none';
                    ajaxState = { lessonId: 0, originalQuestion: '', prevAnswer: '', depthLevel: 0 };
                }
            })
            .catch(function (err) { alert('Request failed: ' + err.message); });
        });

        // ── Toolbar — guard each getElementById so a missing element never crashes the script ──
        var _el;
        _el = document.getElementById('shareButton');
        if (_el) _el.addEventListener('click', function () {
            if (navigator.share) {
                navigator.share({ title: 'Wheelder Lesson', url: window.location.href })
                    .catch(function (e) { console.error('Share error', e); });
            } else {
                prompt('Copy this URL and share it manually', window.location.href);
            }
        });
        _el = document.getElementById('darkMode');
        if (_el) _el.addEventListener('click', function () { document.body.classList.add('dark-mode'); });
        _el = document.getElementById('lightMode');
        if (_el) _el.addEventListener('click', function () { document.body.classList.remove('dark-mode'); });
        _el = document.getElementById('increaseFontSize');
        if (_el) _el.addEventListener('click', function () {
            var cd = document.querySelector('.content');
            if (!cd) return;
            cd.style.fontSize = (parseFloat(window.getComputedStyle(cd).fontSize) * 1.2) + 'px';
        });
        _el = document.getElementById('decreaseFontSize');
        if (_el) _el.addEventListener('click', function () {
            var cd = document.querySelector('.content');
            if (!cd) return;
            cd.style.fontSize = (parseFloat(window.getComputedStyle(cd).fontSize) / 1.2) + 'px';
        });
        _el = document.getElementById('printContent');
        if (_el) _el.addEventListener('click', function () {
            if (!document.querySelector('.content')) { alert('No content to print yet.'); return; }
            window.print();
        });
        _el = document.getElementById('copyToClipboard');
        if (_el) _el.addEventListener('click', function () {
            var cd = document.querySelector('.content');
            if (!cd) { alert('No content to copy yet.'); return; }
            var ta = document.createElement('textarea');
            ta.value = cd.innerText;
            ta.setAttribute('readonly', '');
            ta.style.position = 'absolute';
            ta.style.left = '-9999px';
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
            alert('Content copied to clipboard!');
        });
        _el = document.getElementById('fullscreen');
        if (_el) _el.addEventListener('click', function () {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(function (e) { console.error('Fullscreen error:', e); });
            } else {
                document.exitFullscreen().catch(function (e) { console.error('Exit fullscreen error:', e); });
            }
        });

        // ── Text-to-Speech (same pattern as /demo — uses /demo/tts proxy) ──
        var ttsAudio = null; var ttsPlainText = ''; var ttsIsSpeaking = false;
        var ttsBoundaries = []; var ttsHighlightTimer = null; var ttsIsLoading = false;

        function ttsWrapWords() {
            var cd = document.querySelector('.content');
            if (!cd) return;
            ttsPlainText = cd.innerText;
            var wi = 0;
            var wk = document.createTreeWalker(cd, NodeFilter.SHOW_TEXT, null, false);
            var nodes = [];
            while (wk.nextNode()) nodes.push(wk.currentNode);
            nodes.forEach(function (nd) {
                var parts = nd.textContent.split(/(\s+)/);
                if (parts.length <= 1 && !nd.textContent.trim()) return;
                var fr = document.createDocumentFragment();
                parts.forEach(function (p) {
                    if (!p) return;
                    if (/^\s+$/.test(p)) { fr.appendChild(document.createTextNode(p)); }
                    else {
                        var sp = document.createElement('span');
                        sp.setAttribute('data-tts-idx', wi); sp.textContent = p;
                        fr.appendChild(sp); wi++;
                    }
                });
                nd.parentNode.replaceChild(fr, nd);
            });
        }
        function ttsUnwrapWords() {
            var cd = document.querySelector('.content');
            if (!cd) return;
            cd.querySelectorAll('.tts-word-highlight').forEach(function (el) { el.classList.remove('tts-word-highlight'); });
            cd.querySelectorAll('span[data-tts-idx]').forEach(function (sp) {
                sp.parentNode.replaceChild(document.createTextNode(sp.textContent), sp);
            });
            cd.normalize();
        }
        function ttsHighlightLoop() {
            if (!ttsAudio || ttsAudio.paused || !ttsBoundaries.length) return;
            var cd = document.querySelector('.content'); if (!cd) return;
            var ms = ttsAudio.currentTime * 1000; var idx = -1;
            for (var i = 0; i < ttsBoundaries.length; i++) {
                if (ttsBoundaries[i].offset <= ms) idx = i; else break;
            }
            if (idx >= 0) {
                var spans = cd.querySelectorAll('span[data-tts-idx]');
                var pv    = cd.querySelector('.tts-word-highlight');
                if (idx < spans.length) {
                    if (pv && pv !== spans[idx]) pv.classList.remove('tts-word-highlight');
                    if (!spans[idx].classList.contains('tts-word-highlight')) {
                        spans[idx].classList.add('tts-word-highlight');
                        spans[idx].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                }
            }
            ttsHighlightTimer = requestAnimationFrame(ttsHighlightLoop);
        }
        function ttsStop() {
            if (ttsAudio) { ttsAudio.pause(); ttsAudio.currentTime = 0; ttsAudio = null; }
            ttsIsSpeaking = false; ttsIsLoading = false;
            if (ttsHighlightTimer) cancelAnimationFrame(ttsHighlightTimer);
            ttsUnwrapWords();
        }
        function ttsStart() {
            var cd = document.querySelector('.content');
            if (!cd || !cd.innerText.trim()) { alert('No content to read yet.'); return; }
            if (ttsIsLoading) return;
            ttsStop(); ttsWrapWords(); ttsIsLoading = true;
            var fd = new FormData();
            fd.append('text', ttsPlainText);
            // Try to use cms2 CSRF token first; fall back to demo CSRF token if available
            fd.append('csrf_token', csrfToken);
            fetch('<?php echo url("/demo/tts"); ?>', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                ttsIsLoading = false;
                if (data.error) {
                    // If CSRF token error, try reloading the page to get a fresh token
                    if (data.error.indexOf('CSRF') !== -1 || data.error.indexOf('csrf') !== -1) {
                        alert('Session expired. Please reload the page and try again.');
                        ttsUnwrapWords();
                        return;
                    }
                    alert('Text-to-speech failed: ' + data.error);
                    ttsUnwrapWords();
                    return;
                }
                ttsBoundaries = data.boundaries || [];
                ttsAudio      = new Audio('data:audio/mp3;base64,' + data.audio);
                ttsIsSpeaking = true;
                ttsAudio.onplay   = function () { ttsHighlightTimer = requestAnimationFrame(ttsHighlightLoop); };
                ttsAudio.onended  = function () { ttsIsSpeaking = false; if (ttsHighlightTimer) cancelAnimationFrame(ttsHighlightTimer); ttsUnwrapWords(); };
                ttsAudio.onerror  = function () { ttsIsSpeaking = false; if (ttsHighlightTimer) cancelAnimationFrame(ttsHighlightTimer); ttsUnwrapWords(); };
                ttsAudio.play().catch(function () { ttsIsSpeaking = false; ttsUnwrapWords(); });
            })
            .catch(function () { ttsIsLoading = false; alert('Text-to-speech request failed.'); ttsUnwrapWords(); });
        }
        // Guard TTS buttons — same pattern as toolbar above
        _el = document.getElementById('start');
        if (_el) _el.addEventListener('click', function () { ttsStart(); });
        _el = document.getElementById('pause');
        if (_el) _el.addEventListener('click', function () { if (ttsAudio && ttsIsSpeaking) { ttsAudio.pause(); if (ttsHighlightTimer) cancelAnimationFrame(ttsHighlightTimer); } });
        _el = document.getElementById('resume');
        if (_el) _el.addEventListener('click', function () { if (ttsAudio && ttsAudio.paused && ttsIsSpeaking) { ttsAudio.play(); ttsHighlightTimer = requestAnimationFrame(ttsHighlightLoop); } });
        window.onbeforeunload = function () { ttsStop(); };

    </script>

</body>
</html>
