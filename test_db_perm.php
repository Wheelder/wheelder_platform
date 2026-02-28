<?php
// WHY: Temporary diagnostic script to verify SQLite write permissions on production.
// Delete this file after confirming the fix.
header('Content-Type: text/plain');

$dbPath = __DIR__ . '/apps/edu/ui/views/center/database.sqlite';
echo "DB Path: $dbPath\n";
echo "File exists: " . (file_exists($dbPath) ? 'YES' : 'NO') . "\n";
echo "File writable: " . (is_writable($dbPath) ? 'YES' : 'NO') . "\n";
echo "Dir writable: " . (is_writable(dirname($dbPath)) ? 'YES' : 'NO') . "\n";
echo "Owner: " . posix_getpwuid(fileowner($dbPath))['name'] . "\n";
echo "PHP user: " . posix_getpwuid(posix_geteuid())['name'] . "\n\n";

try {
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec('PRAGMA busy_timeout = 5000');
    $db->exec('PRAGMA journal_mode = WAL');
    echo "WAL mode: OK\n";

    // Test table creation
    $db->exec("CREATE TABLE IF NOT EXISTS conversations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        session_id TEXT NOT NULL,
        question TEXT,
        answer TEXT,
        image TEXT,
        depth_level INTEGER DEFAULT 0,
        created_at TEXT DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Table check: OK\n";

    // Test write
    $db->exec("INSERT INTO conversations (session_id, question, answer, image, depth_level) VALUES ('test_perm_check', 'test', 'test', 'test', 0)");
    echo "Write: OK\n";

    // Test read
    $r = $db->query("SELECT count(*) as cnt FROM conversations")->fetch(PDO::FETCH_ASSOC);
    echo "Rows: " . $r['cnt'] . "\n";

    // Cleanup test row
    $db->exec("DELETE FROM conversations WHERE session_id = 'test_perm_check'");
    echo "Cleanup: OK\n";

    echo "\n=== ALL TESTS PASSED ===\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
