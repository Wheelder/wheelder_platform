<?php
//session_start();
$path = $_SERVER['DOCUMENT_ROOT'];
$host = $_SERVER['HTTP_HOST'];

if ($host === "localhost") {
    $dir = '/wheelder';
    
    require_once $path . $dir . '/apps/edu/ui/views/learn/LearnController.php';
} else {
    require_once $path . '/apps/edu/ui/views/learn/LearnController.php';
}


$learn = new LearnController();
// Temporarily comment out authentication for testing
//$learn->checkAuth();

// Generate CSRF token
$csrfToken = $learn->generateCSRFToken();

// Get recent questions for display
$recentQuestions = $learn->getRecentQuestions(3);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Learning Assistant - Wheelder</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row bg-primary text-white py-3 mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center">
                    <i class="fas fa-brain fa-2x me-3"></i>
                    <div>
                        <h1 class="h3 mb-0">AI Learning Assistant</h1>
                        <p class="mb-0 text-light">Ask questions and get intelligent answers with AI-generated images</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content Area -->
            <div class="col-lg-8">
                <!-- Question Input Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-question-circle text-primary me-2"></i>
                            Ask Your Question
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="questionForm" method="post" action="process_question.php">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                            
                            <div class="mb-3">
                                <label for="questionInput" class="form-label">What would you like to learn about?</label>
                                <textarea 
                                    class="form-control" 
                                    id="questionInput" 
                                    name="question" 
                                    rows="4" 
                                    placeholder="Enter your question here... (e.g., Explain quantum physics, How does photosynthesis work?)"
                                    maxlength="1000"
                                    required
                                ><?php echo htmlspecialchars($_POST['question'] ?? ''); ?></textarea>
                                <div class="form-text">
                                    <span id="charCount">0</span>/1000 characters
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="submit" class="btn btn-primary" name="action" value="ask">
                                    <i class="fas fa-paper-plane me-2"></i>Ask Question
                                </button>
                                <button type="submit" class="btn btn-outline-secondary" name="action" value="deepen">
                                    <i class="fas fa-layer-group me-2"></i>Deepen Previous Answer
                                </button>
                                <button type="reset" class="btn btn-outline-danger">
                                    <i class="fas fa-eraser me-2"></i>Clear
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Results Display Area -->
                <div id="resultsArea" class="mb-4" style="display: none;">
                    <div class="row">
                        <!-- AI Response -->
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-robot me-2"></i>AI Response
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div id="aiResponse" class="ai-response-content"></div>
                                </div>
                                <div class="card-footer">
                                    <div class="btn-group btn-group-sm w-100">
                                        <button class="btn btn-outline-primary" onclick="copyToClipboard('aiResponse')">
                                            <i class="fas fa-copy me-1"></i>Copy
                                        </button>
                                        <button class="btn btn-outline-secondary" onclick="increaseFontSize()">
                                            <i class="fas fa-plus me-1"></i>Font+
                                        </button>
                                        <button class="btn btn-outline-secondary" onclick="decreaseFontSize()">
                                            <i class="fas fa-minus me-1"></i>Font-
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Generated Image -->
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-image me-2"></i>Generated Image
                                    </h6>
                                </div>
                                <div class="card-body d-flex align-items-center justify-content-center">
                                    <div id="generatedImage" class="text-center">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                        <p class="text-muted mt-2">Image will appear here</p>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="btn-group btn-group-sm w-100">
                                        <button class="btn btn-outline-primary" onclick="downloadImage()">
                                            <i class="fas fa-download me-1"></i>Download
                                        </button>
                                        <button class="btn btn-outline-secondary" onclick="refreshImage()">
                                            <i class="fas fa-sync-alt me-1"></i>Regenerate
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Loading Indicator -->
                <div id="loadingIndicator" class="text-center py-5" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">AI is thinking and generating your response...</p>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Recent Questions -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-history text-secondary me-2"></i>Recent Questions
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php if (empty($recentQuestions)): ?>
                            <p class="text-muted text-center">No recent questions yet.</p>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($recentQuestions as $question): ?>
                                    <div class="list-group-item border-0 px-0">
                                        <h6 class="mb-1 text-truncate"><?php echo htmlspecialchars($question['question']); ?></h6>
                                        <small class="text-muted">
                                            <?php echo date('M j, Y', strtotime($question['created_at'])); ?>
                                        </small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tips Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-lightbulb text-warning me-2"></i>Tips
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Be specific in your questions
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Use "deepen" to get more detailed answers
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Images are automatically generated for each question
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-bolt text-danger me-2"></i>Quick Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="loadExampleQuestion('science')">
                                <i class="fas fa-atom me-2"></i>Science Question
                            </button>
                            <button class="btn btn-outline-primary btn-sm" onclick="loadExampleQuestion('history')">
                                <i class="fas fa-landmark me-2"></i>History Question
                            </button>
                            <button class="btn btn-outline-primary btn-sm" onclick="loadExampleQuestion('technology')">
                                <i class="fas fa-microchip me-2"></i>Tech Question
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="app.js"></script>
    
    <script>
        // Character counter
        document.getElementById('questionInput').addEventListener('input', function() {
            const charCount = this.value.length;
            document.getElementById('charCount').textContent = charCount;
            
            if (charCount > 800) {
                document.getElementById('charCount').classList.add('text-danger');
            } else {
                document.getElementById('charCount').classList.remove('text-danger');
            }
        });

        // Form submission handling
        document.getElementById('questionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const question = document.getElementById('questionInput').value.trim();
            if (!question) {
                alert('Please enter a question.');
                return;
            }
            
            submitQuestion(question);
        });

        // Example question loader
        function loadExampleQuestion(type) {
            const examples = {
                'science': 'How does photosynthesis work and why is it important for life on Earth?',
                'history': 'What were the main causes and consequences of the Industrial Revolution?',
                'technology': 'How do artificial neural networks work and what are their applications?'
            };
            
            document.getElementById('questionInput').value = examples[type];
            document.getElementById('questionInput').focus();
        }

        // Question submission
        function submitQuestion(question) {
            const formData = new FormData();
            formData.append('question', question);
            formData.append('csrf_token', '<?php echo $csrfToken; ?>');
            
            // Show loading
            document.getElementById('loadingIndicator').style.display = 'block';
            document.getElementById('resultsArea').style.display = 'none';
            
            fetch('process_question.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('loadingIndicator').style.display = 'none';
                
                if (data.success) {
                    displayResults(data);
                } else {
                    alert('Error: ' + (data.error || 'Unknown error occurred'));
                }
            })
            .catch(error => {
                document.getElementById('loadingIndicator').style.display = 'none';
                alert('Error: ' + error.message);
            });
        }

        // Display results
        function displayResults(data) {
            document.getElementById('aiResponse').innerHTML = data.formattedResponse;
            
            if (data.imageUrl) {
                document.getElementById('generatedImage').innerHTML = 
                    `<img src="${data.imageUrl}" class="img-fluid" alt="AI Generated Image" style="max-height: 400px;">`;
            }
            
            document.getElementById('resultsArea').style.display = 'block';
        }
    </script>
</body>
</html>

