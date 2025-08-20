<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If user not logged in, save the requested URL
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    $_SESSION['requested_url'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
}

// Set default app if available
$dApp = $_SESSION['default_app'] ?? null;
