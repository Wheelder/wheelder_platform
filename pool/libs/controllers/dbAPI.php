<?php
// Use __DIR__ to get the current file's directory (already in pool/libs/controllers)
include __DIR__ . '/DbController.php';

$db = new Db();

// Insert Note
if($_GET['key']=="createdb") {

    //$db->profiles_table();
//    $db->books();
    $db->notes($table = "notes");
    $db->notes_data($table = "notes_data");
    //$db->notes_suggested();
    //$db->blogs($table = "blogs");
    //$db->website_traffic($table = "website_traffic");

}



?>
