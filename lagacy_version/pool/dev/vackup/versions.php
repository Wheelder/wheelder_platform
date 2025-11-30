<!DOCTYPE html>
<html>
<head>
    <title>Vackup</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php
    // Check if the settings file exists
    $settingsFile = 'settings.txt';
    $projectPath = '';
    $projectName = '';
    $backupDirectory = '';

    if (file_exists($settingsFile)) {
        // Read the settings from the file
        $settings = file_get_contents($settingsFile);
        $settingsArray = explode(',', $settings);

        $projectPath = $settingsArray[0];
        $projectName = $settingsArray[1];
        $backupDirectory = $settingsArray[2];
    }

    if (empty($projectPath) || empty($projectName) || empty($backupDirectory)) {
        // Prompt the user to enter the project details
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Save the project details in the settings file
            $projectPath = $_POST['projectPath'];
            $backupDirectory = $_POST['backupDirectory'];

            // Get the project name from the project path
            $projectName = basename($projectPath);

            // Save the settings in the file
            $settings = $projectPath . ',' . $projectName . ',' . $backupDirectory;
            file_put_contents($settingsFile, $settings);

            echo "<div class='container'><p class='alert alert-success'>Settings saved successfully!</p></div>";
        } else {
            // Show the project details form
            ?>
            <nav> 
            <div class="container">
                <h2>Enter Project Details</h2>
                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group">
                        <label for="projectPath">Project Directory:</label>
                        <input type="text" class="form-control" id="projectPath" name="projectPath" required>
                    </div>

                    <div class="form-group">
                        <label for="backupDirectory">Backup Directory:</label>
                        <input type="text" class="form-control" id="backupDirectory" name="backupDirectory" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
            </div>
            <?php
            exit(); // Stop execution until the settings are saved
        }
    }

    // Proceed with the backup process
    ?>
    <nav>
    <h4 class=""><center>Vackup System(Versing & Backup)</center></h4>
        </nav>
    <div class="container">
        <h2>Product Update</h2>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="updateLabel">Product Update Label:</label>
                <input type="text" class="form-control" id="updateLabel" name="updateLabel" required>
            </div>

            <button type="submit" class="btn btn-primary">Create Vackup</button>
        </form>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['updateLabel'])) {
            $updateLabel = $_POST['updateLabel'];

            class ProjectBackup {
                private $projectPath;
                private $backupDirectory;
                private $projectName;

                public function __construct($projectPath, $backupDirectory, $projectName) {
                    $this->projectPath = $projectPath;
                    $this->backupDirectory = $backupDirectory;
                    $this->projectName = $projectName;
                }

                public function createBackup($updateLabel) {
                    // Generate a unique name for the backup file
                    $versionCounter = $this->getVersionCounter($this->backupDirectory);
                    $backupFilename = $this->projectName . '_' . date('Y-m-d-His') . '_Version_' . $versionCounter . '_' . $updateLabel . '.zip';

                    // Create a new zip archive
                    $zip = new ZipArchive();
                    $zipPath = $this->backupDirectory . '/' . $backupFilename;

                    if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                        // Add all files and directories in the project path to the zip archive
                        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->projectPath), RecursiveIteratorIterator::LEAVES_ONLY);

                        foreach ($files as $name => $file) {
                            if (!$file->isDir()) {
                                $filePath = $file->getPathname();
                                $relativePath = substr($filePath, strlen($this->projectPath) + 1);

                                $zip->addFile($filePath, $relativePath);
                            }
                        }

                        // Close the zip archive
                        $zip->close();

                        // Increment the version counter
                        $this->incrementVersionCounter($this->backupDirectory);
                    } else {
                        echo "Failed to create backup!";
                        return;
                    }

                    // Store backup information in a text file
                    $backupInfo = "Backup created: $backupFilename - Update Label: $updateLabel" . PHP_EOL;
                    $backupInfoFile = $this->backupDirectory . '/backup_info.txt';
                    file_put_contents($backupInfoFile, $backupInfo, FILE_APPEND);
                }

                private function getVersionCounter($backupDirectory) {
                    $counterFile = $backupDirectory . '/version_counter.txt';
                    $counter = 1;

                    if (file_exists($counterFile)) {
                        $counter = intval(file_get_contents($counterFile));
                    }

                    return $counter;
                }

                private function incrementVersionCounter($backupDirectory) {
                    $counterFile = $backupDirectory . '/version_counter.txt';
                    $counter = 1;

                    if (file_exists($counterFile)) {
                        $counter = intval(file_get_contents($counterFile));
                    }

                    $counter++;
                    file_put_contents($counterFile, $counter);
                }
            }

            $backup = new ProjectBackup($projectPath, $backupDirectory, $projectName);
            $backup->createBackup($updateLabel);

            echo "<div class='container'><p class='alert alert-success'>Vackup created successfully!</p></div>";
        }
    }
    ?>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
