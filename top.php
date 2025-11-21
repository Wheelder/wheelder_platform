<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    // Secure session configuration
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_strict_mode', 1);
    // Only use secure cookies if HTTPS is enabled
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        ini_set('session.cookie_secure', 1);
    }
    ini_set('session.cookie_samesite', 'Strict');
    session_start();
}

// If user not logged in, save the requested URL
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    $_SESSION['requested_url'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
}

// Set default app if available
$dApp = $_SESSION['default_app'] ?? null;

/**
 * Get the base URL path for the application
 * Automatically detects if the project is in a subdirectory
 * 
 * @return string Base path (e.g., '/wheelder' or '')
 */
function getBasePath() {
    static $basePath = null;
    
    if ($basePath === null) {
        $scriptName = $_SERVER['SCRIPT_NAME']; // Example: /wheelder/index.php or /index.php
        $scriptDir = dirname($scriptName);     // Example: /wheelder or /
        
        // If script is in root, return empty string
        if ($scriptDir === '/' || $scriptDir === '\\') {
            $basePath = '';
        } else {
            // Return the directory path (e.g., /wheelder)
            $basePath = $scriptDir;
        }
    }
    
    return $basePath;
}

/**
 * Get the full base URL including protocol and host
 * 
 * @return string Full base URL (e.g., 'http://localhost/wheelder')
 */
function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    $basePath = getBasePath();
    
    return $protocol . $host . $basePath;
}

/**
 * Generate a URL with the base path automatically included
 * 
 * @param string $path The path to append (e.g., '/log_api', '/login')
 * @return string Full URL with base path
 */
function url($path = '') {
    $basePath = getBasePath();
    $path = '/' . ltrim($path, '/');
    return $basePath . $path;
}
