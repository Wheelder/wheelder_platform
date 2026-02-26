<?php
/**
 * Portfolio CMS — Sections list + create form
 * 
 * WHY: Lets the admin manage content sections (hero, about, contact).
 *      Uses upsert so existing section_keys get updated, not duplicated.
 */
include __DIR__ . '/../../../../controllers/PortfolioController.php';

$portfolio = new PortfolioController();
$portfolio->check_auth();

$sections = $portfolio->list_sections();

require_once __DIR__ . '/../../../layouts/nav.php';
?>

<div class="container mt-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo url('/portfolio/cms'); ?>">Portfolio CMS</a></li>
            <li class="breadcrumb-item active">Sections</li>
        </ol>
    </nav>

    <h4>Content Sections</h4>
    <p class="text-muted">Use section keys: <code>hero</code>, <code>about</code>, <code>contact</code>. You can add custom keys too.</p>

    <!-- WHY: Inline create form keeps the workflow fast — no extra page needed -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <h5 class="card-title">Add / Update Section</h5>
            <form method="POST" action="<?php echo url('/portfolio_api'); ?>">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Section Key</label>
                        <input type="text" class="form-control" name="section_key" placeholder="e.g. hero" required>
                        <small class="text-muted">hero, about, contact</small>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" placeholder="Section title" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Sort Order</label>
                        <input type="number" class="form-control" name="sort_order" value="0">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Content</label>
                        <textarea class="form-control" name="content" rows="5" placeholder="Section content (supports plain text)"></textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" name="upsert_section" class="btn btn-primary">Save Section</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- WHY: Table overview of all sections for quick reference -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Key</th>
                <th>Title</th>
                <th>Content Preview</th>
                <th>Order</th>
                <th>Manage</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($sections)): ?>
                <tr><td colspan="6" class="text-center text-muted">No sections yet.</td></tr>
            <?php else: ?>
                <?php foreach ($sections as $i => $sec): ?>
                    <tr>
                        <td><?php echo $i + 1; ?></td>
                        <td><code><?php echo htmlspecialchars($sec['section_key'], ENT_QUOTES, 'UTF-8'); ?></code></td>
                        <td><?php echo htmlspecialchars($sec['title'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars(substr($sec['content'] ?? '', 0, 80), ENT_QUOTES, 'UTF-8'); ?>...</td>
                        <td><?php echo (int) $sec['sort_order']; ?></td>
                        <td>
                            <a href="<?php echo url('/portfolio/cms/sections/edit?id=' . (int)$sec['id']); ?>" class="btn btn-sm btn-warning">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../../../layouts/footer.php'; ?>
