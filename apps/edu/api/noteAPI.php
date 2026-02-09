<?php
// Auth gate — block unauthenticated access to note management API
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Authentication required']);
    exit();
}

$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/wheelder/apps/edu/controllers/NoteController.php';

$noteController = new NoteController();


if (isset($_POST['insert'])) {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $image = $_POST['image'] ?? '';
    $category = $_POST['category'] ?? '';

    /*
    $fileInput = $_FILES['fileInput'] ?? null; // 
    $urlInput = $_FILES['urlInput'] ?? null;

    if ($fileInput !== null) {
        $img = $fileInput;

        $img_path = ''; // Initialize the variable

        // Check if an image was uploaded
        if ($img && $img['error'] === 0) {
            $uploadDir = '/storage/notes/'; // Specify the directory where you want to store uploaded images
            $imgName = $img['name'];

            // Generate a unique file name to prevent overwriting existing files
            $imgName = uniqid() . '_' . $imgName;

            // Move the uploaded file to the specified directory
            if (move_uploaded_file($img['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $uploadDir . $imgName)) {
                // Image uploaded successfully, now you can use $imgName in your database or further processing
                $img_path = $uploadDir . $imgName; // Set the uploaded file path
            } else {
                echo "Error uploading image: " . $_FILES['img']['error'];
                // You might want to handle this error more gracefully, e.g., redirect back with an error message.
            }
        }
    } else {
        $img_path = $urlInput;
    }
    */

    // Trim the title and content
    $title = trim($title);
    $content = trim($content);
    $category = trim($category);

    // Remove double asterisks from the notes
    $title = preg_replace('/\*\*/', '', $title);
    $content = preg_replace('/\*\*/', '', $content);
    $category = preg_replace('/\*\*/', '', $category);

    // Search for the code script and assign it to $example


    // Avoid converting special characters to HTML entities
    $status = 0;



    // Insert the note into your database, and use $img_path and $section_title_json
    $res = $noteController->insert_data($title, $image,$category,$content,$status);

    if ($res) {
        $noteController->alert_redirect('Note added successfully.', '/notes/cms');
    }
}

// Insert Note
if (isset($_POST['suggest_topic'])) {
    $deadline = $_POST['deadline'] ?? '';
    $content = $_POST['content'] ?? '';
    //trim the title and content
    $deadline = trim($deadline);
    $content = trim($content);
    //trim the title and content

    $deadline = htmlspecialchars($deadline);
    $content = htmlspecialchars($content);



    $deadline = preg_replace('/[###]/', '', $deadline);
    $content = preg_replace('/[###]/', '', $content);

    $deadline = preg_replace('/[\*\*]/', '', $deadline);
    $content = preg_replace('/[\*\*]/', '', $content);

    // here need a function to add line break to the content after each line and full stop

    //add a line break after 10 words including spaces

    //$content = preg_replace('/(\S+\s*){10}/', '$0<br>', $content);

    //make a change here to auto detect the end of a sentence and add a full stop



    $res = $noteController->insert_suggestion($deadline, $content);
    if ($res) {
        $noteController->alert_redirect('Topic Was Suggested Successfully', '/blog');
    }
}

// Update Note
if (isset($_POST['action']) && $_POST['action'] === 'update') {

    $id = $_POST['id'] ?? 0;
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $category = $_POST['category'] ?? '';
    $status = $_POST['status'] ?? '';
    $image = $_POST['image'] ?? '';

    $title = trim($title);
    $content = trim($content);
    //trim the title and content

    $title = htmlspecialchars($title);
    $content = htmlspecialchars($content);

    //trim ** from the notes

    $title = preg_replace('/\*\*/', '', $title);
    $content = preg_replace('/\*\*/', '', $content);
    $category = preg_replace('/\*\*/', '', $category);

    //also remove ### from the notes and ## 

    $title = preg_replace('/[###]/', '', $title);
    $content = preg_replace('/[###]/', '', $content);
    $category = preg_replace('/[###]/', '', $category);


    $res = $noteController->update($id, $title, $category,$image,$content,$status);
    if ($res) {
        $noteController->alert_redirect('Note updated successfully.', '/notes/cms');
    } else {
        $noteController->alert_redirect('Note updated failed.', '/notes/cms');
    }

}

// Delete Note
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'] ?? 0;
    $noteController->delete($id);
    $response['message'] = 'Note deleted successfully.';
}
if($_GET['img']!==null) {

    $q = $_GET['img'];
    echo $noteController->generateImage("Sarah Johnson");

}
/*
if($_GET['note']!==null) {
    
    $q = $_GET['note'];
    echo $noteController->generateResponse($q);
}
*/

?>