<?php
include 'Controller.php';

class FilesController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function handleFileUpload($directory, $uploaderId)
    {
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];

            // Extract file information
            $fileName = $file['name'];
            $fileTmp = $file['tmp_name'];

            // Generate a unique key ID for the file
            $key = uniqid();

            // Get the document root path
            $path = $_SERVER['DOCUMENT_ROOT'];

            // Build the file destination path
            $destination = $path . '/' . $directory . '/' . $fileName;

            // Move the uploaded file to the destination
            if (move_uploaded_file($fileTmp, $destination)) {
                // Save file information to the database
                $this->saveFileToDatabase($destination, $key, $uploaderId);

                // File upload success
                echo "File uploaded successfully.";
            } else {
                // File upload failed
                echo "File upload failed.";
            }
        } else {
            // No file was uploaded
            echo "No file selected.";
        }
    }



    public function saveFileToDatabase($filePath, $key, $uploaderId)
    {
        $query = "INSERT INTO files (file_path, key_id, user_id) VALUES (?, ?, ?)";
        $stmt = $this->run_query->prepare($query);
        $stmt->bind_param("ssi", $filePath, $key, $uploaderId);
        $stmt->execute();
        $stmt->close();
    }


}