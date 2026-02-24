<?php
// Only load Controller.php if it hasn't been declared yet — prevents fatal
// "class Controller already declared" when cms2/ajax.php loads LessonController
// (which includes apps/edu/controllers/Controller.php) before this file.
if (!class_exists('Controller')) {
    // Center lives one directory higher than /learn/backup, so the relative path
    // to pool/libs shrinks by one ../ to avoid resolving outside the project root.
    require_once __DIR__ . '/../../../../../pool/libs/controllers/Controller.php';
}

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
    private $dbPath;

    /**
     * Ensure the center database exists — on fresh deploys the SQLite file is not committed,
     * so we copy the seeded learn/backup DB the first time it is needed.
     */
    private function getDatabasePath(): string
    {
        if (!empty($this->dbPath)) {
            return $this->dbPath;
        }

        $dbPath = __DIR__ . '/database.sqlite';

        if (!file_exists($dbPath)) {
            $fallbackPath = dirname(__DIR__) . '/learn/backup/database.sqlite';
            if (file_exists($fallbackPath)) {
                // Copying preserves the seeded "What's AGI?" research so prod matches local.
                if (!@copy($fallbackPath, $dbPath)) {
                    error_log('Failed to copy fallback database into center context.');
                }
            } else {
                // Touch guarantees PDO can open the file even if no fallback exists.
                touch($dbPath);
            }
        }

        $this->dbPath = $dbPath;
        return $this->dbPath;
    }

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

        // --- Step 2: Summarize the answer into 1-2 focused sentences ---
        // The full answer is too long and noisy for image prompt generation.
        // A concise summary strips away filler, examples, and caveats, giving
        // the image-prompt step a clean, focused description of the core topic.
        $summary = '';
        if (!empty($answer) && $answer[0] !== '{') {
            $summary = $this->summarizeAnswer($answer);
        }

        // --- Step 3: Translate summary into an AI image generation prompt ---
        // The LLM converts the summary into a descriptive visual prompt suitable
        // for Pollinations AI (e.g. "photosynthesis diagram showing sunlight
        // hitting green leaf cells"). Falls back to keyword extraction on failure.
        $imagePrompt = '';
        if (!empty($summary)) {
            $imagePrompt = $this->answerToImagePrompt($summary);
        }
        // Fallback: if summarization or prompt generation failed
        if (empty($imagePrompt)) {
            $fallbackSource = $imageQuery ?? $userInput;
            $imagePrompt = $this->extractKeywords($fallbackSource);
        }

        // --- Step 4: Generate an image via Pollinations AI ---
        // Pollinations generates a custom image from the prompt — no API key needed.
        $imageUrl = $this->generatePollinationsImage($imagePrompt);
        
        // --- Step 5: Fallback to Wikimedia Commons if Pollinations failed or returned empty ---
        // Pollinations may fail silently or return a broken URL. Wikimedia is a reliable fallback.
        if (empty($imageUrl) || strpos($imageUrl, 'placehold') !== false) {
            $imageUrl = $this->searchWikimediaImage($imagePrompt);
        }
        
        // --- Step 6: Final fallback to placeholder if all else fails ---
        if (empty($imageUrl)) {
            $imageUrl = "https://placehold.co/1024x630?text=" . urlencode(mb_substr($imagePrompt, 0, 50));
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
     * Summarize an AI-generated answer into 1-2 concise sentences.
     *
     * Why summarize first? The full answer (500-750 words) contains examples,
     * caveats, and filler that confuse the image-prompt step. A tight summary
     * gives answerToImagePrompt() a clean, focused description of the core topic,
     * producing much more relevant image generation prompts.
     *
     * Uses llama-3.1-8b-instant (fast, cheap) with max_tokens=80.
     * Returns empty string on failure so the caller can fall back.
     */
    private function summarizeAnswer($answerText)
    {
        // Truncate to ~800 chars — enough context for a good summary
        // without wasting tokens on the full answer
        $snippet = mb_substr($answerText, 0, 800);

        $data = [
            'model' => 'llama-3.1-8b-instant',
            'messages' => [
                [
                    'role' => 'system',
                    // Force a tight, factual summary — no preamble or meta-commentary
                    'content' => 'Summarize the following text in exactly 1-2 sentences. Focus on the main topic and key concept. Output ONLY the summary, nothing else.'
                ],
                [
                    'role' => 'user',
                    'content' => $snippet
                ]
            ],
            'temperature' => 0,
            'max_tokens' => 80,
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
        // Short timeout — summary is a helper step, not critical
        curl_setopt($ch, CURLOPT_TIMEOUT, 8);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return '';
        }
        curl_close($ch);

        $json = json_decode($response, true);

        if (!isset($json['choices'][0]['message']['content'])) {
            return '';
        }

        return trim($json['choices'][0]['message']['content']);
    }

    /**
     * Use the LLM to convert a summary into a descriptive image generation prompt
     * suitable for Pollinations AI.
     *
     * Unlike Wikimedia search (which needs short keyword phrases), Pollinations
     * works best with descriptive visual prompts like "detailed diagram of
     * photosynthesis showing sunlight hitting green leaf cells". The LLM
     * understands what would make a good visual and crafts the prompt accordingly.
     *
     * Uses llama-3.1-8b-instant with max_tokens=60 and temperature=0.
     * Returns empty string on failure so the caller can fall back to extractKeywords().
     */
    private function answerToImagePrompt($summaryText)
    {
        $data = [
            'model' => 'llama-3.1-8b-instant',
            'messages' => [
                [
                    'role' => 'system',
                    // Prompt engineered for Pollinations AI — descriptive visual scene,
                    // not just keywords. "educational illustration" steers toward
                    // clean diagrams rather than photorealistic noise.
                    'content' => 'You are an AI image prompt writer. Given a summary, output ONLY a short descriptive prompt (8-15 words) for generating an educational illustration or diagram of the main topic. The prompt should describe what the image should visually show. Output NOTHING else — no quotes, no explanation. Just the image prompt.'
                ],
                [
                    'role' => 'user',
                    'content' => $summaryText
                ]
            ],
            'temperature' => 0,
            // 60 tokens for a descriptive 8-15 word prompt
            'max_tokens' => 60,
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
        curl_setopt($ch, CURLOPT_TIMEOUT, 8);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return '';
        }
        curl_close($ch);

        $json = json_decode($response, true);

        if (!isset($json['choices'][0]['message']['content'])) {
            return '';
        }

        // Clean up — strip quotes, newlines, extra whitespace
        $prompt = trim($json['choices'][0]['message']['content']);
        $prompt = trim($prompt, '"\'\'');
        $prompt = preg_replace('/\s+/', ' ', $prompt);

        // Safety: discard if too long (hallucinated paragraph) or too short
        if (strlen($prompt) > 200 || strlen($prompt) < 5) {
            return '';
        }

        return $prompt;
    }

    /**
     * Generate an image URL using Pollinations AI (free, no API key required).
     *
     * Pollinations works via a simple GET URL: the prompt is URL-encoded in the path.
     * The service generates a unique image on-the-fly and returns it as a JPEG.
     * We add a seed based on the prompt hash so the same prompt always returns
     * the same image (deterministic, cacheable).
     *
     * @param string $prompt  Descriptive image generation prompt
     * @return string         Full Pollinations image URL
     */
    private function generatePollinationsImage($prompt)
    {
        // Append quality keywords to the prompt — steers the AI model toward
        // crisp, detailed output instead of soft/painterly/blurry styles.
        // These are standard Stable Diffusion quality tokens that Pollinations supports.
        $enhancedPrompt = $prompt . ', high quality, sharp focus, detailed, 4k';

        // URL-encode the prompt for safe use in the URL path
        $encodedPrompt = urlencode($enhancedPrompt);

        // Use a hash-based seed so the same prompt always generates the same image
        // (deterministic output, avoids confusion if the user reloads)
        $seed = crc32($prompt);

        // Pollinations API: 2048x1260 (2x the old 1024x630) for sharp rendering
        // on Retina/HiDPI displays. Same 1.63:1 aspect ratio as the panel.
        // enhance=true applies server-side upscaling/sharpening.
        // nologo=true removes the Pollinations watermark.
        return "https://image.pollinations.ai/prompt/{$encodedPrompt}?width=2048&height=1260&seed={$seed}&nologo=true&enhance=true";
    }

    /**
     * Extract 2-3 meaningful keywords from text using word frequency analysis.
     * For long text (AI answers), the most frequently repeated non-stop-words
     * are the actual topic terms — much better than just taking the first 3 words.
     * For short text (questions), falls back to first 3 meaningful words.
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

    /**
     * Store data in the MySQL database in questions table 
     */
    function storeData($questions, $answers, $images) {
        try {
            // Use __DIR__ so the DB file resolves relative to this file, not the CWD
            $db = new PDO('sqlite:' . $this->getDatabasePath());
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Retry for up to 5s if another connection holds a lock (prevents "database is locked")
            $db->exec('PRAGMA busy_timeout = 5000');
            // WAL mode allows concurrent readers + one writer without blocking
            $db->exec('PRAGMA journal_mode = WAL');

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
     * Persist an Ask/Deepen response so the sidebar loads instantly after refresh.
     * WHY: production was missing this function, so ajax_handler.php died at line 232.
     */
    function storeConversation($sessionId, $question, $answer, $image, $depthLevel = 0)
    {
        if (empty($sessionId)) {
            throw new InvalidArgumentException('session_id is required for storeConversation.');
        }

        try {
            $db = new PDO('sqlite:' . $this->getDatabasePath());
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->exec('PRAGMA busy_timeout = 5000');
            $db->exec('PRAGMA journal_mode = WAL');

            // Ensure table + index exist so first-run deploys don't fail to insert
            $db->exec("CREATE TABLE IF NOT EXISTS conversations (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                session_id TEXT NOT NULL,
                question TEXT,
                answer TEXT,
                image TEXT,
                depth_level INTEGER DEFAULT 0,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            )");
            $db->exec("CREATE INDEX IF NOT EXISTS idx_conv_session ON conversations(session_id)");

            $stmt = $db->prepare("INSERT INTO conversations (session_id, question, answer, image, depth_level)
                                  VALUES (?, ?, ?, ?, ?)");
            if (!$stmt->execute([$sessionId, $question, $answer, $image, $depthLevel])) {
                throw new RuntimeException('Failed to insert conversation row.');
            }
        } catch (Exception $e) {
            // Log full error for diagnosis but bubble a clean exception to the caller
            error_log('storeConversation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Persist a regenerated image for an existing conversation row so reloads are not blank.
     */
    function updateConversationImage($rowId, $imageUrl)
    {
        if (empty($rowId) || empty($imageUrl)) {
            throw new InvalidArgumentException('Conversation row id and image URL are required.');
        }

        try {
            $db = new PDO('sqlite:' . $this->getDatabasePath());
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->exec('PRAGMA busy_timeout = 5000');
            $db->exec('PRAGMA journal_mode = WAL');

            $stmt = $db->prepare('UPDATE conversations SET image = ? WHERE id = ?');
            if (!$stmt->execute([$imageUrl, $rowId])) {
                throw new RuntimeException('Failed to update conversation image.');
            }
        } catch (Exception $e) {
            error_log('updateConversationImage failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get all unique conversation sessions for the sidebar list.
     * Returns the first question of each session as the label, ordered newest first.
     */
    function getConversations() {
        try {
            $db = new PDO('sqlite:' . $this->getDatabasePath());
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->exec('PRAGMA busy_timeout = 5000');
            $db->exec('PRAGMA journal_mode = WAL');

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
            $db = new PDO('sqlite:' . $this->getDatabasePath());
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->exec('PRAGMA busy_timeout = 5000');
            $db->exec('PRAGMA journal_mode = WAL');

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
            $db = new PDO('sqlite:' . $this->getDatabasePath());
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->exec('PRAGMA busy_timeout = 5000');
            $db->exec('PRAGMA journal_mode = WAL');

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
            $db = new PDO('sqlite:' . $this->getDatabasePath());
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Retry for up to 5s if another connection holds a lock (prevents "database is locked")
            $db->exec('PRAGMA busy_timeout = 5000');
            // WAL mode allows concurrent readers + one writer without blocking
            $db->exec('PRAGMA journal_mode = WAL');

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
            // Log the full PDO error so we can diagnose DB issues from the error log
            error_log("archiveConversation PDO error: " . $e->getMessage() . " | code: " . $e->getCode() . " | trace: " . $e->getTraceAsString());
            throw new Exception('Failed to archive conversation: ' . $e->getMessage(), 0, $e);
        }
    }

    function getAllData() {
        try {
            // Use getDatabasePath() so fresh deploys auto-copy the seeded DB from backup
            $db = new PDO('sqlite:' . $this->getDatabasePath());
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->exec('PRAGMA busy_timeout = 5000');
            $db->exec('PRAGMA journal_mode = WAL');

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