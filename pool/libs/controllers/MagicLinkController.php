<?php
/**
 * MagicLinkController — Passwordless authentication via email magic links
 * 
 * Replaces password-based login with secure, time-limited email tokens.
 * Works with DigitalOcean-compatible SMTP or API-based email services.
 */

require_once __DIR__ . '/Controller.php';

class MagicLinkController extends Controller
{
    private $tokenLength = 32;  // 256-bit token (hex-encoded)
    private $expiryMinutes = 15; // Link valid for 15 minutes
    private $emailService = null;

    public function __construct()
    {
        parent::__construct();
        $this->ensureMagicLinksTableExists();
        $this->initEmailService();
    }

    /**
     * Ensure magic_links table exists in database
     * WHY: Prevents "table not found" errors if migration wasn't run
     */
    private function ensureMagicLinksTableExists()
    {
        try {
            $db = $this->connectDb();
            if (!$db) {
                error_log("Magic link: Database connection failed");
                return;
            }

            // Check if magic_links table exists
            $result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='magic_links'");
            
            if (!$result || $result->num_rows === 0) {
                // Table doesn't exist, create it
                $createSql = "CREATE TABLE IF NOT EXISTS magic_links (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id INTEGER,
                    email TEXT NOT NULL,
                    token TEXT NOT NULL UNIQUE,
                    expires_at TEXT NOT NULL,
                    used INTEGER DEFAULT 0,
                    used_at TEXT,
                    ip_address TEXT,
                    user_agent TEXT,
                    created_at TEXT DEFAULT CURRENT_TIMESTAMP
                )";
                
                if ($db->query($createSql)) {
                    error_log("Magic link: Created magic_links table automatically");
                } else {
                    error_log("Magic link: Failed to create magic_links table: " . $db->error);
                }
            }
        } catch (Exception $e) {
            error_log("Magic link: Table existence check failed: " . $e->getMessage());
        }
    }

    /**
     * Initialize email service — supports multiple providers
     * WHY: Allows switching between SMTP, SendGrid, Mailgun, etc. without code changes
     */
    private function initEmailService()
    {
        $provider = getenv('EMAIL_PROVIDER') ?: 'smtp';
        
        if ($provider === 'sendgrid') {
            $this->emailService = new SendGridEmailService();
        } elseif ($provider === 'mailgun') {
            $this->emailService = new MailgunEmailService();
        } else {
            // Default to SMTP (works with DigitalOcean App Platform)
            $this->emailService = new SMTPEmailService();
        }
    }

    /**
     * Generate and send magic link to user email
     * WHY: Single entry point for requesting authentication
     * 
     * @param string $email User email address
     * @return array ['success' => bool, 'message' => string, 'user_id' => int|null]
     */
    public function requestMagicLink($email)
    {
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email address.'];
        }

        $email = strtolower(trim($email));

        // Find or create user by email
        $user = $this->findOrCreateUser($email);
        if (!$user) {
            error_log("Magic link: Failed to find or create user for email: $email");
            return ['success' => false, 'message' => 'Unable to process email. Please try again.'];
        }

        // Generate secure token
        $token = bin2hex(random_bytes($this->tokenLength / 2)); // 32 chars hex = 128 bits
        $expiresAt = date('Y-m-d H:i:s', time() + ($this->expiryMinutes * 60));

        // Store token in database
        $stored = $this->storeToken($user['id'], $email, $token, $expiresAt);
        if (!$stored) {
            error_log("Magic link: Failed to store token for email: $email, user_id: {$user['id']}");
            return ['success' => false, 'message' => 'Failed to generate link. Please try again.'];
        }

        // Send email with magic link
        $magicLink = $this->getMagicLinkUrl($token);

        // On localhost/dev, mail() has no server — return the link directly so login still works
        if ($this->isLocalhost()) {
            error_log("Magic link (dev): Link for $email — $magicLink");
            return [
                'success'    => true,
                'message'    => "Dev mode: email not sent. Use the link below to log in.",
                'magic_link' => $magicLink,   // returned to browser on localhost only
                'user_id'    => $user['id']
            ];
        }

        $sent = $this->emailService->sendMagicLink($email, $magicLink, $this->expiryMinutes);
        if (!$sent) {
            error_log("Magic link: Failed to send email to: $email");
            return ['success' => false, 'message' => 'Failed to send email. Please check your email address.'];
        }

        return [
            'success' => true,
            'message' => "Magic link sent to $email. Check your inbox (valid for {$this->expiryMinutes} minutes).",
            'user_id' => $user['id']
        ];
    }

    /**
     * Verify magic link token and create session
     * WHY: Validates token expiry, prevents reuse, and establishes secure session
     * 
     * @param string $token Magic link token from URL
     * @return array ['success' => bool, 'message' => string, 'user_id' => int|null]
     */
    public function verifyMagicLink($token)
    {
        // Validate token format — tokenLength=32 means bin2hex(random_bytes(16)) = 32 hex chars
        if (!preg_match('/^[a-f0-9]{32}$/', $token)) {
            return ['success' => false, 'message' => 'Invalid link format.'];
        }

        // Retrieve token from database
        $sql = "SELECT * FROM magic_links WHERE token = ? AND used = 0";
        $result = $this->run_query_prepared($sql, [$token]);
        
        if (!$result || $result->num_rows === 0) {
            return ['success' => false, 'message' => 'Link not found or already used.'];
        }

        $linkRecord = $result->fetch_assoc();

        // Check expiration
        if (strtotime($linkRecord['expires_at']) < time()) {
            return ['success' => false, 'message' => 'Link has expired. Request a new one.'];
        }

        // Mark token as used (prevents replay attacks)
        $this->markTokenAsUsed($linkRecord['id']);

        // Retrieve user
        $user = $this->getUserById($linkRecord['user_id']);
        if (!$user) {
            return ['success' => false, 'message' => 'User not found.'];
        }

        // Create secure session
        $this->createSession($user);

        return [
            'success' => true,
            'message' => 'Authenticated successfully.',
            'user_id' => $user['id']
        ];
    }

    /**
     * Find existing user or create new one by email
     * WHY: Supports both returning users and new signups via magic link
     */
    private function findOrCreateUser($email)
    {
        // Check if user exists
        $sql = "SELECT id, email FROM users WHERE email = ?";
        $result = $this->run_query_prepared($sql, [$email]);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        // Create new user (single-user CMS, so minimal profile)
        $sql = "INSERT INTO users (email, user_type, user_status, date_created) VALUES (?, 'admin', 'active', ?)";
        $now = date('Y-m-d H:i:s');
        $inserted = $this->run_query_prepared($sql, [$email, $now]);

        if (!$inserted) {
            return null;
        }

        // Return newly created user
        return ['id' => $this->getLastInsertId(), 'email' => $email];
    }

    /**
     * Store magic link token in database
     * WHY: Tracks all tokens for validation and audit logging
     */
    private function storeToken($userId, $email, $token, $expiresAt)
    {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

        $sql = "INSERT INTO magic_links (user_id, email, token, expires_at, ip_address, user_agent, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $now = date('Y-m-d H:i:s');

        $result = $this->run_query_prepared($sql, [$userId, $email, $token, $expiresAt, $ipAddress, $userAgent, $now]);
        
        // Log detailed error if insertion fails — helps diagnose database issues
        if (!$result) {
            $db = $this->connectDb();
            $error = $db ? $db->error : 'Database connection failed';
            error_log("Magic link token storage failed for email: $email, user_id: $userId, error: $error");
        }
        
        return $result;
    }

    /**
     * Mark token as used after successful verification
     * WHY: Prevents token replay attacks — each link can only be used once
     */
    private function markTokenAsUsed($linkId)
    {
        $sql = "UPDATE magic_links SET used = 1, used_at = ? WHERE id = ?";
        $now = date('Y-m-d H:i:s');
        return $this->run_query_prepared($sql, [$now, $linkId]);
    }

    /**
     * Retrieve user by ID
     */
    private function getUserById($userId)
    {
        $sql = "SELECT id, email, first_name, last_name, user_type, role FROM users WHERE id = ?";
        $result = $this->run_query_prepared($sql, [$userId]);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    /**
     * Create secure session after successful authentication
     * WHY: Establishes user session with proper security headers
     */
    private function createSession($user)
    {
        // Regenerate session ID to prevent fixation attacks
        session_regenerate_id(true);

        // Set session variables (minimal set, never include password hash)
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['first_name'] = $user['first_name'] ?? '';
        $_SESSION['last_name'] = $user['last_name'] ?? '';
        $_SESSION['user_type'] = $user['user_type'] ?? 'user';
        $_SESSION['role'] = $user['role'] ?? 'user';
        $_SESSION['auth'] = $user['id'];
        $_SESSION['log'] = 1;

        // Set session timeout (30 minutes inactivity)
        $_SESSION['last_activity'] = time();

        // Update last login in database
        $sql = "UPDATE users SET last_login = ?, last_login_ip = ? WHERE id = ?";
        $now = date('Y-m-d H:i:s');
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $this->run_query_prepared($sql, [$now, $ipAddress, $user['id']]);
    }

    /**
     * Generate full magic link URL
     * WHY: Constructs clickable link for email — uses APP_URL env var or auto-detects from request
     */
    private function getMagicLinkUrl($token)
    {
        // Use APP_URL if set, otherwise auto-detect from current request
        $baseUrl = getenv('APP_URL');
        if (empty($baseUrl)) {
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '/');
            $basePath = ($scriptDir === '/' || $scriptDir === '\\') ? '' : $scriptDir;
            $baseUrl = $protocol . '://' . $host . $basePath;
        }
        return rtrim($baseUrl, '/') . '/auth/verify?token=' . $token;
    }

    /**
     * Detect if running on localhost/dev environment
     * WHY: On localhost, mail() has no SMTP server — skip email and return link directly
     */
    private function isLocalhost()
    {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        return in_array($host, ['localhost', '127.0.0.1', '::1'])
            || strpos($host, 'localhost:') === 0
            || strpos($host, '127.0.0.1:') === 0;
    }

    /**
     * Clean up expired tokens (run periodically via cron)
     * WHY: Prevents database bloat from old, unused tokens
     */
    public function cleanupExpiredTokens()
    {
        $sql = "DELETE FROM magic_links WHERE expires_at < ? AND used = 0";
        $now = date('Y-m-d H:i:s');
        return $this->run_query_prepared($sql, [$now]);
    }

    /**
     * Get last inserted row ID
     * WHY: Needed after INSERT to retrieve the new user ID
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
}
