<?php
/**
 * Password Reset Request Handler
 * 
 * POST /api/password-reset-request
 * Sends password reset link to hardcoded email
 */

// WHY: Prevent any output before JSON header
if (ob_get_level() === 0) {
    ob_start();
}

// WHY: Set JSON header immediately
header('Content-Type: application/json; charset=UTF-8');

// WHY: Catch fatal PHP errors and return JSON response
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        ob_end_clean();
        http_response_code(500);
        echo json_encode(['error' => 'Server error: ' . $error['message']]);
        error_log("Password reset request fatal error: " . $error['message']);
    }
});

// WHY: Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// WHY: Load required files
try {
    require_once __DIR__ . '/../libs/controllers/PasswordResetController.php';
} catch (Exception $e) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['error' => 'Failed to load required files']);
    error_log("Password reset request include error: " . $e->getMessage());
    exit;
}

try {
    // WHY: Only accept POST requests
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed. Use POST.']);
        exit;
    }

    // WHY: Validate CSRF token
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (empty($csrfToken) || $csrfToken !== ($_SESSION['csrf_token'] ?? '')) {
        http_response_code(403);
        echo json_encode(['error' => 'Invalid security token. Please refresh and try again.']);
        exit;
    }

    // WHY: Get email from request
    $email = trim($_POST['email'] ?? '');

    // WHY: Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid email address.']);
        exit;
    }

    // WHY: Normalize email
    $email = strtolower($email);

    // WHY: Only allow hardcoded email
    $allowedEmail = 'regrowup2025@gmail.com';
    if ($email !== $allowedEmail) {
        // WHY: Don't reveal that email doesn't exist (security best practice)
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'If an account exists with this email, a reset link has been sent.'
        ]);
        exit;
    }

    // WHY: Rate limiting: max 3 requests per hour per IP
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $rateKey = "password_reset_requests_$ipAddress";
    
    if (!isset($_SESSION[$rateKey])) {
        $_SESSION[$rateKey] = [];
    }

    // WHY: Clean old timestamps (older than 1 hour)
    $now = time();
    $_SESSION[$rateKey] = array_filter(
        $_SESSION[$rateKey],
        function ($ts) use ($now) { return ($now - $ts) < 3600; } // 3600 seconds = 1 hour
    );

    // WHY: Check if rate limit exceeded
    if (count($_SESSION[$rateKey]) >= 3) {
        http_response_code(429);
        echo json_encode(['error' => 'Too many reset requests. Please wait 1 hour before trying again.']);
        exit;
    }

    // WHY: Record this request
    $_SESSION[$rateKey][] = $now;

    // WHY: Request password reset
    $resetController = new PasswordResetController();
    $result = $resetController->requestPasswordReset($email);

    if ($result['success']) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => $result['message'] ?? 'Reset link sent to your email.'
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => $result['error'] ?? 'Failed to process request.'
        ]);
    }

} catch (Exception $e) {
    error_log("Password reset request exception: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred. Please try again later.']);
}
?>
