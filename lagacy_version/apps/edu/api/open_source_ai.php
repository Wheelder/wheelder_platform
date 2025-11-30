<?php
/**
 * Open Source AI Chat API
 * This endpoint can work with various open-source models
 * Currently configured for Ollama (local deployment) as an example
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents('php://input'), true);
    $userInput = $input['userInput'] ?? '';
    $model = $input['model'] ?? 'llama2'; // Default to llama2
    
    if (empty($userInput)) {
        echo json_encode(['error' => 'No input provided']);
        exit;
    }

    // Configuration for different open-source model providers
    $providers = [
        'ollama' => [
            'endpoint' => 'http://localhost:11434/api/generate',
            'models' => ['llama2', 'codellama', 'mistral', 'phi', 'neural-chat']
        ],
        'huggingface' => [
            'endpoint' => 'https://api-inference.huggingface.co/models/',
            'models' => ['microsoft/DialoGPT-medium', 'facebook/blenderbot-400M-distill']
        ]
    ];

    // Try Ollama first (local deployment)
    $response = callOllama($userInput, $model);
    
    if ($response) {
        echo json_encode($response);
    } else {
        // Fallback to a simple response if no open-source model is available
        echo json_encode([
            'choices' => [
                [
                    'message' => [
                        'content' => "I'm sorry, but the open-source AI models are currently unavailable. Please ensure you have Ollama installed locally or use the OpenAI integration instead.\n\nTo use open-source models:\n1. Install Ollama: https://ollama.ai/\n2. Run: ollama pull llama2\n3. Start the service: ollama serve"
                    ]
                ]
            ]
        ]);
    }
}

function callOllama($prompt, $model = 'llama2') {
    $url = 'http://localhost:11434/api/generate';
    
    $data = [
        'model' => $model,
        'prompt' => "You are an intelligent learning assistant for the Wheeleder educational platform. You help students understand concepts, answer questions, provide explanations, and guide learning. Always be helpful, encouraging, and educational in your responses.\n\nUser: " . $prompt . "\nAssistant:",
        'stream' => false,
        'options' => [
            'temperature' => 0.7,
            'top_p' => 0.9,
            'max_tokens' => 1000
        ]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch) || $httpCode !== 200) {
        curl_close($ch);
        return null;
    }
    
    curl_close($ch);
    
    $result = json_decode($response, true);
    
    if (isset($result['response'])) {
        // Convert Ollama response format to OpenAI format for consistency
        return [
            'choices' => [
                [
                    'message' => [
                        'content' => trim($result['response'])
                    ]
                ]
            ]
        ];
    }
    
    return null;
}

function callHuggingFace($prompt, $model = 'microsoft/DialoGPT-medium') {
    // Example for Hugging Face Inference API
    // You would need a Hugging Face API token for this
    $url = "https://api-inference.huggingface.co/models/" . $model;
    
    $data = [
        'inputs' => $prompt,
        'parameters' => [
            'max_length' => 1000,
            'temperature' => 0.7,
            'do_sample' => true
        ]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer YOUR_HUGGINGFACE_TOKEN' // Replace with actual token
    ]);

    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}
?>
