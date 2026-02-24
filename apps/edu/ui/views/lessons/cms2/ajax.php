<?php
/**
 * AJAX endpoint for /lesson/cms2 — AI lesson generator.
 * Mirrors /demo/ajax exactly but saves results into the central lessons table as drafts.
 * Returns JSON so the page never reloads.
 */

header('Content-Type: application/json');

// Catch fatal PHP errors so the browser always gets parseable JSON, never a blank body
register_shutdown_function(function () {
    $err = error_get_last();
    if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
        if (ob_get_length()) ob_end_clean();
        http_response_code(500);
        echo json_encode(['error' => 'Server error (' . basename($err['file']) . ':' . $err['line'] . '). Please try again.']);
    }
});

// Only accept POST — GET makes no sense for generation requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed. Use POST.']);
    exit;
}

// Load LessonController FIRST — it pulls in apps/edu/controllers/Controller.php.
// AppController.php also includes a Controller class (from pool/libs/controllers/).
// Loading LessonController first lets require_once skip the second Controller.php
// via PHP's include guard, preventing a fatal "class already declared" error.
include_once __DIR__ . '/../../../../controllers/LessonController.php';

// Load AppController for AI generation (generateAnswerAndImage, generateResponse)
// __DIR__ = .../wheelder/apps/edu/ui/views/lessons/cms2
// Go up 2 levels (cms2 -> lessons -> views) then into learn/backup/
$appCtrlPath = __DIR__ . '/../../learn/backup/AppController.php';
if (!file_exists($appCtrlPath)) {
    http_response_code(500);
    echo json_encode(['error' => 'AppController not found. Check the path in cms2/ajax.php.']);
    exit;
}
include_once $appCtrlPath;

$app    = new AppController();
$lesson = new LessonController();

// Auth gate — CMS is only accessible to logged-in users
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Authentication required. Please log in.']);
    exit;
}

// --- CSRF protection (same pattern as /demo/ajax) ---
$csrfToken = $_POST['csrf_token'] ?? '';
if (empty($csrfToken)) {
    http_response_code(403);
    echo json_encode(['error' => 'Missing CSRF token. Please reload the page.']);
    exit;
}
if (empty($_SESSION['cms2_csrf_token'])) {
    // Adopt the token from the page if the session was just created
    $_SESSION['cms2_csrf_token'] = $csrfToken;
}
if (!hash_equals($_SESSION['cms2_csrf_token'], $csrfToken)) {
    http_response_code(403);
    echo json_encode(['error' => 'Invalid CSRF token. Please reload the page.']);
    exit;
}

// --- Rate limiting: max 10 AI requests per 60 seconds per session ---
// Protects Groq API quota from spam-clicking
if (isset($_POST['ask']) || isset($_POST['deepen'])) {
    $rateMax = 10;
    $rateWin = 60;
    if (!isset($_SESSION['cms2_rate_ts'])) { $_SESSION['cms2_rate_ts'] = []; }
    $now = time();
    $_SESSION['cms2_rate_ts'] = array_values(array_filter(
        $_SESSION['cms2_rate_ts'],
        function ($ts) use ($now, $rateWin) { return ($now - $ts) < $rateWin; }
    ));
    if (count($_SESSION['cms2_rate_ts']) >= $rateMax) {
        $retry = $rateWin - ($now - $_SESSION['cms2_rate_ts'][0]);
        http_response_code(429);
        echo json_encode(['error' => 'Too many requests. Wait ' . $retry . ' seconds.']);
        exit;
    }
    $_SESSION['cms2_rate_ts'][] = $now;
}

// ── Markdown-to-HTML formatter (same function as /demo/ajax) ──────────────────
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

