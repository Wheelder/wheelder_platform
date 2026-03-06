<?php
/**
 * Test script to verify /cms2 image generation pipeline
 * Traces: question → summarize answer → generate image
 * 
 * Run this from CLI: php test_cms2_image_gen.php
 */

// Load AppController from /learn/backup
$appCtrlPath = __DIR__ . '/apps/edu/ui/views/learn/backup/AppController.php';
if (!file_exists($appCtrlPath)) {
    die("ERROR: AppController not found at $appCtrlPath\n");
}
require_once $appCtrlPath;

// Test question (same as the screenshot)
$testQuestion = "How Important Is Data Structure and Algorithms in the Era of Gen AI?";

echo "=== /cms2 Image Generation Pipeline Test ===\n\n";
echo "Test Question: $testQuestion\n\n";

try {
    $app = new AppController();
    
    // Step 1: Generate answer
    echo "[STEP 1] Generating answer via Groq API...\n";
    $answer = $app->generateResponse($testQuestion);
    
    if (strpos($answer, 'error') !== false) {
        echo "ERROR in generateResponse: $answer\n";
        exit(1);
    }
    
    echo "Answer length: " . strlen($answer) . " chars\n";
    echo "First 200 chars: " . mb_substr($answer, 0, 200) . "...\n\n";
    
    // Step 2: Generate answer + image together (this is what /cms2 actually calls)
    echo "[STEP 2] Generating answer + image via generateAnswerAndImage()...\n";
    $result = $app->generateAnswerAndImage($testQuestion, $testQuestion);
    
    $generatedAnswer = $result['answer'] ?? '';
    $generatedImage = $result['image'] ?? '';
    
    echo "Generated answer length: " . strlen($generatedAnswer) . " chars\n";
    echo "Generated image URL: $generatedImage\n\n";
    
    // Step 3: Verify image URL is valid
    echo "[STEP 3] Validating image URL...\n";
    if (empty($generatedImage)) {
        echo "ERROR: Image URL is empty!\n";
        exit(1);
    }
    
    if (strpos($generatedImage, 'http') !== 0) {
        echo "ERROR: Image URL does not start with http: $generatedImage\n";
        exit(1);
    }
    
    // Check if it's a placeholder
    if (strpos($generatedImage, 'placehold.co') !== false) {
        echo "WARNING: Image is a placeholder (final fallback)\n";
        echo "This suggests Pollinations and Wikimedia both failed.\n";
    } elseif (strpos($generatedImage, 'pollinations.ai') !== false) {
        echo "SUCCESS: Image from Pollinations AI\n";
    } elseif (strpos($generatedImage, 'wikimedia') !== false || strpos($generatedImage, 'commons.wikimedia') !== false) {
        echo "SUCCESS: Image from Wikimedia Commons\n";
    }
    
    echo "\n=== Test Complete ===\n";
    echo "Image URL: $generatedImage\n";
    
} catch (Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
    exit(1);
}
