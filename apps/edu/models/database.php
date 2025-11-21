<?php
include_once 'db_config.php';
class Database extends config
{
    /**
     * Execute a query and return result
     * @param string $sql SQL query
     * @return mysqli_result|bool
     */
    private function executeQuery($sql)
    {
        $conn = $this->connectDb();
        $result = $conn->query($sql);
        if (!$result) {
            echo "Error: " . $conn->error . "</br>";
        }
        $conn->close();
        return $result;
    }

    /**
     * Check if table exists
     * @param string $table Table name
     * @return bool
     */
    private function tableExists($table)
    {
        $conn = $this->connectDb();
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        $exists = $result && $result->num_rows > 0;
        $conn->close();
        return $exists;
    }

    /**
     * Drop table with tablespace cleanup
     * @param string $table Table name
     */
    private function dropTableSafely($table)
    {
        $conn = $this->connectDb();
        // Try to discard tablespace for InnoDB tables
        @$conn->query("ALTER TABLE $table DISCARD TABLESPACE");
        $conn->query("DROP TABLE IF EXISTS $table");
        $conn->close();
    }

    public function __construct()
    {
        $this->connectDb();
    }

    public function createDb()
    {
        $sql = "CREATE DATABASE IF NOT EXISTS $this->dbname";
        if ($this->executeQuery($sql)) {
            echo "Database created successfully</br>";
        }
    }

    public function renameDb($dbname, $newName)
    {
        $sql = "ALTER DATABASE $dbname RENAME TO $newName";
        if ($this->executeQuery($sql)) {
            echo "Database renamed successfully</br>";
        }
    }

    public function createTable($table, $columns)
    {
        // Drop existing table if it exists (handles tablespace issues)
        if ($this->tableExists($table)) {
            $this->dropTableSafely($table);
        }
        
        // Create the table
        $sql = "CREATE TABLE $table ($columns)";
        if ($this->executeQuery($sql)) {
            echo "Table $table created successfully</br>";
        }
    }


    public function createTableWithDataMigration($table, $columns, $migrateData = false)
    {
        if ($this->tableExists($table)) {
            echo "Table $table already exists</br>";
            
            if ($migrateData) {
                $conn = $this->connectDb();
                $tempTable = $table . '_temp';
                
                // Backup data to temp table
                $conn->query("CREATE TABLE $tempTable LIKE $table");
                $conn->query("INSERT INTO $tempTable SELECT * FROM $table");
                
                // Recreate table
                $this->dropTableSafely($table);
                $this->createTable($table, $columns);
                
                // Restore data
                $conn->query("INSERT INTO $table SELECT * FROM $tempTable");
                $conn->query("DROP TABLE $tempTable");
                $conn->close();
                
                echo "Table $table migrated successfully</br>";
            } else {
                $this->createTable($table, $columns);
            }
        } else {
            $this->createTable($table, $columns);
        }
    }



    public function deleteTable($table)
    {
        if ($this->tableExists($table)) {
            $this->dropTableSafely($table);
            echo "Table $table deleted successfully</br>";
        } else {
            echo "Table $table does not exist</br>";
        }
    }

    public function renameTable($table, $newName)
    {
        $sql = "ALTER TABLE $table RENAME TO $newName";
        if ($this->executeQuery($sql)) {
            echo "Table $table renamed successfully to $newName</br>";
        }
    }

    public function dropDb($dbname)
    {
        $sql = "DROP DATABASE IF EXISTS $dbname";
        if ($this->executeQuery($sql)) {
            echo "Database $dbname deleted successfully</br>";
        }
    }

    public function query($sql, $params = [])
    {
        $conn = $this->connectDb();
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            echo "Error preparing statement: " . $conn->error . "</br>";
            $conn->close();
            return false;
        }

        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }

        $result = $stmt->execute();
        if (!$result) {
            echo "Error executing query: " . $stmt->error . "</br>";
        }

        $stmt->close();
        $conn->close();
        return $result;
    }

    public function fetchAll($result)
    {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    /**
     * Backup database: export all tables to SQL file
     */
    public function backupDatabase()
    {
        $conn = $this->connectDb();
        $conn->set_charset("utf8");

        // Get all table names
        $tables = [];
        $result = $conn->query("SHOW TABLES");
        while ($row = $result->fetch_row()) {
            $tables[] = $row[0];
        }

        $sqlScript = "";
        foreach ($tables as $table) {
            // Get table structure
            $createResult = $conn->query("SHOW CREATE TABLE $table");
            $createRow = $createResult->fetch_row();
            $sqlScript .= "\n\n" . $createRow[1] . ";\n\n";

            // Get table data
            $dataResult = $conn->query("SELECT * FROM $table");
            while ($row = $dataResult->fetch_row()) {
                $values = array_map(function($val) {
                    return isset($val) ? '"' . addslashes($val) . '"' : 'NULL';
                }, $row);
                $sqlScript .= "INSERT INTO $table VALUES(" . implode(',', $values) . ");\n";
            }
            $sqlScript .= "\n";
        }

        if (!empty($sqlScript)) {
            // Get database name from connection
            $dbname = $conn->query("SELECT DATABASE()")->fetch_row()[0] ?? 'database';
            $backup_file = $dbname . '_backup_' . time() . '.sql';
            file_put_contents($backup_file, $sqlScript);

            // Download file
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($backup_file));
            header('Content-Length: ' . filesize($backup_file));
            readfile($backup_file);
            unlink($backup_file);
        }

        $conn->close();
    }

    /**
     * Copy table: create new table, copy data, drop old, rename new
     */
    public function copyTable($oldTable, $newTable)
    {
        $conn = $this->connectDb();
        
        $conn->query("CREATE TABLE $newTable LIKE $oldTable");
        $conn->query("INSERT INTO $newTable SELECT * FROM $oldTable");
        $this->dropTableSafely($oldTable);
        $conn->query("ALTER TABLE $newTable RENAME TO $oldTable");
        
        echo "Table $oldTable copied and replaced successfully</br>";
        $conn->close();
    }

    /**
     * Import SQL file: drop all tables, then execute SQL file
     */
    public function importSQLFile($sqlFile)
    {
        if (!file_exists($sqlFile)) {
            echo "SQL file not found: $sqlFile</br>";
            return;
        }

        $conn = $this->connectDb();
        $conn->begin_transaction();

        try {
            // Drop all existing tables
            $tables = $conn->query("SHOW TABLES");
            if ($tables) {
                while ($row = $tables->fetch_row()) {
                    $this->dropTableSafely($row[0]);
                }
            }

            // Execute SQL file
            $sql = file_get_contents($sqlFile);
            $queries = array_filter(array_map('trim', explode(';', $sql)));
            
            foreach ($queries as $query) {
                if (!empty($query)) {
                    if (!$conn->query($query)) {
                        throw new Exception("Error executing query: " . $conn->error);
                    }
                }
            }

            $conn->commit();
            echo "Database imported successfully</br>";
        } catch (Exception $e) {
            $conn->rollback();
            echo "Error: " . $e->getMessage() . "</br>";
        }

        $conn->close();
    }






}
