<?php


class config
{
    private $servername_local = "localhost";
    private $dbname_local = 'wheeleder';
    private $user_local = "root";
    private $pass_local = "root";
    
    private $servername = "localhost";
    private $dbname = 'u946493694_edu_platform';
    private $user = "u946493694_edu_u";
    private $pass = "M+oNc88e";

    //database details for testing server
    private $servername_d = "localhost";
    private $dbname_d = 'u559678163_wh_dev';
    private $user_d = "u559678163_wdu";
    private $pass_d = "passOfwh_dev!@#123";


    private $charset = 'utf8mb4';

    //write these these database detials with out varialbe scope like private or public

    


    public function __construct() {
        $this->connectDb();
    }

    public function checkHost() {
        $host = $_SERVER['HTTP_HOST'];
    
        // Use switch case to check the host name and return the host number
        switch ($host) {
            case 'localhost:3000':
                return 1;
            case 'regrowup.site':
                return 2;
            case 'wheeleder.com':
                return 3;
            default:
                return 0;
        }
    }
    
    public function connectDb() {
        $hostNumber = $this->checkHost();
    
        // Define an array with database details for different hosts
        $dbDetails = [
            1 => [
                'servername' => $this->servername_local,
                'dbname' => $this->dbname_local,
                'user' => $this->user_local,
                'pass' => $this->pass_local,
            ],
            2 => [
                'servername' => $this->servername,
                'dbname' => $this->dbname,
                'user' => $this->user,
                'pass' => $this->pass,
            ],
            3 => [
                'servername' => $this->servername_d,
                'dbname' => $this->dbname_d,
                'user' => $this->user_d,
                'pass' => $this->pass_d,
            ],
            0 => [
                'servername' => $this->servername,
                'dbname' => $this->dbname,
                'user' => $this->user,
                'pass' => $this->pass,
            ],
        ];
    
        // Get the database details based on the host number
        $dbConfig = $dbDetails[$hostNumber];
    
        // Use try and catch for mysqli connection
        $conn = new mysqli($dbConfig['servername'], $dbConfig['user'], $dbConfig['pass'], $dbConfig['dbname']);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    
        return $conn;
    }
    
    
        public function connectDbPDO() {
            
            $hostNumber = $this->checkHost();
    
        // Define an array with database details for different hosts
        $dbDetails = [
            1 => [
                'servername' => $this->servername_local,
                'dbname' => $this->dbname_local,
                'user' => $this->user_local,
                'pass' => $this->pass_local,
            ],
            2 => [
                'servername' => $this->servername,
                'dbname' => $this->dbname,
                'user' => $this->user,
                'pass' => $this->pass,
            ],
            3 => [
                'servername' => $this->servername_d,
                'dbname' => $this->dbname_d,
                'user' => $this->user_d,
                'pass' => $this->pass_d,
            ],
            0 => [
                'servername' => $this->servername_d,
                'dbname' => $this->dbname_d,
                'user' => $this->user_d,
                'pass' => $this->pass_d,
            ],
        ];
    
        // Get the database details based on the host number
        $dbConfig = $dbDetails[$hostNumber];
    
            //pdo connection
            $dsn = "mysql:host={$dbConfig['servername']};dbname={$dbConfig['dbname']};charset={$this->charset}";

            try {
                $pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['pass']);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $pdo;
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        
        }
        

}