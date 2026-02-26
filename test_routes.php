<?php
/**
 * Test Routes - Verify which routes are registered
 */

session_start();

require_once 'Router.php';

$router = new Router();

// Register all routes from index.php
$router->route('/', function() {
    echo "Home route";
});

$router->route('/login', function() {
    echo "Login route";
});

$router->route('/login2', function() {
    echo "Login2 route";
});

$router->route('/password-reset', function() {
    echo "Password reset route";
});

$router->route('/releases', function() {
    echo "Releases route";
});

// Get the routes using reflection
$reflection = new ReflectionClass($router);
$routesProperty = $reflection->getProperty('routes');
$routesProperty->setAccessible(true);
$routes = $routesProperty->getValue($router);

echo "=== Registered Routes ===\n";
echo "Total routes: " . count($routes) . "\n\n";

foreach ($routes as $path => $handler) {
    echo "- " . $path . "\n";
}

echo "\n=== Route Test Results ===\n";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'NOT SET') . "\n";

// Test if /login2 would be handled
$testPath = '/login2';
echo "\nTesting path: " . $testPath . "\n";
echo "Route exists: " . (isset($routes[$testPath]) ? "YES" : "NO") . "\n";
?>
