<?php
session_start();
$path=$_SERVER['DOCUMENT_ROOT'];
include $path.'/apps/work/controllers/StorageController.php';
$storage=new StorageController();

if (isset($_FILES["upload"])) {
     
  
      ($storage->save() ? "Uploaded" : $_STORE->error);
    
}
?>