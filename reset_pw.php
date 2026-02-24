<?php
// ONE-TIME password reset helper — DELETE THIS FILE after use
// Access: http://localhost/wheelder/reset_pw.php

$path = __DIR__;
require_once $path . '/pool/config/db_config.php';

$config = new config();
$pdo    = $config->connectDbPDO(); // Use raw PDO to avoid any wrapper quirks

// Show current users
$stmt = $pdo->query("SELECT id, email, first_name FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<h3>Users in DB:</h3><pre>";
print_r($users);
echo "</pre>";

// Reset password — use prepared statement so bcrypt $ chars don't break the query
$targetEmail = 'regrowup2025@gmail.com';
$newPassword  = 'Wh@@l2025#Adm!n';
$hash = password_hash($newPassword, PASSWORD_BCRYPT);

$upd = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
$ok  = $upd->execute([$hash, $targetEmail]);

echo "<p>Rows affected: <strong>" . $upd->rowCount() . "</strong></p>";

if ($ok && $upd->rowCount() > 0) {
    // Verify the hash was stored correctly by reading it back
    $check = $pdo->prepare("SELECT password FROM users WHERE email = ?");
    $check->execute([$targetEmail]);
    $stored = $check->fetch(PDO::FETCH_ASSOC);

    if ($stored && password_verify($newPassword, $stored['password'])) {
        echo "<p style='color:green'><strong>SUCCESS — password reset and verified for <code>" . htmlspecialchars($targetEmail) . "</code></strong></p>";
        echo "<p>Login with password: <code>" . htmlspecialchars($newPassword) . "</code></p>";
    } else {
        echo "<p style='color:orange'><strong>Row updated but hash verification failed. Stored hash: <code>" . htmlspecialchars($stored['password'] ?? 'null') . "</code></strong></p>";
    }
} else {
    // Email not found — show all emails so we can spot a mismatch
    echo "<p style='color:red'><strong>No row updated. Email not found or unchanged.</strong></p>";
    echo "<p>Emails in DB:</p><pre>";
    foreach ($users as $u) {
        echo htmlspecialchars($u['email']) . "\n";
    }
    echo "</pre>";
}

echo "<p style='color:red'><strong>DELETE this file after use: c:\\xampp\\htdocs\\wheelder\\reset_pw.php</strong></p>";
