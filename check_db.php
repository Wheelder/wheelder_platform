<?php
require_once 'apps/edu/controllers/Controller.php';

$ctrl = new Controller();

// Check if releases table exists
$result = $ctrl->run_query("SHOW TABLES LIKE 'releases'");
if ($result && $result->num_rows > 0) {
    echo "✓ Releases table exists\n";
    
    // Count total records
    $countResult = $ctrl->run_query("SELECT COUNT(*) as cnt FROM releases");
    if ($countResult) {
        $row = $countResult->fetch_assoc();
        echo "Total records: " . $row['cnt'] . "\n";
        
        // List all records
        $listResult = $ctrl->run_query("SELECT id, title, is_published, created_at FROM releases");
        if ($listResult) {
            while ($record = $listResult->fetch_assoc()) {
                echo "  ID: " . $record['id'] . " | Title: " . $record['title'] . " | Published: " . $record['is_published'] . " | Created: " . $record['created_at'] . "\n";
            }
        } else {
            echo "Failed to list records\n";
        }
    } else {
        echo "Failed to count records\n";
    }
} else {
    echo "✗ Releases table does not exist\n";
}
?>
