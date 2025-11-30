<?php
/**
 * Simple Environment Variable Loader
 * Loads variables from .env file in project root
 */

function loadEnv($filePath = null) {
    if ($filePath === null) {
        // Default to project root .env file
        $filePath = dirname(dirname(__DIR__)) . '/.env';
    }
    
    if (!file_exists($filePath)) {
        // If .env doesn't exist, return false (will use defaults)
        return false;
    }
    
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parse key=value pairs
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes if present
            $value = trim($value, '"\'');
            
            // Set environment variable if not already set
            if (!getenv($key)) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
            }
        }
    }
    return true;
}

// Auto-load .env file when this file is included
loadEnv();

