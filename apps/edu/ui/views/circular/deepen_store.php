<?php
// WHY: Store deepen workflow data in session for the new tab to retrieve
// This is a secure way to pass data between tabs without exposing it in URLs

// WHY: set JSON header before any output to prevent HTML errors
header('Content-Type: application/json');

session_start();

// WHY: validate request method and CSRF token
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// WHY: validate CSRF token
$csrfToken = $_SESSION['csrf_token'] ?? '';
$postedToken = $_POST['csrf_token'] ?? '';

if (empty($csrfToken) || empty($postedToken) || $csrfToken !== $postedToken) {
    http_response_code(403);
    echo json_encode(['error' => 'CSRF token validation failed']);
    exit;
}

// WHY: validate that store_workflow action is set
if (empty($_POST['store_workflow'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

try {
    // WHY: extract and validate workflow data
    $answer = $_POST['answer'] ?? '';
    $recommendedToolsJson = $_POST['recommended_tools'] ?? '[]';
    $queryType = $_POST['query_type'] ?? 'general';
    $originalQuestion = $_POST['original_question'] ?? '';
    $depthLevel = intval($_POST['depth_level'] ?? 0);
    
    // WHY: parse recommended tools JSON safely
    $recommendedTools = [];
    if (!empty($recommendedToolsJson)) {
        $decoded = json_decode($recommendedToolsJson, true);
        if (is_array($decoded)) {
            $recommendedTools = $decoded;
        }
    }
    
    // WHY: validate required fields
    if (empty($answer) || empty($originalQuestion)) {
        throw new Exception('Missing required workflow data');
    }
    
    // WHY: store workflow data in session for deepen.php to retrieve
    $_SESSION['deepen_workflow'] = [
        'answer' => $answer,
        'recommended_tools' => $recommendedTools,
        'query_type' => $queryType,
        'original_question' => $originalQuestion,
        'depth_level' => $depthLevel,
        'created_at' => time()
    ];
    
    // WHY: return success response
    echo json_encode([
        'success' => true,
        'message' => 'Workflow data stored successfully'
    ]);
    
} catch (Exception $e) {
    // WHY: log error and return meaningful message
    error_log("Deepen store error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'Failed to store workflow data: ' . $e->getMessage()
    ]);
}

exit;
