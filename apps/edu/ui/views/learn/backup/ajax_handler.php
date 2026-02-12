<?php
/**
 * AJAX endpoint for Ask and Deepen requests.
 * Returns JSON instead of HTML so the page doesn't need to reload.
 * Reuses the same AppController methods as app_src.php.
 */

// Always return JSON
header('Content-Type: application/json');

// Catch fatal PHP errors (OOM, timeout, etc.) that would otherwise leave an empty
// response body — the browser sees Content-Type: application/json but gets nothing,
// causing "Unexpected end of JSON input". This shutdown handler ensures the browser
// always receives a parseable JSON error message, even if PHP dies mid-execution.
register_shutdown_function(function () {
    $err = error_get_last();
    // Only handle fatal errors (E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR)
    if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
        // Clear any partial output that PHP may have buffered before dying
        if (ob_get_length()) ob_end_clean();
        http_response_code(500);
        echo json_encode([
            'error' => 'A server error occurred. Please try again. (' . basename($err['file']) . ':' . $err['line'] . ')'
        ]);
    }
});

// Only accept POST requests — GET makes no sense for form submissions
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed. Use POST.']);
    exit;
}

// Load the controller (same one used by app_src.php and record.php)
include __DIR__ . '/AppController.php';
$note = new AppController();

// Start session if not already active (needed for session_id tracking and CSRF validation)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- Demo access key gate ---
// Re-validate the access key on every AJAX call.
// The key can come from two sources:
//   1. $_SESSION['demo_access_key'] — set when record.php validated the key on page load
//   2. $_POST['access_key'] — sent by the frontend JS as a fallback for when the
//      PHP session expired (garbage-collected after inactivity). Without this fallback,
//      AJAX calls fail with 403 after the session dies, returning HTML instead of JSON.
$isLoggedIn = !empty($_SESSION['user_id']);
if (!empty(DEMO_ACCESS_KEY) && !$isLoggedIn) {
    $ajaxKeyValid = false;
    // Priority: session first, then POST fallback, then cookie (survives session expiry)
    $keyToCheck = $_SESSION['demo_access_key'] ?? $_POST['access_key'] ?? $_COOKIE['demo_access_key'] ?? '';

    if (!empty($keyToCheck)) {
        // Check 1: hardcoded demo key (always valid)
        if (hash_equals(DEMO_ACCESS_KEY, $keyToCheck)) {
            $ajaxKeyValid = true;
        }

        // Check 2: dashboard-generated code — must still be active in DB
        if (!$ajaxKeyValid) {
            try {
                $acDb = new PDO('sqlite:' . __DIR__ . '/database.sqlite');
                $acDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $acStmt = $acDb->prepare("SELECT id FROM access_codes WHERE code = ? AND is_active = 1 LIMIT 1");
                $acStmt->execute([$keyToCheck]);
                if ($acStmt->fetch()) {
                    $ajaxKeyValid = true;
                }
            } catch (PDOException $e) {
                error_log("AJAX access code lookup failed: " . $e->getMessage());
            }
        }

        // Re-store the key in the (possibly new) session so subsequent AJAX calls
        // within this session don't need to re-validate from POST every time
        if ($ajaxKeyValid) {
            $_SESSION['demo_access_key'] = $keyToCheck;
            $_SESSION['demo_unlocked']   = true;
        }
    }

    if (!$ajaxKeyValid) {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied. Your access key may have been deactivated. Please reload the page.']);
        exit;
    }
}

// --- CSRF protection ---
// Every AJAX POST must include the csrf_token that was embedded in the page.
// This prevents cross-site request forgery — a malicious site can't guess the token.
$csrfToken = $_POST['csrf_token'] ?? '';
if (empty($csrfToken)) {
    http_response_code(403);
    echo json_encode(['error' => 'Missing CSRF token. Please reload the page and try again.']);
    exit;
}
// If the session has no CSRF token (fresh session after expiry), but the access key
// just re-validated above, adopt the frontend's token into the new session.
// This bridges the gap between session expiry and page reload — without it, every
// AJAX call after session expiry would fail with "Invalid CSRF token" even though
// the user's access key is still valid.
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = $csrfToken;
}
// Timing-safe comparison so attackers can't guess the token character by character
if (!hash_equals($_SESSION['csrf_token'], $csrfToken)) {
    http_response_code(403);
    echo json_encode(['error' => 'Invalid CSRF token. Please reload the page and try again.']);
    exit;
}

