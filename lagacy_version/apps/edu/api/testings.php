<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/wheelder/apps/edu/controllers/NoteController.php';

$noteController = new NoteController();

//write unit tests for all functions of the NoteController class

// Insert Note
if (($_GET['insert'])=='test') {
    $title = "Testing Titles";
    $content = "Testing Content with more than 10 words.";
    //trim the title and content
    $title = trim($title);
    $content = trim($content);
    //trim the title and content
    
    $title = htmlspecialchars($title);
    $content = htmlspecialchars($content);



    $title = preg_replace('/[###]/', '', $title);
    $content = preg_replace('/[###]/', '', $content);

    $title = preg_replace('/[\*\*]/', '', $title);
    $content = preg_replace('/[\*\*]/', '', $content);

    // here need a function to add line break to the content after each line and full stop

    //add a line break after 10 words including spaces

    $content = preg_replace('/(\S+\s*){10}/', '$0<br>', $content);

    $res=$noteController->insert($title, $content);
    if($res){
        $noteController->alert_redirect('Note added successfully.', '/edu');
    }
}

// Update Note
if (isset($_POST['action']) && $_POST['action'] === 'update') {
    
    $id = $_POST['id'] ?? 0;
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $res=$noteController->update($id, $title, $content);
    if($res){
        $noteController->alert_redirect('Note updated successfully.', '/edu/home');
    }else{
        $noteController->alert_redirect('Note updated failed.', '/edu/home');
    }

}

// Delete Note
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'] ?? 0;
    $noteController->delete($id);
    $response['message'] = 'Note deleted successfully.';
}