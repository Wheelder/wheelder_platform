<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'];
include $path.'/apps/work/controllers/FilesController.php';
$filesController = new FilesController();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the uploader ID from your authentication or session mechanism
    $uploaderId = 123; // Replace with your own method of retrieving the uploader ID
    
    $directory = '/storage/'; // Specify the directory where you want to store the files
    
    // Handle the file upload
    $filesController->handleFileUpload($directory, $uploaderId);
    
}
