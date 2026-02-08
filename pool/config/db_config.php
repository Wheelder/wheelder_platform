<?php
/**
 * Central Database Configuration — SQLite
 * 
 * Uses a single SQLite file as the database for both the auth system
 * and the edu app. The SQLiteConnection wrapper mimics the mysqli 
 * interface so existing code works without changes.
 */

require_once __DIR__ . '/sqlite_wrapper.php';

class config
{
    // Singleton connection to avoid opening multiple handles
    private static $connection = null;

    // Static DB path — resolved once, works regardless of constructor order
    private static $dbPath = null;

    private static function getDbPath() {
        if (self::$dbPath === null) {
            self::$dbPath = __DIR__ . '/wheelder.db';
        }
        return self::$dbPath;
    }

    public function __construct() {
        $this->connectDb();
    }

    public function checkHost() {
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';

        switch ($host) {
            case 'localhost':
            case 'localhost:80':
            case 'localhost:8080':
            case '127.0.0.1':
            case '127.0.0.1:80':
            case '127.0.0.1:8080':
                return 1;
            case 'wheelder.com':
                return 3;
            default:
                if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
                    return 1;
                }
                return 0;
        }
    }

    public function connectDb() {
        if (self::$connection !== null) {
            return self::$connection;
        }

        $conn = new SQLiteConnection(self::getDbPath());
        if ($conn->connect_error) {
            die("SQLite connection failed: " . $conn->connect_error);
        }

        self::$connection = $conn;
        return $conn;
    }

    public function connectDbPDO() {
        // Return the underlying PDO from the SQLite wrapper
        $conn = $this->connectDb();
        return $conn->getPdo();
    }
}