<?php
/**
 * Minimal test for Wheelder Circular - bypasses session issues
 */

echo "=== Wheelder Circular - Minimal Setup Test ===\n\n";

// Check SQLite3 extension
echo "[1] Checking PHP SQLite3 extension...\n";
if (!extension_loaded('sqlite3')) {
    echo "✗ SQLite3 extension is NOT enabled\n";
    echo "\nTo enable SQLite3:\n";
    echo "1. Open php.ini file\n";
    echo "2. Find line: ;extension=sqlite3\n";
    echo "3. Remove the semicolon: extension=sqlite3\n";
    echo "4. Restart Apache/PHP\n";
    echo "\nAlternatively, use XAMPP which has SQLite3 enabled by default.\n";
    exit(1);
} else {
    echo "✓ SQLite3 extension is enabled\n\n";
}

// Test SQLite3 directly
echo "[2] Testing SQLite3 database creation...\n";
try {
    $dbPath = __DIR__ . '/apps/edu/ui/views/circular/circular_test.db';
    $db = new SQLite3($dbPath);
    
    $db->exec("CREATE TABLE IF NOT EXISTS test_table (
        id INTEGER PRIMARY KEY,
        name TEXT
    )");
    
    $db->exec("INSERT INTO test_table (name) VALUES ('test')");
    
    $result = $db->query("SELECT * FROM test_table");
    $row = $result->fetchArray();
    
    if ($row && $row['name'] === 'test') {
        echo "✓ SQLite3 database working correctly\n";
        echo "  Test database created at: $dbPath\n\n";
    }
    
    $db->close();
    unlink($dbPath); // Clean up test database
    
} catch (Exception $e) {
    echo "✗ SQLite3 error: " . $e->getMessage() . "\n";
    exit(1);
}

// Test DuckDuckGo API
echo "[3] Testing DuckDuckGo API...\n";
try {
    $query = urlencode("ChatGPT AI assistant");
    $url = "https://api.duckduckgo.com/?q={$query}&format=json&no_html=1&skip_disambig=1";
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Wheelder-Circular/1.0');
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200 && $response) {
        $data = json_decode($response, true);
        if (!empty($data['AbstractText'])) {
            echo "✓ DuckDuckGo API working\n";
            echo "  Sample: " . substr($data['AbstractText'], 0, 80) . "...\n\n";
        } else {
            echo "⚠ DuckDuckGo returned empty result\n";
            echo "  This is normal - not all queries return AbstractText\n\n";
        }
    } else {
        echo "✗ DuckDuckGo API error (HTTP $httpCode)\n\n";
    }
} catch (Exception $e) {
    echo "✗ DuckDuckGo error: " . $e->getMessage() . "\n\n";
}

echo "=== Minimal Test Complete ===\n\n";
echo "Next steps:\n";
echo "1. Ensure SQLite3 is enabled in php.ini\n";
echo "2. Visit http://localhost/circular/setup.php in your browser\n";
echo "3. This will initialize the database and populate AI tools\n";
echo "4. Then visit http://localhost/circular to use Wheelder Circular\n";
?>
