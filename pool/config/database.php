<?php
include_once __DIR__ . '/db_config.php';
class Database extends config
{


    public function __construct()
    {

        $conn = $this->connectDb();
    }

    /**
     * Helper: rewrite SQL for SQLite compatibility before executing
     */
    private function rewriteSql($sql)
    {
        return sqlite_rewrite_sql($sql);
    }

    public function createDb()
    {
        // No-op for SQLite (single file database)
        echo "Database ready (SQLite)</br>";
    }

    public function renameDb($dbname, $newName)
    {
        // No-op for SQLite
        echo "Database rename not applicable for SQLite</br>";
    }

    public function createTable($table, $columns)
    {

        $sql = $this->rewriteSql("CREATE TABLE IF NOT EXISTS $table ($columns)");
        if ($this->connectDb()->query($sql) === TRUE) {
            echo "Table $table created successfully</br>";
        } else {
            echo "Error creating table: " . $this->connectDb()->error;
        }
    }


    public function createTableWithDataMigration($table, $columns)
    {
        $migrateData = false;
        $connection = $this->connectDb();

        // Check if the table already exists
        $sql = $this->rewriteSql("SHOW TABLES LIKE '$table'");
        $result = $connection->query($sql);

        if ($result->num_rows == 0) {
            // Table does not exist, so create it
            $createSql = $this->rewriteSql("CREATE TABLE $table ($columns)");
            if ($connection->query($createSql) === TRUE) {
                echo "Table $table created successfully</br>";
            } else {
                echo "Error creating table: " . $connection->error;
            }
        } else {
            // Table already exists
            echo "Table $table already exists</br>";

            if ($migrateData) {
                $tempTable = $table . '_temp';
                // SQLite: CREATE TABLE AS SELECT instead of CREATE TABLE LIKE
                $connection->query("CREATE TABLE $tempTable AS SELECT * FROM $table");
                $connection->query("DROP TABLE $table");
                $createNewSql = $this->rewriteSql("CREATE TABLE $table ($columns)");
                $connection->query($createNewSql);
                $connection->query("INSERT INTO $table SELECT * FROM $tempTable");
                $connection->query("DROP TABLE $tempTable");
                echo "Table $table created successfully with data migration</br>";
            } else {
                $recreateSql = $this->rewriteSql("CREATE TABLE $table ($columns)");
                $connection->query($recreateSql);
                echo "Table $table recreated without data migration</br>";
            }
        }
    }



    public function deleteTable($table)
    {
        $conn = $this->connectDb();
        $sql = "DROP TABLE IF EXISTS $table";
        if ($conn->query($sql) === TRUE) {
            echo "Table $table deleted successfully</br>";
        } else {
            echo "Error deleting table: " . $conn->error;
        }
    }

    public function renameTable($table, $newName)
    {
        $conn = $this->connectDb();
        $sql = "ALTER TABLE $table RENAME TO $newName";
        if ($conn->query($sql) === TRUE) {
            echo "Table $table renamed successfully to $newName</br>";
        } else {
            echo "Error renaming table: " . $conn->error;
        }
    }

    public function dropDb($dbname)
    {
        // No-op for SQLite
        echo "Drop database not applicable for SQLite</br>";
    }

    public function query($sql, $params = array())
    {
        $conn = $this->connectDb();
        $sql = $this->rewriteSql($sql);

        // Prepare the statement
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo "Error preparing statement: " . $conn->error;
            return false;
        }

