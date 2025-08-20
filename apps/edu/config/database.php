<?php
include_once 'config.php';
class Database extends config
{


    public function __construct()
    {

        $conn = $this->connectDb();
    }

    public function createDb()
    {
        $conn = $this->connectDb();

        $sql = "CREATE DATABASE IF NOT EXISTS $this->dbname";
        if ($conn->query($sql) === TRUE) {
            echo "Database created successfully";
        } else {
            echo "Error creating database: " . $conn->error;
        }

    }

    public function renameDb($dbname, $newName)
    {
        $conn = $this->connectDb();
        $sql = "ALTER DATABASE $dbname RENAME TO $newName";
        if ($conn->query($sql) === TRUE) {
            echo "Database renamed successfully";
        } else {
            echo "Error renaming database: " . $conn->error;
        }
    }

    public function createTable($table, $columns)
    {

        $sql = "CREATE TABLE IF NOT EXISTS $table ($columns)";
        if ($this->connectDb()->query($sql) === TRUE) {
            echo "Table $table created successfully</br>";
        } else {
            echo "Error creating table: " . $this->connectDb()->error;
        }
        $this->connectDb()->close();
    }


    public function createTableWithDataMigration($table, $columns)
    {
        $migrateData = false;
        $connection = $this->connectDb();

        // Check if the table already exists
        $sql = "SHOW TABLES LIKE '$table'";
        $result = $connection->query($sql);

        if ($result->num_rows == 0) {
            // Table does not exist, so create it
            $createSql = "CREATE TABLE $table ($columns)";
            if ($connection->query($createSql) === TRUE) {
                echo "Table $table created successfully</br>";
            } else {
                echo "Error creating table: " . $connection->error;
            }
        } else {
            // Table already exists
            echo "Table $table already exists</br>";

            if ($migrateData) {
                // Create a temporary table like the old table
                $tempTable = $table . '_temp';
                $createLikeSql = "CREATE TABLE $tempTable LIKE $table";
                $connection->query($createLikeSql);

                // Copy data from the old table to the temporary table
                $copyDataSql = "INSERT INTO $tempTable SELECT * FROM $table";
                $connection->query($copyDataSql);

                // Drop the old table
                $deleteOldSql = "DROP TABLE $table";
                $connection->query($deleteOldSql);

                // Create a new table with the specified columns
                $createNewSql = "CREATE TABLE $table ($columns)";
                $connection->query($createNewSql);

                // Copy data from the temporary table to the new table
                $copyBackSql = "INSERT INTO $table SELECT * FROM $tempTable";
                $connection->query($copyBackSql);

                // Drop the temporary table
                $deleteTempSql = "DROP TABLE $tempTable";
                $connection->query($deleteTempSql);

                echo "Table $table created successfully with data migration</br>";
            } else {
                // Data migration is disabled, simply recreate the table
                $recreateSql = "CREATE TABLE $table ($columns)";
                $connection->query($recreateSql);
                echo "Table $table recreated without data migration</br>";
            }
        }

        $connection->close();
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
        $conn->close();
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
        $conn->close();
    }

    public function dropDb($dbname)
    {
        $conn = $this->connectDb();
        $sql = "DROP DATABASE IF EXISTS $dbname";
        if ($conn->query($sql) === TRUE) {
            echo "Database $dbname deleted successfully</br>";
        } else {
            echo "Error deleting database: " . $conn->error;
        }
        $conn->close();
    }

    public function query($sql, $params = array())
    {
        $conn = $this->connectDb();

        // Prepare the statement
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo "Error preparing statement: " . $conn->error;
            return false;
        }

        // Bind parameters if provided
        if (!empty($params)) {
            $types = str_repeat('s', count($params)); // Assuming all params are strings
            $stmt->bind_param($types, ...$params);
        }

        // Execute the statement
        $result = $stmt->execute();
        if (!$result) {
            echo "Error executing query: " . $stmt->error;
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

    public function backupDatabase()
    {

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

        $database_name = $dbConfig['dbname'];

        // Connect to the database
        $conn = $this->connectDb();

        $conn->set_charset("utf8");


        // Get All Table Names From the Database
        $tables = array();
        $sql = "SHOW TABLES";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_row($result)) {
            $tables[] = $row[0];
        }

        $sqlScript = "";
        foreach ($tables as $table) {

            // Prepare SQLscript for creating table structure
            $query = "SHOW CREATE TABLE $table";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_row($result);

            $sqlScript .= "\n\n" . $row[1] . ";\n\n";


            $query = "SELECT * FROM $table";
            $result = mysqli_query($conn, $query);

            $columnCount = mysqli_num_fields($result);

            // Prepare SQLscript for dumping data for each table
            for ($i = 0; $i < $columnCount; $i++) {
                while ($row = mysqli_fetch_row($result)) {
                    $sqlScript .= "INSERT INTO $table VALUES(";
                    for ($j = 0; $j < $columnCount; $j++) {
                        $row[$j] = $row[$j];

                        if (isset($row[$j])) {
                            $sqlScript .= '"' . $row[$j] . '"';
                        } else {
                            $sqlScript .= '""';
                        }
                        if ($j < ($columnCount - 1)) {
                            $sqlScript .= ',';
                        }
                    }
                    $sqlScript .= ");\n";
                }
            }

            $sqlScript .= "\n";
        }

        if (!empty($sqlScript)) {
            // Save the SQL script to a backup file
            $backup_file_name = $database_name . '_backup_' . time() . '.sql';
            $fileHandler = fopen($backup_file_name, 'w+');
            $number_of_lines = fwrite($fileHandler, $sqlScript);
            fclose($fileHandler);

            // Download the SQL backup file to the browser
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
            exec('rm ' . $backup_file_name);
        }
    }

    //take a copy of the data from a table and insert it into another table, then delete old table and rename the new table as the old table
    public function copyTable($oldTable, $newTable)
    {
        $conn = $this->connectDb();
        $sql = "CREATE TABLE $newTable LIKE $oldTable";
        if ($conn->query($sql) === TRUE) {
            echo "Table $newTable created successfully</br>";
        } else {
            echo "Error creating table: " . $conn->error;
        }
        $sql = "INSERT INTO $newTable SELECT * FROM $oldTable";
        if ($conn->query($sql) === TRUE) {
            echo "Table $newTable copied successfully</br>";
        } else {
            echo "Error copying table: " . $conn->error;
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
        $conn->close();
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
            $tables = $conn->query("SHOW TABLES");
            if ($tables) {
                while ($row = $tables->fetch_row()) {
                    $table = $row[0];
                    // Drop each existing table
                    $conn->query("DROP TABLE IF EXISTS $table");
                }
            } else {
                echo "Error getting table list: " . $conn->error;
                $conn->rollback(); // Rollback the transaction
                return;
            }

            $sql = file_get_contents($sqlFile);
            $queries = explode(';', $sql);

            // Loop through and execute each query to create tables
            foreach ($queries as $query) {
                $query = trim($query);
                if (!empty($query)) {
                    $stmt = $conn->prepare($query);

                    if (!$stmt) {
                        echo "Error preparing statement: " . $conn->error;
                        $conn->rollback(); // Rollback the transaction
                        return;
                    } else {
                        $result = $stmt->execute();
                        if (!$result) {
                            echo "Error executing query: " . $stmt->error;
                            $conn->rollback(); // Rollback the transaction
                            return;
                        }
                        $stmt->close();
                    }
                }
            }

            // Commit the transaction if everything is successful
            $conn->commit();
            echo "Database tables created successfully.";
        } else {
            echo "SQL file not found: $sqlFile";
        }

        $conn->close();
    }






}
