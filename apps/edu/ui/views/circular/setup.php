<?php
/**
 * Circular Setup Script
 * Initializes database and auto-populates AI tools using DuckDuckGo API
 */

require_once __DIR__ . '/CircularController.php';

echo "<h1>Wheelder Circular - Setup</h1>\n";
echo "<p>Initializing meta-AI orchestrator...</p>\n";

try {
    $circular = new CircularController();
    
    echo "<p>✓ Database initialized successfully</p>\n";
    echo "<p>✓ Tables created: circular_sessions, circular_messages, circular_tools, circular_workflows</p>\n";
    
    echo "<h2>Auto-populating AI Tools Database</h2>\n";
    echo "<p>Searching DuckDuckGo for AI tool information...</p>\n";
    
    $populated = $circular->autoPopulateTools();
    
    echo "<p>✓ Successfully populated <strong>$populated</strong> AI tools</p>\n";
    
    echo "<h2>Verification</h2>\n";
    $tools = $circular->getAllTools();
    echo "<p>Total tools in database: <strong>" . count($tools) . "</strong></p>\n";
    
    if (count($tools) > 0) {
        echo "<h3>Sample Tools:</h3>\n";
        echo "<ul>\n";
        foreach (array_slice($tools, 0, 10) as $tool) {
            echo "<li><strong>{$tool['name']}</strong> ({$tool['category']}) - {$tool['url']}</li>\n";
        }
        echo "</ul>\n";
    }
    
    echo "<hr>\n";
    echo "<p><a href='/circular'>Go to Wheelder Circular →</a></p>\n";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>\n";
    echo "<pre>" . $e->getTraceAsString() . "</pre>\n";
}
?>
