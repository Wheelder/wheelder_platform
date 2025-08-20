<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/apps/edu/controllers/DbController.php';

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
