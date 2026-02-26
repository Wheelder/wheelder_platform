<?php
/**
 * Portfolio CMS — Skills list + create/edit/delete
 * 
 * WHY: Single page for all skill management — inline create form at top,
 *      table of existing skills with edit/delete below. Keeps workflow fast.
 */
include __DIR__ . '/../../../../controllers/PortfolioController.php';

$portfolio = new PortfolioController();
$portfolio->check_auth();

$skills = $portfolio->list_skills();

// WHY: If editing, pre-populate the form with the existing skill data
$editing = false;
$edit_skill = null;
if (isset($_GET['edit_id'])) {
    $edit_skill = $portfolio->get_skill((int) $_GET['edit_id']);
    if ($edit_skill) {
        $editing = true;
    }
}

require_once __DIR__ . '/../../../layouts/nav.php';
?>

<div class="container mt-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo url('/portfolio/cms'); ?>">Portfolio CMS</a></li>
            <li class="breadcrumb-item active">Skills</li>
        </ol>
    </nav>

    <h4>Technical Skills</h4>
    <p class="text-muted">Categories: Languages, Frameworks, Cloud/DevOps, AI/Data, Databases, etc.</p>

    <!-- WHY: Combined create/edit form — toggles based on whether edit_id is in the URL -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <h5 class="card-title"><?php echo $editing ? 'Edit Skill' : 'Add Skill'; ?></h5>
            <form method="POST" action="<?php echo url('/portfolio_api'); ?>">
                <?php if ($editing): ?>
                    <input type="hidden" name="id" value="<?php echo (int) $edit_skill['id']; ?>">
                <?php endif; ?>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Skill Name</label>
                        <input type="text" class="form-control" name="name" 
                               value="<?php echo htmlspecialchars($edit_skill['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" 
                               placeholder="e.g. Python" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <input type="text" class="form-control" name="category" 
                               value="<?php echo htmlspecialchars($edit_skill['category'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" 
                               placeholder="e.g. Languages">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Sort Order</label>
                        <input type="number" class="form-control" name="sort_order" 
                               value="<?php echo (int) ($edit_skill['sort_order'] ?? 0); ?>">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <?php if ($editing): ?>
                            <button type="submit" name="update_skill" class="btn btn-warning w-100">Update</button>
                        <?php else: ?>
                            <button type="submit" name="insert_skill" class="btn btn-primary w-100">Add</button>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
            <?php if ($editing): ?>
                <a href="<?php echo url('/portfolio/cms/skills'); ?>" class="btn btn-sm btn-secondary mt-2">Cancel Edit</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- WHY: Table for quick overview and management of all skills -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Category</th>
                <th>Order</th>
                <th>Manage</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($skills)): ?>
                <tr><td colspan="5" class="text-center text-muted">No skills added yet.</td></tr>
            <?php else: ?>
                <?php foreach ($skills as $i => $sk): ?>
                    <tr>
                        <td><?php echo $i + 1; ?></td>
                        <td><?php echo htmlspecialchars($sk['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($sk['category'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo (int) $sk['sort_order']; ?></td>
                        <td>
                            <a href="<?php echo url('/portfolio/cms/skills?edit_id=' . (int)$sk['id']); ?>" class="btn btn-sm btn-warning">Edit</a>
                            <!-- WHY: Delete uses POST form to prevent accidental deletion via GET -->
                            <form method="POST" action="<?php echo url('/portfolio_api'); ?>" class="d-inline" 
                                  onsubmit="return confirm('Delete this skill?');">
                                <input type="hidden" name="id" value="<?php echo (int) $sk['id']; ?>">
                                <button type="submit" name="delete_skill" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../../../layouts/footer.php'; ?>
