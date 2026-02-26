<?php
/**
 * Portfolio CMS — Edit an existing project
 * 
 * WHY: Pre-populates all project fields so admin can tweak any detail
 *      without re-entering everything. Mirrors projects_create.php layout.
 */
include __DIR__ . '/../../../../controllers/PortfolioController.php';

$portfolio = new PortfolioController();
$portfolio->check_auth();

// WHY: Validate ID before hitting DB to prevent unnecessary queries
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    echo '<p class="text-danger container mt-4">Invalid project ID. <a href="' . url('/portfolio/cms/projects') . '">Go back</a></p>';
    exit;
}

$proj = $portfolio->get_project($id);
if (!$proj) {
    echo '<p class="text-danger container mt-4">Project not found. <a href="' . url('/portfolio/cms/projects') . '">Go back</a></p>';
    exit;
}

require_once __DIR__ . '/../../../layouts/nav.php';
?>

<section class="bg-light py-1">
    <div class="container mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo url('/portfolio/cms'); ?>">Portfolio CMS</a></li>
                <li class="breadcrumb-item"><a href="<?php echo url('/portfolio/cms/projects'); ?>">Projects</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title mb-4 fs-4">Edit Project</h2>
                        <form method="POST" action="<?php echo url('/portfolio_api'); ?>">
                            <input type="hidden" name="id" value="<?php echo (int) $proj['id']; ?>">

                            <div class="mb-3">
                                <label class="form-label">Project Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" 
                                       value="<?php echo htmlspecialchars($proj['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Short Description</label>
                                <textarea class="form-control" name="description" rows="3"><?php echo htmlspecialchars($proj['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Technologies (comma-separated)</label>
                                <input type="text" class="form-control" name="technologies" 
                                       value="<?php echo htmlspecialchars($proj['technologies'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Your Role</label>
                                <input type="text" class="form-control" name="role_text" 
                                       value="<?php echo htmlspecialchars($proj['role_text'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Problem</label>
                                    <textarea class="form-control" name="problem" rows="4"><?php echo htmlspecialchars($proj['problem'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Solution</label>
                                    <textarea class="form-control" name="solution" rows="4"><?php echo htmlspecialchars($proj['solution'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Result / Impact</label>
                                    <textarea class="form-control" name="result_text" rows="4"><?php echo htmlspecialchars($proj['result_text'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Live Demo URL</label>
                                    <input type="url" class="form-control" name="demo_url" 
                                           value="<?php echo htmlspecialchars($proj['demo_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">GitHub URL</label>
                                    <input type="url" class="form-control" name="github_url" 
                                           value="<?php echo htmlspecialchars($proj['github_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Image URL</label>
                                    <input type="url" class="form-control" name="image_url" 
                                           value="<?php echo htmlspecialchars($proj['image_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Sort Order</label>
                                    <input type="number" class="form-control" name="sort_order" 
                                           value="<?php echo (int) ($proj['sort_order'] ?? 0); ?>">
                                </div>
                                <div class="col-md-3 mb-3 d-flex align-items-end">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_visible" id="is_visible"
                                               <?php echo ((int)($proj['is_visible'] ?? 1) === 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="is_visible">Visible on public page</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="<?php echo url('/portfolio/cms/projects'); ?>" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" name="update_project" class="btn btn-primary">Update Project</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../../../layouts/footer.php'; ?>
