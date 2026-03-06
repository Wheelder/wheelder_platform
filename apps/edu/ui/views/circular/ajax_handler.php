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

// Load the Circular controller for meta-AI orchestration
include __DIR__ . '/CircularController.php';
$circular = new CircularController();

// Start session if not already active (needed for session_id tracking and CSRF validation)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Public center experience — access key was removed. CSRF + rate limiting + server-side
// validation already protect costs, so we do not gate with DEMO_ACCESS_KEY here.

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
if (isset($_POST['ask']) || isset($_POST['deepen']) || isset($_POST['regenerate_image'])) {

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

    // --- ASK handler: Circular meta-AI orchestration ---
    if (isset($_POST['ask'])) {

        $q = trim($_POST['query'] ?? '');

        // Validate: don't process empty questions
        if (empty($q)) {
            echo json_encode(['error' => 'Please enter a question.']);
            exit;
        }

        // Process query through Circular orchestrator
        // This analyzes the query, generates base answer, and recommends tools
        $result = $circular->processQuery($q);

        if (!$result['success']) {
            http_response_code(500);
            echo json_encode(['error' => $result['message'] ?? 'Failed to process query']);
            exit;
        }

        // Convert markdown to HTML
        $content = formatMarkdown($result['base_answer']);

        // Return orchestrated response with tool recommendations
        echo json_encode([
            'success'           => true,
            'answer'            => $content,
            'session_id'        => $result['session_id'],
            'original_question' => $q,
            'prev_answer'       => $content,
            'analysis'          => $result['analysis'],
            'recommended_tools' => $result['recommended_tools'],
            'query_type'        => $result['analysis']['query_type'],
            'complexity'        => $result['analysis']['complexity'],
            'depth_level'       => 0
        ]);
        exit;

    // --- DEEPEN handler: get more tool recommendations or workflow ---
    } elseif (isset($_POST['deepen'])) {

        $sessionId = trim($_POST['session_id'] ?? '');
        $mainq     = trim($_POST['original_question'] ?? '');

        // Validate: need a question to deepen
        if (empty($mainq)) {
            echo json_encode(['error' => 'No question to deepen.']);
            exit;
        }

        // Re-analyze with more depth to get workflow suggestions
        $result = $circular->processQuery($mainq);
        
        if (!$result['success']) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to generate workflow']);
            exit;
        }

        // Generate workflow steps for complex queries
        // WHY: use generateResponse from parent AppController (via CircularController inheritance)
        $workflowPrompt = "Create a step-by-step workflow for: $mainq. " .
            "List 3-5 concrete steps the user should take, including which AI tools to use for each step.";
        
        try {
            // WHY: generateResponse is inherited from AppController, not a CircularController method
            $workflow = $circular->generateResponse($workflowPrompt);
            if (empty($workflow)) {
                throw new Exception('Workflow generation returned empty response');
            }
            $content = formatMarkdown($workflow);
        } catch (Exception $e) {
            // WHY: log error with context for debugging
            error_log("Circular deepen error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Failed to generate workflow: ' . $e->getMessage()]);
            exit;
        }

        // WHY: include recommended tools with workflow so right panel shows both workflow + tools tabs
        $recommendedTools = $result['recommended_tools'] ?? [];
        
        echo json_encode([
            'success'           => true,
            'answer'            => $content,
            'session_id'        => $sessionId,
            'original_question' => $mainq,
            'recommended_tools' => $recommendedTools,
            'query_type'        => $result['query_type'] ?? 'general',
            'is_workflow'       => true,
            'depth_level'       => intval($_POST['depth_level'] ?? 0)
        ]);
        exit;

    // --- POPULATE_TOOLS handler: auto-populate tools database ---
    } elseif (isset($_POST['populate_tools'])) {

        // Auto-populate tools using DuckDuckGo API
        $populated = $circular->autoPopulateTools();

        echo json_encode([
            'success' => true,
            'message' => "Populated $populated AI tools from DuckDuckGo",
            'count'   => $populated
        ]);
        exit;

    // --- GET_TOOLS handler: retrieve all tools from database ---
    } elseif (isset($_POST['get_tools'])) {

        $tools = $circular->getAllTools();

        echo json_encode([
            'success' => true,
            'tools'   => $tools,
            'count'   => count($tools)
        ]);
        exit;

    // --- GET_SESSION handler: retrieve session history ---
    } elseif (isset($_POST['get_session'])) {

        $sessionId = trim($_POST['session_id'] ?? '');

        if (empty($sessionId)) {
            echo json_encode(['error' => 'Missing session_id']);
            exit;
        }

        $history = $circular->getSessionHistory($sessionId);

        echo json_encode([
            'success' => true,
            'history' => $history
        ]);
        exit;

    } else {
        // No recognised action parameter — bad request
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
