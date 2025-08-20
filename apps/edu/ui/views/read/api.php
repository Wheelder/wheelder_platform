<?php
$host = 'localhost';
$db   = 'wheeleder';
$user = 'root';
$pass = 'root';

$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

$action = $_GET['action'] ?? $_POST['action'];

switch($action) {
    case 'getBooks':
        $stmt = $pdo->query('SELECT id, title FROM books');
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'getBook':
        $id = $_GET['id'];
        $stmt = $pdo->prepare('SELECT title, content, filepath FROM books WHERE id = ?');
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        break;

    case 'uploadBook':
        $title = $_POST['title'];
        $file = $_FILES['bookFile'];

        if ($file['type'] == "application/pdf") {
            $uploadDir = 'uploads/';
            $filePath = $uploadDir . basename($file['name']);
            
            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                $stmt = $pdo->prepare('INSERT INTO books (title, filepath) VALUES (?, ?)');
                $stmt->execute([$title, $filePath]);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to upload the file.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid file type.']);
        }
        break;
}
?>
