<?php
include 'config.php';
include 'FTPUploader.php';

$uploader = new FTPUploader();

if (isset($_POST["submit"])) {
    $localFile = $_FILES["fileToUpload"]["tmp_name"];
    $remoteFileName = $_FILES["fileToUpload"]["name"];

    if ($uploader->uploadFile($localFile, $remoteFileName)) {
        echo "File uploaded successfully.";
    } else {
        echo "Error uploading the file.";
    }
}

$uploader->closeConnection();
?>

<!DOCTYPE html>
<html>
<head>
    <title>File and Folder Upload</title>
</head>
<body>
    <form action="index.php" method="post" enctype="multipart/form-data">
        Select file or ZIP archive to upload (ZIP for folders):
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload" name="submit">
    </form>
</body>
</html>
