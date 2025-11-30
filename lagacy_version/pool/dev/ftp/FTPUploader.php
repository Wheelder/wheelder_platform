<?php
class FTPUploader {
    private $ftpConn;

    public function __construct() {
        try {
            $this->ftpConn = ftp_connect(Config::FTP_SERVER);
            if (!$this->ftpConn) {
                throw new Exception("Could not connect to FTP server");
            }

            if (!ftp_login($this->ftpConn, Config::FTP_USER, Config::FTP_PASS)) {
                throw new Exception("FTP login failed");
            }

            // Additional FTP setup steps can be added here
            ftp_pasv($this->ftpConn, true); // Enable passive mode (optional)
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function uploadFile($localFilePath, $remoteFileName) {
        if (ftp_put($this->ftpConn, $remoteFileName, $localFilePath, FTP_BINARY)) {
            return true;
        } else {
            return false;
        }
    }

    public function closeConnection() {
        ftp_close($this->ftpConn);
    }
}
?>
