<?php
// Resolve the controller path the same way blog/app.php does — host-aware include
$path = $_SERVER['DOCUMENT_ROOT'];
$host = $_SERVER['HTTP_HOST'];

if ($host === 'localhost') {
    $dir = '/wheelder';
    require_once $path . $dir . '/apps/edu/controllers/LessonController.php';
} else {
    include $path . '/apps/edu/controllers/LessonController.php';
}

$lesson = new LessonController();

// Auth gate — unauthenticated visitors are redirected to home
$lesson->check_auth();

// Fetch the full lesson row here so both center and right panels share the same data
$t = isset($_GET['t']) ? trim($_GET['t']) : null;

if ($t === null) {
    $sql = "SELECT * FROM lessons ORDER BY id DESC LIMIT 1";
} else {
    $title_q = ucwords(str_replace('_', ' ', $t));
    $title_q = $lesson->connectDb()->real_escape_string($title_q);
    $sql = "SELECT * FROM lessons WHERE title = '$title_q'";
}

$stmt = $lesson->run_query($sql);
$row  = ($stmt && $stmt->num_rows > 0) ? $stmt->fetch_assoc() : null;

// When older lessons were generated before the /demo image pipeline upgrade,
// image_url ended up empty or pointing at the placeholder service. Attempt to
// regenerate a real diagram on demand so editors immediately see a useful visual.
if ($row && (empty($row['image_url']) || strpos($row['image_url'], 'placehold.co') !== false)) {
    $appCtrlPath = __DIR__ . '/../learn/backup/AppController.php';
    if (!class_exists('AppController')) {
        if (file_exists($appCtrlPath)) {
            require_once $appCtrlPath;
        } else {
            error_log('Lesson page missing AppController for image regeneration: ' . $appCtrlPath);
        }
    }

    if (class_exists('AppController')) {
        try {
            $imageHelper = new AppController();
            $imagePrompt = trim($row['title'] ?? '');
            if (empty($imagePrompt)) {
                // Strip HTML so keywords come from plain text, preventing markup noise.
                $imagePrompt = mb_substr(trim(strip_tags($row['content'] ?? '')), 0, 160);
            }

            if (!empty($imagePrompt)) {
                $freshImage = $imageHelper->generateImage($imagePrompt);
                if (!empty($freshImage) && strpos($freshImage, 'placehold.co') === false) {
                    $row['image_url'] = $freshImage;
                    // Persist the regenerated image so future loads do not repeat work.
                    $lesson->update(
                        $row['id'],
                        $row['title'] ?? '',
                        $row['category'] ?? '',
                        $row['content'] ?? '',
                        $freshImage,
                        $row['code_block'] ?? ''
                    );
                }
            }
        } catch (Throwable $imageErr) {
            error_log('Lesson image regeneration failed: ' . $imageErr->getMessage());
        }
    }
}

