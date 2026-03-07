<?php
/**
 * Vackup Database Configuration
 * Separate SQLite database for Vackup app using PDO
 */

class VackupDatabase
{
    private static $instance = null;
    private $pdo;
    private $dbPath;

    private function __construct()
    {
        $this->dbPath = __DIR__ . '/vackup.db';
        $this->connect();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function connect()
    {
        try {
            $this->pdo = new PDO('sqlite:' . $this->dbPath);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            // Enable foreign keys
            $this->pdo->exec('PRAGMA foreign_keys = ON');
        } catch (PDOException $e) {
            die("Vackup Database Connection Error: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->pdo;
    }

    public function query($sql)
    {
        return $this->pdo->query($sql);
    }

    public function exec($sql)
    {
        return $this->pdo->exec($sql);
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    public function lastInsertRowID()
    {
        return $this->pdo->lastInsertId();
    }

    public function escapeString($string)
    {
        // PDO uses prepared statements, but for compatibility:
        return substr($this->pdo->quote($string), 1, -1);
    }

    public function close()
    {
        $this->pdo = null;
    }
}
