<?php
/**
 * Portfolio CMS — Projects list + create link
 * 
 * WHY: Shows all projects (visible + draft) with edit/delete actions.
 *      Create and edit use separate pages because project forms are large.
 */
include __DIR__ . '/../../../../controllers/PortfolioController.php';

$portfolio = new PortfolioController();
$portfolio->check_auth();

$projects = $portfolio->list_projects();

require_once __DIR__ . '/../../../layouts/nav.php';
?>

<div class="container mt-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo url('/portfolio/cms'); ?>">Portfolio CMS</a></li>
            <li class="breadcrumb-item active">Projects</li>
        </ol>
    </nav>

    <h4>Projects</h4>
    <a href="<?php echo url('/portfolio/cms/projects/create'); ?>" class="btn btn-primary mb-3">Add New Project</a>

    <table class="table table-striped table-responsive">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Technologies</th>
                <th>Visible</th>
                <th>Order</th>
                <th>Manage</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($projects)): ?>
                <tr><td colspan="6" class="text-center text-muted">No projects yet. Add your first one!</td></tr>
            <?php else: ?>
                <?php foreach ($projects as $i => $proj): ?>
                    <tr>
                        <td><?php echo $i + 1; ?></td>
                        <td><?php echo htmlspecialchars($proj['title'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars(substr($proj['technologies'] ?? '', 0, 50), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <?php if ((int)$proj['is_visible'] === 1): ?>
                                <span class="badge bg-success">Yes</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Draft</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo (int) $proj['sort_order']; ?></td>
                        <td>
                            <a href="<?php echo url('/portfolio/cms/projects/edit?id=' . (int)$proj['id']); ?>" class="btn btn-sm btn-warning">Edit</a>
                            <form method="POST" action="<?php echo url('/portfolio_api'); ?>" class="d-inline"
                                  onsubmit="return confirm('Delete this project?');">
                                <input type="hidden" name="id" value="<?php echo (int) $proj['id']; ?>">
                                <button type="submit" name="delete_project" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../../../layouts/footer.php'; ?>