// --- Rate limiting (sliding window) ---
// Only rate-limit Ask and Deepen — these hit the Groq API and cost quota.
// Archive/Delete are cheap DB operations, no need to throttle them.
if (isset($_POST['ask']) || isset($_POST['deepen'])) {

    // Max 10 API requests per 60 seconds per session — enough for normal use,
    // but stops bots and spam-clickers from burning through the Groq free tier
    $rateMaxRequests = 10;
    $rateWindowSecs  = 60;

    // Initialize the timestamps array on first request
    if (!isset($_SESSION['rate_timestamps'])) {
        $_SESSION['rate_timestamps'] = [];
    }

    $now = time();

    // Prune timestamps older than the window — only keep recent ones
    $_SESSION['rate_timestamps'] = array_values(array_filter(
        $_SESSION['rate_timestamps'],
        function ($ts) use ($now, $rateWindowSecs) {
            return ($now - $ts) < $rateWindowSecs;
        }
    ));

    // Check if the user has exceeded the limit
    if (count($_SESSION['rate_timestamps']) >= $rateMaxRequests) {
        // Tell the user how long to wait before trying again
        $oldestTs   = $_SESSION['rate_timestamps'][0];
        $retryAfter = $rateWindowSecs - ($now - $oldestTs);
        http_response_code(429);
        echo json_encode([
            'error' => 'Too many requests. Please wait ' . $retryAfter . ' seconds before trying again.'
        ]);
        exit;
    }

    // Record this request's timestamp
    $_SESSION['rate_timestamps'][] = $now;
}

// ==========================================================================
// formatMarkdown — lightweight markdown-to-HTML converter for AI responses.
// Handles the most common patterns the AI returns: headings, bold, italic,
// code blocks, inline code, numbered lists, bullet lists, and paragraphs.
// No external library needed — keeps the codebase simple and self-contained.
// ==========================================================================
function formatMarkdown($text) {
    // Normalize line endings so regex works consistently across OS
    $text = str_replace("\r\n", "\n", $text);
    $text = str_replace("\r", "\n", $text);

    // --- Fenced code blocks (```lang ... ```) → <pre><code> ---
    // Must run BEFORE inline rules so backticks inside code blocks aren't mangled
    $text = preg_replace('/```(\w*)\n([\s\S]*?)```/m', '<pre><code>$2</code></pre>', $text);

    // --- Inline code (`...`) → <code> ---
    $text = preg_replace('/`([^`]+)`/', '<code>$1</code>', $text);

    // --- Headings (### → h5, ## → h4, # → h3) ---
    // Using h3-h5 so they fit inside the .qa-answer panel without being too large
    $text = preg_replace('/^### (.+)$/m', '<h5>$1</h5>', $text);
    $text = preg_replace('/^## (.+)$/m',  '<h4>$1</h4>', $text);
    $text = preg_replace('/^# (.+)$/m',   '<h3>$1</h3>', $text);

    // --- Bold and italic (order matters: bold-italic first) ---
    $text = preg_replace('/\*\*\*(.+?)\*\*\*/', '<strong><em>$1</em></strong>', $text);
    $text = preg_replace('/\*\*(.+?)\*\*/',     '<strong>$1</strong>', $text);
    $text = preg_replace('/\*(.+?)\*/',          '<em>$1</em>', $text);

    // --- Horizontal rules (--- or *** on their own line) ---
    $text = preg_replace('/^[-*]{3,}$/m', '<hr>', $text);

    // --- Lists and paragraphs: process line-by-line ---
    // Split into lines, group consecutive list items into <ol>/<ul> blocks,
    // and wrap remaining text lines into <p> tags separated by blank lines.
    $lines  = explode("\n", $text);
    $html   = '';
    $inOl   = false;  // currently inside an ordered list?
    $inUl   = false;  // currently inside an unordered list?

    foreach ($lines as $line) {
        $trimmed = trim($line);

        // Skip empty lines — they act as paragraph separators
        if ($trimmed === '') {
            // Close any open list before the gap
            if ($inOl) { $html .= '</ol>'; $inOl = false; }
            if ($inUl) { $html .= '</ul>'; $inUl = false; }
            continue;
        }

        // Ordered list item: "1. text" or "1) text"
        if (preg_match('/^\d+[\.\)]\s+(.+)$/', $trimmed, $m)) {
            if ($inUl) { $html .= '</ul>'; $inUl = false; }
            if (!$inOl) { $html .= '<ol>'; $inOl = true; }
            $html .= '<li>' . $m[1] . '</li>';
            continue;
        }

        // Unordered list item: "- text" or "* text" or "• text"
        if (preg_match('/^[-*•]\s+(.+)$/', $trimmed, $m)) {
            if ($inOl) { $html .= '</ol>'; $inOl = false; }
            if (!$inUl) { $html .= '<ul>'; $inUl = true; }
            $html .= '<li>' . $m[1] . '</li>';
            continue;
        }

        // Regular text line — close any open list, then wrap in <p>
        if ($inOl) { $html .= '</ol>'; $inOl = false; }
        if ($inUl) { $html .= '</ul>'; $inUl = false; }

        // Don't wrap lines that are already block-level HTML (headings, hr, pre)
        if (preg_match('/^<(h[1-6]|hr|pre|ol|ul|li|blockquote)/', $trimmed)) {
            $html .= $trimmed;
        } else {
            $html .= '<p>' . $trimmed . '</p>';
        }
    }

    // Close any list left open at end of text
    if ($inOl) $html .= '</ol>';
    if ($inUl) $html .= '</ul>';

    return $html;
}

