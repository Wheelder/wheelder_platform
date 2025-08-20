<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path.'/apps/edu/controllers/NoteController.php';
$noteController = new NoteController();

$noteController->check_auth();

$notes = $noteController->list_notes(); // Assuming this returns an array of notes


//include the nav
include $path.'/apps/edu/ui/layouts/nav.php';
?>

<body>
    <div class="container mt-5">
        <h4>Note List</h4>
        <a href="/notes/cms/create" class="btn btn-primary mb-3">Create New Note</a>
        <table class="table table-striped table-responsive">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Category</th>
                    <th scope="col">Content Preview</th>
                    <th scope="col">Manage</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notes as $index => $note): ?>
                    <tr>
                        <th scope="row"><?php echo $index + 1; ?></th>
                        <td><?php echo $note['title']; ?></td>
                        <td><?php echo $note['category']; ?></td>
                        <td><?php echo substr($note['content'], 0, 50).'...'; ?></td>
                        <td>
                            <a href="/notes?title=<?php echo $note['title']; ?>" class="btn btn-success">View</a>
                            <a href="/notes/cms/edit?id=<?php echo $note['id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="/notes/cms/delete?id=<?php echo $note['id']; ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        

    </div>
<?php include $path.'/apps/edu/ui/layouts/footer.php'; ?>