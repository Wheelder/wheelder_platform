<?php
/**
 * Direct Magic Link Request Endpoint (Alternative Access)
 * 
 * This file provides direct access to the magic link endpoint
 * without relying on the Router class, in case routing is causing issues.
 * 
 * Access via: http://localhost/auth_magic_link_request.php
 */

// Prevent any output before JSON header
if (ob_get_level() === 0) {
    ob_start();
}

// Set JSON header immediately
header('Content-Type: application/json; charset=UTF-8');

// Catch fatal PHP errors and return JSON response
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        ob_end_clean();
        http_response_code(500);
        echo json_encode(['error' => 'Server error: ' . $error['message']]);
        error_log("Magic link fatal error: " . $error['message'] . " in " . $error['file'] . ":" . $error['line']);
    }
});

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load MagicLinkController and EmailService
try {
    require_once __DIR__ . '/pool/libs/controllers/MagicLinkController.php';
    require_once __DIR__ . '/pool/libs/services/EmailService.php';
} catch (Exception $e) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['error' => 'Failed to load required files: ' . $e->getMessage()]);
    error_log("Magic link include error: " . $e->getMessage());
    exit;
}

try {
    // Only accept POST requests
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed. Use POST.']);
        exit;
    }

    // Get email from request
    $email = trim($_POST['email'] ?? '');

    // Validate email
    if (empty($email)) {
        http_response_code(400);
        echo json_encode(['error' => 'Email address is required.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid email address.']);
        exit;
    }

    // Rate limiting: max 5 requests per 15 minutes per IP
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $rateKey = "magic_link_requests_$ipAddress";
    
    if (!isset($_SESSION[$rateKey])) {
        $_SESSION[$rateKey] = [];
    }

    // Clean old timestamps (older than 15 minutes)
    $now = time();
    $_SESSION[$rateKey] = array_filter(
        $_SESSION[$rateKey],
        function ($ts) use ($now) { return ($now - $ts) < 900; } // 900 seconds = 15 minutes
    );

    // Check if rate limit exceeded
    if (count($_SESSION[$rateKey]) >= 5) {
        http_response_code(429);
        echo json_encode(['error' => 'Too many requests. Please wait 15 minutes before trying again.']);
        exit;
    }

    // Record this request
    $_SESSION[$rateKey][] = $now;

    // Request magic link
    $magicLink = new MagicLinkController();
    $result = $magicLink->requestMagicLink($email);

    if ($result['success']) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => $result['message'],
            'email' => $email
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => $result['message']
        ]);
    }

} catch (Exception $e) {
    error_log("Magic link request exception: " . $e->getMessage() . " | Trace: " . $e->getTraceAsString());
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred. Please try again later.']);
}

// Flush output buffer to send JSON response
ob_end_flush();
