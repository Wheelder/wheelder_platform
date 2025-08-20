<?php
/**
 * AI Chat Test Endpoint
 * Use this to test the AI integration without making actual API calls
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents('php://input'), true);
    $userInput = $input['userInput'] ?? '';
    
    if (empty($userInput)) {
        echo json_encode(['error' => 'No input provided']);
        exit;
    }

    // Check which model is being requested
    $model = $input['model'] ?? 'test';
    
    // Simulate different responses based on the question
    $responses = [
        'hello' => "Hello! I'm your AI learning assistant. I'm working perfectly! How can I help you learn today?",
        'test' => "✅ Test successful! The chat system is working correctly. You can ask me questions about:\n\n• Mathematics and Science\n• History and Literature\n• Programming and Technology\n• Study tips and learning strategies\n\nWhat would you like to learn about?",
        'help' => "I'm here to help you learn! I can:\n\n📚 Explain complex topics\n💡 Provide study tips\n🧮 Help with math problems\n💻 Assist with coding\n📖 Discuss literature and history\n\nJust ask me anything!",
        'default' => "I understand you're asking about: \"$userInput\"\n\nThis is a test response. The AI chat system is working! In a real scenario, I would provide detailed educational assistance about your topic.\n\n🔧 **Current Status**: Test Mode\n🤖 **Model**: Test AI Assistant\n✅ **System**: Operational\n\nTo use the full AI capabilities, please configure a valid OpenAI API key or set up local models."
    ];
    
    // Determine response based on input
    $lowerInput = strtolower(trim($userInput));
    $responseText = $responses['default'];
    
    foreach ($responses as $keyword => $response) {
        if ($keyword !== 'default' && strpos($lowerInput, $keyword) !== false) {
            $responseText = $response;
            break;
        }
    }
    
    // Return in OpenAI format for compatibility
    $response = [
        'choices' => [
            [
                'message' => [
                    'content' => $responseText
                ]
            ]
        ],
        'model' => 'test-assistant',
        'usage' => [
            'total_tokens' => strlen($userInput) + strlen($responseText)
        ]
    ];
    
    // Add small delay to simulate real API
    usleep(500000); // 0.5 second delay
    
    echo json_encode($response);
} else {
    echo json_encode(['error' => 'Only POST method allowed']);
}
?>
