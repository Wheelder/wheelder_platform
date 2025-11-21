<?php
$docRoot = $_SERVER['DOCUMENT_ROOT'];        // e.g. C:/xampp/htdocs
$scriptPath = $_SERVER['SCRIPT_NAME'];       // e.g. /wheelder/index.php
$parts = explode('/', trim($scriptPath, '/'));
$projectDir = $parts[0];      
$path = $docRoot."/".$projectDir;               // "wheelder"
include $path . '/apps/edu/controllers/BlogController.php';
$blog = new BlogController();
$blog->check_auth();
// Initialize NoteController


// Get the ID from the URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Check if the ID is valid
if ($id !== null && is_numeric($id)) {
    // Perform the delete operation
    $result = $blog->delete($id);
    
    if ($result) {
        $blog->alert_redirect('Note deleted successfully.', '/blog/cms');
    } else {
        
        $blog->alert_redirect('Failed to delete note.', '/blog/cms');
    }
} else {
    echo "Invalid ID.";
}

// Redirect or show a message here...
?>
