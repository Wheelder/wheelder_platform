<?php
/**
 * Portfolio CMS — Create a new project
 * 
 * WHY: Separate page because the project form has many fields (title,
 *      description, technologies, problem/solution/result, links, etc.).
 */
include __DIR__ . '/../../../../controllers/PortfolioController.php';

$portfolio = new PortfolioController();
$portfolio->check_auth();

require_once __DIR__ . '/../../../layouts/nav.php';
?>

<section class="bg-light py-1">
    <div class="container mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo url('/portfolio/cms'); ?>">Portfolio CMS</a></li>
                <li class="breadcrumb-item"><a href="<?php echo url('/portfolio/cms/projects'); ?>">Projects</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title mb-4 fs-4">Add New Project</h2>
                        <form method="POST" action="<?php echo url('/portfolio_api'); ?>">

                            <div class="mb-3">
                                <label class="form-label">Project Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" placeholder="e.g. Deep Research Reflection" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Short Description</label>
                                <textarea class="form-control" name="description" rows="3" placeholder="Brief overview of the project"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Technologies (comma-separated)</label>
                                <input type="text" class="form-control" name="technologies" placeholder="e.g. Python, FastAPI, OpenAI, React">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Your Role</label>
                                <input type="text" class="form-control" name="role_text" placeholder="e.g. Lead Backend Engineer">
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Problem</label>
                                    <textarea class="form-control" name="problem" rows="4" placeholder="What problem did this solve?"></textarea>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Solution</label>
                                    <textarea class="form-control" name="solution" rows="4" placeholder="Your approach and decisions"></textarea>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Result / Impact</label>
                                    <textarea class="form-control" name="result_text" rows="4" placeholder="Business impact, metrics, outcomes"></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Live Demo URL</label>
                                    <input type="url" class="form-control" name="demo_url" placeholder="https://...">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">GitHub URL</label>
                                    <input type="url" class="form-control" name="github_url" placeholder="https://github.com/...">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Image URL</label>
                                    <input type="url" class="form-control" name="image_url" placeholder="https://... (optional screenshot)">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Sort Order</label>
                                    <input type="number" class="form-control" name="sort_order" value="0">
                                </div>
                                <div class="col-md-3 mb-3 d-flex align-items-end">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_visible" id="is_visible" checked>
                                        <label class="form-check-label" for="is_visible">Visible on public page</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="<?php echo url('/portfolio/cms/projects'); ?>" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" name="insert_project" class="btn btn-primary">Create Project</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../../../layouts/footer.php'; ?>
