<?php
// Use __DIR__ for reliable paths regardless of DOCUMENT_ROOT (works on XAMPP and production)
require_once __DIR__ . '/../../../../../../pool/libs/controllers/Controller.php';

// Load API keys from a separate config file so secrets stay out of version control.
// config.local.php returns an associative array with GROQ_API_KEY, GROQ_API_ENDPOINT, GROQ_MODEL.
// See config.local.php for setup instructions.
$_configPath = __DIR__ . '/config.local.php';
if (!file_exists($_configPath)) {
    die('Missing config.local.php — copy the template and add your API keys. See README.');
}
$_config = require $_configPath;

// Define constants from the config file so the rest of the code works unchanged
define('GROQ_API_ENDPOINT', $_config['GROQ_API_ENDPOINT'] ?? '');
define('GROQ_API_KEY',      $_config['GROQ_API_KEY']      ?? '');
define('GROQ_MODEL',        $_config['GROQ_MODEL']        ?? '');
define('DEMO_ACCESS_KEY',   $_config['DEMO_ACCESS_KEY']   ?? '');

// Safety check — catch missing keys early with a clear message
if (empty(GROQ_API_KEY)) {
    die('GROQ_API_KEY is empty in config.local.php — get your free key at https://console.groq.com');
}

class AppController extends Controller
{
    // Groq API key stored as class property for convenience
    private $api_key = GROQ_API_KEY;

