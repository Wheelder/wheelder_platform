<?php
// WHY: Diagnose why releases are not saving/showing on production
// Visit https://wheelder.com/diagnose_releases.php to see results
header('Content-Type: text/plain');

echo "=== RELEASE DIAGNOSTIC ===\n\n";

// 1. Check which ReleaseController code is deployed
$rcFile = __DIR__ . '/apps/edu/controllers/ReleaseController.php';
echo "1. ReleaseController exists: " . (file_exists($rcFile) ? 'YES' : 'NO') . "\n";
if (file_exists($rcFile)) {
    $rcContent = file_get_contents($rcFile);
    $hasPrepared = strpos($rcContent, 'connectDbPDO') !== false;
    echo "   Has PDO prepared statements fix: " . ($hasPrepared ? 'YES (fixed)' : 'NO (old broken code)') . "\n";
}

// 2. Check DB path and writability
$dbPath = __DIR__ . '/pool/config/wheelder.db';
echo "\n2. Database path: $dbPath\n";
echo "   DB file exists: " . (file_exists($dbPath) ? 'YES' : 'NO') . "\n";
if (file_exists($dbPath)) {
    echo "   DB file writable: " . (is_writable($dbPath) ? 'YES' : 'NO') . "\n";
    echo "   DB file size: " . filesize($dbPath) . " bytes\n";
    echo "   DB dir writable: " . (is_writable(dirname($dbPath)) ? 'YES' : 'NO') . "\n";
    $perms = substr(sprintf('%o', fileperms($dbPath)), -4);
    echo "   DB file permissions: $perms\n";
    $dirPerms = substr(sprintf('%o', fileperms(dirname($dbPath))), -4);
    echo "   DB dir permissions: $dirPerms\n";
    echo "   PHP running as: " . get_current_user() . " (posix: " . (function_exists('posix_getpwuid') ? posix_getpwuid(posix_geteuid())['name'] : 'N/A') . ")\n";
}

// 3. Try direct PDO query
echo "\n3. Direct PDO database test:\n";
try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "   PDO connection: OK\n";
    
    // Check if releases table exists
    $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='releases'")->fetchAll();
    echo "   Releases table exists: " . (count($tables) > 0 ? 'YES' : 'NO') . "\n";
    
    if (count($tables) > 0) {
        // Count all releases
        $count = $pdo->query("SELECT COUNT(*) as cnt FROM releases")->fetch(PDO::FETCH_ASSOC);
        echo "   Total releases: " . $count['cnt'] . "\n";
        
        // Count published
        $pubCount = $pdo->query("SELECT COUNT(*) as cnt FROM releases WHERE is_published = 1")->fetch(PDO::FETCH_ASSOC);
        echo "   Published releases: " . $pubCount['cnt'] . "\n";
        
        // List all
        $all = $pdo->query("SELECT id, title, version, is_published, created_at FROM releases ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($all)) {
            echo "\n   All releases in DB:\n";
            foreach ($all as $r) {
                echo "   - ID:{$r['id']} | Published:" . ($r['is_published'] ? 'YES' : 'NO') . " | v{$r['version']} | {$r['title']} | {$r['created_at']}\n";
            }
        }
        
        // Try a test insert
        echo "\n4. Write test:\n";
        try {
            $pdo->exec("INSERT INTO releases (title, description, content, version) VALUES ('__DIAG_TEST__', 'test', 'test', '0.0')");
            $lastId = $pdo->lastInsertId();
            echo "   INSERT: OK (id=$lastId)\n";
            $pdo->exec("DELETE FROM releases WHERE title = '__DIAG_TEST__'");
            echo "   DELETE cleanup: OK\n";
        } catch (Exception $e) {
            echo "   INSERT FAILED: " . $e->getMessage() . "\n";
            echo "   >>> THIS IS THE PROBLEM: Database is not writable! <<<\n";
        }
    }
} catch (Exception $e) {
    echo "   PDO FAILED: " . $e->getMessage() . "\n";
}

echo "\n=== END DIAGNOSTIC ===\n";
