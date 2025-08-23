<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/apps/edu/controllers/BlogController.php';

$blog = new BlogController();


// Insert Blog
if (isset($_POST['insert'])) {

    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $category = $_POST['category'] ?? '';
    //trim the title and content
    $title = trim($title);
    $content = trim($content);
    $category = trim($category);
    //trim the title and content
    
    //$title = htmlspecialchars($title);
    //$content = htmlspecialchars($content);
    //$category = htmlspecialchars($category);

    //trim ** from the notes

    $title = preg_replace('/\*\*/', '', $title);
    $content = preg_replace('/\*\*/', '', $content);
    $category = preg_replace('/\*\*/', '', $category);

    $title = preg_replace('/[###]/', '', $title);
    $content = preg_replace('/[###]/', '', $content);

    //avoid to replace the  ' with &#039; and &quot; with " and &lt; with < and &gt; with > and &amp; with &

    $title = preg_replace('/&039;/', "'", $title);
    $content = preg_replace('/&039;/', "'", $content);
    $category = preg_replace('/&039;/', "'", $category);
    $title = preg_replace("/&quot;/", '"', $title);
    $content = preg_replace("/&quot;/", '"', $content);
    $category = preg_replace("/&quot;/", '"', $category);

    $res=$blog->insert($title, $content);

    if($res){
        $blog->alert_redirect('Note added successfully.', '/blog/cms');
    }

}

if (isset($_POST['insert-main'])) {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
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

    $res=$blog->insert($title, $content);
    if($res){
        $blog->alert_redirect('Blog added successfully.', '/edu/home');
    }
}

// Update Note
if (isset($_POST['action']) && $_POST['action'] === 'update') {
    
    $id = $_POST['id'] ?? 0;
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $res=$blog->update($id, $title, $content);
    if($res){
        $blog->alert_redirect('Blog updated successfully.', '/blog/cms');
    }else{
        $blog->alert_redirect('Blog updated failed.', '/blog/cms');
    }

}


// Delete Note
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'] ?? 0;
    $blog->delete($id);
    $response['message'] = 'Note deleted successfully.';
}


?>
