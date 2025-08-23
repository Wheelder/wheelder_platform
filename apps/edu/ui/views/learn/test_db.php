<?php
// Simple database connection test
echo "<h2>Database Connection Test</h2>";

try {
    $path = $_SERVER['DOCUMENT_ROOT'];
    require_once $path . '/apps/edu/controllers/Controller.php';
    
    $controller = new Controller();
    echo "<p style='color: green;'>✅ Controller instantiated successfully</p>";
    
    // Test database connection
    $conn = $controller->connectDb();
    if ($conn) {
        echo "<p style='color: green;'>✅ Database connection successful</p>";
        
        // Test if questions table exists
        $result = $conn->query("SHOW TABLES LIKE 'questions'");
        if ($result && $result->num_rows > 0) {
            echo "<p style='color: green;'>✅ Questions table exists</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ Questions table does not exist - you may need to run setup_database.php</p>";
        }
        
        $conn->close();
    } else {
        echo "<p style='color: red;'>❌ Database connection failed</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<h3>Database Test Complete!</h3>";
echo "<p><a href='test_config.php'>Test Configuration</a> | <a href='app_main.php'>Go to Learn Module</a></p>";
?>
