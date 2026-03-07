<?php
/**
 * Admin Script: Update Project Path
 * WHY: Temporary script to fix production project paths
 * DELETE THIS FILE AFTER USE
 */

require_once __DIR__ . '/config/config.php';

// WHY: Only allow execution with secret parameter to prevent abuse
$secret = $_GET['secret'] ?? '';
if ($secret !== 'update_path_2026') {
    die('Access denied');
}

try {
    $db = VackupDatabase::getInstance();
    
    // WHY: Get current project details
    $result = $db->query("SELECT id, name, project_path FROM projects");
    $projects = $result->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Current Projects</h2>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Name</th><th>Current Path</th><th>Action</th></tr>";
    
    foreach ($projects as $project) {
        echo "<tr>";
        echo "<td>{$project['id']}</td>";
        echo "<td>{$project['name']}</td>";
        echo "<td>{$project['project_path']}</td>";
        echo "<td>";
        
        // WHY: Provide update button for each project
        if (isset($_POST['update_' . $project['id']])) {
            $newPath = $_POST['new_path_' . $project['id']];
            $stmt = $db->prepare("UPDATE projects SET project_path = :path WHERE id = :id");
            $stmt->bindValue(':path', $newPath, PDO::PARAM_STR);
            $stmt->bindValue(':id', $project['id'], PDO::PARAM_INT);
            $stmt->execute();
            echo "<strong style='color:green'>Updated to: {$newPath}</strong>";
        } else {
            echo "<form method='POST'>";
            echo "<input type='text' name='new_path_{$project['id']}' value='/var/www/environments/production/wheelder.com' size='50'>";
            echo "<button type='submit' name='update_{$project['id']}'>Update</button>";
            echo "</form>";
        }
        
        echo "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
} catch (Exception $e) {
    die("Error: " . htmlspecialchars($e->getMessage()));
}
?>
