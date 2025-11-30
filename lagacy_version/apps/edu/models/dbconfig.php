<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/pool/config/database.php';



class Dbconfig extends Database
{
   private $dbfile;

    public function __construct()
    {
        //$this->backupDatabase();
        $this->importSQLFile($this->dbfile);

    }



}

$dbc = new Dbconfig();

$dbc->importSQLFile("wheeleder_backup_1697997845.sql");
