<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/apps/edu/controllers/BlogController.php';

$blog = new BlogController();
$blog->check_auth();

$blogPost = $blog->get_blog_edit($_GET['id']);

include $path . '/apps/edu/ui/layouts/nav.php';
?>

<section class="bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="mb-3">
                            <a href="/blog/cms-new/list" class="btn btn-secondary">← Back to List</a>
                            <a href="/blog/cms-new/edit?id=<?php echo $blogPost['id']; ?>" class="btn btn-warning float-end">Edit</a>
                        </div>
                        
                        <h1 class="card-title mb-4"><?php echo htmlspecialchars($blogPost['title']); ?></h1>
                        
                        <?php if (!empty($blogPost['file_name'])): ?>
                        <div class="mb-4">
                            <img src="/apps/edu/api/blogAPI.php?action=get-image&id=<?php echo $blogPost['id']; ?>" 
                                 alt="<?php echo htmlspecialchars($blogPost['title']); ?>" 
                                 class="img-fluid rounded">
                        </div>
                        <?php endif; ?>
                        
                        <div class="content">
                            <?php echo $blogPost['content']; ?>
                        </div>
                        
                        <div class="mt-4 text-muted">
                            <small>Created: <?php echo date('F j, Y, g:i a', strtotime($blogPost['date_created'])); ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.content {
    line-height: 1.8;
    font-size: 1.1rem;
}

.content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 20px 0;
}

.content pre {
    background-color: #f4f4f4;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 15px;
    overflow-x: auto;
}

.content code {
    background-color: #f4f4f4;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: 'Courier New', monospace;
}

.content pre code {
    background-color: transparent;
    padding: 0;
}
</style>

<?php
include $path . '/apps/edu/ui/layouts/footer.php';
?>

