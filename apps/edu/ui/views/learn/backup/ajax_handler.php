<?php
/**
 * AJAX endpoint for Ask and Deepen requests.
 * Returns JSON instead of HTML so the page doesn't need to reload.
 * Reuses the same AppController methods as app_src.php.
 */

// Always return JSON
header('Content-Type: application/json');

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
// Two ways to be authorized: logged-in user (from dashboard) OR demo key unlocked via URL.
// This prevents anonymous visitors from bypassing the UI and hitting the API directly.
$isLoggedIn = !empty($_SESSION['user_id']);
if (!empty(DEMO_ACCESS_KEY) && !$isLoggedIn && empty($_SESSION['demo_unlocked'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied. Please open the app with a valid access key first.']);
    exit;
}

// --- CSRF protection ---
// Every AJAX POST must include the csrf_token that was embedded in the page.
// This prevents cross-site request forgery — a malicious site can't guess the token.
$csrfToken = $_POST['csrf_token'] ?? '';
if (empty($_SESSION['csrf_token']) || empty($csrfToken)) {
    http_response_code(403);
    echo json_encode(['error' => 'Missing CSRF token. Please reload the page and try again.']);
    exit;
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

try {

    // --- ASK handler: new question ---
    if (isset($_POST['ask'])) {

        $q = trim($_POST['query'] ?? '');

        // Validate: don't process empty questions
        if (empty($q)) {
            echo json_encode(['error' => 'Please enter a question.']);
            exit;
        }

        // Generate the AI answer
        $answer = $note->generateResponse($q);

        // Split sentences for readable formatting (same logic as app_src.php)
        $sentences = preg_split('/(?<=[.?!])\s+(?=[A-Z])/', $answer);
        $formattedAnswer = implode("<br></br>", $sentences);
        $formattedAnswer = trim($formattedAnswer);

        // Strip markdown symbols so the answer reads as clean plain text
        $content = str_replace(array('###', '##', '#', '***', '**', '*', '---', '`'), '', $formattedAnswer);
        $content = trim($content);

        // Generate a relevant image for the question
        $image = $note->generateImage($q);

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

        // Generate the deeper answer and a relevant image
        $answer = $note->generateResponse($deeperPrompt);
        $image  = $note->generateImage($mainq);

        // Format the answer (same logic as app_src.php)
        $sentences = preg_split('/(?<=[.?!])\s+(?=[A-Z])/', $answer);
        $formattedAnswer = implode("<br></br>", $sentences);
        $formattedAnswer = trim($formattedAnswer);

        // Strip markdown symbols
        $content = str_replace(array('###', '##', '#', '***', '**', '*', '---', '`'), '', $formattedAnswer);
        $content = trim($content);

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
