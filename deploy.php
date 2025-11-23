<?php

$TOKEN = "123qweasdzxc";  // change this to a strong random string
$provided = $_GET['token'] ?? '';

if ($provided !== $TOKEN) {
    http_response_code(403);
    echo "Invalid token";
    exit;
}

shell_exec("git pull origin main 2>&1");

echo "OK";
