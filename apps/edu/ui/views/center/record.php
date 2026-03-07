<?php
// $path = __DIR__;
include __DIR__ . '/AppController.php';

// Include top.php for url() helper — center sits one level higher than /learn/backup,
// so the relative path uses one fewer ../ segments.
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/top.php';
require_once dirname(__DIR__, 2) . '/layouts/legacy_split.php';

$note = new AppController();

 
$blog = $note;

// Auth is intentionally disabled for demo mode — anyone with the URL can use the app.
// API costs are protected by: CSRF tokens (no forged requests) + rate limiting (10 req/60s per session).
// To require login, uncomment the line below:
// $note->check_auth();

// Fetch all past conversations for the sidebar (newest first)
$conversations = $note->getConversations();

// Central research thread — opens by default when no ?view= is supplied
$defaultCenterSession = 'conv_698e63c6054173.74206200';

// Pre-compute URLs so we can seamlessly point to the root path (no /center in the address bar)
$centerBaseUrl      = url('/');
$centerAjaxEndpoint = url('/center/ajax');
$centerTtsEndpoint  = url('/center/tts');

// If the user clicked a past conversation in the sidebar, load it via GET ?view=session_id
// But NOT when a deepen POST is active — that means the user clicked "Circular Search/Deep Research"
// and we need app_src.php to process the deeper request instead of showing the old stored answer
$viewConversation = null;
if (empty($_POST['deepen'])) {
    $requestedSessionId = null;
    if (!empty($_GET['view'])) {
        // Sanitize the session_id from the URL to prevent injection
        $requestedSessionId = htmlspecialchars($_GET['view'], ENT_QUOTES, 'UTF-8');
    } elseif (!empty($defaultCenterSession)) {
        $requestedSessionId = $defaultCenterSession;
    }

    if (!empty($requestedSessionId)) {
        $viewConversation = $note->getConversationById($requestedSessionId);
        if ($viewConversation && empty($_GET['view'])) {
            // Mirror the default session into $_GET so active-state detection keeps working
            $_GET['view'] = $requestedSessionId;
        }
    }
}

$lastEntryIndex = null;
$lastEntry = null;
if (!empty($viewConversation)) {
    // array_key_last keeps the structure intact even if numeric indexes are non-sequential.
    $lastEntryIndex = function_exists('array_key_last') ? array_key_last($viewConversation) : (count($viewConversation) ? array_keys($viewConversation)[count($viewConversation) - 1] : null);
    if ($lastEntryIndex !== null) {
        $lastEntry = $viewConversation[$lastEntryIndex];

        if (empty($lastEntry['image']) && !empty($lastEntry['question'])) {
            try {
                // WHY: legacy rows often lack image URLs, so we lazily regenerate one to keep the UI consistent.
                $regeneratedImage = $note->generateImage($lastEntry['question']);
                if (!empty($regeneratedImage)) {
                    $viewConversation[$lastEntryIndex]['image'] = $regeneratedImage;
                    $lastEntry = $viewConversation[$lastEntryIndex];

                    if (method_exists($note, 'updateConversationImage') && !empty($lastEntry['id'])) {
                        $note->updateConversationImage($lastEntry['id'], $regeneratedImage);
                    }
                }
            } catch (Throwable $imgErr) {
                // WHY: we log instead of surfacing to the user so UX remains smooth even if image regeneration fails.
                error_log('center/record image regeneration failed: ' . $imgErr->getMessage());
            }
        }
    }
}

// Only start a session if one isn't already active (avoids Notice when the router starts one)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Public center experience — no access key required. Rate limiting + CSRF still
// protect API usage, so we just keep URLs clean.
$keyParam = '';
$newResearchUrl = $centerBaseUrl;

