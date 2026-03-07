<?php
/**
 * Vackup Configuration
 * Version Control + Backup Platform
 */

// Timezone
date_default_timezone_set('Canada/Eastern');

// Error reporting (dev mode)
error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED & ~E_NOTICE);
ini_set('display_errors', 'On');

// Vackup paths
define('VACKUP_ROOT', dirname(__DIR__));
define('VACKUP_CONFIG', __DIR__);
define('VACKUP_INCLUDES', VACKUP_ROOT . '/includes');
define('VACKUP_TEMPLATES', VACKUP_ROOT . '/templates');
define('VACKUP_API', VACKUP_ROOT . '/api');

// Default storage locations (can be overridden per project)
define('DEFAULT_LOCAL_STORAGE', 'C:/Users/' . get_current_user() . '/OneDrive/Vackups');
define('DEFAULT_GOOGLE_DRIVE', 'C:/Users/' . get_current_user() . '/Google Drive/Vackups');

// GitHub settings (token-based auth for simplicity)
// Store actual token in .env or settings table
define('GITHUB_API_URL', 'https://api.github.com');

// Version format
define('VERSION_FORMAT', 'semantic'); // 'semantic' (v1.0, v1.1) or 'incremental' (1, 2, 3)

// Zip exclusions (common patterns to skip)
$VACKUP_EXCLUDE_PATTERNS = [
    '.git',
    'node_modules',
    'vendor',
    '.env',
    '*.log',
    '.DS_Store',
    'Thumbs.db',
    '__pycache__',
    '*.pyc',
    '.idea',
    '.vscode',
    'storage/logs',
    'storage/cache',
];

// Load database
require_once __DIR__ . '/database.php';
