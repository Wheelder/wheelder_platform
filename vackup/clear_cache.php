<?php
/**
 * OPcache Clear Script
 * WHY: Force clear OPcache when files are updated
 * DELETE AFTER USE
 */

$secret = $_GET['secret'] ?? '';
if ($secret !== 'clear_cache_2026') {
    die('Access denied');
}

if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "OPcache cleared successfully\n";
} else {
    echo "OPcache not enabled\n";
}

if (function_exists('opcache_invalidate')) {
    $files = [
        __DIR__ . '/settings.php',
        __DIR__ . '/index.php',
        __DIR__ . '/projects.php',
        __DIR__ . '/projects_new.php',
        __DIR__ . '/projects_edit.php',
    ];
    
    foreach ($files as $file) {
        if (file_exists($file)) {
            opcache_invalidate($file, true);
            echo "Invalidated: $file\n";
        }
    }
}

echo "\nDone. Refresh your pages now.";
?>
