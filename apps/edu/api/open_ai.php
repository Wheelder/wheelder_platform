<?php

// No CORS wildcard — only same-origin requests allowed (prevents external abuse)
header("Content-Type: application/json");

// Session required — block unauthenticated AI requests (prevents cost abuse)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Authentication required']);
    exit();
}

// Load API key from config file — never hardcode secrets in source code
$_configPath = __DIR__ . '/../ui/views/learn/backup/config.local.php';
if (!file_exists($_configPath)) {
    http_response_code(500);
    echo json_encode(['error' => 'Missing config.local.php']);
    exit();
}
$_config = require $_configPath;

define('OPENAI_API_ENDPOINT', 'https://api.openai.com/v1/chat/completions');
define('OPENAI_API_KEY', $_config['OPENAI_API_KEY'] ?? '');

if (empty(OPENAI_API_KEY)) {
    http_response_code(500);
    echo json_encode(['error' => 'OPENAI_API_KEY not configured']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents('php://input'), true);
    $userInput = $input['userInput'];

    $data = [
        'model' => 'gpt-3.5-turbo-16k-0613',
        'messages' => [
            [
                'role' => 'system',
                'content' => 'You are an intelligent learning assistant for the Wheeleder educational platform. You help students understand concepts, answer questions, provide explanations, and guide learning. Always be helpful, encouraging, and educational in your responses. Break down complex topics into simple, understandable parts.'
            ],
            [
                'role' => 'user',
                'content' => $userInput
            ]
        ],
        'temperature' => 0.7,
        'max_tokens' => 2000,
        'top_p' => 1,
        'frequency_penalty' => 0,
        'presence_penalty' => 0
    ];

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . OPENAI_API_KEY
    ];

    $ch = curl_init(OPENAI_API_ENDPOINT);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        curl_close($ch);
        echo json_encode(['error' => 'Connection error: ' . curl_error($ch)]);
        exit();
    }

    curl_close($ch);
    
    // Decode the response to check for errors
    $decodedResponse = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['error' => 'Invalid JSON response from API']);
        exit();
    }
    
    // Check for OpenAI API errors
    if (isset($decodedResponse['error'])) {
        echo json_encode(['error' => 'OpenAI API Error: ' . $decodedResponse['error']['message']]);
        exit();
    }
    
    // Check HTTP status code
    if ($httpCode !== 200) {
        echo json_encode(['error' => "HTTP Error: $httpCode"]);
        exit();
    }
    
    // Return the response
    echo $response;
}
