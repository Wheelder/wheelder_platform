<?php
// Use __DIR__ for reliable paths regardless of DOCUMENT_ROOT (same pattern as blog cms/edit.php)
include __DIR__ . '/../../../../controllers/LessonController.php';

$lesson = new LessonController();

// Auth gate — only logged-in users may edit lessons
$lesson->check_auth();

// Validate the ID before hitting the database
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    // Meaningful error — ID missing or non-numeric
    echo '<p class="text-danger container mt-4">Invalid lesson ID. <a href="' . url('/lesson/cms') . '">Go back</a></p>';
    exit();
}

$row = $lesson->get_lesson_edit($id);

if (!$row) {
    // Lesson not found — avoid showing a blank form
    echo '<p class="text-danger container mt-4">Lesson not found. <a href="' . url('/lesson/cms') . '">Go back</a></p>';
    exit();
}

include __DIR__ . '/../../../layouts/nav.php';
?>

<section class="bg-light py-1">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title mb-4 fs-3">Edit Lesson</h2>

                        <!-- Posts to lesson API; action=update triggers the update branch -->
                        <form action="<?php echo url('/lesson_api'); ?>" method="post">

                            <!-- Hidden ID so the API knows which row to update -->
                            <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">

                            <div class="mb-3">
                                <label for="title" class="form-label">Lesson Title</label>
                                <input type="text" class="form-control" name="title"
                                       value="<?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <input type="text" class="form-control" name="category"
                                       value="<?php echo htmlspecialchars($row['category'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">Lesson Content</label>
                                <textarea class="form-control" name="content"
                                          rows="12" cols="8"><?php echo htmlspecialchars($row['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="image_url" class="form-label">Visual / Diagram URL <small class="text-muted">(image, gif, or diagram — shown in right panel)</small></label>
                                <input type="url" class="form-control" name="image_url"
                                       value="<?php echo htmlspecialchars($row['image_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                       placeholder="https://example.com/diagram.png">
                            </div>

                            <div class="mb-3">
                                <label for="code_block" class="form-label">Code Block <small class="text-muted">(shown as copyable card in right panel)</small></label>
                                <textarea class="form-control font-monospace" name="code_block"
                                          rows="8" cols="8"
                                          placeholder="Paste your code example here..."><?php echo htmlspecialchars($row['code_block'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                <div class="form-text">Plain code only — no markdown fences needed.</div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="<?php echo url('/lesson/cms'); ?>" class="btn btn-secondary">Cancel</a>
                                <input type="submit" name="action" value="update" class="btn btn-primary">
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../../../layouts/footer.php'; ?>
