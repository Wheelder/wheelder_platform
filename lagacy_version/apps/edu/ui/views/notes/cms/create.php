
    <?php
    $path = $_SERVER['DOCUMENT_ROOT'];
    include $path.'/apps/edu/controllers/NoteController.php';
    $noteController = new NoteController();
    $noteController->check_auth();
    include $path . '/apps/edu/ui/layouts/nav.php';
    ?>

    <section class="bg-light py-1">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-8 mx-auto">
                    <div class="card shadow">
                        <div class="card-body">
                            <h2 class="card-title mb-4 fs-3">Post Note</h2>
                            <form action="/edu_note" method="post" enctype="multipart/form-data">

                                <div class="mb-3">
                                    <label for="title" class="form-label">Title of Note</label>
                                    <input type="text" class="form-control" name="title" placeholder="Enter the title" required>
                                </div>
                                <div class="mb-3">
                                    <label for="title" class="form-label">Category</label>
                                    <input type="text" class="form-control" id="category" name="category" placeholder="Category" required>
                                </div>
                                <div class="mb-3">
                                    <label for="content" class "form-label">Image</label>
                                    <textarea class="form-control" name="image" rows="2" cols="5" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="content" class "form-label">Your Content</label>
                                    <textarea class="form-control" name="content" rows="15" cols="5" required></textarea>
                                </div>
                                <br>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary" name="insert">Create Note</button>
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

    <script>
        // JavaScript to handle the toggle functionality
        const fileInput = document.getElementById('fileInput');
        const urlInput = document.getElementById('urlInput');
        const toggleButton = document.getElementById('toggleButton');
        const toggleUrlButton = document.getElementById('toggleUrlButton');

        toggleButton.addEventListener('click', () => {
            fileInput.style.display = 'block';
            urlInput.style.display = 'none';
        });

        toggleUrlButton.addEventListener('click', () => {
            fileInput.style.display = 'none';
            urlInput.style.display = 'block';
        });
    </script>
</body>
</html>
