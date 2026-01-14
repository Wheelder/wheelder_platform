<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/apps/edu/controllers/BlogController.php';

$blog = new BlogController();
$blog->check_auth();

$blogs = $blog->list_blogs();

include $_SERVER['DOCUMENT_ROOT'] . '/apps/edu/ui/layouts/nav.php';
?>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Blog Posts</h4>
            <a href="/blog/cms-new/create" class="btn btn-primary">Create New Blog Post</a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Content Preview</th>
                        <th scope="col">Image</th>
                        <th scope="col">Created</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $index = 1;
                    while ($blogPost = $blogs->fetch_assoc()): 
                    ?>
                        <tr>
                            <th scope="row"><?php echo $index++; ?></th>
                            <td><?php echo htmlspecialchars($blogPost['title']); ?></td>
                            <td><?php echo htmlspecialchars(substr(strip_tags($blogPost['content']), 0, 100)) . '...'; ?></td>
                            <td>
                                <?php if (!empty($blogPost['file_name'])): ?>
                                    <img src="/apps/edu/api/blogAPI.php?action=get-image&id=<?php echo $blogPost['id']; ?>" 
                                         alt="Blog image" 
                                         style="width: 50px; height: 50px; object-fit: cover;" 
                                         class="img-thumbnail">
                                <?php else: ?>
                                    <span class="text-muted">No image</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('Y-m-d H:i', strtotime($blogPost['date_created'])); ?></td>
                            <td>
                                <a href="/blog/cms-new/view?id=<?php echo $blogPost['id']; ?>" class="btn btn-sm btn-info">View</a>
                                <a href="/blog/cms-new/edit?id=<?php echo $blogPost['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="/blog/cms-new/delete?id=<?php echo $blogPost['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this blog post?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php require_once $_SERVER['DOCUMENT_ROOT'] .'/apps/edu/ui/layouts/footer.php'; ?>


