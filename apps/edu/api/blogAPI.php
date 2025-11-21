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

// Advanced CMS - Create Blog with Image
if (isset($_POST['action']) && $_POST['action'] === 'create-advanced') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $title = trim($title);
    $content = trim($content);
    
    $file_name = null;
    $file_mime = null;
    $file_data = null;
    
    if (isset($_FILES['blog_image']) && $_FILES['blog_image']['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['blog_image']['name'];
        $file_mime = $_FILES['blog_image']['type'];
        $file_tmp = $_FILES['blog_image']['tmp_name'];
        $file_data = file_get_contents($file_tmp);
    }
    
    $res = $blog->insert_advanced($title, $content, $file_name, $file_mime, $file_data);
    
    if ($res) {
        $blog->alert_redirect('Blog post created successfully.', '/blog/cms-new/list');
    } else {
        $blog->alert_redirect('Failed to create blog post.', '/blog/cms-new/create');
    }
}

// Advanced CMS - Update Blog with Image
if (isset($_POST['action']) && $_POST['action'] === 'update-advanced') {
    $id = $_POST['id'] ?? 0;
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $title = trim($title);
    $content = trim($content);
    
    $file_name = null;
    $file_mime = null;
    $file_data = null;
    
    if (isset($_FILES['blog_image']) && $_FILES['blog_image']['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['blog_image']['name'];
        $file_mime = $_FILES['blog_image']['type'];
        $file_tmp = $_FILES['blog_image']['tmp_name'];
        $file_data = file_get_contents($file_tmp);
    }
    
    $res = $blog->update_advanced($id, $title, $content, $file_name, $file_mime, $file_data);
    
    if ($res) {
        $blog->alert_redirect('Blog post updated successfully.', '/blog/cms-new/list');
    } else {
        $blog->alert_redirect('Failed to update blog post.', '/blog/cms-new/edit?id=' . $id);
    }
}

// Upload Image for TinyMCE Editor
if (isset($_POST['action']) && $_POST['action'] === 'upload-image') {
    header('Content-Type: application/json');
    
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['file']['name'];
        $file_mime = $_FILES['file']['type'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_data = file_get_contents($file_tmp);
        
        // Insert image into database temporarily or save to a temp location
        // For now, we'll save it to a temp directory and return the URL
        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/storage/blog_images/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $unique_name = uniqid() . '_' . $file_name;
        $file_path = $upload_dir . $unique_name;
        
        if (move_uploaded_file($file_tmp, $file_path)) {
            $image_url = '/storage/blog_images/' . $unique_name;
            echo json_encode(['location' => $image_url]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to upload image']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'No file uploaded']);
    }
    exit;
}

// Get Image from Database
if (isset($_GET['action']) && $_GET['action'] === 'get-image') {
    $id = $_GET['id'] ?? 0;
    $image_data = $blog->get_image_by_id($id);
    
    if ($image_data && !empty($image_data['file_data'])) {
        header('Content-Type: ' . $image_data['file_mime']);
        echo $image_data['file_data'];
    } else {
        http_response_code(404);
    }
    exit;
}


?>
