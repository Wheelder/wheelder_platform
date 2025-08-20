<?php
class FileLister {
    private $basePath;
    private $fileList;

    public function __construct($basePath) {
        $this->basePath = $basePath;
        $this->fileList = array();
    }

    public function listFiles() {
        $this->listFilesInDirectory($this->basePath);
        return $this->fileList;
    }

    private function listFilesInDirectory($dir) {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            $filePath = $dir . '/' . $file;
            if (is_dir($filePath)) {
                $this->listFilesInDirectory($filePath);
            } else {
                $this->fileList[] = $filePath;
            }
        }
    }
}

// Example usage:
$projectFolder = '/path/to/your/project/folder';
$fileLister = new FileLister($projectFolder);
$files = $fileLister->listFiles();

foreach ($files as $file) {
    echo $file . "<br>";
}
