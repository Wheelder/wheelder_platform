<?php
// Temporary web-accessible diagnostic — tests archiveConversation in PHP-FPM context
header('Content-Type: text/plain');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/apps/edu/ui/views/learn/backup/AppController.php';
$note = new AppController();

$dbPath = __DIR__ . '/apps/edu/ui/views/learn/backup/database.sqlite';
echo "DB writable: " . (is_writable($dbPath) ? 'yes' : 'no') . "\n";
echo "Dir writable: " . (is_writable(dirname($dbPath)) ? 'yes' : 'no') . "\n";
echo "PHP user: " . (function_exists('posix_geteuid') ? posix_getpwuid(posix_geteuid())['name'] : get_current_user()) . "\n\n";

// List conversations
$db = new PDO('sqlite:' . $dbPath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
echo "Conversations:\n";
foreach ($db->query('SELECT session_id FROM conversations GROUP BY session_id') as $r) {
    echo '  ' . $r['session_id'] . "\n";
}

// Try archive via the actual AppController method
$testSession = 'conv_698e1919775572.97860847';
echo "\nArchiving $testSession via AppController...\n";
try {
    $note->archiveConversation($testSession, 'archived');
    echo "SUCCESS\n";
} catch (PDOException $e) {
    echo "PDOException: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo "Previous: " . ($e->getPrevious() ? $e->getPrevious()->getMessage() : 'none') . "\n";
}
