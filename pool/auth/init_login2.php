<?php
/**
 * Initialize Login2 System
 * 
 * WHY: Sets up initial password for hardcoded email and creates necessary tables
 * Run this once after deploying login2 system
 */

// WHY: Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// WHY: Load required files
require_once __DIR__ . '/../libs/controllers/Login2Controller.php';
require_once __DIR__ . '/../libs/controllers/PasswordResetController.php';

// WHY: Check if user is authenticated (admin only)
if (empty($_SESSION['auth']) || empty($_SESSION['log']) || $_SESSION['log'] != 1) {
    http_response_code(403);
    echo '<h1>403 Forbidden</h1>';
    echo '<p>You must be logged in to access this page.</p>';
    exit;
}

// WHY: Initialize controllers
$login2 = new Login2Controller();
$passwordReset = new PasswordResetController();

// WHY: Ensure tables exist
$login2->ensureRememberTokensTableExists();
$passwordReset->ensurePasswordResetTableExists();

// WHY: Set initial password if provided
$initialPassword = 'Wheelder@2025!Secure'; // Default initial password
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    
    if (empty($password)) {
        $error = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long.';
    } else {
        $result = $login2->setInitialPassword($password);
        if ($result['success']) {
            $message = $result['message'];
        } else {
            $error = $result['message'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Initialize Login2 — Wheelder</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .init-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 500px;
            width: 100%;
        }
        .init-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .init-header h2 {
            color: #333;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .init-header p {
            color: #666;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        .form-control {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 12px 15px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .btn-init {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-init:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .alert {
            border-radius: 6px;
            border: none;
            margin-bottom: 20px;
        }
        .info-box {
            background: #f0f4ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #333;
        }
        .info-box strong {
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="init-card">
        <div class="init-header">
            <h2>Initialize Login2</h2>
            <p>Set up password authentication for regrowup2025@gmail.com</p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-success">
                <strong>Success!</strong> <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <strong>Error:</strong> <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <div class="info-box">
            <strong>Email:</strong> regrowup2025@gmail.com<br>
            <strong>Default Password:</strong> <?php echo htmlspecialchars($initialPassword, ENT_QUOTES, 'UTF-8'); ?><br>
            <small>You can set a custom password below</small>
        </div>

        <form method="POST">
            <div class="form-group">
                <label for="password" class="form-label">Set Password</label>
                <input 
                    type="password" 
                    class="form-control" 
                    id="password" 
                    name="password" 
                    placeholder="Enter password (min 8 characters)" 
                    required 
                    minlength="8"
                />
                <small class="text-muted d-block mt-2">Minimum 8 characters recommended</small>
            </div>

            <button type="submit" class="btn-init">Set Password</button>
        </form>

        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee; text-align: center;">
            <small class="text-muted">
                After setting the password, you can log in at <a href="/login2" style="color: #667eea;">/login2</a>
            </small>
        </div>
    </div>
</body>
</html>
