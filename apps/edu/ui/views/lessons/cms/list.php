<?php
// Use __DIR__ for reliable paths regardless of DOCUMENT_ROOT (same pattern as blog cms/list.php)
include __DIR__ . '/../../../../controllers/LessonController.php';

$lesson = new LessonController();

// Auth gate — only logged-in users may access the CMS
$lesson->check_auth();

// Fetch all lessons for the table
$lessons = $lesson->list_lessons();

// Shared nav layout (Bootstrap + header)
require_once __DIR__ . '/../../../layouts/nav.php';
?>

<div class="container mt-5">
    <h4>Lesson List</h4>
    <a href="<?php echo url('/lesson/cms/create'); ?>" class="btn btn-primary mb-3">Create New Lesson</a>

    <table class="table table-striped table-responsive">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Category</th>
                <th scope="col">Preview</th>
                <th scope="col">Manage</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($lessons)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted">No lessons yet. Create your first one!</td>
                </tr>
            <?php else: ?>
                <?php foreach ($lessons as $index => $row): ?>
                    <tr>
                        <th scope="row"><?php echo $index + 1; ?></th>
                        <td><?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['category'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars(substr($row['content'], 0, 60), ENT_QUOTES, 'UTF-8') . '...'; ?></td>
                        <td>
                            <!-- View opens the reader with the lesson pre-selected -->
                            <!-- WHY: route is /edu not /lesson — /lesson has no route and returns 404 -->
                            <a href="<?php echo url('/edu?t=' . urlencode(str_replace(' ', '_', strtolower($row['title'])))); ?>"
                               class="btn btn-success btn-sm">View</a>
                            <a href="<?php echo url('/lesson/cms/edit?id=' . (int)$row['id']); ?>"
                               class="btn btn-warning btn-sm">Edit</a>
                            <a href="<?php echo url('/lesson/cms/delete?id=' . (int)$row['id']); ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Delete this lesson?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../../../layouts/footer.php'; ?>
