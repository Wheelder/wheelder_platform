<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/wheelder/apps/edu/controllers/DbController.php';

$db = new Db();

// Insert Note
if($_GET['key']=="createdb") {

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
