<?php
/**
 * Magic Link Diagnostic Test
 * 
 * Helps identify why magic link login is failing
 * Access via: http://localhost/auth/magic-link-test
 */

header('Content-Type: text/html; charset=UTF-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../libs/controllers/MagicLinkController.php';
require_once __DIR__ . '/../libs/services/EmailService.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Magic Link Diagnostic Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .pass { background: #d4edda; border-color: #28a745; }
        .fail { background: #f8d7da; border-color: #dc3545; }
        .warn { background: #fff3cd; border-color: #ffc107; }
        h2 { color: #333; }
        code { background: #f4f4f4; padding: 2px 5px; border-radius: 3px; }
    </style>
</head>
<body>
    <h1>🔍 Magic Link Diagnostic Test</h1>
    
    <?php
    $tests = [];
    
    // Test 1: Database connection
    try {
        require_once __DIR__ . '/../config/db_config.php';
        $config = new config();
        $db = $config->connectDb();
        
        if ($db) {
            $tests[] = ['name' => 'Database Connection', 'status' => 'pass', 'message' => 'Connected successfully'];
        } else {
            $tests[] = ['name' => 'Database Connection', 'status' => 'fail', 'message' => 'Connection failed'];
        }
    } catch (Exception $e) {
        $tests[] = ['name' => 'Database Connection', 'status' => 'fail', 'message' => $e->getMessage()];
    }
    
    // Test 2: magic_links table exists
    try {
        $db = $config->connectDb();
        $result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='magic_links'");
        
        if ($result && $result->num_rows > 0) {
            $tests[] = ['name' => 'magic_links Table', 'status' => 'pass', 'message' => 'Table exists'];
        } else {
            $tests[] = ['name' => 'magic_links Table', 'status' => 'fail', 'message' => 'Table does not exist - will be auto-created on first request'];
        }
    } catch (Exception $e) {
        $tests[] = ['name' => 'magic_links Table', 'status' => 'fail', 'message' => $e->getMessage()];
    }
    
    // Test 3: users table exists
    try {
        $db = $config->connectDb();
        $result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
        
        if ($result && $result->num_rows > 0) {
            $tests[] = ['name' => 'users Table', 'status' => 'pass', 'message' => 'Table exists'];
        } else {
            $tests[] = ['name' => 'users Table', 'status' => 'fail', 'message' => 'Table does not exist'];
        }
    } catch (Exception $e) {
        $tests[] = ['name' => 'users Table', 'status' => 'fail', 'message' => $e->getMessage()];
    }
    
    // Test 4: SMTP Configuration
    $smtpHost = getenv('SMTP_HOST');
    $smtpUsername = getenv('SMTP_USERNAME');
    $smtpPassword = getenv('SMTP_PASSWORD');
    
    if (!empty($smtpUsername) && !empty($smtpPassword)) {
        $tests[] = ['name' => 'SMTP Configuration', 'status' => 'pass', 'message' => "Host: $smtpHost, Username: " . substr($smtpUsername, 0, 5) . '***'];
    } else {
        $tests[] = ['name' => 'SMTP Configuration', 'status' => 'warn', 'message' => 'SMTP credentials not configured in .env - emails will not send'];
    }
    
    // Test 5: MagicLinkController instantiation
    try {
        $magicLink = new MagicLinkController();
        $tests[] = ['name' => 'MagicLinkController', 'status' => 'pass', 'message' => 'Instantiated successfully'];
    } catch (Exception $e) {
        $tests[] = ['name' => 'MagicLinkController', 'status' => 'fail', 'message' => $e->getMessage()];
    }
    
    // Test 6: Test token generation
    try {
        $token = bin2hex(random_bytes(16));
        if (strlen($token) === 32) {
            $tests[] = ['name' => 'Token Generation', 'status' => 'pass', 'message' => 'Generated 32-char token: ' . substr($token, 0, 8) . '...'];
        } else {
            $tests[] = ['name' => 'Token Generation', 'status' => 'fail', 'message' => 'Token length incorrect'];
        }
    } catch (Exception $e) {
        $tests[] = ['name' => 'Token Generation', 'status' => 'fail', 'message' => $e->getMessage()];
    }
    
    // Display results
    foreach ($tests as $test) {
        $class = $test['status'];
        echo "<div class='test $class'>";
        echo "<h2>" . ($test['status'] === 'pass' ? '✓' : ($test['status'] === 'fail' ? '✗' : '⚠')) . " " . $test['name'] . "</h2>";
        echo "<p>" . $test['message'] . "</p>";
        echo "</div>";
    }
    
    // Summary
    $passCount = count(array_filter($tests, fn($t) => $t['status'] === 'pass'));
    $failCount = count(array_filter($tests, fn($t) => $t['status'] === 'fail'));
    $warnCount = count(array_filter($tests, fn($t) => $t['status'] === 'warn'));
    
    echo "<hr>";
    echo "<h2>Summary</h2>";
    echo "<p><strong>Passed:</strong> $passCount | <strong>Failed:</strong> $failCount | <strong>Warnings:</strong> $warnCount</p>";
    
    if ($failCount > 0) {
        echo "<p style='color: red;'><strong>⚠️ Issues found:</strong> Please fix the failures above before using magic link login.</p>";
    } else if ($warnCount > 0) {
        echo "<p style='color: orange;'><strong>⚠️ Warnings:</strong> Magic link login may not work fully. Configure SMTP in .env file.</p>";
    } else {
        echo "<p style='color: green;'><strong>✓ All systems operational!</strong> Magic link login should work.</p>";
    }
    ?>
    
    <hr>
    <h3>Next Steps</h3>
    <ol>
        <li>If database connection fails: Check that <code>pool/config/wheelder.db</code> exists and is readable</li>
        <li>If tables don't exist: Run database migration at <code>/sqlite_setup?action=cr</code></li>
        <li>If SMTP not configured: Create <code>.env</code> file with SMTP credentials (copy from <code>.env.example</code>)</li>
        <li>After fixing issues: Try magic link login again at <code>/login</code></li>
    </ol>
</body>
</html>
