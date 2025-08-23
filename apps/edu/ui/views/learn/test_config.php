<?php
// Simple test file to verify configuration is working
session_start();

echo "<h2>Learn Module Configuration Test</h2>";

// Test if constants are defined
echo "<h3>Checking Constants:</h3>";
echo "<ul>";

$constants = [
    'OPENAI_API_ENDPOINT',
    'OPENAI_IMAGE_ENDPOINT',
    'OPENAI_API_KEY',
    'OPENAI_IMAGE_API_KEY',
    'DEFAULT_MODEL',
    'DEFAULT_IMAGE_MODEL',
    'MAX_TOKENS',
    'DEFAULT_TEMPERATURE',
    'DEFAULT_IMAGE_SIZE',
    'MAX_QUERY_LENGTH',
    'CSRF_TOKEN_NAME',
    'SESSION_TIMEOUT'
];

foreach ($constants as $constant) {
    if (defined($constant)) {
        echo "<li style='color: green;'>✅ $constant = " . constant($constant) . "</li>";
    } else {
        echo "<li style='color: red;'>❌ $constant is NOT defined</li>";
    }
}

echo "</ul>";

// Test if LearnController can be instantiated
echo "<h3>Testing LearnController:</h3>";
try {
    $path = $_SERVER['DOCUMENT_ROOT'];
    require_once $path . '/apps/edu/ui/views/learn/LearnController.php';
    
    $learn = new LearnController();
    echo "<p style='color: green;'>✅ LearnController instantiated successfully</p>";
    
    // Test CSRF token generation
    $token = $learn->generateCSRFToken();
    echo "<p style='color: green;'>✅ CSRF token generated: " . substr($token, 0, 20) . "...</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

// Test session variables
echo "<h3>Session Status:</h3>";
if (isset($_SESSION['user_id'])) {
    echo "<p style='color: green;'>✅ User is logged in (ID: " . $_SESSION['user_id'] . ")</p>";
} else {
    echo "<p style='color: orange;'>⚠️ User is not logged in</p>";
}

echo "<h3>Configuration Complete!</h3>";
echo "<p>If you see all green checkmarks above, your configuration is working properly.</p>";
echo "<p><a href='app_main.php'>Go to Learn Module</a></p>";
?>
