<?php
/**
 * Password Reset Handler
 * 
 * POST /api/password-reset
 * Resets password using valid reset token
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
        error_log("Password reset fatal error: " . $error['message']);
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
    error_log("Password reset include error: " . $e->getMessage());
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

    // WHY: Get reset token and new password
    $token = trim($_POST['token'] ?? '');
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // WHY: Validate required fields
    if (empty($token) || empty($newPassword) || empty($confirmPassword)) {
        http_response_code(400);
        echo json_encode(['error' => 'All fields are required.']);
        exit;
    }

    // WHY: Validate passwords match
    if ($newPassword !== $confirmPassword) {
        http_response_code(400);
        echo json_encode(['error' => 'Passwords do not match.']);
        exit;
    }

    // WHY: Validate password strength
    if (strlen($newPassword) < 8) {
        http_response_code(400);
        echo json_encode(['error' => 'Password must be at least 8 characters long.']);
        exit;
    }

    // WHY: Validate password length (prevent extremely long inputs)
    if (strlen($newPassword) > 255) {
        http_response_code(400);
        echo json_encode(['error' => 'Password is too long.']);
        exit;
    }

    // WHY: Reset password
    $resetController = new PasswordResetController();
    $result = $resetController->resetPassword($token, $newPassword);

    if ($result['success']) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => $result['message'] ?? 'Password reset successfully!'
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => $result['error'] ?? 'Failed to reset password.'
        ]);
    }

} catch (Exception $e) {
    error_log("Password reset exception: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred. Please try again later.']);
}
?>
