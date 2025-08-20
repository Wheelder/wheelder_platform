<?php

trait CrudController {
    
    // Create a new record in the specified table
    public function createRecord($tableName, $data) {
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_map(function($value) {
            return "'" . $this->escape_string($value) . "'";
        }, array_values($data)));

        $query = "INSERT INTO $tableName ($columns) VALUES ($values)";
        $this->run_query($query);
    }
    
    // Read all records from the specified table
    public function readAllRecords($tableName) {
        $query = "SELECT * FROM $tableName";
        $result = $this->run_query($query);
        
        // Process the result and return records
        // ...
    }
    
    // Read a single record from the specified table by its ID
    public function readRecordByID($tableName, $id) {
        $query = "SELECT * FROM $tableName WHERE id = $id";
        $result = $this->run_query($query);
        
        // Process the result and return the record
        // ...
    }
    
    // Update a record in the specified table
    public function updateRecord($tableName, $id, $data) {
        $updates = implode(', ', array_map(function($key, $value) {
            return "$key = '" . $this->escape_string($value) . "'";
        }, array_keys($data), array_values($data)));

        $query = "UPDATE $tableName SET $updates WHERE id = $id";
        $this->run_query($query);
    }
    
    // Delete a record from the specified table by its ID
    public function deleteRecord($tableName, $id) {
        $query = "DELETE FROM $tableName WHERE id = $id";
        $this->run_query($query);
    }
    
    // Search for records in the specified table based on a specific field value
    public function searchRecords($tableName, $field, $value) {
        $query = "SELECT * FROM $tableName WHERE $field = '" . $this->escape_string($value) . "'";
        $result = $this->run_query($query);
        
        // Process the result and return records
        // ...
    }
    
    // Filter records in the specified table based on a specific condition
    public function filterRecords($tableName, $condition) {
        $query = "SELECT * FROM $tableName WHERE $condition";
        $result = $this->run_query($query);
        
        // Process the result and return records
        // ...
    }
    
    // Helper function to escape special characters in a string
    
    
    // Helper function to execute SQL queries
    
}
?>
