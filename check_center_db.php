<?php
// WHY: Temporary diagnostic to verify SQLite write permissions and inspect stored data.
// DELETE after confirming fix.
header('Content-Type: text/plain');

$dbPath = __DIR__ . '/apps/edu/ui/views/center/database.sqlite';
echo "=== CENTER DB DIAGNOSTIC ===\n\n";
echo "DB Path: $dbPath\n";
echo "File exists: " . (file_exists($dbPath) ? 'YES' : 'NO') . "\n";
echo "File writable: " . (is_writable($dbPath) ? 'YES' : 'NO') . "\n";
echo "Dir writable: " . (is_writable(dirname($dbPath)) ? 'YES' : 'NO') . "\n";

if (function_exists('posix_geteuid')) {
    echo "PHP user: " . posix_getpwuid(posix_geteuid())['name'] . "\n";
}

echo "\n--- Write Test ---\n";
try {
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec('PRAGMA busy_timeout = 5000');
    $db->exec('PRAGMA journal_mode = WAL');
    echo "WAL mode: OK\n";

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

    $db->exec("INSERT INTO conversations (session_id, question, answer, image, depth_level) VALUES ('_test_perm_', 'test', 'test', 'test', 0)");
    echo "Write: OK\n";

    $db->exec("DELETE FROM conversations WHERE session_id = '_test_perm_'");
    echo "Cleanup: OK\n";
} catch (Exception $e) {
    echo "WRITE ERROR: " . $e->getMessage() . "\n";
}

echo "\n--- Recent Conversations ---\n";
try {
    $db2 = new PDO('sqlite:' . $dbPath);
    $db2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $rows = $db2->query("SELECT id, session_id, substr(question,1,50) as q, substr(image,1,120) as img, depth_level FROM conversations ORDER BY id DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
    echo "Total recent rows: " . count($rows) . "\n\n";
    foreach ($rows as $r) {
        echo "ID: {$r['id']} | Session: {$r['session_id']} | Depth: {$r['depth_level']}\n";
        echo "  Q: {$r['q']}\n";
        echo "  IMG: {$r['img']}\n\n";
    }
} catch (Exception $e) {
    echo "READ ERROR: " . $e->getMessage() . "\n";
}

echo "=== END ===\n";
