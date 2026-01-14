<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/pool/config/database.php';



class Migration extends Database
{


    public function __construct()
    {
        $this->backupDatabase();
    }



}

$backup = new Migration();
if($backup->backupDatabase()){
    echo "Backup created successfully";
}else{
    echo "Backup failed";
}
