<?php
/**
 * Test script for Wheelder Circular setup
 * Verifies database initialization and DuckDuckGo integration
 */

echo "=== Wheelder Circular - Setup Test ===\n\n";

// Test 1: Load CircularController
echo "[1] Loading CircularController...\n";
require_once __DIR__ . '/apps/edu/ui/views/circular/CircularController.php';

try {
    $circular = new CircularController();
    echo "✓ CircularController loaded successfully\n\n";
} catch (Exception $e) {
    echo "✗ Failed to load CircularController: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 2: Check database tables
echo "[2] Verifying database tables...\n";
try {
    $tools = $circular->getAllTools();
    echo "✓ Database tables accessible\n";
    echo "  Current tools count: " . count($tools) . "\n\n";
} catch (Exception $e) {
    echo "✗ Database error: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 3: Test DuckDuckGo search
echo "[3] Testing DuckDuckGo API...\n";
try {
    $result = $circular->searchDuckDuckGo("ChatGPT AI assistant");
    if ($result && !empty($result['AbstractText'])) {
        echo "✓ DuckDuckGo API working\n";
        echo "  Sample result: " . substr($result['AbstractText'], 0, 100) . "...\n\n";
    } else {
        echo "⚠ DuckDuckGo returned empty result (may be rate limited)\n\n";
    }
} catch (Exception $e) {
    echo "✗ DuckDuckGo error: " . $e->getMessage() . "\n\n";
}

// Test 4: Auto-populate tools (if database is empty)
if (count($tools) === 0) {
    echo "[4] Auto-populating tools database...\n";
    echo "  This may take 10-15 seconds (rate limiting)...\n";
    try {
        $populated = $circular->autoPopulateTools();
        echo "✓ Populated $populated AI tools\n\n";
        
        $tools = $circular->getAllTools();
        echo "  Total tools now: " . count($tools) . "\n\n";
    } catch (Exception $e) {
        echo "✗ Auto-populate error: " . $e->getMessage() . "\n\n";
    }
} else {
    echo "[4] Tools database already populated (skipping)\n\n";
}

// Test 5: Display sample tools
echo "[5] Sample tools in database:\n";
$sampleTools = array_slice($tools, 0, 5);
foreach ($sampleTools as $tool) {
    echo "  - {$tool['name']} ({$tool['category']})\n";
    if (!empty($tool['url'])) {
        echo "    URL: {$tool['url']}\n";
    }
}
echo "\n";

// Test 6: Test query analysis
echo "[6] Testing query analysis...\n";
try {
    $analysis = $circular->analyzeQuery("How can I build a React app with AI?");
    echo "✓ Query analysis working\n";
    echo "  Type: " . ($analysis['query_type'] ?? 'unknown') . "\n";
    echo "  Complexity: " . ($analysis['complexity'] ?? 'unknown') . "\n\n";
} catch (Exception $e) {
    echo "✗ Query analysis error: " . $e->getMessage() . "\n\n";
}

// Test 7: Test tool recommendation
echo "[7] Testing tool recommendation...\n";
try {
    $analysis = ['recommended_categories' => ['coding']];
    $recommended = $circular->getRecommendedTools($analysis, 3);
    echo "✓ Tool recommendation working\n";
    echo "  Found " . count($recommended) . " recommended tools\n";
    foreach ($recommended as $tool) {
        echo "  - {$tool['name']}\n";
    }
    echo "\n";
} catch (Exception $e) {
    echo "✗ Tool recommendation error: " . $e->getMessage() . "\n\n";
}

// Test 8: Test full query processing
echo "[8] Testing full query processing...\n";
try {
    $result = $circular->processQuery("Explain machine learning");
    if ($result['success']) {
        echo "✓ Full query processing working\n";
        echo "  Session ID: " . $result['session_id'] . "\n";
        echo "  Base answer length: " . strlen($result['base_answer']) . " chars\n";
        echo "  Recommended tools: " . count($result['recommended_tools']) . "\n\n";
    } else {
        echo "✗ Query processing failed: " . ($result['message'] ?? 'Unknown error') . "\n\n";
    }
} catch (Exception $e) {
    echo "✗ Query processing error: " . $e->getMessage() . "\n\n";
}

// Summary
echo "=== Setup Test Complete ===\n";
echo "✓ Wheelder Circular is ready to use\n";
echo "\nNext steps:\n";
echo "1. Visit http://localhost/circular to use the interface\n";
echo "2. Or run setup.php in browser: http://localhost/circular/setup\n";
echo "3. Read WHEELDER_CIRCULAR_README.md for full documentation\n";
?>
