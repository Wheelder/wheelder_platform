<?php
/**
 * Setup Login2 Password
 * 
 * WHY: Direct script to set initial password for regrowup2025@gmail.com
 * Run this once from command line or browser to initialize the system
 */

// WHY: Load required files
require_once __DIR__ . '/pool/libs/controllers/Login2Controller.php';
require_once __DIR__ . '/pool/libs/controllers/PasswordResetController.php';

// WHY: Initialize controllers
$login2 = new Login2Controller();
$passwordReset = new PasswordResetController();

// WHY: Ensure tables exist
$login2->ensureRememberTokensTableExists();
$passwordReset->ensurePasswordResetTableExists();

// WHY: Set initial password
$initialPassword = 'Wheelder@2025!Secure';
$result = $login2->setInitialPassword($initialPassword);

// WHY: Output result
if ($result['success']) {
    echo "✓ SUCCESS: " . $result['message'] . "\n";
    echo "Email: regrowup2025@gmail.com\n";
    echo "Password: " . $initialPassword . "\n";
    echo "Login URL: http://localhost/login2\n";
} else {
    echo "✗ ERROR: " . $result['message'] . "\n";
    exit(1);
}
?>
