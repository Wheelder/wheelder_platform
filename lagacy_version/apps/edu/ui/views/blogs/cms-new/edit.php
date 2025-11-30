<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/apps/edu/controllers/BlogController.php';

$blog = new BlogController();
$blog->check_auth();

$blogPost = $blog->get_blog_edit($_GET['id']);

include $path . '/apps/edu/ui/layouts/nav.php';
?>

<section class="bg-light py-1">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title mb-4 fs-3">Edit Blog Post</h2>
                        <form action="/apps/edu/api/blogAPI.php" method="post" enctype="multipart/form-data" id="blogForm">
                            <input type="hidden" name="action" value="update-advanced">
                            <input type="hidden" id="id" name="id" value="<?php echo $blogPost['id']; ?>">
                            
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($blogPost['title']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="content" class="form-label">Content</label>
                                <div id="content" style="min-height: 400px;"></div>
                                <textarea name="content" id="content-textarea" style="display: none;"><?php echo htmlspecialchars($blogPost['content']); ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="blog_image" class="form-label">Featured Image</label>
                                <input type="file" class="form-control" name="blog_image" id="blog_image" accept="image/*">
                                <small class="form-text text-muted">Upload a new image to replace the existing one (optional)</small>
                            </div>
                            
                            <?php if (!empty($blogPost['file_name'])): ?>
                            <div class="mb-3">
                                <label class="form-label">Current Image</label>
                                <div>
                                    <img src="/apps/edu/api/blogAPI.php?action=get-image&id=<?php echo $blogPost['id']; ?>" alt="Current image" class="img-thumbnail" style="max-width: 300px; max-height: 300px;">
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <div id="imagePreview" style="display: none;">
                                    <img id="previewImg" src="" alt="Preview" class="img-thumbnail" style="max-width: 300px; max-height: 300px;">
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Update Blog Post</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quill Rich Text Editor -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    // Image preview
    document.getElementById('blog_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });

    // Initialize Quill Editor
    var quill = new Quill('#content', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['link', 'image', 'code-block'],
                ['clean']
            ]
        },
        placeholder: 'Start writing your blog post...'
    });

    // Set existing content
    var existingContent = document.getElementById('content-textarea').value;
    if (existingContent) {
        quill.root.innerHTML = existingContent;
    }

    // Handle image uploads in editor
    var toolbar = quill.getModule('toolbar');
    toolbar.addHandler('image', function() {
        var input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();
        
        input.onchange = function() {
            var file = input.files[0];
            if (file) {
                var formData = new FormData();
                formData.append('action', 'upload-image');
                formData.append('file', file);
                
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '/apps/edu/api/blogAPI.php', true);
                
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var json = JSON.parse(xhr.responseText);
                        if (json.location) {
                            var range = quill.getSelection(true);
                            quill.insertEmbed(range.index, 'image', json.location);
                        }
                    }
                };
                
                xhr.send(formData);
            }
        };
    });

    // Update textarea with HTML content before form submission
    document.getElementById('blogForm').addEventListener('submit', function() {
        var content = document.querySelector('#content .ql-editor').innerHTML;
        document.getElementById('content-textarea').value = content;
    });
</script>

<?php
include $path . '/apps/edu/ui/layouts/footer.php';
?>

