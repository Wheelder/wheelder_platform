<?php
/**
 * TTS Proxy — converts text to natural speech using Microsoft Edge's neural TTS.
 * 
 * WHY: The browser's built-in SpeechSynthesis API only has robotic voices on Windows.
 *      Edge TTS provides free, high-quality neural voices without any API key.
 * 
 * HOW: Receives text via POST, synthesizes it with Edge TTS, returns MP3 audio
 *      and word boundary timestamps (for word-by-word highlighting on the frontend).
 * 
 * SECURITY: Requires a valid session (demo_unlocked or user_id) and CSRF token.
 *           Text length is capped to prevent abuse.
 */

// Suppress deprecation warnings from third-party libraries (e.g. react/dns)
// that would corrupt the JSON response with HTML error output
error_reporting(E_ERROR | E_PARSE);

// Start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// --- Access control: same rules as the learn app ---
// Must be logged in OR have demo_unlocked session
$isLoggedIn = !empty($_SESSION['user_id']);
$isDemoUnlocked = !empty($_SESSION['demo_unlocked']);
if (!$isLoggedIn && !$isDemoUnlocked) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Access denied']);
    exit;
}

// --- CSRF validation ---
$csrfToken = $_POST['csrf_token'] ?? '';
if (empty($csrfToken) || !hash_equals($_SESSION['csrf_token'] ?? '', $csrfToken)) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid CSRF token']);
    exit;
}

// --- Input validation ---
$text = $_POST['text'] ?? '';
if (empty(trim($text))) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No text provided']);
    exit;
}

// Cap text length to prevent abuse (10,000 chars is ~2 minutes of speech)
$maxLength = 10000;
if (mb_strlen($text) > $maxLength) {
    $text = mb_substr($text, 0, $maxLength);
}

// --- Load Composer autoloader ---
$autoloadPath = dirname(__DIR__, 6) . '/vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'TTS library not installed. Run: composer require afaya/edge-tts']);
    error_log("Edge TTS autoloader not found at: " . $autoloadPath);
    exit;
}
require_once $autoloadPath;

use Afaya\EdgeTTS\Service\EdgeTTS;

try {
    $tts = new EdgeTTS();

    // Voice: en-US-GuyNeural — natural-sounding American male voice
    // Rate: -5% slightly slower for clarity
    $tts->synthesize($text, 'en-US-GuyNeural', [
        'rate'   => '-5%',
        'volume' => '0%',
        'pitch'  => '0Hz'
    ]);

    // Get word boundary timestamps for frontend highlighting
    // Edge TTS returns offsets in ticks (100-nanosecond units).
    // Convert to milliseconds so the frontend can compare with audio.currentTime * 1000.
    $rawBoundaries = $tts->getWordBoundaries();
    $wordBoundaries = [];
    foreach ($rawBoundaries as $b) {
        $wordBoundaries[] = [
            'offset' => round($b['offset'] / 10000, 1),  // ticks → ms
            'text'   => $b['text'] ?? ''
        ];
    }

    // Get audio as base64 so we can send it in a JSON response
    // alongside the word boundaries for synchronized highlighting
    $base64Audio = $tts->toBase64();

    header('Content-Type: application/json');
    echo json_encode([
        'audio'      => $base64Audio,
        'boundaries' => $wordBoundaries
    ]);

} catch (\Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'TTS synthesis failed: ' . $e->getMessage()]);
    error_log("Edge TTS error: " . $e->getMessage());
}