// Generate a CSRF token once per session — sent with every AJAX POST
// so the backend can verify the request came from our page, not a forged form
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// =========================================================================
// SECTION 1: Extra <head> CSS
// =========================================================================
ob_start();
?>
<style>
    /* Textarea shadow — matches the panels below */
    #queryInput { box-shadow: 8px 8px 8px #ccc; }

    /* Scrollable text panel — holds Q&A pairs in questionnaire style */
    .content {
        position: relative; width: 100%; padding: 10px; height: 600px;
        overflow-y: auto; overflow-x: hidden; background-color: #fff;
        white-space: normal; border: 2px solid #ccc;
        font-family: Verdana, sans-serif; font-size: 17px; font-weight: 400;
        border-radius: 10px; box-shadow: 8px 8px 8px #ccc;
    }
    .qa-question {
        font-weight: 700; font-size: 15px; color: #212529;
        margin-top: 12px; margin-bottom: 8px; padding: 10px 14px;
        border: 2px solid #212529; border-radius: 6px; background-color: #f8f9fa;
    }
    .qa-answer {
        margin-bottom: 16px; padding-bottom: 8px;
        border-bottom: 1px dashed #ddd; line-height: 1.7;
    }
    .qa-answer ol, .qa-answer ul { padding-left: 28px; margin: 8px 0; }
    .qa-answer li { margin-bottom: 4px; }
    .qa-answer p { margin: 6px 0; }
    .qa-answer h3 { font-size: 1.15em; margin: 12px 0 6px; }
    .qa-answer h4 { font-size: 1.05em; margin: 10px 0 4px; }
    .qa-answer h5 { font-size: 0.95em; margin: 8px 0 4px; }
    .qa-answer code { background-color: #f0f0f0; padding: 1px 5px; border-radius: 3px; font-size: 0.92em; }
    .qa-answer pre {
        background-color: #1e1e1e; color: #d4d4d4; padding: 12px 14px;
        border-radius: 6px; overflow-x: auto; margin: 10px 0; font-size: 0.88em; line-height: 1.5;
    }
    .qa-answer pre code { background: none; padding: 0; font-size: inherit; }
    .qa-answer hr { border: none; border-top: 1px solid #ddd; margin: 12px 0; }
    .qa-answer strong { font-weight: 700; }
    .qa-answer em { font-style: italic; }

    .dark-mode .qa-answer pre { background-color: #333; color: #fff; }
    .dark-mode .qa-answer code { background-color: #444; color: #fff; }

    /* Image panel */
    .contentImage {
        position: relative; width: 100%; height: 600px; overflow: hidden;
        border: 2px solid #ccc; border-radius: 10px; box-shadow: 8px 8px 8px #ccc;
        display: flex; align-items: center; justify-content: center; background-color: #f9f9f9;
    }
    .contentImage img { width: 100%; height: 100%; object-fit: cover; }

    .img-fullscreen-btn {
        position: absolute; top: 10px; right: 10px; z-index: 10;
        background: rgba(0,0,0,0.6); color: #fff; border: none; border-radius: 6px;
        padding: 6px 10px; cursor: pointer; font-size: 16px; transition: background 0.2s;
    }
    .img-fullscreen-btn:hover { background: rgba(0,0,0,0.85); }

    .text-fullscreen-btn {
        position: sticky; top: 10px; float: right; z-index: 10;
        background: rgba(0,0,0,0.6); color: #fff; border: none; border-radius: 6px;
        padding: 6px 10px; cursor: pointer; font-size: 16px; transition: background 0.2s;
    }
    .text-fullscreen-btn:hover { background: rgba(0,0,0,0.85); }

    /* Text zoom overlay */
    .text-overlay {
        display: none; position: fixed; inset: 0; width: 100vw; height: 100vh; height: 100dvh;
        z-index: 99999; background: rgba(0,0,0,0.95); overflow-y: auto; -webkit-overflow-scrolling: touch;
    }
    .text-overlay.active { display: block; }
    .text-overlay-content {
        max-width: 800px; margin: 60px auto 40px; padding: 30px;
        color: #fff; font-family: Verdana, sans-serif; font-size: 18px; line-height: 1.7;
    }
    .text-overlay-content .qa-question { border: 2px solid #fff; padding: 8px 12px; margin-bottom: 16px; font-weight: 700; font-size: 1.1em; color: #fff; background: transparent; }
    .text-overlay-content .qa-answer { margin-bottom: 24px; }
    .text-overlay-content .qa-answer strong { font-weight: 700; }
    .text-overlay-content .qa-answer em { font-style: italic; }
    .text-overlay-content .qa-depth-label { color: #aaa; font-size: 0.85em; margin-bottom: 6px; }
    .text-overlay-content .qa-answer pre { background-color: #333; color: #d4d4d4; padding: 12px 14px; border-radius: 6px; overflow-x: auto; }
    .text-overlay-content .qa-answer code { background-color: #444; color: #fff; padding: 1px 5px; border-radius: 3px; }
    .text-overlay-content .qa-answer pre code { background: none; padding: 0; }
    .text-overlay-close {
        position: fixed; top: calc(18px + env(safe-area-inset-top, 0px)); right: calc(24px + env(safe-area-inset-right, 0px));
        background: none; border: none; color: #fff; font-size: 32px; cursor: pointer; z-index: 100000; min-width: 44px; min-height: 44px;
    }
    .text-overlay-close:hover { color: #ccc; }

    /* Fullscreen image overlay */
    .img-overlay {
        display: none; position: fixed; inset: 0; width: 100vw; height: 100vh; height: 100dvh;
        z-index: 99999; background: rgba(0,0,0,0.95); align-items: center; justify-content: center;
        touch-action: none; -webkit-overflow-scrolling: touch; overflow: hidden;
    }
    .img-overlay.active { display: flex; }
    .img-overlay img { max-width: 95vw; max-height: 90vh; max-height: 90dvh; object-fit: contain; border-radius: 8px; touch-action: pinch-zoom; }
    .img-overlay-close {
        position: absolute; top: calc(18px + env(safe-area-inset-top, 0px)); right: calc(24px + env(safe-area-inset-right, 0px));
        background: none; border: none; color: #fff; font-size: 32px; cursor: pointer; z-index: 100000; min-width: 44px; min-height: 44px;
    }
    .img-overlay-close:hover { color: #ccc; }

    /* Print styles */
    @media print {
        header, .navbar, .sidebar, .controls, #sidebarMenu, #questionInput, #askBtn, #deepenBtn, #clearBtn,
        #depthBadge, #loadingSpinner, #errorMsg, .img-fullscreen-btn, .text-fullscreen-btn,
        .img-overlay, .text-overlay, .mb-5, .d-flex.gap-2, .row.justify-content-center,
        .legacy-brand-bar, .legacy-toolbar, .legacy-sidebar { display: none !important; }
        .content { height: auto !important; overflow: visible !important; border: none !important; box-shadow: none !important; font-size: 12pt !important; padding: 0 !important; }
        .contentImage { height: auto !important; max-height: 400px !important; overflow: visible !important; border: none !important; box-shadow: none !important; page-break-before: always; }
        .contentImage img { max-width: 100% !important; height: auto !important; object-fit: contain !important; }
        .qa-question { border: 1px solid #000 !important; color: #000 !important; background: #fff !important; }
        body { background: #fff !important; color: #000 !important; }
    }

    /* Dark mode */
    .dark-mode { background-color: #000; color: #fff; }
    .dark-mode .content { background-color: #1a1a1a; color: #fff; border-color: #444; }
    .dark-mode .qa-question { color: #eee; border-color: #888; background-color: #2a2a2a; }
    .dark-mode .qa-answer { border-color: #444; }
    .dark-mode .contentImage { background-color: #1a1a1a; border-color: #444; }
    .dark-mode .form-control { background-color: #1a1a1a; color: #fff; border-color: #444; }
    .dark-mode .controls { background-color: #000; color: #fff; }

    /* TTS word highlight */
    .tts-word-highlight { background-color: #ffe066; border-radius: 3px; padding: 0 2px; transition: background-color 0.15s ease; }
    .dark-mode .tts-word-highlight { background-color: #665500; color: #fff; }

    /* Sidebar conversation items */
    .sidebar-conv-item { margin: 3px; border: 1px solid #ccc; border-radius: 5px; box-shadow: 3px 3px 3px #ccc; display: flex; align-items: center; max-width: 100%; overflow: hidden; }
    .sidebar-conv-link {
        color: #000; font-size: 13px; font-weight: 400; text-decoration: none;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1; min-width: 0; padding: 6px 8px; line-height: 1.3;
    }
    .sidebar-conv-link:hover { background-color: #f0f0f0; }
    .sidebar-conv-link.active { background-color: #212529; color: #fff; border-radius: 5px; }
    .conv-date { font-size: 10px; color: #999; display: block; white-space: normal; transition: color 0.15s; margin-top: 1px; }
    .conv-actions { display: flex; align-items: center; gap: 6px; padding-right: 6px; flex-shrink: 0; margin-left: auto; }
    .conv-actions i { font-size: 11px; color: #999; cursor: pointer; transition: color 0.15s; }
    .conv-actions .conv-archive:hover { color: #0d6efd; }
    .conv-actions .conv-delete:hover { color: #dc3545; }

    .dark-mode .sidebar-conv-link { color: #fff; background-color: #000; }
    .dark-mode .conv-actions i { color: #666; }
    .dark-mode .conv-actions .conv-archive:hover { color: #6ea8fe; }
    .dark-mode .conv-actions .conv-delete:hover { color: #ea868f; }

    a { text-decoration: none; }

    /* Prompt Modal */
    #promptModalBackdrop { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.75); z-index: 1050; opacity: 0; transition: opacity 0.3s ease; }
    #promptModalBackdrop.show { opacity: 1; }
    #promptModal {
        display: none; position: fixed; z-index: 1060; top: 50%; left: 50%;
        transform: translate(-50%, -50%) rotate(0deg) scale(0);
        width: 92vw; max-width: 720px; max-height: 90vh; background: #fff;
        border-radius: 16px; box-shadow: 0 12px 40px rgba(0,0,0,0.3);
        padding: 24px; overflow-y: auto; will-change: transform, opacity;
    }
    @keyframes promptSpinIn {
        0%   { transform: translate(-50%, -50%) rotate(0deg)   scale(0);   opacity: 0; }
        60%  { transform: translate(-50%, -50%) rotate(540deg) scale(1.05); opacity: 1; }
        80%  { transform: translate(-50%, -50%) rotate(700deg) scale(0.97); opacity: 1; }
        100% { transform: translate(-50%, -50%) rotate(720deg) scale(1);    opacity: 1; }
    }
    @keyframes promptSpinOut {
        0%   { transform: translate(-50%, -50%) rotate(0deg)   scale(1);  opacity: 1; }
        100% { transform: translate(-50%, -50%) rotate(-360deg) scale(0); opacity: 0; }
    }
    #promptModal.spin-in { display: block; animation: promptSpinIn 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards; }
    #promptModal.spin-out { display: block; animation: promptSpinOut 0.45s ease-in forwards; }
    #promptModal .prompt-modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
    #promptModal .prompt-modal-header h4 { margin: 0; font-size: 1.25rem; font-weight: 700; }
    #promptModalClose { background: none; border: none; font-size: 1.6rem; cursor: pointer; color: #333; line-height: 1; padding: 4px 8px; }
    #promptModalClose:hover { color: #000; }
    #promptModalTextarea {
        width: 100%; min-height: 220px; max-height: 55vh; border: 2px solid #dee2e6;
        border-radius: 10px; padding: 14px; font-size: 16px; font-family: inherit; resize: vertical; outline: none; transition: border-color 0.2s;
    }
    #promptModalTextarea:focus { border-color: #333; box-shadow: 0 0 0 3px rgba(0,0,0,0.08); }
    #promptModal .prompt-modal-footer { display: flex; gap: 10px; margin-top: 16px; flex-wrap: wrap; }
    #promptModal .prompt-modal-footer .btn { min-height: 44px; font-size: 15px; }
    #promptCharCount { text-align: right; font-size: 0.8rem; color: #888; margin-top: 4px; }
    .dark-mode #promptModal { background: #1e1e1e; color: #e0e0e0; }
    .dark-mode #promptModalTextarea { background: #2a2a2a; color: #e0e0e0; border-color: #555; }
    .dark-mode #promptModalTextarea:focus { border-color: #aaa; box-shadow: 0 0 0 3px rgba(255,255,255,0.08); }
    .dark-mode #promptModalClose { color: #ccc; }
    .dark-mode #promptModalClose:hover { color: #fff; }

    /* Toolbar icons inside white toolbar */
    .center-toolbar i { cursor: pointer; margin: 0 3px; font-size: 16px; color: #333; }
    .center-toolbar i:hover { color: #000; }
    .center-toolbar .navbar-branding { flex-shrink: 0; white-space: nowrap; padding: 0 16px; font-family: Verdana, sans-serif; font-size: 15px; color: #cc0000; }
    .center-toolbar .footer-heart { color: #cc0000; margin-right: 4px; font-size: 17px; }

    /* Responsive */
    @media (max-width: 767.98px) {
        .content { height: 350px; font-size: 14px; box-shadow: 4px 4px 4px #ccc; }
        .contentImage { height: 250px; margin-top: 12px; box-shadow: 4px 4px 4px #ccc; }
        #promptModal { width: 96vw; padding: 16px; border-radius: 12px; }
        #promptModal.spin-in { animation-duration: 0.6s; }
        #promptModalTextarea { min-height: 180px; font-size: 15px; }
    }
    @media (prefers-reduced-motion: reduce) {
        #promptModal.spin-in { animation: none; transform: translate(-50%, -50%) scale(1); opacity: 1; }
        #promptModal.spin-out { animation: none; display: none; }
    }
</style>
<?php
$extraHead = ob_get_clean();

// =========================================================================
// SECTION 2: Toolbar HTML
// =========================================================================
ob_start();
?>
<span class="center-toolbar d-flex align-items-center gap-1 flex-wrap">
    <button type="button" class="btn btn-outline-dark btn-sm" data-legacy-toggle="#legacySidebar" aria-expanded="false" aria-controls="legacySidebar" title="Toggle sidebar">=</button>
    <i id="start" class="fas fa-play" title="Read aloud"></i>
    <i id="pause" class="fas fa-pause" title="Pause"></i>
    <i id="resume" class="fas fa-step-forward" title="Resume"></i>
    <span>|</span>
    <i id="fullscreen" class="fas fa-expand" title="Fullscreen"></i>
    <span>|</span>
    <i id="copyToClipboard" class="far fa-copy" title="Copy"></i>
    <span>|</span>
    <i id="printContent" class="fas fa-print" title="Print"></i>
    <span>|</span>
    <i class="fas fa-share-alt" id="shareButton" title="Share"></i>
    <span>|</span>
    <i id="increaseFontSize" class="fas fa-search-plus" title="Increase font"></i>
    <i id="decreaseFontSize" class="fas fa-search-minus" title="Decrease font"></i>
    <span>|</span>
    <i id="darkMode" class="fas fa-moon" title="Dark mode"></i>
    <i id="lightMode" class="fas fa-sun" title="Light mode"></i>
    <span class="navbar-branding d-none d-md-inline ms-auto">
        <span class="footer-heart">&#9829;</span>Proudly made in Canada.
    </span>
</span>
<?php
$toolbarHtml = ob_get_clean();

// =========================================================================
// SECTION 3: Sidebar HTML
// =========================================================================
ob_start();
?>
<div class="pt-3 px-2" id="sidebarMenu">
    <ul class="nav flex-column list-unstyled">
        <li class="sidebar-conv-item">
            <a href="<?php echo htmlspecialchars($newResearchUrl, ENT_QUOTES, 'UTF-8'); ?>" class="sidebar-conv-link <?php echo empty($_GET['view']) && empty($_POST['ask']) && empty($_POST['deepen']) ? 'active' : ''; ?>">
                <i class="fas fa-plus"></i> New Research
            </a>
        </li>
        <li class="sidebar-conv-item">
            <a href="/releases" class="sidebar-conv-link">
                <i class="fas fa-rocket"></i> Latest Features
            </a>
        </li>
        <?php foreach ($conversations as $conv):
            $label = mb_substr($conv['question'], 0, 30);
            if (mb_strlen($conv['question']) > 30) $label .= '...';
            $isActive = (!empty($_GET['view']) && $_GET['view'] === $conv['session_id']) ? 'active' : '';
        ?>
        <li class="sidebar-conv-item" data-session="<?php echo htmlspecialchars($conv['session_id']); ?>">
            <a href="<?php echo $centerBaseUrl; ?>?view=<?php echo urlencode($conv['session_id']); ?><?php echo $keyParam ? str_replace('&', '&amp;', $keyParam) : ''; ?>" class="sidebar-conv-link flex-grow-1 <?php echo $isActive; ?>" title="<?php echo htmlspecialchars($conv['question']); ?>">
                <?php echo htmlspecialchars($label); ?>
                <span class="conv-date"><?php echo $conv['created_at']; ?></span>
            </a>
            <span class="conv-actions">
                <i class="fas fa-box-archive conv-archive" title="Archive this research" data-session="<?php echo htmlspecialchars($conv['session_id']); ?>"></i>
                <i class="fas fa-trash conv-delete" title="Delete this research" data-session="<?php echo htmlspecialchars($conv['session_id']); ?>"></i>
            </span>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php
$sidebarHtml = ob_get_clean();

// =========================================================================
// SECTION 4a: Above panels — title, query input, buttons, loading, error, prompt modal
// =========================================================================
ob_start();
?>
<h2 class="text-center mb-3">Ask to Learn</h2>

<div class="mb-3">
    <textarea class="form-control" id="queryInput" rows="2"
        readonly
        style="overflow-y:hidden; resize:none; min-height:60px; max-height:240px; cursor:pointer;"
        placeholder="Type your question here..."><?php
        if (!empty($viewConversation)) {
            $lastEntry = end($viewConversation);
            echo htmlspecialchars($lastEntry['question'] ?? '');
        }
    ?></textarea>
</div>

<div class="d-flex gap-2 mb-3 flex-wrap">
    <button type="button" id="askBtn" class="btn btn-dark" style="display:none;">Ask</button>
    <button type="button" id="deepenBtn" class="btn btn-outline-dark" style="display:none;">Circular Search/Deep Research</button>
    <button type="button" id="clearBtn" class="btn btn-dark" style="display:none;">Clear</button>
    <span id="depthBadge" class="badge bg-dark fs-6 ms-auto align-self-center" style="display:none;"></span>
</div>

<div id="loadingSpinner" class="text-center mb-3" style="display:none;">
    <div class="spinner-border text-dark" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <p class="mt-2 text-muted">Generating response...</p>
</div>

<div id="errorMsg" class="alert alert-danger mb-3" style="display:none;" role="alert"></div>

<!-- Prompt Modal -->
<div id="promptModalBackdrop"></div>
<div id="promptModal" role="dialog" aria-labelledby="promptModalTitle" aria-modal="true">
    <div class="prompt-modal-header">
        <h4 id="promptModalTitle">Write Your Prompt</h4>
        <button type="button" id="promptModalClose" aria-label="Close">&times;</button>
    </div>
    <textarea id="promptModalTextarea" spellcheck="true" lang="en" autocomplete="off" autocorrect="on"
        placeholder="Take your time... write a clear, detailed prompt to get the best answer."></textarea>
    <div id="promptCharCount">0 characters</div>
    <div class="prompt-modal-footer">
        <button type="button" id="promptModalAskBtn" class="btn btn-dark">Ask</button>
        <button type="button" id="promptModalClearBtn" class="btn btn-outline-secondary">Clear</button>
        <button type="button" id="promptModalCancelBtn" class="btn btn-outline-dark">Cancel</button>
    </div>
</div>
<?php
$abovePanelsHtml = ob_get_clean();

// =========================================================================
// SECTION 4b: Left pane — answer panel only
// =========================================================================
ob_start();
?>
<div id="answerPanel" class="content" style="<?php echo empty($viewConversation) ? 'display:none;' : ''; ?>">
    <button class="text-fullscreen-btn" title="Focus view" onclick="openTextOverlay()">
        <i class="fas fa-expand"></i>
    </button>
    <?php if (!empty($viewConversation)):
        foreach ($viewConversation as $entry): ?>
            <?php if (!empty($entry['depth_level']) && $entry['depth_level'] > 0): ?>
                <div class="qa-depth-label">Depth Level <?php echo (int)$entry['depth_level']; ?>/7</div>
            <?php endif; ?>
            <div class="qa-question"><?php echo htmlspecialchars($entry['question'] ?? ''); ?></div>
            <div class="qa-answer"><?php echo $entry['answer']; ?></div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php
$leftPaneHtml = ob_get_clean();

// =========================================================================
// SECTION 5: Right pane — image panel
// =========================================================================
ob_start();
?>
<div class="contentImage" id="imagePanel">
    <?php if (!empty($lastEntry['image'])): ?>
        <button class="img-fullscreen-btn" title="View fullscreen" onclick="openImageOverlay(this)">
            <i class="fas fa-expand"></i>
        </button>
        <button class="img-fullscreen-btn" id="regenerateImageBtn" title="Regenerate image" style="right:60px;" onclick="regenerateImage()">
            <i class="fas fa-redo"></i>
        </button>
        <img src="<?php echo htmlspecialchars($lastEntry['image']); ?>" alt="Generated image"/>
    <?php endif; ?>
</div>

<!-- Fullscreen image overlay -->
<div class="img-overlay" id="imgOverlay">
    <button class="img-overlay-close" title="Close" onclick="closeImageOverlay()">&times;</button>
    <img id="imgOverlayImg" src="" alt="Fullscreen image"/>
</div>

<!-- Text focus overlay -->
<div class="text-overlay" id="textOverlay">
    <button class="text-overlay-close" title="Close" onclick="closeTextOverlay()">&times;</button>
    <div class="text-overlay-content" id="textOverlayContent"></div>
</div>
<?php
$rightPaneHtml = ob_get_clean();

// =========================================================================
// SECTION 6: Extra scripts
// =========================================================================
ob_start();
?>
<script>
    // AJAX State
    var ajaxState = {
        sessionId: '', originalQuestion: '', prevAnswer: '', depthLevel: 0, lastRowId: 0
    };
    var csrfToken = <?php echo json_encode($_SESSION['csrf_token']); ?>;
    var accessKey = <?php echo json_encode($_SESSION['demo_access_key'] ?? ''); ?>;

    <?php if (!empty($viewConversation) && !empty($lastEntry)): ?>
    ajaxState.sessionId        = <?php echo json_encode($_GET['view'] ?? ''); ?>;
    ajaxState.originalQuestion = <?php echo json_encode($lastEntry['question'] ?? ''); ?>;
    ajaxState.prevAnswer       = <?php echo json_encode($lastEntry['answer'] ?? ''); ?>;
    ajaxState.depthLevel       = 0;
    ajaxState.lastRowId        = <?php echo (int)($lastEntry['id'] ?? 0); ?>;
    <?php endif; ?>

    var sidebarNav     = document.querySelector('#sidebarMenu .nav');
    var askBtn         = document.getElementById('askBtn');
    var deepenBtn      = document.getElementById('deepenBtn');
    var clearBtn       = document.getElementById('clearBtn');
    var depthBadge     = document.getElementById('depthBadge');
    var queryInput     = document.getElementById('queryInput');
    var loadingSpinner = document.getElementById('loadingSpinner');
    var errorMsg       = document.getElementById('errorMsg');

    function ensurePanels() {
        var ap = document.getElementById('answerPanel');
        if (ap) { ap.style.display = ''; return; }
    }

    function scrollAnswerPanel() {
        var panel = document.getElementById('answerPanel');
        if (!panel) return;
        var questions = panel.querySelectorAll('.qa-question');
        if (questions.length > 0) {
            questions[questions.length - 1].scrollIntoView({ block: 'start', behavior: 'smooth' });
        } else {
            panel.scrollTop = panel.scrollHeight;
        }
    }

    // --- Image overlay helpers ---
    var _overlayScrollY = 0;
    function openImageOverlay(btn) {
        var panel = btn.closest('.contentImage');
        var img = panel ? panel.querySelector('img') : null;
        if (!img || !img.src) return;
        document.getElementById('imgOverlayImg').src = img.src;
        document.getElementById('imgOverlay').classList.add('active');
        _overlayScrollY = window.scrollY;
        document.body.style.position = 'fixed';
        document.body.style.top = '-' + _overlayScrollY + 'px';
        document.body.style.left = '0';
        document.body.style.right = '0';
        document.body.style.overflow = 'hidden';
        document.documentElement.style.overflow = 'hidden';
        window.scrollTo(0, _overlayScrollY);
    }
    function closeImageOverlay() {
        document.getElementById('imgOverlay').classList.remove('active');
        document.getElementById('imgOverlayImg').src = '';
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.left = '';
        document.body.style.right = '';
        document.body.style.overflow = '';
        document.documentElement.style.overflow = '';
        window.scrollTo(0, _overlayScrollY);
    }

    function openTextOverlay() {
        var panel = document.getElementById('answerPanel');
        if (!panel) return;
        var overlayContent = document.getElementById('textOverlayContent');
        overlayContent.innerHTML = '';
        var children = panel.children;
        for (var i = 0; i < children.length; i++) {
            if (children[i].classList.contains('text-fullscreen-btn')) continue;
            overlayContent.innerHTML += children[i].outerHTML;
        }
        document.getElementById('textOverlay').classList.add('active');
        _overlayScrollY = window.scrollY;
        document.body.style.position = 'fixed';
        document.body.style.top = '-' + _overlayScrollY + 'px';
        document.body.style.left = '0';
        document.body.style.right = '0';
        document.body.style.overflow = 'hidden';
        document.documentElement.style.overflow = 'hidden';
    }
    function closeTextOverlay() {
        document.getElementById('textOverlay').classList.remove('active');
        document.getElementById('textOverlayContent').innerHTML = '';
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.left = '';
        document.body.style.right = '';
        document.body.style.overflow = '';
        document.documentElement.style.overflow = '';
        window.scrollTo(0, _overlayScrollY);
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { closeImageOverlay(); closeTextOverlay(); }
    });

    // --- Regenerate Image ---
    function regenerateImage() {
        if (!ajaxState.originalQuestion) { alert('No question available to regenerate image for.'); return; }
        var imgPanel = document.getElementById('imagePanel');
        var regenBtn = document.getElementById('regenerateImageBtn');
        if (!imgPanel) return;
        if (regenBtn) { regenBtn.disabled = true; regenBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'; }

        var formData = new FormData();
        formData.append('regenerate_image', '1');
        formData.append('original_question', ajaxState.originalQuestion);
        formData.append('session_id', ajaxState.sessionId);
        formData.append('row_id', ajaxState.lastRowId || 0);
        formData.append('csrf_token', csrfToken);
        if (accessKey) formData.append('access_key', accessKey);

        fetch('<?php echo $centerAjaxEndpoint; ?>', { method: 'POST', body: formData })
        .then(function (response) { return response.text().then(function (text) { return { text: text, status: response.status, ct: response.headers.get('content-type') || '' }; }); })
        .then(function (res) {
            if (!res.text || !res.text.trim()) throw new Error('Server returned an empty response (HTTP ' + res.status + ').');
            if (res.ct.indexOf('application/json') === -1) throw new Error('Server returned HTML instead of JSON (HTTP ' + res.status + ').');
            try { return JSON.parse(res.text); } catch (e) { throw new Error('Server returned invalid JSON (HTTP ' + res.status + ').'); }
        })
        .then(function (data) {
            if (data.error) { alert('Image regeneration failed: ' + data.error); return; }
            if (data.image) {
                var imgEl = imgPanel.querySelector('img');
                if (imgEl) { imgEl.src = data.image; }
                else {
                    imgPanel.innerHTML =
                        '<button class="img-fullscreen-btn" title="View fullscreen" onclick="openImageOverlay(this)"><i class="fas fa-expand"></i></button>' +
                        '<button class="img-fullscreen-btn" id="regenerateImageBtn" title="Regenerate image" style="right:60px;" onclick="regenerateImage()"><i class="fas fa-redo"></i></button>' +
                        '<img src="' + data.image + '" alt="Generated image"/>';
                }
            }
        })
        .catch(function (err) { alert('Image regeneration error: ' + err.message); })
        .finally(function () { var btn = document.getElementById('regenerateImageBtn'); if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-redo"></i>'; } });
    }

    scrollAnswerPanel();

    <?php if (!empty($viewConversation)): ?>
    deepenBtn.style.display = 'inline-block';
    <?php endif; ?>

    // Auto-expand textarea
    function autoResizeTextarea() {
        queryInput.style.height = 'auto';
        queryInput.style.height = Math.min(queryInput.scrollHeight, 240) + 'px';
    }
    queryInput.addEventListener('input', autoResizeTextarea);
    autoResizeTextarea();

    // Prompt Modal
    var promptBackdrop  = document.getElementById('promptModalBackdrop');
    var promptModal     = document.getElementById('promptModal');
    var promptTextarea  = document.getElementById('promptModalTextarea');
    var promptCharCount = document.getElementById('promptCharCount');
    var promptCloseBtn  = document.getElementById('promptModalClose');
    var promptAskBtn    = document.getElementById('promptModalAskBtn');
    var promptClearBtn  = document.getElementById('promptModalClearBtn');
    var promptCancelBtn = document.getElementById('promptModalCancelBtn');
    var _promptModalOpen = false;

    function openPromptModal() {
        if (_promptModalOpen) return;
        _promptModalOpen = true;
        promptTextarea.value = queryInput.value;
        updatePromptCharCount();
        promptBackdrop.style.display = 'block';
        requestAnimationFrame(function () { promptBackdrop.classList.add('show'); });
        promptModal.classList.remove('spin-out');
        promptModal.classList.add('spin-in');
        document.body.style.overflow = 'hidden';
        setTimeout(function () { promptTextarea.focus(); }, 850);
    }
    function closePromptModal() {
        if (!_promptModalOpen) return;
        _promptModalOpen = false;
        queryInput.value = promptTextarea.value;
        autoResizeTextarea();
        promptBackdrop.classList.remove('show');
        promptModal.classList.remove('spin-in');
        promptModal.classList.add('spin-out');
        setTimeout(function () {
            promptModal.classList.remove('spin-out');
            promptModal.removeAttribute('style');
            promptBackdrop.removeAttribute('style');
            document.body.style.overflow = '';
        }, 500);
    }
    function updatePromptCharCount() {
        var len = promptTextarea.value.length;
        promptCharCount.textContent = len + ' character' + (len !== 1 ? 's' : '');
    }

    queryInput.addEventListener('click', openPromptModal);
    queryInput.addEventListener('focus', function () { openPromptModal(); queryInput.blur(); });
    askBtn.addEventListener('click', function (e) {
        if (!queryInput.value.trim() && !_promptModalOpen) { e.stopImmediatePropagation(); openPromptModal(); }
    }, true);
    promptCloseBtn.addEventListener('click', closePromptModal);
    promptCancelBtn.addEventListener('click', closePromptModal);
    promptBackdrop.addEventListener('click', closePromptModal);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && _promptModalOpen) closePromptModal(); });
    promptTextarea.addEventListener('input', updatePromptCharCount);
    promptClearBtn.addEventListener('click', function () { promptTextarea.value = ''; promptTextarea.focus(); updatePromptCharCount(); });
    promptAskBtn.addEventListener('click', function () {
        var text = promptTextarea.value.trim();
        if (!text) { promptTextarea.style.borderColor = '#dc3545'; setTimeout(function () { promptTextarea.style.borderColor = ''; }, 600); promptTextarea.focus(); return; }
        queryInput.value = text; autoResizeTextarea(); closePromptModal();
        setTimeout(function () { askBtn.click(); }, 150);
    });
    promptTextarea.addEventListener('keydown', function (e) { if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) { e.preventDefault(); promptAskBtn.click(); } });

    // addSidebarThread
    function addSidebarThread(sessionId, questionText) {
        if (!sidebarNav) return;
        var label = questionText.length > 30 ? questionText.substring(0, 30) + '...' : questionText;
        var safeLabel = label.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        var safeTitle = questionText.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        var safeSession = sessionId.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        var keyParam = '';
        var urlParams = new URLSearchParams(window.location.search);
        var sidebarKey = urlParams.get('key') || accessKey;
        if (sidebarKey) keyParam = '&key=' + encodeURIComponent(sidebarKey);
        var today = new Date();
        var dateStr = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');

        var li = document.createElement('li');
        li.className = 'sidebar-conv-item';
        li.setAttribute('data-session', sessionId);
        li.innerHTML =
            '<a href="<?php echo $centerBaseUrl; ?>?view=' + encodeURIComponent(sessionId) + keyParam + '"' +
            '   class="sidebar-conv-link flex-grow-1 active" title="' + safeTitle + '">' +
                safeLabel + '<span class="conv-date">' + dateStr + '</span>' +
            '</a>' +
            '<span class="conv-actions">' +
                '<i class="fas fa-box-archive conv-archive" title="Archive this research" data-session="' + safeSession + '"></i>' +
                '<i class="fas fa-trash conv-delete" title="Delete this research" data-session="' + safeSession + '"></i>' +
            '</span>';

        sidebarNav.querySelectorAll('.sidebar-conv-link').forEach(function (link) { link.classList.remove('active'); });
        var firstItem = sidebarNav.querySelector('li.sidebar-conv-item');
        if (firstItem && firstItem.nextSibling) { sidebarNav.insertBefore(li, firstItem.nextSibling); }
        else { sidebarNav.appendChild(li); }
    }

    // sendAjax
    function sendAjax(formData, questionText) {
        loadingSpinner.style.display = 'block';
        errorMsg.style.display = 'none';
        askBtn.disabled = true;
        deepenBtn.disabled = true;
        formData.append('csrf_token', csrfToken);
        if (accessKey) formData.append('access_key', accessKey);

        fetch('<?php echo $centerAjaxEndpoint; ?>', { method: 'POST', body: formData })
        .then(function (response) { return response.text().then(function (text) { return { text: text, status: response.status, ct: response.headers.get('content-type') || '' }; }); })
        .then(function (res) {
            if (!res.text || !res.text.trim()) throw new Error('Server returned an empty response (HTTP ' + res.status + '). Please try again.');
            if (res.ct.indexOf('application/json') === -1) throw new Error('Server returned HTML instead of JSON (HTTP ' + res.status + '). Please reload the page.');
            try { return JSON.parse(res.text); } catch (e) { throw new Error('Server returned invalid JSON (HTTP ' + res.status + '). Please try again.'); }
        })
        .then(function (data) {
            if (data.error) {
                loadingSpinner.style.display = 'none';
                errorMsg.textContent = data.error; errorMsg.style.display = 'block';
                askBtn.disabled = false; deepenBtn.disabled = false; return;
            }

            function renderBothPanels(imageUrl) {
                loadingSpinner.style.display = 'none';
                ensurePanels();
                var answerPanel = document.getElementById('answerPanel');
                var imagePanel  = document.getElementById('imagePanel');

                var depthHtml = '';
                if (data.depth_level >= 1) depthHtml = '<div class="qa-depth-label">Depth Level ' + data.depth_level + '/7</div>';

                answerPanel.innerHTML += depthHtml +
                    '<div class="qa-question">' + questionText.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</div>' +
                    '<div class="qa-answer">' + data.answer + '</div>';
                scrollAnswerPanel();

                if (imageUrl) {
                    imagePanel.innerHTML =
                        '<button class="img-fullscreen-btn" title="View fullscreen" onclick="openImageOverlay(this)"><i class="fas fa-expand"></i></button>' +
                        '<button class="img-fullscreen-btn" id="regenerateImageBtn" title="Regenerate image" style="right:60px;" onclick="regenerateImage()"><i class="fas fa-redo"></i></button>' +
                        '<img src="' + imageUrl + '" alt="Generated image"/>';
                }

                if (!ajaxState.sessionId && data.session_id) addSidebarThread(data.session_id, data.original_question || questionText);

                ajaxState.sessionId        = data.session_id;
                ajaxState.originalQuestion = data.original_question;
                ajaxState.prevAnswer       = data.prev_answer;
                ajaxState.depthLevel       = data.depth_level;

                if (data.depth_level < 7) deepenBtn.style.display = 'inline-block';
                else deepenBtn.style.display = 'none';

                if (data.depth_level >= 1) { depthBadge.textContent = 'Depth Level ' + data.depth_level + '/7'; depthBadge.style.display = 'inline-block'; }
                else depthBadge.style.display = 'none';

                askBtn.disabled = false; deepenBtn.disabled = false;

                // WHY: trigger auto-show hook after answer is rendered and panels are populated
                if (typeof window.onAnswerGenerated === 'function') {
                    try { window.onAnswerGenerated(); } catch (e) { console.error('onAnswerGenerated hook failed:', e); }
                }
            }

            if (data.image) {
                var preloader = new Image();
                var imageLoaded = false;
                function onImageReady() { if (imageLoaded) return; imageLoaded = true; clearTimeout(imageTimeout); renderBothPanels(data.image); }
                preloader.onload = onImageReady;
                preloader.onerror = onImageReady;
                var imageTimeout = setTimeout(onImageReady, 15000);
                preloader.src = data.image;
            } else {
                renderBothPanels('');
            }
        })
        .catch(function (err) {
            loadingSpinner.style.display = 'none';
            errorMsg.textContent = 'Request failed: ' + err.message; errorMsg.style.display = 'block';
            askBtn.disabled = false; deepenBtn.disabled = false;
        });
    }

    // ASK button
    askBtn.addEventListener('click', function () {
        var query = queryInput.value.trim();
        if (!query) { errorMsg.textContent = 'Please enter a question.'; errorMsg.style.display = 'block'; return; }
        queryInput.value = ''; autoResizeTextarea();
        var formData = new FormData();
        formData.append('ask', '1'); formData.append('query', query);
        if (ajaxState.sessionId) formData.append('session_id', ajaxState.sessionId);
        ajaxState.depthLevel = 0; depthBadge.style.display = 'none';
        sendAjax(formData, query);
    });

    // DEEPEN button
    deepenBtn.addEventListener('click', function () {
        if (!ajaxState.originalQuestion) { errorMsg.textContent = 'Ask a question first before deepening.'; errorMsg.style.display = 'block'; return; }
        var formData = new FormData();
        formData.append('deepen', '1');
        formData.append('depth_level', ajaxState.depthLevel + 1);
        formData.append('original_question', ajaxState.originalQuestion);
        formData.append('prev_answer', ajaxState.prevAnswer);
        formData.append('session_id', ajaxState.sessionId);
        sendAjax(formData, 'Deepening: ' + ajaxState.originalQuestion);
    });

    // CLEAR button
    clearBtn.addEventListener('click', function () {
        queryInput.value = ''; promptTextarea.value = ''; updatePromptCharCount(); autoResizeTextarea();
        var ap = document.getElementById('answerPanel'); if (ap) { ap.innerHTML = '<button class="text-fullscreen-btn" title="Focus view" onclick="openTextOverlay()"><i class="fas fa-expand"></i></button>'; ap.style.display = 'none'; }
        var ip = document.getElementById('imagePanel'); if (ip) ip.innerHTML = '';
        deepenBtn.style.display = 'none'; depthBadge.style.display = 'none';
        errorMsg.style.display = 'none'; loadingSpinner.style.display = 'none';
        ajaxState = { sessionId: '', originalQuestion: '', prevAnswer: '', depthLevel: 0, lastRowId: 0 };
    });

    // Enter key
    queryInput.addEventListener('keydown', function (e) { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); askBtn.click(); } });

    // Archive / Delete
    document.getElementById('sidebarMenu').addEventListener('click', function (e) {
        var target = e.target;
        var isArchive = target.classList.contains('conv-archive');
        var isDelete  = target.classList.contains('conv-delete');
        if (!isArchive && !isDelete) return;
        e.preventDefault(); e.stopPropagation();
        var sessionId = target.getAttribute('data-session');
        if (!sessionId) return;
        if (isDelete && !confirm('Delete this research? It will be moved to archive.')) return;

        var formData = new FormData();
        formData.append(isArchive ? 'archive' : 'delete', '1');
        formData.append('session_id', sessionId);
        formData.append('csrf_token', csrfToken);
        if (accessKey) formData.append('access_key', accessKey);

        fetch('<?php echo $centerAjaxEndpoint; ?>', { method: 'POST', body: formData })
        .then(function (response) { return response.text().then(function (text) { return { text: text, status: response.status, ct: response.headers.get('content-type') || '' }; }); })
        .then(function (res) {
            if (!res.text || !res.text.trim()) throw new Error('Server returned an empty response (HTTP ' + res.status + ').');
            if (res.ct.indexOf('application/json') === -1) throw new Error('Server returned non-JSON response. Please reload the page.');
            try { return JSON.parse(res.text); } catch (e) { throw new Error('Server returned invalid JSON (HTTP ' + res.status + ').'); }
        })
        .then(function (data) {
            if (data.error) { alert('Error: ' + data.error); return; }
            var listItem = target.closest('li.sidebar-conv-item');
            if (listItem) listItem.remove();
            if (ajaxState.sessionId === sessionId) window.location.href = '<?php echo $centerBaseUrl; ?>';
        })
        .catch(function (err) { alert('Request failed: ' + err.message); });
    });

    // Toolbar JS
    document.getElementById('shareButton').addEventListener('click', function () {
        if (navigator.share) { navigator.share({ title: 'Check out this link', url: window.location.href }).catch(function () {}); }
        else { prompt('Copy this URL and share it manually', window.location.href); }
    });
    document.getElementById('darkMode').addEventListener('click', function () { document.body.classList.add('dark-mode'); });
    document.getElementById('lightMode').addEventListener('click', function () { document.body.classList.remove('dark-mode'); });
    document.getElementById('increaseFontSize').addEventListener('click', function () {
        var cd = document.querySelector('.content'); if (!cd) return;
        cd.style.fontSize = (parseFloat(window.getComputedStyle(cd).fontSize) * 1.2) + 'px';
    });
    document.getElementById('decreaseFontSize').addEventListener('click', function () {
        var cd = document.querySelector('.content'); if (!cd) return;
        cd.style.fontSize = (parseFloat(window.getComputedStyle(cd).fontSize) / 1.2) + 'px';
    });
    document.getElementById('printContent').addEventListener('click', function () {
        var cd = document.querySelector('.content'); if (!cd) { alert('No content to print yet.'); return; } window.print();
    });
    document.getElementById('copyToClipboard').addEventListener('click', function () {
        var cd = document.querySelector('.content'); if (!cd) { alert('No content to copy yet.'); return; }
        var ta = document.createElement('textarea'); ta.value = cd.innerText; ta.setAttribute('readonly', '');
        ta.style.position = 'absolute'; ta.style.left = '-9999px';
        document.body.appendChild(ta); ta.select(); document.execCommand('copy'); document.body.removeChild(ta);
        alert('Content copied to clipboard!');
    });

    // TTS
    var ttsAudio = null, ttsPlainText = '', ttsIsSpeaking = false, ttsBoundaries = [], ttsHighlightTimer = null, ttsIsLoading = false;
    function ttsWrapWords() {
        var cd = document.querySelector('.content'); if (!cd) return;
        ttsPlainText = cd.innerText;
        var wi = 0, wk = document.createTreeWalker(cd, NodeFilter.SHOW_TEXT, null, false), nodes = [];
        while (wk.nextNode()) nodes.push(wk.currentNode);
        nodes.forEach(function (nd) {
            var parts = nd.textContent.split(/(\s+)/);
            if (parts.length <= 1 && !nd.textContent.trim()) return;
            var fr = document.createDocumentFragment();
            parts.forEach(function (p) {
                if (!p) return;
                if (/^\s+$/.test(p)) { fr.appendChild(document.createTextNode(p)); }
                else { var sp = document.createElement('span'); sp.setAttribute('data-tts-idx', wi); sp.textContent = p; fr.appendChild(sp); wi++; }
            });
            nd.parentNode.replaceChild(fr, nd);
        });
    }
    function ttsUnwrapWords() {
        var cd = document.querySelector('.content'); if (!cd) return;
        cd.querySelectorAll('.tts-word-highlight').forEach(function (el) { el.classList.remove('tts-word-highlight'); });
        cd.querySelectorAll('span[data-tts-idx]').forEach(function (sp) { sp.parentNode.replaceChild(document.createTextNode(sp.textContent), sp); });
        cd.normalize();
    }
    function ttsHighlightLoop() {
        if (!ttsAudio || ttsAudio.paused || !ttsBoundaries.length) return;
        var cd = document.querySelector('.content'); if (!cd) return;
        var ms = ttsAudio.currentTime * 1000, idx = -1;
        for (var i = 0; i < ttsBoundaries.length; i++) { if (ttsBoundaries[i].offset <= ms) idx = i; else break; }
        if (idx >= 0) {
            var pv = cd.querySelector('.tts-word-highlight'), spans = cd.querySelectorAll('span[data-tts-idx]');
            if (idx < spans.length) {
                if (pv && pv !== spans[idx]) pv.classList.remove('tts-word-highlight');
                if (!spans[idx].classList.contains('tts-word-highlight')) { spans[idx].classList.add('tts-word-highlight'); spans[idx].scrollIntoView({ behavior: 'smooth', block: 'nearest' }); }
            }
        }
        ttsHighlightTimer = requestAnimationFrame(ttsHighlightLoop);
    }
    function ttsStart() {
        var cd = document.querySelector('.content'); if (!cd || !cd.innerText.trim()) { alert('No content to read yet.'); return; }
        if (ttsIsLoading) return; ttsStop(); ttsWrapWords(); ttsIsLoading = true;
        var fd = new FormData(); fd.append('text', ttsPlainText); fd.append('csrf_token', csrfToken);
        fetch('<?php echo $centerTtsEndpoint; ?>', { method: 'POST', body: fd })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            ttsIsLoading = false;
            if (data.error) { console.error('TTS error: ' + data.error); alert('Text-to-speech failed: ' + data.error); ttsUnwrapWords(); return; }
            ttsBoundaries = data.boundaries || [];
            ttsAudio = new Audio('data:audio/mp3;base64,' + data.audio);
            ttsIsSpeaking = true;
            ttsAudio.onplay = function () { ttsHighlightTimer = requestAnimationFrame(ttsHighlightLoop); };
            ttsAudio.onended = function () { ttsIsSpeaking = false; if (ttsHighlightTimer) cancelAnimationFrame(ttsHighlightTimer); ttsUnwrapWords(); };
            ttsAudio.onerror = function () { ttsIsSpeaking = false; if (ttsHighlightTimer) cancelAnimationFrame(ttsHighlightTimer); ttsUnwrapWords(); };
            ttsAudio.play().catch(function () { ttsIsSpeaking = false; ttsUnwrapWords(); });
        })
        .catch(function () { ttsIsLoading = false; alert('Text-to-speech request failed.'); ttsUnwrapWords(); });
    }
    function ttsStop() {
        if (ttsAudio) { ttsAudio.pause(); ttsAudio.currentTime = 0; ttsAudio = null; }
        ttsIsSpeaking = false; ttsIsLoading = false;
        if (ttsHighlightTimer) cancelAnimationFrame(ttsHighlightTimer); ttsUnwrapWords();
    }
    document.getElementById('start').addEventListener('click', function () { ttsStart(); });
    document.getElementById('pause').addEventListener('click', function () { if (ttsAudio && ttsIsSpeaking) { ttsAudio.pause(); if (ttsHighlightTimer) cancelAnimationFrame(ttsHighlightTimer); } });
    document.getElementById('resume').addEventListener('click', function () { if (ttsAudio && ttsAudio.paused && ttsIsSpeaking) { ttsAudio.play(); ttsHighlightTimer = requestAnimationFrame(ttsHighlightLoop); } });
    window.onbeforeunload = function () { ttsStop(); };

    // Fullscreen Toggle
    document.getElementById('fullscreen').addEventListener('click', function () {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().then(function () {
                var txt = document.querySelector('.content'), img = document.querySelector('.contentImage');
                if (txt) txt.style.height = '80vh'; if (img) img.style.height = '80vh';
            }).catch(function (e) { console.error('Failed to enter fullscreen mode: ', e); });
        } else {
            document.exitFullscreen().then(function () {
                var txt = document.querySelector('.content'), img = document.querySelector('.contentImage');
                if (txt) txt.style.height = '600px'; if (img) img.style.height = '600px';
            }).catch(function (e) { console.error('Failed to exit fullscreen mode: ', e); });
        }
    });

    // WHY: Auto-show panels after answer generation. Panels are visible by default;
    // this hook fires when AJAX completes to ensure smooth UX after response arrives.
    window.onAnswerGenerated = function() {
        try {
            var answerPanel = document.getElementById('answerPanel');
            var imagePanel = document.getElementById('imagePanel');
            // WHY: show() ensures panels are visible after answer arrives; safe if already visible
            if (answerPanel) answerPanel.style.display = 'block';
            if (imagePanel) imagePanel.style.display = 'block';
        } catch (e) {
            console.error('Failed to show panels after answer generation:', e);
        }
    };
</script>
<?php
$extraScripts = ob_get_clean();

// =========================================================================
// RENDER via legacy split layout
// =========================================================================
renderLegacySplitLayout([
    'title'          => 'Wheelder',
    'brand_label'    => 'Wheelder',
    'created_label'  => 'Created · Aug 2023',
    'toolbar'        => $toolbarHtml,
    'sidebar'        => $sidebarHtml,
    'sidebar_hidden' => false,
    'above_panels'   => $abovePanelsHtml,
    'left'           => $leftPaneHtml,
    'right'          => $rightPaneHtml,
    'head'           => $extraHead,
    'scripts'        => $extraScripts,
]);