try {

    // ── ASK: generate a new lesson draft ──────────────────────────────────────
    if (isset($_POST['ask'])) {

        $q = trim($_POST['query'] ?? '');
        if (empty($q)) {
            echo json_encode(['error' => 'Please enter a topic or question.']);
            exit;
        }

        // Build a lesson-focused prompt so the AI structures the response as a lesson
        $lessonPrompt = "Write a clear, structured educational lesson about: " . $q
            . ". Include: an introduction, key concepts with explanations, practical examples, and a summary. "
            . "Format with headings and bullet points where appropriate.";

        // Generate AI answer + relevant diagram image in one call (same as /demo)
        $result  = $app->generateAnswerAndImage($lessonPrompt, $q);
        $answer  = $result['answer'];
        $image   = $result['image'];
        $content = formatMarkdown($answer);

        // Derive a lesson title from the first 60 chars of the question
        $title    = ucwords(mb_substr(trim($q), 0, 60));
        $category = trim($_POST['category'] ?? '');

        // Save as draft in the central lessons table — NOT published yet
        // lesson_id is returned so the sidebar can link to it and the publish button knows which row to update
        $ok = $lesson->insert_draft($title, $category, $answer, $image, '');
        if (!$ok) {
            error_log("Failed to insert lesson draft: title=$title");
            echo json_encode(['error' => 'Failed to insert lesson draft.']);
            exit;
        }

        // Retrieve the ID of the just-inserted row
        $db     = $lesson->connectDb();
        $idStmt = $db->query("SELECT id FROM lessons WHERE title = '" . $db->real_escape_string($title) . "' ORDER BY id DESC LIMIT 1");
        $idRow  = $idStmt ? $idStmt->fetch_assoc() : null;
        $lessonId = $idRow ? (int)$idRow['id'] : 0;

        echo json_encode([
            'success'           => true,
            'answer'            => $content,
            'image'             => $image,
            'lesson_id'         => $lessonId,
            'title'             => htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
            'original_question' => $q,
            'prev_answer'       => $answer,
            'depth_level'       => 0
        ]);
        exit;

    // ── DEEPEN: go one level deeper on the current lesson ────────────────────
    } elseif (isset($_POST['deepen'])) {

        $depthLevel = (int)($_POST['depth_level'] ?? 1);
        $mainq      = trim($_POST['original_question'] ?? '');
        $prevAnswer = $_POST['prev_answer'] ?? '';
        $lessonId   = (int)($_POST['lesson_id'] ?? 0);

        if (empty($mainq)) {
            echo json_encode(['error' => 'No topic to deepen.']);
            exit;
        }
        if ($depthLevel < 1) $depthLevel = 1;
        if ($depthLevel > 7) $depthLevel = 7;

        if (empty($prevAnswer)) {
            $prevAnswer = $app->generateResponse($mainq);
        }

        $deeperPrompt = "Here I already asked this question before: " . $mainq
            . " and the provided answer is: " . $prevAnswer
            . " Make this answer one level more deeper (depth level " . $depthLevel . " of 7)"
            . " beside keeping this answer. Add more detail, examples, and nuance.";

        $result  = $app->generateAnswerAndImage($deeperPrompt, $mainq);
        $answer  = $result['answer'];
        $image   = $result['image'];
        $content = formatMarkdown($answer);

        // Append the deeper content to the existing draft row (if we have a valid ID)
        if ($lessonId > 0) {
            $existing = $lesson->get_lesson_edit($lessonId);
            if ($existing) {
                $combined = ($existing['content'] ?? '') . "\n\n--- Depth Level {$depthLevel} ---\n\n" . $answer;
                $lesson->update(
                    $lessonId,
                    $existing['title'],
                    $existing['category'] ?? '',
                    $combined,
                    $image,
                    $existing['code_block'] ?? ''
                );
            }
        }

        echo json_encode([
            'success'           => true,
            'answer'            => $content,
            'image'             => $image,
            'lesson_id'         => $lessonId,
            'original_question' => $mainq,
            'prev_answer'       => $answer,
            'depth_level'       => $depthLevel
        ]);
        exit;

    // ── PUBLISH: set status = 'published' so lesson appears on /lesson ────────
    } elseif (isset($_POST['publish'])) {

        $lessonId = (int)($_POST['lesson_id'] ?? 0);
        if ($lessonId <= 0) {
            echo json_encode(['error' => 'Invalid lesson ID for publish.']);
            exit;
        }
        $ok = $lesson->publish($lessonId);
        if ($ok) {
            echo json_encode(['success' => true, 'action' => 'published', 'lesson_id' => $lessonId]);
        } else {
            echo json_encode(['error' => 'Publish failed. Lesson not found or already published.']);
        }
        exit;

    // ── ARCHIVE: set status = 'archived' so lesson disappears from cms2 sidebar ──
    } elseif (isset($_POST['archive'])) {

        $lessonId = (int)($_POST['lesson_id'] ?? 0);
        if ($lessonId <= 0) {
            echo json_encode(['error' => 'Invalid lesson ID for archive.']);
            exit;
        }
        $ok = $lesson->archive($lessonId);
        echo $ok
            ? json_encode(['success' => true, 'action' => 'archived', 'lesson_id' => $lessonId])
            : json_encode(['error' => 'Archive failed.']);
        exit;

    // ── DELETE: permanently remove a draft lesson ─────────────────────────────
    } elseif (isset($_POST['delete'])) {

        $lessonId = (int)($_POST['lesson_id'] ?? 0);
        if ($lessonId <= 0) {
            echo json_encode(['error' => 'Invalid lesson ID for delete.']);
            exit;
        }
        $ok = $lesson->delete($lessonId);
        echo $ok
            ? json_encode(['success' => true, 'action' => 'deleted', 'lesson_id' => $lessonId])
            : json_encode(['error' => 'Delete failed.']);
        exit;

    // ── REGENERATE_IMAGE: create a new image for an existing lesson ────────────
    } elseif (isset($_POST['regenerate_image'])) {

        $lessonId = (int)($_POST['lesson_id'] ?? 0);
        $mainq    = trim($_POST['original_question'] ?? '');

        if (empty($mainq)) {
            echo json_encode(['error' => 'No topic to regenerate image for.']);
            exit;
        }
        if ($lessonId <= 0) {
            echo json_encode(['error' => 'Invalid lesson ID.']);
            exit;
        }

        // Generate a new image using the same process as generateAnswerAndImage()
        // but only for the image, not the text
        $result = $app->generateImage($mainq);

        // Update the lesson with the new image
        $existing = $lesson->get_lesson_edit($lessonId);
        if ($existing) {
            $lesson->update(
                $lessonId,
                $existing['title'],
                $existing['category'] ?? '',
                $existing['content'] ?? '',
                $result,
                $existing['code_block'] ?? ''
            );
        }

        echo json_encode([
            'success' => true,
            'image'   => $result,
            'lesson_id' => $lessonId
        ]);
        exit;

    } else {
        echo json_encode(['error' => 'Invalid request. Missing action parameter.']);
        exit;
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
    exit;
}
