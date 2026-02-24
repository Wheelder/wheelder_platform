<?php
// Auth gate — only authenticated users may manage lessons (same pattern as blogAPI.php)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Authentication required']);
    exit();
}

$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/wheelder/apps/edu/controllers/LessonController.php';

$lesson = new LessonController();

// --- INSERT a new lesson ---
if (isset($_POST['insert'])) {

    $title      = trim($_POST['title']      ?? '');
    $category   = trim($_POST['category']   ?? '');
    $content    = trim($_POST['content']    ?? '');
    $image_url  = trim($_POST['image_url']  ?? '');
    $code_block = trim($_POST['code_block'] ?? '');

    // Strip markdown artefacts that may arrive from AI-generated content
    $title   = preg_replace('/\*\*|[###]/', '', $title);
    $content = preg_replace('/\*\*|[###]/', '', $content);

    // Reject empty title — a lesson without a title is not diagnosable later
    if ($title === '') {
        $lesson->alert_redirect('Title is required.', '/lesson/cms');
        exit();
    }

    $res = $lesson->insert($title, $category, $content, $image_url, $code_block);

    if ($res) {
        $lesson->alert_redirect('Lesson added successfully.', '/lesson/cms');
    } else {
        $lesson->alert_redirect('Failed to add lesson. Please try again.', '/lesson/cms');
    }
}

// --- UPDATE an existing lesson ---
if (isset($_POST['action']) && $_POST['action'] === 'update') {

    $id         = (int) ($_POST['id']         ?? 0);
    $title      = trim($_POST['title']      ?? '');
    $category   = trim($_POST['category']   ?? '');
    $content    = trim($_POST['content']    ?? '');
    $image_url  = trim($_POST['image_url']  ?? '');
    $code_block = trim($_POST['code_block'] ?? '');

    // Validate ID before touching the database
    if ($id <= 0) {
        $lesson->alert_redirect('Invalid lesson ID.', '/lesson/cms');
        exit();
    }

    $res = $lesson->update($id, $title, $category, $content, $image_url, $code_block);

    if ($res) {
        $lesson->alert_redirect('Lesson updated successfully.', '/lesson/cms');
    } else {
        $lesson->alert_redirect('Lesson update failed. Please try again.', '/lesson/cms');
    }
}

// --- DELETE a lesson ---
if (isset($_POST['action']) && $_POST['action'] === 'delete') {

    $id = (int) ($_POST['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(['error' => 'Invalid lesson ID.']);
        exit();
    }

    $res = $lesson->delete($id);
    echo json_encode(['message' => $res ? 'Lesson deleted successfully.' : 'Delete failed.']);
}
