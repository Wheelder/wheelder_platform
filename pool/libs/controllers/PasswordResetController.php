<?php
/**
 * PasswordResetController
 * 
 * Handles password reset functionality for hardcoded email
 * Generates secure reset tokens and validates them
 */

require_once __DIR__ . '/../../../apps/edu/controllers/Controller.php';

class PasswordResetController extends Controller
{
    // WHY: Hardcoded email that can reset password
    private $allowedEmail = 'regrowup2025@gmail.com';
    
    // WHY: Reset token validity (1 hour)
    private $tokenExpiryMinutes = 60;

    public function __construct()
    {
        parent::__construct();
        $this->ensurePasswordResetTableExists();
    }

    /**
     * Ensure password_reset_tokens table exists
     * WHY: Creates table for password reset tokens if it doesn't exist
     */
    private function ensurePasswordResetTableExists()
    {
        try {
            $db = $this->connectDb();
            if (!$db) {
                error_log("Password reset: Database connection failed");
                return;
            }

            // WHY: Check if password_reset_tokens table exists
            $result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='password_reset_tokens'");
            
            if (!$result || $result->num_rows === 0) {
                // WHY: Table doesn't exist, create it
                $createSql = "CREATE TABLE IF NOT EXISTS password_reset_tokens (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id INTEGER NOT NULL,
                    email TEXT NOT NULL,
                    token_hash TEXT NOT NULL UNIQUE,
                    expires_at TEXT NOT NULL,
                    used INTEGER DEFAULT 0,
                    used_at TEXT,
                    ip_address TEXT,
                    user_agent TEXT,
                    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                )";
                
                if ($db->query($createSql)) {
                    error_log("Password reset: Created password_reset_tokens table automatically");
                } else {
                    error_log("Password reset: Failed to create password_reset_tokens table: " . $db->error);
                }
            }
        } catch (Exception $e) {
            error_log("Password reset: Table existence check failed: " . $e->getMessage());
        }
    }

    /**
     * Request password reset
     * WHY: Generates reset token and sends to email (or returns for dev mode)
     * 
     * @param string $email User email
     * @return array ['success' => bool, 'message' => string, 'error' => string]
     */
    public function requestPasswordReset($email)
    {
        // WHY: Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'Invalid email address.'];
        }

        // WHY: Normalize email
        $email = strtolower(trim($email));

        // WHY: Only allow hardcoded email
        if ($email !== $this->allowedEmail) {
            return ['success' => false, 'error' => 'Email not found.'];
        }

        // WHY: Get or create user
        $user = $this->getUserByEmail($email);
        if (!$user) {
            // WHY: Create user if doesn't exist
            $user = $this->createUser($email);
            if (!$user) {
                return ['success' => false, 'error' => 'Failed to process request.'];
            }
        }

        // WHY: Generate secure reset token
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);
        $expiresAt = date('Y-m-d H:i:s', time() + ($this->tokenExpiryMinutes * 60));

        // WHY: Store token in database
        $stored = $this->storeResetToken($user['id'], $email, $tokenHash, $expiresAt);
        if (!$stored) {
            return ['success' => false, 'error' => 'Failed to generate reset link.'];
        }

        // WHY: Generate reset link
        $resetLink = $this->getResetLinkUrl($token);

        // WHY: On localhost/dev, return link directly (no mail server)
        if ($this->isLocalhost()) {
            error_log("Password reset (dev): Link for $email — $resetLink");
            return [
                'success' => true,
                'message' => "Dev mode: Reset link — $resetLink",
                'reset_link' => $resetLink
            ];
        }

        // WHY: Send email with reset link (in production)
        $sent = $this->sendResetEmail($email, $resetLink);
        if (!$sent) {
            error_log("Password reset: Failed to send email to: $email");
            return ['success' => false, 'error' => 'Failed to send email.'];
        }

        return [
            'success' => true,
            'message' => "Password reset link sent to $email (valid for {$this->tokenExpiryMinutes} minutes)."
        ];
    }

    /**
     * Reset password using token
     * WHY: Validates token and updates password
     * 
     * @param string $token Reset token
     * @param string $newPassword New password
     * @return array ['success' => bool, 'message' => string, 'error' => string]
     */
    public function resetPassword($token, $newPassword)
    {
        // WHY: Validate token format (64 hex chars = 32 bytes)
        if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
            return ['success' => false, 'error' => 'Invalid reset link.'];
        }

        // WHY: Validate password strength
        if (strlen($newPassword) < 8) {
            return ['success' => false, 'error' => 'Password must be at least 8 characters.'];
        }

        // WHY: Hash token for lookup
        $tokenHash = hash('sha256', $token);

        // WHY: Retrieve token from database
        $sql = "SELECT * FROM password_reset_tokens WHERE token_hash = ? AND used = 0";
        $result = $this->run_query_prepared($sql, [$tokenHash]);
        
        if (!$result || $result->num_rows === 0) {
            return ['success' => false, 'error' => 'Invalid or expired reset link.'];
        }

        $tokenRecord = $result->fetch_assoc();

        // WHY: Check expiration
        if (strtotime($tokenRecord['expires_at']) < time()) {
            return ['success' => false, 'error' => 'Reset link has expired.'];
        }

        // WHY: Hash new password using bcrypt
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);

        // WHY: Update user password
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $updated = $this->run_query_prepared($sql, [$hashedPassword, $tokenRecord['user_id']]);

        if (!$updated) {
            return ['success' => false, 'error' => 'Failed to update password.'];
        }

        // WHY: Mark token as used (prevents reuse)
        $this->markTokenAsUsed($tokenRecord['id']);

        return [
            'success' => true,
            'message' => 'Password reset successfully!'
        ];
    }

    /**
     * Get user by email
     * WHY: Retrieves user record with prepared statement
     * 
     * @param string $email User email
     * @return array|null User record or null
     */
    private function getUserByEmail($email)
    {
        $sql = "SELECT id, email FROM users WHERE email = ?";
        $result = $this->run_query_prepared($sql, [$email]);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    /**
     * Create user with email
     * WHY: Creates new user for password reset
     * 
     * @param string $email User email
     * @return array|null User record or null
     */
    private function createUser($email)
    {
        $sql = "INSERT INTO users (email, user_type, user_status, date_created) VALUES (?, 'admin', 'active', ?)";
        $now = date('Y-m-d H:i:s');
        $result = $this->run_query_prepared($sql, [$email, $now]);

        if (!$result) {
            return null;
        }

        return ['id' => $this->getLastInsertId(), 'email' => $email];
    }

    /**
     * Store reset token in database
     * WHY: Tracks all tokens for validation
     * 
     * @param int $userId User ID
     * @param string $email User email
     * @param string $tokenHash Hashed token
     * @param string $expiresAt Expiry timestamp
     * @return bool Success status
     */
    private function storeResetToken($userId, $email, $tokenHash, $expiresAt)
    {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

        $sql = "INSERT INTO password_reset_tokens (user_id, email, token_hash, expires_at, ip_address, user_agent, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $now = date('Y-m-d H:i:s');

        $result = $this->run_query_prepared($sql, [$userId, $email, $tokenHash, $expiresAt, $ipAddress, $userAgent, $now]);
        
        if (!$result) {
            $db = $this->connectDb();
            $error = $db ? $db->error : 'Database connection failed';
            error_log("Password reset token storage failed: $error");
        }
        
        return $result ? true : false;
    }

    /**
     * Mark token as used
     * WHY: Prevents token reuse
     * 
     * @param int $tokenId Token ID
     * @return bool Success status
     */
    private function markTokenAsUsed($tokenId)
    {
        $sql = "UPDATE password_reset_tokens SET used = 1, used_at = ? WHERE id = ?";
        $now = date('Y-m-d H:i:s');
        return $this->run_query_prepared($sql, [$now, $tokenId]) ? true : false;
    }

    /**
     * Send reset email
     * WHY: Sends password reset link via email
     * 
     * @param string $email User email
     * @param string $resetLink Reset link URL
     * @return bool Success status
     */
    private function sendResetEmail($email, $resetLink)
    {
        // WHY: Email subject and body
        $subject = "Password Reset Request — Wheelder";
        $message = "
            <html>
            <body style='font-family: Arial, sans-serif; color: #333;'>
                <h2>Password Reset Request</h2>
                <p>We received a request to reset your password. Click the link below to proceed:</p>
                <p><a href='" . htmlspecialchars($resetLink, ENT_QUOTES, 'UTF-8') . "' style='background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Reset Password</a></p>
                <p>This link expires in {$this->tokenExpiryMinutes} minutes.</p>
                <p>If you didn't request this, you can safely ignore this email.</p>
                <hr>
                <p style='font-size: 12px; color: #999;'>Wheelder — Secure Password Reset</p>
            </body>
            </html>
        ";

        // WHY: Email headers
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: noreply@wheelder.com\r\n";

        // WHY: Send email (returns false on localhost where mail() is not configured)
        $sent = @mail($email, $subject, $message, $headers);
        
        return $sent;
    }

    /**
     * Generate reset link URL
     * WHY: Constructs clickable link for email
     * 
     * @param string $token Reset token
     * @return string Reset link URL
     */
    private function getResetLinkUrl($token)
    {
        // WHY: Use APP_URL if set, otherwise auto-detect
        $baseUrl = getenv('APP_URL');
        if (empty($baseUrl)) {
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '/');
            $basePath = ($scriptDir === '/' || $scriptDir === '\\') ? '' : $scriptDir;
            $baseUrl = $protocol . '://' . $host . $basePath;
        }
        return rtrim($baseUrl, '/') . '/password-reset?step=reset&token=' . $token;
    }

    /**
     * Detect if running on localhost
     * WHY: On localhost, mail() has no SMTP server
     * 
     * @return bool True if localhost
     */
    private function isLocalhost()
    {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        return in_array($host, ['localhost', '127.0.0.1', '::1'])
            || strpos($host, 'localhost:') === 0
            || strpos($host, '127.0.0.1:') === 0;
    }

    /**
     * Get last inserted row ID
     * WHY: Needed after INSERT to retrieve new ID
     * 
     * @return int Last insert ID
     */
    private function getLastInsertId()
    {
        $db = $this->connectDb();
        $result = $db->query("SELECT last_insert_rowid() as id");
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['id'] ?? 0;
        }
        return 0;
    }

    /**
     * Clean up expired tokens
     * WHY: Prevents database bloat
     */
    public function cleanupExpiredTokens()
    {
        $sql = "DELETE FROM password_reset_tokens WHERE expires_at < ? AND used = 0";
        $now = date('Y-m-d H:i:s');
        return $this->run_query_prepared($sql, [$now]);
    }
}
?>
