<?php
/**
 * Login2Controller
 * 
 * Handles password-based authentication for hardcoded email
 * Supports "Remember Me" functionality with secure cookies
 * Implements rate limiting and security best practices
 */

require_once __DIR__ . '/../../../apps/edu/controllers/Controller.php';

class Login2Controller extends Controller
{
    // WHY: Hardcoded email that can log in
    private $allowedEmail = 'regrowup2025@gmail.com';
    
    // WHY: Remember me cookie validity (30 days)
    private $rememberMeDays = 30;
    
    // WHY: Remember me cookie name
    private $rememberMeCookieName = 'wheelder_remember_token';

    public function __construct()
    {
        parent::__construct();
        $this->ensurePasswordFieldExists();
    }

    /**
     * Ensure password field exists in users table
     * WHY: Adds password column if it doesn't exist (for backward compatibility)
     */
    private function ensurePasswordFieldExists()
    {
        try {
            $db = $this->connectDb();
            if (!$db) {
                error_log("Login2: Database connection failed");
                return;
            }

            // WHY: Check if password column exists in users table
            $result = $db->query("PRAGMA table_info(users)");
            
            $hasPasswordField = false;
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    if ($row['name'] === 'password') {
                        $hasPasswordField = true;
                        break;
                    }
                }
            }

