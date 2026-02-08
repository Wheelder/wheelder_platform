<?php
/**
 * SQLite Wrapper Classes
 * 
 * These classes mimic the mysqli interface so that existing code
 * using mysqli methods (query, prepare, bind_param, execute, 
 * get_result, fetch_assoc, num_rows, etc.) works with SQLite
 * without requiring changes throughout the codebase.
 */

class SQLiteResult
{
    private $rows = [];
    private $index = 0;
    public $num_rows = 0;

    public function __construct($stmt)
    {
        if ($stmt instanceof PDOStatement) {
            $this->rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->num_rows = count($this->rows);
        }
    }

    public function fetch_assoc()
    {
        if ($this->index < $this->num_rows) {
            return $this->rows[$this->index++];
        }
        return null;
    }

    public function fetch_array()
    {
        if ($this->index < $this->num_rows) {
            $row = $this->rows[$this->index++];
            // Return both associative and numeric keys like MYSQLI_BOTH
            return array_merge($row, array_values($row));
        }
        return null;
    }

    public function fetch_row()
    {
        if ($this->index < $this->num_rows) {
            return array_values($this->rows[$this->index++]);
        }
        return null;
    }

    public function fetch_all($mode = PDO::FETCH_ASSOC)
    {
        return $this->rows;
    }
}

class SQLiteStatement
{
    private $stmt;
    private $pdo;
    private $params = [];
    private $types = '';
    public $error = '';

    public function __construct($pdo, $sql)
    {
        $this->pdo = $pdo;
        // Convert MySQL-style ? placeholders — they work in SQLite PDO as-is
        try {
            $this->stmt = $pdo->prepare($sql);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            $this->stmt = false;
        }
    }

    public function bind_param($types, &...$params)
    {
        $this->types = $types;
        $this->params = $params;
        return true;
    }

