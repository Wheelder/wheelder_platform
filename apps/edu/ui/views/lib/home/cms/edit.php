<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/apps/edu/controllers/BlogController.php';
$blog = new BlogController();
$blog->check_auth();

$note = $blog-> get_blog_edit($_GET['id']);

include $path . '/apps/edu/ui/layouts/nav.php';
?>

<section class="bg-light py-1">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title mb-4 fs-3">Edit Note</h2>
                        <form action="/edu_blog" method="post">
                            <input type="hidden" id="id" name="id" value="<?php echo $note['id']; ?>">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?php echo $note['title']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="content" class="form-label">Content</label>
                                <textarea class="form-control" id="content" name="content" rows="18" cols="8"><?php echo $note['content']; ?></textarea>
                            </div>
                            <br>
                            <div class="d-flex justify-content-end">
                                <input type="submit" name="action" value="update" class="btn btn-primary">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php
include $path . '/apps/edu/ui/layouts/footer.php';
?>