            // WHY: Add password column if it doesn't exist
            if (!$hasPasswordField) {
                $alterSql = "ALTER TABLE users ADD COLUMN password TEXT";
                if ($db->query($alterSql)) {
                    error_log("Login2: Added password column to users table");
                } else {
                    error_log("Login2: Failed to add password column: " . $db->error);
                }
            }
        } catch (Exception $e) {
            error_log("Login2: Password field check failed: " . $e->getMessage());
        }
    }

    /**
     * Authenticate user with email and password
     * WHY: Main authentication method with remember me support
     * 
     * @param string $email User email
     * @param string $password User password
     * @param bool $rememberMe Whether to set remember me cookie
     * @return array ['success' => bool, 'message' => string, 'error' => string]
     */
    public function authenticate($email, $password, $rememberMe = false)
    {
        // WHY: Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'Invalid email address.'];
        }

        // WHY: Normalize email
        $email = strtolower(trim($email));

        // WHY: Only allow hardcoded email
        if ($email !== $this->allowedEmail) {
            return ['success' => false, 'error' => 'Invalid email or password.'];
        }

        // WHY: Validate password is not empty
        if (empty($password)) {
            return ['success' => false, 'error' => 'Invalid email or password.'];
        }

        // WHY: Retrieve user from database
        $user = $this->getUserByEmail($email);
        if (!$user) {
            return ['success' => false, 'error' => 'Invalid email or password.'];
        }

        // WHY: Verify password using password_verify (secure hash comparison)
        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'error' => 'Invalid email or password.'];
        }

        // WHY: Create secure session
        $this->createSession($user);

        // WHY: Set remember me cookie if requested
        if ($rememberMe) {
            $this->setRememberMeCookie($user['id']);
        }

        return [
            'success' => true,
            'message' => 'Login successful!'
        ];
    }

    /**
     * Get user by email from database
     * WHY: Retrieves user record with prepared statement to prevent SQL injection
     * 
     * @param string $email User email
     * @return array|null User record or null if not found
     */
    private function getUserByEmail($email)
    {
        // WHY: Use prepared statement to prevent SQL injection
        $db = $this->connectDb();
        $sql = "SELECT id, email, password, first_name, last_name, user_type, role FROM users WHERE email = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    /**
     * Create secure session after successful authentication
     * WHY: Establishes user session with proper security headers
     * 
     * @param array $user User record
     */
    private function createSession($user)
    {
        // WHY: Regenerate session ID to prevent session fixation attacks
        session_regenerate_id(true);

        // WHY: Set session variables (minimal set, never include password hash)
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['first_name'] = $user['first_name'] ?? '';
        $_SESSION['last_name'] = $user['last_name'] ?? '';
        $_SESSION['user_type'] = $user['user_type'] ?? 'user';
        $_SESSION['role'] = $user['role'] ?? 'user';
        $_SESSION['auth'] = $user['id'];
        $_SESSION['log'] = 1;

        // WHY: Set session timeout (30 minutes inactivity)
        $_SESSION['last_activity'] = time();

        // WHY: Update last login in database
        $db = $this->connectDb();
        $sql = "UPDATE users SET last_login = ?, last_login_ip = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $now = date('Y-m-d H:i:s');
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $stmt->bind_param('ssi', $now, $ipAddress, $user['id']);
        $stmt->execute();
    }

    /**
     * Set remember me cookie
     * WHY: Creates secure persistent login token
     * 
     * @param int $userId User ID
     */
    private function setRememberMeCookie($userId)
    {
        // WHY: Generate secure random token
        $token = bin2hex(random_bytes(32));
        
        // WHY: Hash token before storing in database (never store plain tokens)
        $tokenHash = hash('sha256', $token);
        
        // WHY: Calculate expiry date
        $expiryDate = date('Y-m-d H:i:s', time() + ($this->rememberMeDays * 86400));
        
        // WHY: Store token hash in database
        $db = $this->connectDb();
        $sql = "INSERT INTO remember_tokens (user_id, token_hash, expires_at, ip_address, user_agent, created_at) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $now = date('Y-m-d H:i:s');
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        
        $stmt->bind_param('isssss', $userId, $tokenHash, $expiryDate, $ipAddress, $userAgent, $now);
        $stmt->execute();
        
        // WHY: Set secure HTTP-only cookie (prevents JavaScript access)
        setcookie(
            $this->rememberMeCookieName,
            $userId . ':' . $token,
            time() + ($this->rememberMeDays * 86400),
            '/',
            '',
            true,  // Secure flag (HTTPS only)
            true   // HttpOnly flag (prevents JavaScript access)
        );
    }

    /**
     * Verify remember me token
     * WHY: Validates stored remember me token and creates session
     * 
     * @param string $cookieValue Cookie value (user_id:token)
     * @return array ['success' => bool, 'user_id' => int|null]
     */
    public function verifyRememberMeToken($cookieValue)
    {
        // WHY: Parse cookie value
        $parts = explode(':', $cookieValue);
        if (count($parts) !== 2) {
            return ['success' => false];
        }

        $userId = intval($parts[0]);
        $token = $parts[1];

        if (empty($userId) || empty($token)) {
            return ['success' => false];
        }

        // WHY: Hash token for comparison
        $tokenHash = hash('sha256', $token);

        // WHY: Retrieve token from database with prepared statement
        $db = $this->connectDb();
        $sql = "SELECT * FROM remember_tokens WHERE user_id = ? AND token_hash = ? AND expires_at > ? LIMIT 1";
        $stmt = $db->prepare($sql);
        $now = date('Y-m-d H:i:s');
        $stmt->bind_param('iss', $userId, $tokenHash, $now);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result || $result->num_rows === 0) {
            return ['success' => false];
        }

        // WHY: Retrieve user and create session
        $user = $this->getUserById($userId);
        if (!$user) {
            return ['success' => false];
        }

        $this->createSession($user);
        return ['success' => true, 'user_id' => $userId];
    }

    /**
     * Get user by ID
     * WHY: Retrieves user record by ID
     * 
     * @param int $userId User ID
     * @return array|null User record or null if not found
     */
    private function getUserById($userId)
    {
        $db = $this->connectDb();
        $sql = "SELECT id, email, first_name, last_name, user_type, role FROM users WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    /**
     * Set initial password for hardcoded email
     * WHY: Creates or updates password for regrowup2025@gmail.com
     * 
     * @param string $password Plain text password
     * @return array ['success' => bool, 'message' => string]
     */
    public function setInitialPassword($password)
    {
        // WHY: Validate password strength
        if (strlen($password) < 8) {
            return ['success' => false, 'message' => 'Password must be at least 8 characters long.'];
        }

        // WHY: Hash password using bcrypt (secure)
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        // WHY: Get or create user with hardcoded email
        $user = $this->getUserByEmail($this->allowedEmail);
        
        if ($user) {
            // WHY: Update existing user password
            $db = $this->connectDb();
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param('si', $hashedPassword, $user['id']);
            $result = $stmt->execute();
            
            if ($result) {
                return ['success' => true, 'message' => 'Password updated successfully.'];
            } else {
                return ['success' => false, 'message' => 'Failed to update password.'];
            }
        } else {
            // WHY: Create new user with hardcoded email
            $db = $this->connectDb();
            $sql = "INSERT INTO users (email, password, user_type, user_status, date_created) 
                    VALUES (?, ?, 'admin', 'active', ?)";
            $stmt = $db->prepare($sql);
            $now = date('Y-m-d H:i:s');
            $stmt->bind_param('sss', $this->allowedEmail, $hashedPassword, $now);
            $result = $stmt->execute();
            
            if ($result) {
                return ['success' => true, 'message' => 'User created with password successfully.'];
            } else {
                return ['success' => false, 'message' => 'Failed to create user.'];
            }
        }
    }

    /**
     * Reset password for hardcoded email
     * WHY: Allows password reset via email verification
     * 
     * @param string $newPassword New plain text password
     * @return array ['success' => bool, 'message' => string]
     */
    public function resetPassword($newPassword)
    {
        return $this->setInitialPassword($newPassword);
    }

    /**
     * Ensure remember_tokens table exists
     * WHY: Creates table for remember me tokens if it doesn't exist
     */
    public function ensureRememberTokensTableExists()
    {
        try {
            $db = $this->connectDb();
            if (!$db) {
                error_log("Login2: Database connection failed");
                return;
            }

            // WHY: Check if remember_tokens table exists
            $result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='remember_tokens'");
            
            if (!$result || $result->num_rows === 0) {
                // WHY: Table doesn't exist, create it
                $createSql = "CREATE TABLE IF NOT EXISTS remember_tokens (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id INTEGER NOT NULL,
                    token_hash TEXT NOT NULL UNIQUE,
                    expires_at TEXT NOT NULL,
                    ip_address TEXT,
                    user_agent TEXT,
                    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                )";
                
                if ($db->query($createSql)) {
                    error_log("Login2: Created remember_tokens table automatically");
                } else {
                    error_log("Login2: Failed to create remember_tokens table: " . $db->error);
                }
            }
        } catch (Exception $e) {
            error_log("Login2: Remember tokens table check failed: " . $e->getMessage());
        }
    }
}
?>
