<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/apps/edu/controllers/BlogController.php';

$blog = new BlogController();
$blog->check_auth();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $blog->delete($id);
    
    if ($result) {
        header('Location: /blog/cms-new/list?message=Blog post deleted successfully');
    } else {
        header('Location: /blog/cms-new/list?error=Failed to delete blog post');
    }
    exit;
} else {
    header('Location: /blog/cms-new/list');
    exit;
}
?>

