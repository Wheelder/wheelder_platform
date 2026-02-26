<?php
/**
 * Portfolio CMS — Main dashboard listing all manageable sections
 * 
 * WHY: Single entry point for admins to navigate to sections, skills,
 *      projects, and contact messages. Follows blog/cms/list.php pattern.
 */
include __DIR__ . '/../../../../controllers/PortfolioController.php';

$portfolio = new PortfolioController();
$portfolio->check_auth();

$sections = $portfolio->list_sections();

// WHY: Reuse shared nav layout for consistent admin experience
require_once __DIR__ . '/../../../layouts/nav.php';
?>

<div class="container mt-5">
    <h4>Portfolio CMS</h4>
    <p class="text-muted">Manage your portfolio content. Changes appear instantly on the public portfolio page.</p>

    <!-- WHY: Quick links to each sub-section of the CMS -->
    <div class="row mb-4">
        <div class="col-md-3 mb-2">
            <a href="<?php echo url('/portfolio/cms/sections'); ?>" class="btn btn-outline-primary w-100">
                <i class="fas fa-layer-group"></i> Sections
            </a>
        </div>
        <div class="col-md-3 mb-2">
            <a href="<?php echo url('/portfolio/cms/skills'); ?>" class="btn btn-outline-success w-100">
                <i class="fas fa-code"></i> Skills
            </a>
        </div>
        <div class="col-md-3 mb-2">
            <a href="<?php echo url('/portfolio/cms/projects'); ?>" class="btn btn-outline-warning w-100">
                <i class="fas fa-project-diagram"></i> Projects
            </a>
        </div>
        <div class="col-md-3 mb-2">
            <a href="<?php echo url('/portfolio/cms/contacts'); ?>" class="btn btn-outline-info w-100">
                <i class="fas fa-envelope"></i> Messages
            </a>
        </div>
    </div>

    <hr>

    <!-- WHY: Quick overview of current sections so admin sees what's configured -->
    <h5>Content Sections</h5>
    <table class="table table-striped table-responsive">
        <thead>
            <tr>
                <th>#</th>
                <th>Key</th>
                <th>Title</th>
                <th>Order</th>
                <th>Manage</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($sections)): ?>
                <tr><td colspan="5" class="text-center text-muted">No sections yet. Add Hero, About, and Contact sections to get started.</td></tr>
            <?php else: ?>
                <?php foreach ($sections as $i => $sec): ?>
                    <tr>
                        <td><?php echo $i + 1; ?></td>
                        <td><code><?php echo htmlspecialchars($sec['section_key'], ENT_QUOTES, 'UTF-8'); ?></code></td>
                        <td><?php echo htmlspecialchars($sec['title'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo (int) $sec['sort_order']; ?></td>
                        <td>
                            <a href="<?php echo url('/portfolio/cms/sections/edit?id=' . (int)$sec['id']); ?>" class="btn btn-sm btn-warning">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="<?php echo url('/portfolio'); ?>" class="btn btn-dark mt-2" target="_blank">
        <i class="fas fa-eye"></i> View Public Portfolio
    </a>
</div>

<?php require_once __DIR__ . '/../../../layouts/footer.php'; ?>
