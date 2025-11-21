<?php


class config
{

    /*
    private $servername_local = "localhost";
    private $dbname_local = 'wheelder';
    private $user_local = "root";
    private $pass_local = "";
    */
    private $servername_local = "localhost";
    private $dbname_local = 'whd';
    private $user_local = "root";
    private $pass_local = "";
    
    private $servername = "localhost";
    private $dbname = 'wheelder_db';
    private $user = "root";
    private $pass = "YourNewPassword123!";

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
            case 'localhost':
            case 'localhost:80':
            case 'localhost:8080':
            case '127.0.0.1':
            case '127.0.0.1:80':
            case '127.0.0.1:8080':
                return 1; // Use local development database
            case 'wheelder.com':
                return 3;
            default:
                // For local development, default to localhost config
                if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
                    return 1;
                }
                return 0;
        }
    }
    
    public function connectDb() {
        $hostNumber = $this->checkHost();
    
        // Define an array with database details for different hosts
        $dbDetails = [
            1=>[
                'servername' => $this->servername_local,
                'dbname' => $this->dbname_local,
                'user' => $this->user_local,
                'pass' => $this->pass_local,
            ],
            2 => [
                'servername' => $this->servername_local,
                'dbname' => $this->dbname_local,
                'user' => $this->user_local,
                'pass' => $this->pass_local,
            ],
            3 => [
                'servername' => $this->servername,
                'dbname' => $this->dbname,
                'user' => $this->user,
                'pass' => $this->pass,
            ],
            4 => [
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
                'servername' => $this->servername_local,
                'dbname' => $this->dbname_local,
                'user' => $this->user_local,
                'pass' => $this->pass_local,
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