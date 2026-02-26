<?php
/**
 * Login2 API Handler
 * 
 * POST /api/login2
 * Authenticates user with email and password
 * Only accepts hardcoded email: regrowup2025@gmail.com
 * Supports "Remember Me" for persistent login
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
        error_log("Login2 fatal error: " . $error['message'] . " in " . $error['file'] . ":" . $error['line']);
    }
});

// WHY: Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// WHY: Load required files
try {
    require_once __DIR__ . '/../libs/controllers/Login2Controller.php';
} catch (Exception $e) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['error' => 'Failed to load required files: ' . $e->getMessage()]);
    error_log("Login2 include error: " . $e->getMessage());
    exit;
}

try {
    // WHY: Only accept POST requests
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed. Use POST.']);
        exit;
    }

    // WHY: Validate CSRF token to prevent cross-site request forgery
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (empty($csrfToken) || $csrfToken !== ($_SESSION['csrf_token'] ?? '')) {
        http_response_code(403);
        echo json_encode(['error' => 'Invalid security token. Please refresh and try again.']);
        exit;
    }

    // WHY: Get email and password from request
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $rememberMe = isset($_POST['remember_me']) ? true : false;

    // WHY: Validate required fields
    if (empty($email) || empty($password)) {
        http_response_code(400);
        echo json_encode(['error' => 'Email and password are required.']);
        exit;
    }

    // WHY: Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid email address.']);
        exit;
    }

    // WHY: Normalize email (lowercase and trim)
    $email = strtolower($email);

    // WHY: Only allow hardcoded email
    $allowedEmail = 'regrowup2025@gmail.com';
    if ($email !== $allowedEmail) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid email or password.']);
        exit;
    }

    // WHY: Validate password length (prevent extremely long inputs)
    if (strlen($password) > 255) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid password.']);
        exit;
    }

    // WHY: Rate limiting: max 5 failed attempts per 15 minutes per IP
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $rateKey = "login2_attempts_$ipAddress";
    
    if (!isset($_SESSION[$rateKey])) {
        $_SESSION[$rateKey] = [];
    }

    // WHY: Clean old timestamps (older than 15 minutes)
    $now = time();
    $_SESSION[$rateKey] = array_filter(
        $_SESSION[$rateKey],
        function ($ts) use ($now) { return ($now - $ts) < 900; } // 900 seconds = 15 minutes
    );

    // WHY: Check if rate limit exceeded
    if (count($_SESSION[$rateKey]) >= 5) {
        http_response_code(429);
        echo json_encode(['error' => 'Too many failed login attempts. Please wait 15 minutes before trying again.']);
        exit;
    }

    // WHY: Authenticate user
    $login2 = new Login2Controller();
    $result = $login2->authenticate($email, $password, $rememberMe);

    if ($result['success']) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => $result['message'] ?? 'Login successful!'
        ]);
    } else {
        // WHY: Record failed attempt for rate limiting
        $_SESSION[$rateKey][] = $now;

        http_response_code(401);
        echo json_encode([
            'success' => false,
            'error' => $result['error'] ?? 'Invalid email or password.'
        ]);
    }

} catch (Exception $e) {
    error_log("Login2 handler exception: " . $e->getMessage() . " | Trace: " . $e->getTraceAsString());
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred. Please try again later.']);
}
?>
