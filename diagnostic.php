<?php
/**
 * Diagnostic Script for Wheelder Platform
 * This script helps identify routing and configuration issues
 */

echo "=== Wheelder Platform Diagnostic Report ===\n\n";

// 1. Check PHP Version
echo "1. PHP Version: " . phpversion() . "\n";

// 2. Check if Router.php exists
echo "2. Router.php exists: " . (file_exists(__DIR__ . '/Router.php') ? "YES" : "NO") . "\n";

// 3. Check if index.php exists
echo "3. index.php exists: " . (file_exists(__DIR__ . '/index.php') ? "YES" : "NO") . "\n";

// 4. Check login2.php file
echo "4. pool/auth/login2.php exists: " . (file_exists(__DIR__ . '/pool/auth/login2.php') ? "YES" : "NO") . "\n";

// 5. Check login2_handler.php file
echo "5. pool/auth/login2_handler.php exists: " . (file_exists(__DIR__ . '/pool/auth/login2_handler.php') ? "YES" : "NO") . "\n";

// 6. Check database
echo "6. Database file exists: " . (file_exists(__DIR__ . '/pool/config/wheelder.db') ? "YES" : "NO") . "\n";

// 7. Check git status
echo "7. Git Status:\n";
$gitStatus = shell_exec("cd " . __DIR__ . " && git log --oneline -3 2>&1");
echo $gitStatus ? $gitStatus : "   Git not available\n";

// 8. Check REQUEST_URI
echo "8. REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "\n";

// 9. Check SCRIPT_NAME
echo "9. SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'NOT SET') . "\n";

// 10. Check HTTP_HOST
echo "10. HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'NOT SET') . "\n";

// 11. Test Router
echo "\n11. Testing Router:\n";
require_once __DIR__ . '/Router.php';
$router = new Router();
echo "    Base Path: '" . (new ReflectionClass($router))->getProperty('basePath')->getValue($router) . "'\n";

// 12. Check if routes are registered
echo "\n12. Checking Routes:\n";
$reflection = new ReflectionClass($router);
$routesProperty = $reflection->getProperty('routes');
$routesProperty->setAccessible(true);
$routes = $routesProperty->getValue($router);
echo "    Total routes registered: " . count($routes) . "\n";
echo "    /login2 route exists: " . (isset($routes['/login2']) ? "YES" : "NO") . "\n";
echo "    /login route exists: " . (isset($routes['/login']) ? "YES" : "NO") . "\n";

// 13. List first 10 routes
echo "\n13. First 10 registered routes:\n";
$count = 0;
foreach ($routes as $path => $handler) {
    if ($count >= 10) break;
    echo "    - " . $path . "\n";
    $count++;
}

echo "\n=== End of Diagnostic Report ===\n";
?>
