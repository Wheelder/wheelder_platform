<?php
$path = $_SERVER['DOCUMENT_ROOT'];
$host = $_SERVER['HTTP_HOST'];

if ($host === "localhost") {
    $dir = '/wheelder';
    
    require_once $path . $dir . '/apps/edu/controllers/Controller.php';
} else {
    require_once $path . '/apps/edu/controllers/Controller.php';
}

require_once __DIR__ . '/config.php';

class LearnController extends Controller
{
    private $apiKey;
    private $imageApiKey;
    private $rateLimit = [];
    private $maxRequestsPerMinute = 10;

    public function __construct()
    {
        parent::__construct();
        $this->apiKey = OPENAI_API_KEY;
        $this->imageApiKey = OPENAI_IMAGE_API_KEY;
        
        // Initialize rate limiting
        if (!isset($_SESSION['learn_rate_limit'])) {
            $_SESSION['learn_rate_limit'] = [];
        }
    }

    /**
     * Check if user is authenticated
     */
    public function checkAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
            header('Location: /index.php');
            exit;
        }
    }

    /**
     * Validate and sanitize user input
     */
    public function validateInput($input)
    {
        if (empty(trim($input))) {
            return ['valid' => false, 'error' => ERROR_MESSAGES['invalid_input']];
        }

        if (strlen($input) > MAX_QUERY_LENGTH) {
            return ['valid' => false, 'error' => 'Question is too long. Please keep it under ' . MAX_QUERY_LENGTH . ' characters.'];
        }

        // Basic XSS protection
        $cleanInput = htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
        
        return ['valid' => true, 'data' => $cleanInput];
    }

    /**
     * Check rate limiting
     */
    public function checkRateLimit()
    {
        $currentTime = time();
        $userIP = $_SERVER['REMOTE_ADDR'];
        
        // Clean old entries
        $_SESSION['learn_rate_limit'] = array_filter(
            $_SESSION['learn_rate_limit'], 
            function($entry) use ($currentTime) {
                return ($currentTime - $entry['time']) < 60;
            }
        );

        // Count requests in last minute
        $recentRequests = array_filter(
            $_SESSION['learn_rate_limit'], 
            function($entry) use ($userIP, $currentTime) {
                return $entry['ip'] === $userIP && ($currentTime - $entry['time']) < 60;
            }
        );

        if (count($recentRequests) >= $this->maxRequestsPerMinute) {
            return false;
        }

        // Add current request
        $_SESSION['learn_rate_limit'][] = [
            'ip' => $userIP,
            'time' => $currentTime
        ];

        return true;
    }

    /**
     * Generate AI response using OpenAI API
     */
    public function generateResponse($userInput)
    {
        if (!$this->checkRateLimit()) {
            return ['error' => ERROR_MESSAGES['rate_limit']];
        }

        $data = [
            'model' => DEFAULT_MODEL,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $userInput
                ]
            ],
            'temperature' => DEFAULT_TEMPERATURE,
            'max_tokens' => MAX_TOKENS,
            'top_p' => 1,
            'frequency_penalty' => 0,
            'presence_penalty' => 0
        ];

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ];

        $ch = curl_init(OPENAI_API_ENDPOINT);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            curl_close($ch);
            return ['error' => 'cURL Error: ' . curl_error($ch)];
        }

        curl_close($ch);

        if ($httpCode !== 200) {
            return ['error' => 'API Error: HTTP ' . $httpCode];
        }

        $responseData = json_decode($response, true);

        if (isset($responseData['choices'][0]['message']['content'])) {
            return ['success' => true, 'content' => $responseData['choices'][0]['message']['content']];
        } else {
            return ['error' => 'Invalid response format from API'];
        }
    }

    /**
     * Generate image using OpenAI DALL-E API
     */
    public function generateImage($prompt)
    {
        if (!$this->checkRateLimit()) {
            return ['error' => ERROR_MESSAGES['rate_limit']];
        }

        // Clean and optimize prompt for image generation
        $cleanPrompt = $this->optimizeImagePrompt($prompt);

        $data = [
            'model' => DEFAULT_IMAGE_MODEL,
            'prompt' => $cleanPrompt,
            'n' => 1,
            'size' => DEFAULT_IMAGE_SIZE,
            'quality' => 'standard',
            'style' => 'natural'
        ];

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->imageApiKey
        ];

        $ch = curl_init(OPENAI_IMAGE_ENDPOINT);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_CONNECTTIMEOUT => 15
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            curl_close($ch);
            return ['error' => 'cURL Error: ' . curl_error($ch)];
        }

        curl_close($ch);

        if ($httpCode !== 200) {
            return ['error' => 'Image API Error: HTTP ' . $httpCode];
        }

        $responseData = json_decode($response, true);

        if (isset($responseData['data'][0]['url'])) {
            return ['success' => true, 'url' => $responseData['data'][0]['url']];
        } else {
            return ['error' => 'No image URL found in response'];
        }
    }

    /**
     * Optimize prompt for image generation
     */
    private function optimizeImagePrompt($prompt)
    {
        // Remove common words that don't help with image generation
        $removeWords = [
            'what', 'is', 'the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for',
            'of', 'with', 'by', 'from', 'up', 'about', 'into', 'through', 'during', 'before',
            'after', 'above', 'below', 'between', 'among', 'within', 'without', 'against',
            'toward', 'towards', 'upon', 'beside', 'behind', 'beneath', 'beyond', 'across'
        ];

        $words = explode(' ', strtolower($prompt));
        $filteredWords = array_filter($words, function($word) use ($removeWords) {
            return !in_array($word, $removeWords) && strlen($word) > 2;
        });

        return ucfirst(implode(' ', $filteredWords));
    }

    /**
     * Format AI response for display
     */
    public function formatResponse($response)
    {
        if (isset($response['error'])) {
            return '<div class="alert alert-danger">' . htmlspecialchars($response['error']) . '</div>';
        }

        if (!isset($response['content'])) {
            return '<div class="alert alert-warning">No content received from AI.</div>';
        }

        $content = $response['content'];
        
        // Split into sentences for better readability
        $sentences = preg_split('/(?<=[.?!])\s+(?=[A-Z])/', $content);
        $formattedContent = implode('<br><br>', array_map('trim', $sentences));
        
        return '<div class="ai-response">' . $formattedContent . '</div>';
    }

    /**
     * Store question and answer in database
     */
    public function storeQuestion($question, $answer, $imageUrl = null)
    {
        try {
            $question = $this->connectDb()->real_escape_string($question);
            $answer = $this->connectDb()->real_escape_string($answer);
            $imageUrl = $imageUrl ? $this->connectDb()->real_escape_string($imageUrl) : '';
            
            $sql = "INSERT INTO questions (question, answer, image, created_at) VALUES (?, ?, ?, NOW())";
            $stmt = $this->connectDb()->prepare($sql);
            
            if ($stmt) {
                $stmt->bind_param("sss", $question, $answer, $imageUrl);
                $result = $stmt->execute();
                $stmt->close();
                return $result;
            }
            
            return false;
        } catch (Exception $e) {
            error_log("Error storing question: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get recent questions
     */
    public function getRecentQuestions($limit = 5)
    {
        try {
            $sql = "SELECT question, answer, image, created_at FROM questions ORDER BY created_at DESC LIMIT ?";
            $stmt = $this->connectDb()->prepare($sql);
            
            if ($stmt) {
                $stmt->bind_param("i", $limit);
                $stmt->execute();
                $result = $stmt->get_result();
                $questions = $result->fetch_all(MYSQLI_ASSOC);
                $stmt->close();
                return $questions;
            }
            
            return [];
        } catch (Exception $e) {
            error_log("Error getting recent questions: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Generate CSRF token
     */
    public function generateCSRFToken()
    {
        if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
            $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        }
        return $_SESSION[CSRF_TOKEN_NAME];
    }

    /**
     * Verify CSRF token
     */
    public function verifyCSRFToken($token)
    {
        return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
    }
}
?>
