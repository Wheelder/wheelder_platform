<?php
/**
 * Portfolio CMS — Edit a single section by ID
 * 
 * WHY: Dedicated edit page so admin can update title + content
 *      for an existing section without needing to remember the key.
 */
include __DIR__ . '/../../../../controllers/PortfolioController.php';

$portfolio = new PortfolioController();
$portfolio->check_auth();

// WHY: Validate ID before querying to prevent unnecessary DB calls
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    echo '<p class="text-danger container mt-4">Invalid section ID. <a href="' . url('/portfolio/cms/sections') . '">Go back</a></p>';
    exit;
}

$section = $portfolio->get_section_by_id($id);
if (!$section) {
    echo '<p class="text-danger container mt-4">Section not found. <a href="' . url('/portfolio/cms/sections') . '">Go back</a></p>';
    exit;
}

require_once __DIR__ . '/../../../layouts/nav.php';
?>

<section class="bg-light py-1">
    <div class="container mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo url('/portfolio/cms'); ?>">Portfolio CMS</a></li>
                <li class="breadcrumb-item"><a href="<?php echo url('/portfolio/cms/sections'); ?>">Sections</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title mb-4 fs-4">Edit Section: <code><?php echo htmlspecialchars($section['section_key'], ENT_QUOTES, 'UTF-8'); ?></code></h2>
                        <form method="POST" action="<?php echo url('/portfolio_api'); ?>">
                            <input type="hidden" name="id" value="<?php echo (int) $section['id']; ?>">

                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($section['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Content</label>
                                <textarea class="form-control" name="content" rows="10"><?php echo htmlspecialchars($section['content'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Sort Order</label>
                                <input type="number" class="form-control" name="sort_order" value="<?php echo (int) ($section['sort_order'] ?? 0); ?>">
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="<?php echo url('/portfolio/cms/sections'); ?>" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" name="update_section" class="btn btn-primary">Update Section</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../../../layouts/footer.php'; ?>