    public function check_auth()
    {
        // Check if the session is not set
        if (!isset($_SESSION['user_id'])) {
            // Store the current page URL in a session variable
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];

            // Redirect the user to the login page or any other desired page
            header('Location: /index.php'); // Replace "login.php" with the desired URL
            exit;
        }
    }
    
    public function generateResponse($userInput)
    {
        // Using Groq API — same format as OpenAI (OpenAI-compatible)
        $data = [
            'model' => GROQ_MODEL,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $userInput
                ]
            ],
            'temperature' => 1,
            'max_tokens' => 4096,
            'top_p' => 1
        ];

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . GROQ_API_KEY
        ];

        $ch = curl_init(GROQ_API_ENDPOINT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return json_encode(['error' => curl_error($ch)]);
        }

        curl_close($ch);

        $responseData = json_decode($response, true);

        // Groq returns the same JSON structure as OpenAI
        if (isset($responseData['choices'][0]['message']['content'])) {
            return $responseData['choices'][0]['message']['content'];
        } else {
            // Show the actual API error so we can diagnose (e.g. invalid key, rate limit)
            $apiError = $responseData['error']['message'] ?? 'Unknown API error';
            $apiType  = $responseData['error']['type'] ?? 'unknown';
            return json_encode([
                'error' => $apiError,
                'type'  => $apiType,
                'hint'  => 'Check your Groq API key at https://console.groq.com'
            ]);
        }
    }
 
    public function generateImage($prompt)
    {
        // Step 1: Ask Groq to turn the question into short image-search keywords
        // e.g. "What's AI?" → "artificial intelligence,robot,technology"
        // This makes the image relevant to the topic (like the old formulaic prompt approach)
        $keywords = $this->generateImageKeywords($prompt);

        // Step 2: Search Wikimedia Commons for a relevant image (free, no API key)
        $imageUrl = $this->searchWikimediaImage($keywords);

        // Step 3: If Wikimedia fails, fall back to a topic placeholder
        if (empty($imageUrl)) {
            $imageUrl = "https://placehold.co/1024x630?text=" . urlencode($keywords);
        }

        return $imageUrl;
    }

    /**
     * Searches Wikimedia Commons for a topic-relevant image using their public API
     * Returns the thumbnail URL (1024px wide) or empty string on failure
     */
    private function searchWikimediaImage($keywords)
    {
        // Build the Wikimedia Commons API URL — search in File namespace (ns=6)
        // Fetch 10 results so we can skip non-photo files (PDFs, SVGs, documents)
        $apiUrl = "https://commons.wikimedia.org/w/api.php?"
            . "action=query"
            . "&generator=search"
            . "&gsrsearch=" . urlencode($keywords)
            . "&gsrnamespace=6"
            . "&gsrlimit=10"
            . "&prop=imageinfo"
            . "&iiprop=url|mime"
            . "&iiurlwidth=1024"
            . "&format=json";

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 8);
        // Wikimedia requires a User-Agent header for API requests
        curl_setopt($ch, CURLOPT_USERAGENT, "WheelderApp/1.0");

        $response = curl_exec($ch);

        // If cURL fails, return empty so the caller can use the fallback
        if (curl_errno($ch)) {
            curl_close($ch);
            return "";
        }

        curl_close($ch);

        $data = json_decode($response, true);

        // Loop through results to find an actual photo (skip PDFs, SVGs, etc.)
        if (isset($data['query']['pages'])) {
            foreach ($data['query']['pages'] as $page) {
                if (!isset($page['imageinfo'][0])) {
                    continue;
                }
                $info = $page['imageinfo'][0];
                $mime = $info['mime'] ?? '';

                // Only accept actual image files (JPEG, PNG, WebP)
                if (strpos($mime, 'image/jpeg') === false
                    && strpos($mime, 'image/png') === false
                    && strpos($mime, 'image/webp') === false) {
                    continue;
                }

                // Prefer the resized thumbnail (1024px wide)
                if (isset($info['thumburl'])) {
                    return $info['thumburl'];
                }
                // Fall back to the original full-size URL
                if (isset($info['url'])) {
                    return $info['url'];
                }
            }
        }

        return "";
    }

    /**
     * Uses Groq to convert a user question into 3-5 descriptive image keywords
     * so the image search returns relevant results
     */
    private function generateImageKeywords($prompt)
    {
        // Ask the LLM to extract visual keywords from the question
        $data = [
            'model' => GROQ_MODEL,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a keyword extractor for image search. Given a question, reply with ONLY 2 to 3 simple space-separated words that would find a relevant photograph. No commas, no sentences, no academic terms. Example: "What is AI?" → "robot technology", "Tell me about space travel" → "space rocket launch", "What is automation?" → "industrial robot factory"'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.3,
            'max_tokens' => 50
        ];

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . GROQ_API_KEY
        ];

        $ch = curl_init(GROQ_API_ENDPOINT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        $response = curl_exec($ch);

        // If Groq fails, fall back to the raw prompt words as keywords
        if (curl_errno($ch)) {
            curl_close($ch);
            return trim($prompt);
        }

        curl_close($ch);

        $responseData = json_decode($response, true);

        // Extract the keywords from Groq's response
        if (isset($responseData['choices'][0]['message']['content'])) {
            return trim($responseData['choices'][0]['message']['content']);
        }

        // Fallback: use the original prompt if Groq didn't return keywords
        return trim($prompt);
    }

    //store data in the MySQL database in questions table 
    



    function storeData($questions, $answers, $images) {
        try {
            // Use __DIR__ so the DB file resolves relative to this file, not the CWD
            $db = new PDO('sqlite:' . __DIR__ . '/database.sqlite');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Auto-create table if it doesn't exist (first-run safety)
            $db->exec("CREATE TABLE IF NOT EXISTS ans_data (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                questions TEXT,
                answers TEXT,
                images TEXT,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            )");
    
            // Escape and quote strings for safe use with exec (not recommended for user input)
            $questions = $db->quote($questions);
            $answers = $db->quote($answers);
            $images = $db->quote($images);
    
            // Build and execute the query
            $query = "INSERT INTO ans_data (questions, answers, images) VALUES ($questions, $answers, $images)";
            $db->exec($query);
    
           // echo "Data inserted successfully!";
        } catch (PDOException $e) {
            echo "Insert failed: " . $e->getMessage();
        }
    }

    /**
     * Store a conversation entry (question + answer + image) grouped by session_id.
     * Each "Ask" creates a new session; each "Deepen" appends to the same session.
     */
    function storeConversation($sessionId, $question, $answer, $image, $depthLevel = 0) {
        try {
            $db = new PDO('sqlite:' . __DIR__ . '/database.sqlite');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Auto-create table on first run — session_id groups entries that belong together
            $db->exec("CREATE TABLE IF NOT EXISTS conversations (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                session_id TEXT NOT NULL,
                question TEXT,
                answer TEXT,
                image TEXT,
                depth_level INTEGER DEFAULT 0,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            )");

            // Index on session_id for fast sidebar lookups
            $db->exec("CREATE INDEX IF NOT EXISTS idx_conv_session ON conversations(session_id)");

            // Use prepared statement to prevent SQL injection
            $stmt = $db->prepare("INSERT INTO conversations (session_id, question, answer, image, depth_level) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$sessionId, $question, $answer, $image, $depthLevel]);

        } catch (PDOException $e) {
            error_log("storeConversation failed: " . $e->getMessage());
        }
    }

    /**
     * Get all unique conversation sessions for the sidebar list.
     * Returns the first question of each session as the label, ordered newest first.
     */
    function getConversations() {
        try {
            $db = new PDO('sqlite:' . __DIR__ . '/database.sqlite');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Auto-create table so the query doesn't fail on a fresh install
            $db->exec("CREATE TABLE IF NOT EXISTS conversations (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                session_id TEXT NOT NULL,
                question TEXT,
                answer TEXT,
                image TEXT,
                depth_level INTEGER DEFAULT 0,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            )");

            // Pick the first entry per session (MIN id) to use its question as the sidebar label
            $stmt = $db->query("
                SELECT c.session_id, c.question, c.created_at
                FROM conversations c
                INNER JOIN (
                    SELECT session_id, MIN(id) AS first_id
                    FROM conversations
                    GROUP BY session_id
                ) g ON c.id = g.first_id
                ORDER BY c.created_at DESC
            ");

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("getConversations failed: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all entries for a specific conversation session, ordered by depth level.
     */
    function getConversationById($sessionId) {
        try {
            $db = new PDO('sqlite:' . __DIR__ . '/database.sqlite');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Use prepared statement to prevent injection via session_id
            $stmt = $db->prepare("SELECT * FROM conversations WHERE session_id = ? ORDER BY depth_level ASC");
            $stmt->execute([$sessionId]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("getConversationById failed: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get the next depth_level for a conversation thread.
     * Used when a follow-up question is added to an existing session — ensures entries stay ordered.
     * Returns MAX(depth_level) + 1, or 0 if the session has no entries yet.
     */
    function getNextDepthLevel($sessionId) {
        try {
            $db = new PDO('sqlite:' . __DIR__ . '/database.sqlite');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $db->prepare("SELECT COALESCE(MAX(depth_level), -1) + 1 AS next_level FROM conversations WHERE session_id = ?");
            $stmt->execute([$sessionId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return (int)($row['next_level'] ?? 0);
        } catch (PDOException $e) {
            error_log("getNextDepthLevel failed: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Soft-delete a conversation by moving it from 'conversations' to 'archived_conversations'.
     * The $status param distinguishes between 'archived' and 'deleted' so both are recoverable.
     * Returns true on success, throws Exception on failure so the caller can return a clean error.
     */
    function archiveConversation($sessionId, $status = 'archived') {
        try {
            $db = new PDO('sqlite:' . __DIR__ . '/database.sqlite');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Create the archive table if it doesn't exist — mirrors conversations + status column
            $db->exec("CREATE TABLE IF NOT EXISTS archived_conversations (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                session_id TEXT NOT NULL,
                question TEXT,
                answer TEXT,
                image TEXT,
                depth_level INTEGER DEFAULT 0,
                status TEXT DEFAULT 'archived',
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                archived_at TEXT DEFAULT CURRENT_TIMESTAMP
            )");

            // Index on session_id for potential future "restore" lookups
            $db->exec("CREATE INDEX IF NOT EXISTS idx_arch_session ON archived_conversations(session_id)");

            // Validate status to prevent injection — only two allowed values
            if (!in_array($status, ['archived', 'deleted'], true)) {
                throw new Exception('Invalid status value.');
            }

            // Validate session_id is not empty
            if (empty($sessionId)) {
                throw new Exception('Missing session_id.');
            }

            // Use a transaction so the copy + delete is atomic — no orphaned data
            $db->beginTransaction();

            // Copy all entries for this session into the archive table
            $stmt = $db->prepare("
                INSERT INTO archived_conversations (session_id, question, answer, image, depth_level, status, created_at)
                SELECT session_id, question, answer, image, depth_level, ?, created_at
                FROM conversations
                WHERE session_id = ?
            ");
            $stmt->execute([$status, $sessionId]);

            // Check that rows were actually copied — prevents deleting from conversations if session_id was invalid
            if ($stmt->rowCount() === 0) {
                $db->rollBack();
                throw new Exception('No conversation found with that session_id.');
            }

            // Remove from the active conversations table so it disappears from the sidebar
            $del = $db->prepare("DELETE FROM conversations WHERE session_id = ?");
            $del->execute([$sessionId]);

            $db->commit();
            return true;

        } catch (PDOException $e) {
            if (isset($db) && $db->inTransaction()) {
                $db->rollBack();
            }
            error_log("archiveConversation failed: " . $e->getMessage());
            throw new Exception('Failed to archive conversation.');
        }
    }

    function getAllData() {
        try {
            // Use __DIR__ so the DB file resolves relative to this file, not the CWD
            $db = new PDO('sqlite:' . __DIR__ . '/database.sqlite');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Auto-create table if it doesn't exist (first-run safety)
            $db->exec("CREATE TABLE IF NOT EXISTS ans_data (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                questions TEXT,
                answers TEXT,
                images TEXT,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            )");
    
            // Query to select all data
            $query = "SELECT * FROM ans_data";
            $stmt = $db->query($query);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Display the data
            if ($results) {
                foreach ($results as $row) {
                    echo "ID: " . $row['id'] . "<br>";
                    echo "Questions: " . $row['questions'] . "<br>";
                    echo "Answers: " . $row['answers'] . "<br>";
                    echo "Images: " . $row['images'] . "<br>";
                    echo "<hr>";
                }
            } else {
                echo "No data found.";
            }
        } catch (PDOException $e) {
            echo "Select failed: " . $e->getMessage();
        }
    }


}