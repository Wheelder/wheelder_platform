<?php
// Use __DIR__ for reliable paths regardless of DOCUMENT_ROOT (works on XAMPP and production)
include __DIR__ . '/../../../../controllers/BlogController.php';

$blog = new BlogController();

$blog->check_auth();

$notes = $blog->list_blogs(); // Assuming this returns an array of notes

$stopics= $blog->list_suggestions(); // Assuming this returns an array of suggested topics

//include the nav
require_once __DIR__ . '/../../../layouts/nav.php';
?>

<body>
    <div class="container mt-5">
        <h4>Blog List</h4>
        <a href="<?php echo url('/blog/cms/create'); ?>" class="btn btn-primary mb-3">Create New Blog</a>
        <table class="table table-striped table-responsive">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Content Preview</th>
                    <th scope="col">Manage</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notes as $index => $note): ?>
                    <tr>
                        <th scope="row"><?php echo $index + 1; ?></th>
                        <td><?php echo $note['title']; ?></td>
                        <td><?php echo substr($note['title'], 0, 50).'...'; ?></td>
                        <td>
                            <a href="<?php echo url('/blog?title=' . urlencode($note['title'])); ?>" class="btn btn-success">View</a>
                            <a href="<?php echo url('/blog/cms/edit?id=' . $note['id']); ?>" class="btn btn-warning">Edit</a>
                            <a href="<?php echo url('/blog/cms/delete?id=' . $note['id']); ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    
    </div>
<?php require_once __DIR__ . '/../../../layouts/footer.php'; ?>