<?php
/**
 * Magic Link Verification Endpoint
 * 
 * GET /auth/verify?token=...
 * Verifies magic link token and creates authenticated session
 */

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load MagicLinkController
require_once __DIR__ . '/../libs/controllers/MagicLinkController.php';
require_once __DIR__ . '/../libs/services/EmailService.php';

// WHY: build base URL once — prefer APP_URL env var, fall back to auto-detect from the
// current request so the redirect always matches the domain the user is actually visiting.
$appUrl = getenv('APP_URL');
if (empty($appUrl)) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $appUrl   = $protocol . '://' . $host;
}
$appUrl = rtrim($appUrl, '/');

try {
    // Get token from URL
    $token = trim($_GET['token'] ?? '');

    if (empty($token)) {
        $error = 'No token provided.';
    } else {
        // Verify magic link token
        $magicLink = new MagicLinkController();
        $result = $magicLink->verifyMagicLink($token);

        if ($result['success']) {
            // WHY: redirect to the same domain the user clicked the link on — never hardcode localhost
            header('Location: ' . $appUrl . '/dashboard');
            exit;
        } else {
            $error = $result['message'];
        }
    }

} catch (Exception $e) {
    error_log("Magic link verification error: " . $e->getMessage());
    $error = 'An error occurred during verification. Please try again.';
}

// If we reach here, authentication failed — show error page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication Error — Wheelder</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; }
        .container { max-width: 500px; margin: 100px auto; padding: 20px; }
        .card { background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 40px; text-align: center; }
        h1 { color: #d32f2f; margin-bottom: 20px; }
        p { color: #666; margin-bottom: 20px; line-height: 1.6; }
        a { display: inline-block; background: #007bff; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin-top: 20px; }
        a:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>⚠️ Authentication Failed</h1>
            <p><?php echo htmlspecialchars($error ?? 'Unknown error'); ?></p>
            <p>This link may have expired or already been used. Please request a new magic link.</p>
            <a href="<?php echo htmlspecialchars($appUrl, ENT_QUOTES, 'UTF-8'); ?>/login">Request New Link</a>
        </div>
    </div>
</body>
</html>
