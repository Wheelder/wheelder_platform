<?php
// Diagnostic script to check route availability and file existence
header('Content-Type: text/plain');

echo "=== WHEELDER ROUTE DIAGNOSTICS ===\n\n";

// Check if portfolio files exist
$portfolioFiles = [
    'Controller' => 'apps/edu/controllers/PortfolioController.php',
    'API' => 'apps/edu/api/portfolioAPI.php',
    'Public View' => 'apps/edu/ui/views/portfolio/app.php',
    'CMS Index' => 'apps/edu/ui/views/portfolio/cms/index.php',
    'CMS Sections' => 'apps/edu/ui/views/portfolio/cms/sections.php',
    'CMS Skills' => 'apps/edu/ui/views/portfolio/cms/skills.php',
    'CMS Projects' => 'apps/edu/ui/views/portfolio/cms/projects.php',
    'CMS Contacts' => 'apps/edu/ui/views/portfolio/cms/contacts.php',
];

echo "1. FILE EXISTENCE CHECK:\n";
echo str_repeat("-", 50) . "\n";
foreach ($portfolioFiles as $name => $file) {
    $fullPath = __DIR__ . '/' . $file;
    $exists = file_exists($fullPath);
    $status = $exists ? '✓ EXISTS' : '✗ MISSING';
    echo sprintf("%-20s %s\n", $name . ':', $status);
    if ($exists) {
        echo sprintf("%-20s %s\n", '', $fullPath);
    }
}

echo "\n2. ROUTER CONFIGURATION:\n";
echo str_repeat("-", 50) . "\n";
require_once 'Router.php';
$router = new Router();
echo "Base Path: " . (empty($router) ? '(empty)' : 'Detected') . "\n";
echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Project Dir: " . __DIR__ . "\n";

echo "\n3. .HTACCESS CHECK:\n";
echo str_repeat("-", 50) . "\n";
$htaccess = __DIR__ . '/.htaccess';
if (file_exists($htaccess)) {
    echo "✓ .htaccess exists\n";
    echo "Content:\n";
    echo file_get_contents($htaccess);
} else {
    echo "✗ .htaccess MISSING\n";
}

echo "\n4. GIT STATUS:\n";
echo str_repeat("-", 50) . "\n";
if (file_exists(__DIR__ . '/.git')) {
    echo "✓ Git repository detected\n";
    $gitHead = file_get_contents(__DIR__ . '/.git/HEAD');
    echo "Current branch: " . trim($gitHead) . "\n";
    
    // Get last commit
    exec('git log -1 --oneline 2>&1', $output, $returnCode);
    if ($returnCode === 0) {
        echo "Last commit: " . implode("\n", $output) . "\n";
    }
} else {
    echo "✗ Not a git repository\n";
}

echo "\n5. APACHE MODULES:\n";
echo str_repeat("-", 50) . "\n";
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    $rewriteEnabled = in_array('mod_rewrite', $modules);
    echo "mod_rewrite: " . ($rewriteEnabled ? '✓ ENABLED' : '✗ DISABLED') . "\n";
} else {
    echo "Cannot check (not running under Apache or CGI)\n";
}

echo "\n6. DATABASE CHECK:\n";
echo str_repeat("-", 50) . "\n";
$dbPath = __DIR__ . '/pool/config/wheelder.db';
if (file_exists($dbPath)) {
    echo "✓ Database exists: $dbPath\n";
    try {
        $db = new SQLite3($dbPath);
        $tables = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name LIKE 'portfolio_%'");
        echo "Portfolio tables:\n";
        $count = 0;
        while ($row = $tables->fetchArray(SQLITE3_ASSOC)) {
            echo "  - " . $row['name'] . "\n";
            $count++;
        }
        if ($count === 0) {
            echo "  ✗ No portfolio tables found\n";
            echo "  Run: /edu_db?key=portfolio_setup\n";
        }
    } catch (Exception $e) {
        echo "✗ Database error: " . $e->getMessage() . "\n";
    }
} else {
    echo "✗ Database not found\n";
}

echo "\n=== END DIAGNOSTICS ===\n";