    public function execute()
    {
        if ($this->stmt === false) {
            return false;
        }
        try {
            if (!empty($this->params)) {
                // Bind each parameter by position (1-indexed for PDO)
                foreach ($this->params as $i => $value) {
                    $type = isset($this->types[$i]) ? $this->types[$i] : 's';
                    switch ($type) {
                        case 'i':
                            $pdoType = PDO::PARAM_INT;
                            break;
                        case 'd':
                            $pdoType = PDO::PARAM_STR; // PDO has no float type
                            break;
                        case 'b':
                            $pdoType = PDO::PARAM_LOB;
                            break;
                        default:
                            $pdoType = PDO::PARAM_STR;
                    }
                    $this->stmt->bindValue($i + 1, $value, $pdoType);
                }
            }
            return $this->stmt->execute();
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function get_result()
    {
        return new SQLiteResult($this->stmt);
    }

    public function close()
    {
        $this->stmt = null;
        return true;
    }
}

class SQLiteConnection
{
    private $pdo;
    public $connect_error = null;
    public $error = '';
    public $insert_id = 0;
    public $affected_rows = 0;

    public function __construct($dbPath)
    {
        try {
            $this->pdo = new PDO('sqlite:' . $dbPath);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Enable WAL mode for better concurrent access
            $this->pdo->exec('PRAGMA journal_mode=WAL');
            // Enable foreign keys
            $this->pdo->exec('PRAGMA foreign_keys=ON');
        } catch (PDOException $e) {
            $this->connect_error = $e->getMessage();
        }
    }

    public function query($sql)
    {
        try {
            $stmt = $this->pdo->query($sql);
            if ($stmt === false) {
                return false;
            }
            // For SELECT statements, return a result object
            if (stripos(trim($sql), 'SELECT') === 0 || stripos(trim($sql), 'SHOW') === 0 || stripos(trim($sql), 'PRAGMA') === 0) {
                return new SQLiteResult($stmt);
            }
            // For INSERT/UPDATE/DELETE, update metadata and return true
            $this->affected_rows = $stmt->rowCount();
            $this->insert_id = $this->pdo->lastInsertId();
            return true;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function prepare($sql)
    {
        $wrapper = new SQLiteStatement($this->pdo, $sql);
        if ($wrapper->error) {
            $this->error = $wrapper->error;
            return false;
        }
        return $wrapper;
    }

    public function real_escape_string($value)
    {
        // SQLite uses '' to escape single quotes
        return str_replace("'", "''", $value);
    }

    public function escape_string($value)
    {
        return $this->real_escape_string($value);
    }

    public function set_charset($charset)
    {
        // SQLite always uses UTF-8, no-op
        return true;
    }

    public function begin_transaction()
    {
        return $this->pdo->beginTransaction();
    }

    public function commit()
    {
        return $this->pdo->commit();
    }

    public function rollback()
    {
        return $this->pdo->rollBack();
    }

    public function close()
    {
        // PDO closes on null, but we keep the object alive
        // since the codebase creates new connections frequently
        return true;
    }

    /**
     * Simulate SHOW TABLES LIKE 'tablename' for SQLite
     * Returns a SQLiteResult with num_rows > 0 if table exists
     */
    public function showTablesLike($table)
    {
        $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name=:name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':name' => $table]);
        return new SQLiteResult($stmt);
    }

    /**
     * Get the underlying PDO instance (for advanced use)
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * Get number of fields in last result (for backup compatibility)
     */
    public function field_count()
    {
        return 0;
    }
}

/**
 * Query interceptor: rewrites MySQL-specific SQL to SQLite-compatible SQL
 */
function sqlite_rewrite_sql($sql)
{
    // SHOW TABLES LIKE 'x' → SELECT name FROM sqlite_master WHERE type='table' AND name='x'
    if (preg_match("/SHOW\s+TABLES\s+LIKE\s+'([^']+)'/i", $sql, $m)) {
        return "SELECT name FROM sqlite_master WHERE type='table' AND name='" . $m[1] . "'";
    }

    // SHOW TABLES → list all tables
    if (preg_match("/^\s*SHOW\s+TABLES\s*$/i", $sql)) {
        return "SELECT name FROM sqlite_master WHERE type='table' ORDER BY name";
    }

    // SHOW CREATE TABLE x → not supported in SQLite, return table sql
    if (preg_match("/SHOW\s+CREATE\s+TABLE\s+(\S+)/i", $sql, $m)) {
        return "SELECT sql as 'Create Table' FROM sqlite_master WHERE type='table' AND name='" . $m[1] . "'";
    }

    // Remove MySQL-specific column modifiers
    // INT(6) UNSIGNED → INTEGER
    $sql = preg_replace('/\bINT\(\d+\)\s*UNSIGNED\b/i', 'INTEGER', $sql);
    // INT(6) → INTEGER
    $sql = preg_replace('/\bINT\(\d+\)\b/i', 'INTEGER', $sql);
    // INT AUTO_INCREMENT → INTEGER PRIMARY KEY AUTOINCREMENT (only if followed by AUTO_INCREMENT)
    // Handle: id INT AUTO_INCREMENT PRIMARY KEY → id INTEGER PRIMARY KEY AUTOINCREMENT
    $sql = preg_replace('/\bAUTO_INCREMENT\b/i', 'AUTOINCREMENT', $sql);
    // VARCHAR(n) → TEXT
    $sql = preg_replace('/\bVARCHAR\(\d+\)/i', 'TEXT', $sql);
    // LONGTEXT → TEXT
    $sql = preg_replace('/\bLONGTEXT\b/i', 'TEXT', $sql);
    // DATETIME → TEXT (SQLite stores dates as text)
    $sql = preg_replace('/\bdatetime\b/i', 'TEXT', $sql);
    // TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP → TEXT DEFAULT CURRENT_TIMESTAMP
    $sql = preg_replace('/\bTIMESTAMP\s+DEFAULT\s+CURRENT_TIMESTAMP\s+ON\s+UPDATE\s+CURRENT_TIMESTAMP\b/i', 'TEXT DEFAULT CURRENT_TIMESTAMP', $sql);
    $sql = preg_replace('/\bTIMESTAMP\b/i', 'TEXT', $sql);
    // JSON → TEXT
    $sql = preg_replace('/\bJSON\b/i', 'TEXT', $sql);
    // DATE → TEXT
    $sql = preg_replace('/\bDATE\b/i', 'TEXT', $sql);

    // Remove ENGINE=InnoDB or similar
    $sql = preg_replace('/\s*ENGINE\s*=\s*\w+/i', '', $sql);
    // Remove DEFAULT CHARSET=...
    $sql = preg_replace('/\s*DEFAULT\s+CHARSET\s*=\s*\w+/i', '', $sql);

    // ALTER DATABASE ... RENAME → not supported, no-op
    if (preg_match('/ALTER\s+DATABASE/i', $sql)) {
        return "SELECT 1"; // no-op
    }

    // CREATE DATABASE → no-op for SQLite
    if (preg_match('/CREATE\s+DATABASE/i', $sql)) {
        return "SELECT 1";
    }

    // DROP DATABASE → no-op for SQLite
    if (preg_match('/DROP\s+DATABASE/i', $sql)) {
        return "SELECT 1";
    }

    // ALTER TABLE ... DISCARD TABLESPACE → no-op
    if (preg_match('/DISCARD\s+TABLESPACE/i', $sql)) {
        return "SELECT 1";
    }

    // CREATE TABLE ... LIKE → not supported in SQLite
    if (preg_match('/CREATE\s+TABLE\s+(\S+)\s+LIKE\s+(\S+)/i', $sql, $m)) {
        // We'll handle this as a no-op; the backup/migration functions are rarely used
        return "SELECT 1";
    }

    // Fix PRIMARY KEY AUTOINCREMENT ordering
    // SQLite requires: INTEGER PRIMARY KEY AUTOINCREMENT
    // Handle case: INTEGER AUTOINCREMENT PRIMARY KEY NOT NULL
    $sql = preg_replace(
        '/\bINTEGER\s+AUTOINCREMENT\s+PRIMARY\s+KEY(?:\s+NOT\s+NULL)?\b/i',
        'INTEGER PRIMARY KEY AUTOINCREMENT',
        $sql
    );

    // Handle: INTEGER PRIMARY KEY NOT NULL → INTEGER PRIMARY KEY (NOT NULL is implicit)
    $sql = preg_replace(
        '/\bINTEGER\s+PRIMARY\s+KEY\s+AUTOINCREMENT\s+NOT\s+NULL\b/i',
        'INTEGER PRIMARY KEY AUTOINCREMENT',
        $sql
    );

    return $sql;
}
