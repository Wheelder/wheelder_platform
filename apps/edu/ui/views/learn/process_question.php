<?php
session_start();
header('Content-Type: application/json');

$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . '/apps/edu/ui/views/learn/LearnController.php';

$learn = new LearnController();

// Check authentication - temporarily commented out for testing
// $learn->checkAuth();

// Verify CSRF token
if (!isset($_POST['csrf_token']) || !$learn->verifyCSRFToken($_POST['csrf_token'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Invalid security token']);
    exit;
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

try {
    $question = $_POST['question'] ?? '';
    $action = $_POST['action'] ?? 'ask';
    
    // Validate input
    $validation = $learn->validateInput($question);
    if (!$validation['valid']) {
        echo json_encode(['success' => false, 'error' => $validation['error']]);
        exit;
    }
    
    $cleanQuestion = $validation['data'];
    
    // Process based on action
    if ($action === 'deepen') {
        // Get the previous question from session or use current
        $previousQuestion = $_SESSION['last_question'] ?? $cleanQuestion;
        $enhancedQuestion = "Based on the previous question: '{$previousQuestion}', please provide a deeper, more detailed explanation of: {$cleanQuestion}";
        
        $aiResponse = $learn->generateResponse($enhancedQuestion);
    } else {
        // Regular question
        $aiResponse = $learn->generateResponse($cleanQuestion);
    }
    
    // Check for API errors
    if (isset($aiResponse['error'])) {
        echo json_encode(['success' => false, 'error' => $aiResponse['error']]);
        exit;
    }
    
    // Generate image based on the question
    $imageResult = $learn->generateImage($cleanQuestion);
    
    // Store in database
    $stored = $learn->storeQuestion($cleanQuestion, $aiResponse['content'], $imageResult['url'] ?? null);
    
    // Store last question in session for deepening
    $_SESSION['last_question'] = $cleanQuestion;
    
    // Prepare response
    $response = [
        'success' => true,
        'question' => $cleanQuestion,
        'formattedResponse' => $learn->formatResponse($aiResponse),
        'rawResponse' => $aiResponse['content'],
        'imageUrl' => $imageResult['url'] ?? null,
        'stored' => $stored
    ];
    
    echo json_encode($response);
    
} catch (Exception $e) {
    error_log("Learn module error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'error' => 'An internal error occurred. Please try again later.'
    ]);
}
?>
