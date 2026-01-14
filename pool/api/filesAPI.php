<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'];
include $path.'/apps/work/controllers/FilesController.php';
$filesController = new FilesController();

// Security: Restrict CORS to specific origins (update with your actual domain)
$allowedOrigins = ['http://localhost', 'http://localhost:8080', 'http://127.0.0.1'];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowedOrigins) || strpos($origin, 'localhost') !== false) {
    header("Access-Control-Allow-Origin: $origin");
} else {
    // For production, set your actual domain
    // header("Access-Control-Allow-Origin: https://yourdomain.com");
}
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Security: Get the uploader ID from session (authenticated user)
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit();
    }
    $uploaderId = $_SESSION['user_id'];
    
    $directory = '/storage/'; // Specify the directory where you want to store the files
    
    // Handle the file upload
    $filesController->handleFileUpload($directory, $uploaderId);
    
}
