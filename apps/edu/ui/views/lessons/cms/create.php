<?php
// Use __DIR__ for reliable paths regardless of DOCUMENT_ROOT (same pattern as blog cms/create.php)
include __DIR__ . '/../../../../controllers/LessonController.php';

$lesson = new LessonController();

// Auth gate — only logged-in users may create lessons
$lesson->check_auth();

include __DIR__ . '/../../../layouts/nav.php';
?>

<section class="bg-light py-1">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-8 mx-auto">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title mb-4 fs-3">Create New Lesson</h2>

                        <!-- Form posts to the lesson API endpoint -->
                        <form action="<?php echo url('/lesson_api'); ?>" method="post">

                            <div class="mb-3">
                                <label for="title" class="form-label">Lesson Title</label>
                                <input type="text" class="form-control" name="title"
                                       placeholder="e.g. Introduction to PHP" required>
                            </div>

                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <input type="text" class="form-control" name="category"
                                       placeholder="e.g. PHP, JavaScript, Databases">
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">Lesson Content</label>
                                <textarea class="form-control" name="content"
                                          rows="12" cols="5" required
                                          placeholder="Write the lesson content here..."></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="image_url" class="form-label">Visual / Diagram URL <small class="text-muted">(image, gif, or diagram — shown in right panel)</small></label>
                                <input type="url" class="form-control" name="image_url"
                                       placeholder="https://example.com/diagram.png">
                            </div>

                            <div class="mb-3">
                                <label for="code_block" class="form-label">Code Block <small class="text-muted">(shown as copyable card in right panel)</small></label>
                                <textarea class="form-control font-monospace" name="code_block"
                                          rows="8" cols="5"
                                          placeholder="Paste your code example here..."></textarea>
                                <div class="form-text">Plain code only — no markdown fences needed. Language label is auto-detected from category.</div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="<?php echo url('/lesson/cms'); ?>" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary" name="insert">Save Lesson</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../../../layouts/footer.php'; ?>