// formatMarkdown — converts AI-generated markdown to HTML for display.
// WHY: content is stored as raw markdown (from cms2 AI generation); nl2br+htmlspecialchars
// would show raw symbols like ## and ** instead of rendered headings and bold text.
// Identical function to the one in cms2/ajax.php so both views render consistently.
function formatMarkdown($text) {
    $text = str_replace(["\r\n", "\r"], "\n", $text);
    $text = preg_replace('/```(\w*)\n([\s\S]*?)```/m', '<pre><code>$2</code></pre>', $text);
    $text = preg_replace('/`([^`]+)`/', '<code>$1</code>', $text);
    $text = preg_replace('/^### (.+)$/m', '<h5>$1</h5>', $text);
    $text = preg_replace('/^## (.+)$/m',  '<h4>$1</h4>', $text);
    $text = preg_replace('/^# (.+)$/m',   '<h3>$1</h3>', $text);
    $text = preg_replace('/\*\*\*(.+?)\*\*\*/', '<strong><em>$1</em></strong>', $text);
    $text = preg_replace('/\*\*(.+?)\*\*/',     '<strong>$1</strong>', $text);
    $text = preg_replace('/\*(.+?)\*/',          '<em>$1</em>', $text);
    $text = preg_replace('/^[-*]{3,}$/m', '<hr>', $text);
    $lines = explode("\n", $text);
    $html = ''; $inOl = false; $inUl = false;
    foreach ($lines as $line) {
        $t = trim($line);
        if ($t === '') {
            if ($inOl) { $html .= '</ol>'; $inOl = false; }
            if ($inUl) { $html .= '</ul>'; $inUl = false; }
            continue;
        }
        if (preg_match('/^\d+[\.\)]\s+(.+)$/', $t, $m)) {
            if ($inUl) { $html .= '</ul>'; $inUl = false; }
            if (!$inOl) { $html .= '<ol>'; $inOl = true; }
            $html .= '<li>' . $m[1] . '</li>'; continue;
        }
        if (preg_match('/^[-*•]\s+(.+)$/', $t, $m)) {
            if ($inOl) { $html .= '</ol>'; $inOl = false; }
            if (!$inUl) { $html .= '<ul>'; $inUl = true; }
            $html .= '<li>' . $m[1] . '</li>'; continue;
        }
        if ($inOl) { $html .= '</ol>'; $inOl = false; }
        if ($inUl) { $html .= '</ul>'; $inUl = false; }
        if (preg_match('/^<(h[1-6]|hr|pre|ol|ul|li|blockquote)/', $t)) {
            $html .= $t;
        } else {
            $html .= '<p>' . $t . '</p>';
        }
    }
    if ($inOl) $html .= '</ol>';
    if ($inUl) $html .= '</ul>';
    return $html;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Wheelder</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <style>
        /* ── Base ── */
        html, body { height: 100%; margin: 0; padding: 0; font-family: 'Inter', 'Segoe UI', sans-serif; background: #f5f6fb; }
        a { text-decoration: none; color: inherit; }

        /* ── Top navbar — mirrors /demo toolbar ── */
        .lesson-navbar {
            position: sticky; top: 0; z-index: 200;
            background: #121722;
            color: #fff; display: flex; align-items: center;
            padding: 0 20px; height: 52px; box-shadow: 0 6px 20px rgba(0,0,0,.3);
            gap: 20px;
        }
        /* Controls now sit to the left to mimic /demo toolbar */
        .lesson-navbar .brand { font-size: 1.1rem; font-weight: 700; color: #fff; }
        .lesson-navbar .controls { display: flex; align-items: center; gap: 14px; font-size: 18px; cursor: pointer; }
        .lesson-navbar .controls i { color: #fff; transition: opacity .15s; }
        .lesson-navbar .controls i:hover { opacity: .7; }
        .lesson-navbar .controls .sep { color: rgba(255,255,255,.4); font-size: 14px; }

        /* ── Three-column layout ── */
        .lesson-layout {
            display: flex; min-height: calc(100vh - 52px);
            background: linear-gradient(180deg, #f9fafc 0%, #f3f4f8 60%, #eef1f7 100%);
        }

        /* ── Left sidebar: minimal list like /demo ── */
        .lesson-sidebar {
            width: 250px; min-width: 220px; flex-shrink: 0;
            background: #ffffff; border-right: 1px solid #e3e7ef;
            overflow-y: auto;
        }
        .lesson-sidebar .nav-link {
            display: block; padding: 7px 16px; font-size: .85rem;
            color: #1a202c; border-left: 3px solid transparent;
            transition: background .12s, border-color .12s;
        }
        .lesson-sidebar .nav-link:hover  { background: #f5f7fb; border-left-color: #11152b; }
        .lesson-sidebar .nav-link.active { background: #eef0fb; border-left-color: #11152b; color: #11152b; font-weight: 600; }

        /* ── Center: hero + lesson card ── */
        .lesson-content {
            flex: 1; padding: 40px 4vw 60px;
            overflow-y: auto; display: flex; justify-content: center;
        }
        .lesson-panels {
            width: 100%; max-width: 1100px;
            display: grid; grid-template-columns: minmax(0, 1.1fr) minmax(0, .9fr);
            gap: 30px;
        }
        .lesson-card {
            background: #fff; border-radius: 18px; box-shadow: 0 20px 60px rgba(15,18,44,.08);
            padding: 28px; width: 100%;
        }
        .lesson-card header { display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px; }
        .lesson-card h4 { font-size: 1.8rem; font-weight: 700; color: #0f172a; margin: 0; }
        .lesson-card .badge { align-self: flex-start; background: #101936; color: #fff; }
        .lesson-meta { font-size: .85rem; color: #94a3b8; }

        .lesson-body { font-size: 1rem; line-height: 1.85; color: #1f2937; }
        .lesson-body h3, .lesson-body h4, .lesson-body h5 { margin-top: 1.5rem; color: #0f172a; }
        .lesson-body p  { margin-bottom: 1rem; }
        .lesson-body ul, .lesson-body ol { padding-left: 1.4rem; margin-bottom: 1rem; }
        .lesson-body li { margin-bottom: .4rem; }
        .lesson-body pre { background: #0f172a; color: #f8fafc; padding: 14px; border-radius: 10px; overflow-x: auto; }
        .lesson-body code { background: #f3f4f6; padding: 2px 6px; border-radius: 4px; }

        .visual-panel {
            background: #fff; border-radius: 18px; box-shadow: 0 20px 60px rgba(15,18,44,.08);
            padding: 18px; display: flex; flex-direction: column; gap: 12px;
        }
        .lesson-image {
            flex: 1; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;
        }
        .lesson-image img { width: 100%; display: block; object-fit: cover; height: 100%; }
        .lesson-image .placeholder { padding: 40px; text-align: center; color: #94a3b8; background: #f7f8fb; }

        .code-snippet {
            margin-top: 20px; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;
            background: #0f172a;
        }
        .code-snippet header {
            background: rgba(255,255,255,.08); display: flex; justify-content: space-between;
            padding: 10px 16px; align-items: center; color: #94a3b8; font-size: .8rem;
        }
        .code-snippet .copy-btn { border: 1px solid rgba(255,255,255,.4); color: #fff; }
        .code-snippet pre { margin: 0; padding: 16px; }
        .code-snippet code { color: #f8fafc; font-family: 'Courier New', monospace; white-space: pre-wrap; display: block; }

        .empty-state { text-align: center; color: #94a3b8; padding: 60px 0; }

        /* Visual image card */
        .visual-card { display: none; }

        /* Copyable code card */
        .code-card {
            background: #1e1e2e; border-radius: 8px; overflow: hidden;
            margin-bottom: 20px; box-shadow: 0 1px 4px rgba(0,0,0,.2);
        }
        .code-card .code-header {
            display: flex; justify-content: space-between; align-items: center;
            padding: 6px 12px; background: #2a2a3e;
        }
        .code-card .code-lang { font-size: .72rem; color: #04AA6D; font-weight: 700; text-transform: uppercase; }
        .code-card .copy-btn {
            font-size: .72rem; color: #ccc; background: none; border: 1px solid #555;
            border-radius: 4px; padding: 2px 8px; cursor: pointer; transition: background .15s;
        }
        .code-card .copy-btn:hover { background: #3a3a5e; color: #fff; }
        .code-card pre {
            margin: 0; padding: 14px 12px; overflow-x: auto;
            font-size: .82rem; line-height: 1.6; color: #cdd6f4;
            font-family: 'Courier New', monospace; white-space: pre-wrap; word-break: break-word;
        }

        /* ── Reading progress bar (thin line under navbar) ── */
        #readingProgress {
            position: fixed; top: 48px; left: 0; height: 3px;
            background: #04AA6D; width: 0; z-index: 300; transition: width .1s;
        }

        /* ── Dark mode ── */
        body.dark-mode { background: #0f0f1a; color: #cdd6f4; }
        body.dark-mode .lesson-sidebar { background: #1a1a2e; border-color: #2a2a3e; }
        body.dark-mode .lesson-sidebar .nav-link { color: #cdd6f4; }
        body.dark-mode .lesson-sidebar .nav-link:hover  { background: #1e1e3e; }
        body.dark-mode .lesson-sidebar .nav-link.active { background: #1e2e2e; color: #04AA6D; }
        body.dark-mode .lesson-content { background: #13131f; border-color: #2a2a3e; color: #cdd6f4; }
        body.dark-mode .lesson-content h4 { color: #cdd6f4; }
        body.dark-mode .lesson-right { background: #1a1a2e; }
        body.dark-mode .visual-card { background: #1e1e2e; border-color: #2a2a3e; }
        body.dark-mode .visual-card .placeholder { background: #1a1a2e; color: #555; }

        /* ── Mobile: stack columns ── */
        @media (max-width: 1024px) {
            .lesson-panels { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .lesson-layout { flex-direction: column; }
            .lesson-sidebar { width: 100%; border-right: none; border-bottom: 1px solid #e3e7ef; }
            .lesson-content { padding: 24px 16px 40px; }
        }
    </style>
</head>
<body>

    <!-- Thin reading progress line at top — fills as user scrolls the article -->
    <div id="readingProgress"></div>

    <!-- ── Top navbar — dark style matching /blog ── -->
    <nav class="lesson-navbar">
        <span class="brand">Wheelder</span>
        <!-- need space between controls and brand labl-->
         &nbsp;&nbsp;&nbsp;
        <div class="controls">
            <i id="start"            class="fas fa-play"         title="Read aloud"></i>
            <i id="pause"            class="fas fa-pause"        title="Pause"></i>
            <i id="resume"           class="fas fa-step-forward" title="Resume"></i>
            <span class="sep">|</span>
            <i id="increaseFontSize" class="fas fa-search-plus"  title="Increase font"></i>
            <i id="decreaseFontSize" class="fas fa-search-minus" title="Decrease font"></i>
            <span class="sep">|</span>
            <i id="copyToClipboard"  class="far fa-copy"         title="Copy article"></i>
            <i id="printContent"     class="fas fa-print"        title="Print"></i>
            <i id="shareButton"      class="fas fa-share-alt"    title="Share"></i>
            <span class="sep">|</span>
            <i id="darkMode"         class="fas fa-moon"         title="Dark mode"></i>
            <i id="lightMode"        class="fas fa-sun"          title="Light mode"></i>
            <span class="sep">|</span>
            <i id="fullscreen"       class="fas fa-expand"       title="Fullscreen"></i>
        </div>
    </nav>

    <!-- ── Three-column layout ── -->
    <div class="lesson-layout">

        <!-- Left: lesson navigation list -->
        <aside class="lesson-sidebar" id="sidebarMenu">
            <div class="sidebar-header">
                <h6>Lessons</h6>
                <a href="<?php echo url('/lesson/cms2'); ?>" class="new-lesson-btn">+ New Lesson</a>
            </div>
            <nav>
                <?php
                // Render all lesson titles as sidebar links; mark the active one
                $all = $lesson->run_query("SELECT id, title FROM lessons ORDER BY id DESC");
                if ($all && $all->num_rows > 0) {
                    while ($r = $all->fetch_assoc()) {
                        $slug    = urlencode(str_replace(' ', '_', strtolower($r['title'])));
                        $label   = htmlspecialchars($r['title'], ENT_QUOTES, 'UTF-8');
                        // Highlight the currently viewed lesson so the user knows where they are
                        $active  = ($row && $row['id'] == $r['id']) ? ' active' : '';
                        echo '<a class="nav-link' . $active . '" href="' . url('/lesson') . '?t=' . $slug . '">' . $label . '</a>';
                    }
                } else {
                    echo '<p class="px-3 text-muted" style="font-size:.8rem">No lessons yet.</p>';
                }
                ?>
            </nav>
        </aside>

        <!-- Center: hero + lesson card -->
        <main class="lesson-content">
            <section class="lesson-hero">
                <h1>Ask to Learn</h1>
                <p>Review AI-generated drafts in the same clean view as /demo.</p>
            </section>

            <?php if ($row): ?>
                <article class="lesson-card lesson-scroll">
                    <header>
                        <h4><?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?></h4>
                        <?php if (!empty($row['category'])): ?>
                            <span class="badge"><?php echo htmlspecialchars($row['category'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php endif; ?>
                        <div class="lesson-meta">Last updated: <?php echo htmlspecialchars($row['created_at'] ?? '—', ENT_QUOTES, 'UTF-8'); ?></div>
                    </header>

                    <div class="lesson-body" id="contentDiv">
                        <?php
                        // formatMarkdown renders headings, bold, lists etc. from AI-generated content
                        echo formatMarkdown($row['content'] ?? '');
                        ?>
                    </div>

                    <div class="lesson-image">
                        <?php if ($row && !empty($row['image_url'])): ?>
                            <img src="<?php echo htmlspecialchars($row['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="Lesson visual"
                                 onerror="this.parentElement.innerHTML='<div class=\'placeholder\'>Image unavailable</div>'">
                        <?php else: ?>
                            <div class="placeholder">No visual provided for this lesson.</div>
                        <?php endif; ?>
                    </div>

                    <?php if ($row && !empty($row['code_block'])): ?>
                        <div class="code-snippet">
                            <header>
                                <span><?php echo htmlspecialchars($row['category'] ?? 'Code', ENT_QUOTES, 'UTF-8'); ?> snippet</span>
                                <button class="copy-btn" onclick="copyCode(this)">Copy</button>
                            </header>
                            <pre id="codeContent"><?php echo htmlspecialchars($row['code_block'], ENT_QUOTES, 'UTF-8'); ?></pre>
                        </div>
                    <?php endif; ?>
                </article>
            <?php else: ?>
                <div class="lesson-card empty-state">
                    <p>No lesson found. Generate one from CMS2 to see it here.</p>
                </div>
            <?php endif; ?>
        </main>

    </div><!-- /.lesson-layout -->

    <script>
        // ── Copy code block to clipboard ──
        function copyCode(btn) {
            const code = document.getElementById('codeContent');
            if (!code) return;
            navigator.clipboard.writeText(code.innerText).then(function () {
                btn.textContent = 'Copied!';
                setTimeout(function () { btn.textContent = 'Copy'; }, 2000);
            }).catch(function () {
                // Fallback for browsers without Clipboard API
                const ta = document.createElement('textarea');
                ta.value = code.innerText;
                ta.style.position = 'absolute'; ta.style.left = '-9999px';
                document.body.appendChild(ta); ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
                btn.textContent = 'Copied!';
                setTimeout(function () { btn.textContent = 'Copy'; }, 2000);
            });
        }

        // ── Share button — Web Share API with clipboard fallback ──
        document.getElementById('shareButton').addEventListener('click', function () {
            if (navigator.share) {
                navigator.share({ title: 'Check out this lesson', url: window.location.href })
                    .catch(function (e) { console.error('Share error', e); });
            } else {
                prompt('Copy this URL to share:', window.location.href);
            }
        });

        // ── Dark / light mode ──
        document.getElementById('darkMode').addEventListener('click',  function () { document.body.classList.add('dark-mode'); });
        document.getElementById('lightMode').addEventListener('click', function () { document.body.classList.remove('dark-mode'); });

        // ── Font size controls ──
        document.getElementById('increaseFontSize').addEventListener('click', function () {
            const el = document.getElementById('contentDiv');
            el.style.fontSize = (parseFloat(window.getComputedStyle(el).fontSize) * 1.15) + 'px';
        });
        document.getElementById('decreaseFontSize').addEventListener('click', function () {
            const el = document.getElementById('contentDiv');
            el.style.fontSize = (parseFloat(window.getComputedStyle(el).fontSize) / 1.15) + 'px';
        });

        // ── Print ──
        document.getElementById('printContent').addEventListener('click', function () {
            window.print ? window.print() : alert('Print not supported in this browser.');
        });

        // ── Copy article to clipboard ──
        document.getElementById('copyToClipboard').addEventListener('click', function () {
            const el = document.getElementById('contentDiv');
            const ta = document.createElement('textarea');
            ta.value = el ? el.innerText : '';
            ta.style.position = 'absolute'; ta.style.left = '-9999px';
            document.body.appendChild(ta); ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
            alert('Article copied to clipboard!');
        });

        // ── Text-to-speech ──
        const synth = window.speechSynthesis;
        const contentDiv = document.getElementById('contentDiv');
        const sentences = contentDiv ? contentDiv.innerText.split('. ') : [];
        let currentSentence = 0;

        function speakText(startIndex) {
            if (startIndex >= sentences.length) return;
            synth.cancel();
            const utterance = new SpeechSynthesisUtterance(sentences[startIndex]);
            utterance.onend = function () {
                currentSentence = startIndex + 1;
                speakText(currentSentence);
            };
            synth.speak(utterance);
        }

        document.getElementById('start').addEventListener('click',  function () { speakText(currentSentence); });
        document.getElementById('pause').addEventListener('click',  function () { synth.pause(); });
        document.getElementById('resume').addEventListener('click', function () { synth.resume(); });
        window.onbeforeunload = function () { synth.cancel(); };

        // ── Reading progress bar — fills as user scrolls the center article ──
        const articleEl = document.querySelector('.lesson-scroll');
        function updateProgress() {
            if (!articleEl) return;
            const total = articleEl.scrollHeight - articleEl.clientHeight;
            const pct   = total > 0 ? (articleEl.scrollTop / total) * 100 : 0;
            document.getElementById('readingProgress').style.width = pct + '%';
        }
        if (articleEl) articleEl.addEventListener('scroll', updateProgress);

        // ── Fullscreen toggle ──
        document.getElementById('fullscreen').addEventListener('click', function () {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen()
                    .catch(function (err) { console.error('Fullscreen error:', err); });
            } else {
                document.exitFullscreen()
                    .catch(function (err) { console.error('Exit fullscreen error:', err); });
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

</body>
</html>