try {

    // --- ASK handler: new question ---
    if (isset($_POST['ask'])) {

        $q = trim($_POST['query'] ?? '');

        // Validate: don't process empty questions
        if (empty($q)) {
            echo json_encode(['error' => 'Please enter a question.']);
            exit;
        }

        // Generate the AI answer AND find a relevant image in parallel.
        // Before: 3 sequential HTTP calls (8-25s). After: 2 parallel calls (5-15s).
        $result = $note->generateAnswerAndImage($q);
        $answer = $result['answer'];
        $image  = $result['image'];

        // Convert markdown to HTML so numbered lists, bullets, bold, etc. render properly
        $content = formatMarkdown($answer);

        // If a session_id was passed, this is a follow-up question in an existing thread.
        // Otherwise create a new conversation thread.
        // depth_level stays 0 for all Ask requests — only Deepen increments depth.
        $sessionId  = trim($_POST['session_id'] ?? '');
        $depthLevel = 0;

        if (empty($sessionId)) {
            // Brand-new conversation — generate a unique session_id
            $sessionId = uniqid('conv_', true);
        }

        // Store the Q&A under the (possibly existing) session
        $note->storeConversation($sessionId, $q, $content, $image, $depthLevel);

        // Return everything the frontend needs to render the result and chain deeper calls
        echo json_encode([
            'success'           => true,
            'answer'            => $content,
            'image'             => $image,
            'session_id'        => $sessionId,
            'original_question' => $q,
            'prev_answer'       => $answer,
            'depth_level'       => $depthLevel
        ]);
        exit;

    // --- DEEPEN handler: go one level deeper on a previous answer ---
    } elseif (isset($_POST['deepen'])) {

        $depthLevel = (int)($_POST['depth_level'] ?? 1);
        $mainq      = trim($_POST['original_question'] ?? '');
        $prevAnswer = $_POST['prev_answer'] ?? '';
        $sessionId  = $_POST['session_id'] ?? '';

        // Validate: need a question to deepen
        if (empty($mainq)) {
            echo json_encode(['error' => 'No question to deepen.']);
            exit;
        }

        // Safety: clamp depth to 1-7 range
        if ($depthLevel < 1) $depthLevel = 1;
        if ($depthLevel > 7) $depthLevel = 7;

        // If no previous answer yet, generate the base answer first
        if (empty($prevAnswer)) {
            $prevAnswer = $note->generateResponse($mainq);
        }

        // Build the "go deeper" prompt — tells the AI to deepen the previous answer
        $deeperPrompt = "Here I already asked this question before: " . $mainq
            . " and the provided answer is: " . $prevAnswer
            . " Make this answer one level more deeper (depth level " . $depthLevel . " of 7)"
            . " beside keeping this answer. Add more detail, examples, and nuance.";

        // Generate the deeper answer AND find a relevant image in parallel.
        // Pass $mainq as the image query so the image matches the original topic,
        // not the verbose deepen prompt.
        $result = $note->generateAnswerAndImage($deeperPrompt, $mainq);
        $answer = $result['answer'];
        $image  = $result['image'];

        // Convert markdown to HTML so numbered lists, bullets, bold, etc. render properly
        $content = formatMarkdown($answer);

        // Generate session_id if not provided (shouldn't happen, but safety net)
        if (empty($sessionId)) {
            $sessionId = uniqid('conv_', true);
        }

        // Store the deepened Q&A under the same session
        $note->storeConversation($sessionId, $mainq, $content, $image, $depthLevel);

        // Also store in the legacy ans_data table (existing behaviour)
        $note->storeData($mainq, $content, $image);

        // Return everything the frontend needs
        echo json_encode([
            'success'           => true,
            'answer'            => $content,
            'image'             => $image,
            'session_id'        => $sessionId,
            'original_question' => $mainq,
            'prev_answer'       => $answer,
            'depth_level'       => $depthLevel
        ]);
        exit;

    // --- ARCHIVE handler: soft-delete a conversation by moving it to the archive table ---
    } elseif (isset($_POST['archive'])) {

        $sessionId = trim($_POST['session_id'] ?? '');

        // Validate: need a session_id to know which conversation to archive
        if (empty($sessionId)) {
            echo json_encode(['error' => 'Missing session_id for archive.']);
            exit;
        }

        // Move to archived_conversations with status 'archived'
        $note->archiveConversation($sessionId, 'archived');

        echo json_encode(['success' => true, 'action' => 'archived', 'session_id' => $sessionId]);
        exit;

    // --- DELETE handler: soft-delete a conversation (same move, different status label) ---
    } elseif (isset($_POST['delete'])) {

        $sessionId = trim($_POST['session_id'] ?? '');

        // Validate: need a session_id to know which conversation to delete
        if (empty($sessionId)) {
            echo json_encode(['error' => 'Missing session_id for delete.']);
            exit;
        }

        // Move to archived_conversations with status 'deleted'
        $note->archiveConversation($sessionId, 'deleted');

        echo json_encode(['success' => true, 'action' => 'deleted', 'session_id' => $sessionId]);
        exit;

    } else {
        // Neither ask, deepen, archive, nor delete was sent — bad request
        echo json_encode(['error' => 'Invalid request. Missing action parameter.']);
        exit;
    }

} catch (Exception $e) {
    // Catch any unexpected errors and return a clean JSON error
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
    exit;
}
?>
