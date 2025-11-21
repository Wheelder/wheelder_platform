<?php
// Use __DIR__ to get the current file's directory and navigate relatively
$path = dirname(__DIR__); // Goes from apps/edu/api to apps/edu
include $path . '/controllers/DbController.php';

$db = new Db();

// Insert Note
if($_GET['key']=="createdb") {

    // Create users table if it doesn't exist (required for authentication)
    $db->profiles_table();
    $db->books();
    $db->questions();
    $db->notes();
    $db->notes_data();
    $db->notes_suggested();
    $db->blogs();
    $db->website_traffic();

}



?>
