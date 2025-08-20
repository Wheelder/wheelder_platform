<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/apps/edu/controllers/BlogController.php';
$blog = new BlogController();

$blog->check_auth();

$notes = $blog->list_blogs(); // Assuming this returns an array of notes

$stopics= $blog->list_suggestions(); // Assuming this returns an array of suggested topics

//include the nav
include $path.'/apps/edu/ui/layouts/nav.php';
?>

<body>
    <div class="container mt-5">
        <h4>Blog List</h4>
        <a href="/lab/cms/create" class="btn btn-primary mb-3">Create New Blog</a>
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
                        <td><?php echo substr($note['content'], 0, 50).'...'; ?></td>
                        <td>
                            <a href="/blog?title=<?php echo $note['title']; ?>" class="btn btn-success">View</a>
                            <a href="/lab/cms/edit?id=<?php echo $note['id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="/lab/cms/delete?id=<?php echo $note['id']; ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    
    </div>
<?php include $path.'/apps/edu/ui/layouts/footer.php'; ?>