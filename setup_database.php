<?php
/**
 * Database Setup Script for XAMPP Local Development
 * Run this script once to create the wheeleder database
 */

// XAMPP default connection (no database selected)
$servername = "localhost";
$username = "root";
$password = "";

try {
    // Create connection without specifying database
    $conn = new mysqli($servername, $username, $password);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    echo "Connected successfully to MySQL server<br>";
    
    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS wheeleder CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci";
    if ($conn->query($sql) === TRUE) {
        echo "Database 'wheeleder' created successfully or already exists<br>";
    } else {
        echo "Error creating database: " . $conn->error . "<br>";
    }
    
    // Select the database
    $conn->select_db("wheeleder");
    
    // Check if we have backup SQL files to import
    $sqlFiles = [
        __DIR__ . '/apps/edu/models/wheeleder_backup_1697997845.sql',
        __DIR__ . '/apps/edu/config/wheeleder_backup_1697997845.sql',
        __DIR__ . '/pool/config/wheeleder_backup_1697997845.sql'
    ];
    
    $sqlFileFound = false;
    foreach ($sqlFiles as $sqlFile) {
        if (file_exists($sqlFile)) {
            echo "Found SQL backup file: $sqlFile<br>";
            echo "To import the database structure and data, please:<br>";
            echo "1. Open phpMyAdmin: <a href='http://localhost/phpmyadmin' target='_blank'>http://localhost/phpmyadmin</a><br>";
            echo "2. Select the 'wheeleder' database<br>";
            echo "3. Go to Import tab<br>";
            echo "4. Choose file: $sqlFile<br>";
            echo "5. Click Go to import<br><br>";
            $sqlFileFound = true;
            break;
        }
    }
    
    if (!$sqlFileFound) {
        echo "No SQL backup files found. The empty database 'wheeleder' has been created.<br>";
        echo "You may need to create tables manually or import your SQL backup through phpMyAdmin.<br>";
    }
    
    $conn->close();
    echo "<br>Database setup completed!<br>";
    echo "<a href='/wheelder/learn'>Test your application</a>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
