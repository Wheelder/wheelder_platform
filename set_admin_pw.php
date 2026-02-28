<?php
/**
 * One-time script to set a password for an admin user.
 * DELETE THIS FILE IMMEDIATELY AFTER USE.
 */

$dbPath = __DIR__ . '/pool/config/wheelder.db';

if (!file_exists($dbPath)) {
    die("DB not found at: $dbPath");
}

$email = 'regrowup2025@gmail.com';
$newPassword = '123$5sdfvoon45Hj';
$hashed = password_hash($newPassword, PASSWORD_BCRYPT);

try {
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verify user exists first
    $check = $db->prepare("SELECT id, email FROM users WHERE email = ?");
    $check->execute([$email]);
    $user = $check->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("No user found with email: $email");
    }

    // Update password
    $stmt = $db->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->execute([$hashed, $email]);

    echo "Password updated for user ID {$user['id']} ({$email})";
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
