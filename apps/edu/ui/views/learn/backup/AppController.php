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
            // 1024 tokens is enough for a detailed answer (~750 words). The old 4096
            // caused very long responses that exceeded nginx's 60s gateway timeout → 504.
            'max_tokens' => 1024,
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
        // 30s timeout — prevents hanging if Groq is slow; must finish before nginx's
        // gateway timeout (60-120s) to avoid a 504 that returns HTML instead of JSON
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

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
 
    public function generateAnswerAndImage($userInput, $imageQuery = null)
    {
        // --- Step 1: Get the AI answer first ---
        // We need the answer text BEFORE searching for an image, because keywords
        // extracted from a short question (e.g. "What's AI?") are too vague and
        // produce unrelated Wikimedia results. The answer text is rich with
        // topic-specific vocabulary that yields much better image matches.
        $textData = [
            'model' => GROQ_MODEL,
            'messages' => [
                ['role' => 'user', 'content' => $userInput]
            ],
            'temperature' => 1,
            // 1024 tokens ≈ 750 words — enough for a detailed answer without
            // exceeding nginx's gateway timeout
            'max_tokens' => 1024,
            'top_p' => 1
        ];
        $textHeaders = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . GROQ_API_KEY
        ];

        $chText = curl_init(GROQ_API_ENDPOINT);
        curl_setopt($chText, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chText, CURLOPT_POST, true);
        curl_setopt($chText, CURLOPT_POSTFIELDS, json_encode($textData));
        curl_setopt($chText, CURLOPT_HTTPHEADER, $textHeaders);
        curl_setopt($chText, CURLOPT_TIMEOUT, 30);

        $textResponse = curl_exec($chText);
        $answer = '';

        if (curl_errno($chText)) {
            $answer = json_encode(['error' => curl_error($chText)]);
        } else {
            $textJson = json_decode($textResponse, true);
            if (isset($textJson['choices'][0]['message']['content'])) {
                $answer = $textJson['choices'][0]['message']['content'];
            } else {
                $apiError = $textJson['error']['message'] ?? 'Unknown API error';
                $apiType  = $textJson['error']['type'] ?? 'unknown';
                $answer = json_encode([
                    'error' => $apiError,
                    'type'  => $apiType,
                    'hint'  => 'Check your Groq API key at https://console.groq.com'
                ]);
            }
        }
        curl_close($chText);

        // --- Step 2: Extract keywords from the ANSWER (not the question) ---
        // The answer contains rich, descriptive text about the topic. Extracting
        // keywords from it produces far more relevant image search terms than
        // a short question like "What's AI?" which yields almost nothing after
        // stop-word removal. Falls back to the question if the answer is an error.
        $keywordSource = $answer;
        // If the answer looks like a JSON error, fall back to the original question
        if (empty($answer) || $answer[0] === '{') {
            $keywordSource = $imageQuery ?? $userInput;
        }
        $keywords = $this->extractKeywords($keywordSource);

        // --- Step 3: Search Wikimedia for a relevant image ---
        $imageUrl = $this->searchWikimediaImage($keywords);

        // Fallback to placeholder if Wikimedia returned nothing
        if (empty($imageUrl)) {
            $imageUrl = "https://placehold.co/1024x630?text=" . urlencode($keywords);
        }

        return ['answer' => $answer, 'image' => $imageUrl];
    }

    public function generateImage($prompt)
    {
        // Extract keywords locally instead of calling Groq (saves 1-5s)
        $keywords = $this->extractKeywords($prompt);

        // Search Wikimedia Commons for a relevant image (free, no API key)
        $imageUrl = $this->searchWikimediaImage($keywords);

        // Fall back to a topic placeholder if Wikimedia returned nothing
        if (empty($imageUrl)) {
            $imageUrl = "https://placehold.co/1024x630?text=" . urlencode($keywords);
        }

        return $imageUrl;
    }

    /**
     * Extract 2-3 meaningful keywords from text using word frequency analysis.
     * For long text (AI answers), the most frequently repeated non-stop-words
     * are the actual topic terms — much better than just taking the first 3 words.
     * For short text (questions), falls back to first-3 meaningful words.
     * Instant (<1ms), no API call needed.
     */
    private function extractKeywords($prompt)
    {
        // Common English stop words that don't help image search — includes
        // question words, pronouns, prepositions, auxiliaries, and generic verbs
        $stopWords = ['what','whats','how','why','when','where','who','which','whom',
            'is','are','was','were','be','been','being','do','does','did',
            'the','a','an','and','or','but','in','on','at','to','for','of',
            'with','by','from','as','into','about','between','through',
            'can','could','would','should','will','shall','may','might',
            'have','has','had','not','no','its','it','this','that','these',
            'those','i','me','my','we','our','you','your','he','she','they',
            'tell','explain','describe','define','give','make','please',
            'definition','meaning','example','difference','also','known',
            'such','more','most','some','other','than','very','just','like',
            'well','even','much','many','each','every','both','all','any',
            'few','own','same','here','there','then','now','only','over',
            'after','before','while','during','may','include','includes',
            'including','refers','refer','based','using','used','use',
            'create','creating','created','one','two','three','new','way',
            'ways','however','although','often','still','another','become',
            'becomes','able','need','needs','help','helps','part','parts',
            'called','call','calls','allows','allow','various','several',
            'different','specific','particular','general','overall','key',
            'important','main','first','second','third','last','next'];

        // Strip punctuation, lowercase, split into words
        $clean = preg_replace('/[^a-zA-Z0-9\s]/', '', strtolower($prompt));
        $words = preg_split('/\s+/', trim($clean));

        // Filter to meaningful words only (not stop words, at least 3 chars)
        $meaningful = [];
        foreach ($words as $w) {
            if (strlen($w) >= 3 && !in_array($w, $stopWords)) {
                $meaningful[] = $w;
            }
        }

        // For long text (AI answers): pick the top 3 most frequent words.
        // Repeated words in an answer are strong topic signals — e.g. an answer
        // about "photosynthesis" will mention "photosynthesis", "plant", "light"
        // many times, while generic words appear only once or twice.
        if (count($meaningful) > 10) {
            $freq = array_count_values($meaningful);
            arsort($freq);
            // Take the top 3 most frequent meaningful words
            $keywords = array_slice(array_keys($freq), 0, 3);
        } else {
            // Short text (questions): just take the first 3 meaningful words
            $keywords = array_slice($meaningful, 0, 3);
        }

        // Fallback: if no keywords survived filtering, use first 3 raw words
        if (empty($keywords)) {
            $keywords = array_slice($words, 0, 3);
        }

        return implode(' ', $keywords);
    }

    /**
     * Searches Wikimedia Commons for a topic-relevant image using their public API.
     * Returns the thumbnail URL (1024px wide) or empty string on failure.
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
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        // Wikimedia requires a User-Agent header for API requests
        curl_setopt($ch, CURLOPT_USERAGENT, "WheelderApp/1.0");

        $response = curl_exec($ch);

        // If cURL fails, return empty so the caller can use the fallback
        if (curl_errno($ch)) {
            curl_close($ch);
            return "";
        }

        curl_close($ch);

        return $this->parseWikimediaResponse($response);
    }

    /**
     * Parse a Wikimedia API JSON response and return the first valid image URL.
     * Extracted into its own method so both generateImage() and generateAnswerAndImage()
     * can reuse the same parsing logic without duplication.
     */
    private function parseWikimediaResponse($responseJson)
    {
        $data = json_decode($responseJson, true);

        if (!isset($data['query']['pages'])) {
            return '';
        }

        // Loop through results to find an actual photo (skip PDFs, SVGs, etc.)
        foreach ($data['query']['pages'] as $page) {
            if (!isset($page['imageinfo'][0])) continue;
            $info = $page['imageinfo'][0];
            $mime = $info['mime'] ?? '';

            // Only accept actual image files (JPEG, PNG, WebP)
            if (strpos($mime, 'image/jpeg') === false
                && strpos($mime, 'image/png') === false
                && strpos($mime, 'image/webp') === false) {
                continue;
            }

            // Prefer the resized thumbnail (1024px wide)
            if (isset($info['thumburl'])) return $info['thumburl'];
            if (isset($info['url']))      return $info['url'];
        }

        return '';
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