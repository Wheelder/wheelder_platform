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

// WHY: Separate key so portfolio tables can be created without touching existing data
if($_GET['key']=="portfolio_setup") {
    $db->portfolio_sections();
    $db->portfolio_skills();
    $db->portfolio_projects();
    $db->portfolio_contacts();
}



?>