        // Bind parameters if provided
        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }

        // Execute the statement
        $result = $stmt->execute();
        if (!$result) {
            echo "Error executing query: " . $stmt->error;
        }

        $stmt->close();
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

    public function backupDatabase()
    {
        $conn = $this->connectDb();
        $database_name = 'wheelder';

        $conn->set_charset("utf8");

        // Get All Table Names From the Database
        $tables = array();
        $sql = $this->rewriteSql("SHOW TABLES");
        $result = $conn->query($sql);

        while ($row = $result->fetch_row()) {
            $tables[] = $row[0];
        }

        $sqlScript = "";
        foreach ($tables as $table) {

            // Get table creation SQL
            $query = $this->rewriteSql("SHOW CREATE TABLE $table");
            $result = $conn->query($query);
            $row = $result->fetch_row();

            if ($row) {
                $sqlScript .= "\n\n" . $row[0] . ";\n\n";
            }

            // Get all data
            $query = "SELECT * FROM $table";
            $result = $conn->query($query);

            while ($row = $result->fetch_assoc()) {
                $values = array_map(function($v) {
                    return $v === null ? 'NULL' : '"' . str_replace('"', '""', $v) . '"';
                }, array_values($row));
                $sqlScript .= "INSERT INTO $table VALUES(" . implode(',', $values) . ");\n";
            }

            $sqlScript .= "\n";
        }

        if (!empty($sqlScript)) {
            $backup_file_name = $database_name . '_backup_' . time() . '.sql';
            $fileHandler = fopen($backup_file_name, 'w+');
            fwrite($fileHandler, $sqlScript);
            fclose($fileHandler);

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($backup_file_name));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($backup_file_name));
            ob_clean();
            flush();
            readfile($backup_file_name);
            unlink($backup_file_name);
        }
    }

    //take a copy of the data from a table and insert it into another table, then delete old table and rename the new table as the old table
    public function copyTable($oldTable, $newTable)
    {
        $conn = $this->connectDb();
        // SQLite: use CREATE TABLE AS SELECT instead of CREATE TABLE LIKE
        $sql = "CREATE TABLE $newTable AS SELECT * FROM $oldTable";
        if ($conn->query($sql) === TRUE) {
            echo "Table $newTable created and copied successfully</br>";
        } else {
            echo "Error creating table: " . $conn->error;
        }
        $sql = "DROP TABLE $oldTable";
        if ($conn->query($sql) === TRUE) {
            echo "Table $oldTable deleted successfully</br>";
        } else {
            echo "Error deleting table: " . $conn->error;
        }
        $sql = "ALTER TABLE $newTable RENAME TO $oldTable";
        if ($conn->query($sql) === TRUE) {
            echo "Table $newTable renamed successfully to $oldTable</br>";
        } else {
            echo "Error renaming table: " . $conn->error;
        }
    }

    //create a function to run sql queries to drop all tables in a database and then run the queries to create the tables again
    public function importSQLFile($sqlFile)
    {
        $conn = $this->connectDb();

        // Check if the SQL file exists
        if (file_exists($sqlFile)) {
            // Start a transaction for this operation
            $conn->begin_transaction();

            // Get a list of all tables in the database
            $tables = $conn->query($this->rewriteSql("SHOW TABLES"));
            if ($tables) {
                while ($row = $tables->fetch_row()) {
                    $table = $row[0];
                    $conn->query("DROP TABLE IF EXISTS $table");
                }
            } else {
                echo "Error getting table list: " . $conn->error;
                $conn->rollback();
                return;
            }

            $sql = file_get_contents($sqlFile);
            $queries = explode(';', $sql);

            foreach ($queries as $query) {
                $query = trim($query);
                if (!empty($query)) {
                    $query = $this->rewriteSql($query);
                    $stmt = $conn->prepare($query);

                    if (!$stmt) {
                        echo "Error preparing statement: " . $conn->error;
                        $conn->rollback();
                        return;
                    } else {
                        $result = $stmt->execute();
                        if (!$result) {
                            echo "Error executing query: " . $stmt->error;
                            $conn->rollback();
                            return;
                        }
                        $stmt->close();
                    }
                }
            }

            $conn->commit();
            echo "Database tables created successfully.";
        } else {
            echo "SQL file not found: $sqlFile";
        }
    }




}
