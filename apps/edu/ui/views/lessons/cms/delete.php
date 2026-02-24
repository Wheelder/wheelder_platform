<?php
// Use __DIR__ for reliable paths regardless of DOCUMENT_ROOT (same pattern as blog cms/delete.php)
include __DIR__ . '/../../../../controllers/LessonController.php';

$lesson = new LessonController();

// Auth gate — only logged-in users may delete lessons
$lesson->check_auth();

// Validate the ID before touching the database
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    // Reject non-numeric or missing IDs immediately — prevents accidental mass deletes
    echo '<p class="text-danger container mt-4">Invalid lesson ID. <a href="' . url('/lesson/cms') . '">Go back</a></p>';
    exit();
}

$result = $lesson->delete($id);

if ($result) {
    $lesson->alert_redirect('Lesson deleted successfully.', '/lesson/cms');
} else {
    // Deletion failed — could be a DB error or the row no longer exists
    $lesson->alert_redirect('Failed to delete lesson. It may have already been removed.', '/lesson/cms');
}
?>
