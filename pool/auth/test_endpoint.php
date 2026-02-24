<?php
/**
 * Test Magic Link Endpoint
 * Direct test without fetch to diagnose issues
 */

header('Content-Type: text/html; charset=UTF-8');

?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Magic Link Endpoint</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .pass { background: #d4edda; }
        .fail { background: #f8d7da; }
        pre { background: #f4f4f4; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🔧 Magic Link Endpoint Test</h1>
    
    <?php
    // Test 1: Direct POST request simulation
    echo "<div class='test'>";
    echo "<h2>Test 1: Simulating POST Request</h2>";
    
    // Simulate POST data
    $_POST['email'] = 'test@example.com';
    $_SERVER['REQUEST_METHOD'] = 'POST';
    
    // Capture output
    ob_start();
    
    try {
        // Include the endpoint
        include __DIR__ . '/magic_link_request.php';
        $output = ob_get_clean();
        
        echo "<p><strong>Response:</strong></p>";
        echo "<pre>" . htmlspecialchars($output) . "</pre>";
        
        // Try to parse as JSON
        $data = json_decode($output, true);
        if ($data) {
            echo "<p class='pass'><strong>✓ Valid JSON Response</strong></p>";
            echo "<pre>" . json_encode($data, JSON_PRETTY_PRINT) . "</pre>";
        } else {
            echo "<p class='fail'><strong>✗ Invalid JSON Response</strong></p>";
        }
    } catch (Exception $e) {
        ob_end_clean();
        echo "<p class='fail'><strong>✗ Exception:</strong> " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
    
    echo "</div>";
    
    // Test 2: Check if endpoint file exists
    echo "<div class='test'>";
    echo "<h2>Test 2: File Existence</h2>";
    $endpoint = __DIR__ . '/magic_link_request.php';
    if (file_exists($endpoint)) {
        echo "<p class='pass'><strong>✓ Endpoint file exists:</strong> $endpoint</p>";
    } else {
        echo "<p class='fail'><strong>✗ Endpoint file not found:</strong> $endpoint</p>";
    }
    echo "</div>";
    
    // Test 3: Check includes
    echo "<div class='test'>";
    echo "<h2>Test 3: Required Files</h2>";
    $files = [
        __DIR__ . '/../libs/controllers/MagicLinkController.php',
        __DIR__ . '/../libs/services/EmailService.php',
        __DIR__ . '/../libs/controllers/Controller.php',
        __DIR__ . '/../config/database.php'
    ];
    
    foreach ($files as $file) {
        if (file_exists($file)) {
            echo "<p class='pass'>✓ " . basename($file) . "</p>";
        } else {
            echo "<p class='fail'>✗ " . basename($file) . " NOT FOUND</p>";
        }
    }
    echo "</div>";
    ?>
</body>
</html>
