<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/apps/edu/controllers/NoteController.php';
$noteController = new NoteController();
$noteController->check_auth();
// Initialize NoteController


// Get the ID from the URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Check if the ID is valid
if ($id !== null && is_numeric($id)) {
    // Perform the delete operation
    $result = $noteController->delete($id);
    
    if ($result) {
        $noteController->alert_redirect('Note deleted successfully.', '/notes/cms');
    } else {
        
        $noteController->alert_redirect('Failed to delete note.', '/notes/cms');
    }
} else {
    echo "Invalid ID.";
}


// Redirect or show a message here...
?>
