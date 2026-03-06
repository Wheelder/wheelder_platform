<?php
/**
 * CircularController - Meta-AI Orchestrator
 * 
 * Routes user queries to the best AI tools and coordinates multi-tool workflows
 * Acts as the central hub connecting all AI platforms
 */

require_once __DIR__ . '/../learn/backup/AppController.php';

class CircularController extends AppController
{
    private $db;
    private $dbPath;
    
    public function __construct()
    {
        parent::__construct();
        $this->dbPath = __DIR__ . '/circular.db';
        $this->initDatabase();
    }
    
    /**
     * Initialize Circular database with schema
     */
    private function initDatabase()
    {
        try {
            // Use PDO instead of SQLite3 for better compatibility
            $this->db = new PDO('sqlite:' . $this->dbPath);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Sessions table
            $this->db->exec("CREATE TABLE IF NOT EXISTS circular_sessions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER,
                title TEXT,
                query TEXT,
                query_type TEXT,
                complexity TEXT,
                status TEXT DEFAULT 'active',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )");
            
            // Messages table
            $this->db->exec("CREATE TABLE IF NOT EXISTS circular_messages (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                session_id INTEGER,
                role TEXT,
                content TEXT,
                tool_name TEXT,
                tool_url TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (session_id) REFERENCES circular_sessions(id)
            )");
            
            // Tools knowledge base
            $this->db->exec("CREATE TABLE IF NOT EXISTS circular_tools (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT UNIQUE,
                category TEXT,
                url TEXT,
                description TEXT,
                strengths TEXT,
                limitations TEXT,
                best_for TEXT,
                how_to_use TEXT,
                pricing_tier TEXT,
                api_available INTEGER DEFAULT 0,
                popularity_score INTEGER DEFAULT 0,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )");
            
            // Workflows table
            $this->db->exec("CREATE TABLE IF NOT EXISTS circular_workflows (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                session_id INTEGER,
                steps TEXT,
                current_step INTEGER DEFAULT 0,
                status TEXT DEFAULT 'active',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (session_id) REFERENCES circular_sessions(id)
            )");
            
            error_log("Circular database initialized successfully");
        } catch (PDOException $e) {
            error_log("Circular DB init error: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Search DuckDuckGo for AI tool information
     * Returns structured data about AI tools
     */
    public function searchDuckDuckGo($query)
    {
        try {
            $encodedQuery = urlencode($query);
            $url = "https://api.duckduckgo.com/?q={$encodedQuery}&format=json&no_html=1&skip_disambig=1";
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Wheelder-Circular/1.0');
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode === 200 && $response) {
                $data = json_decode($response, true);
                return $data;
            }
            
            return null;
        } catch (Exception $e) {
            error_log("DuckDuckGo search error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Helper: Execute SQL query
     */
    private function executeQuery($sql, $params = [])
    {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Query execution error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Auto-populate tools database using DuckDuckGo API
     * Searches for popular AI tools and extracts information
     */
    public function autoPopulateTools()
    {
        $aiTools = [
            'ChatGPT AI assistant',
            'Claude AI Anthropic',
            'Google Gemini AI',
            'Grok AI xAI',
            'Perplexity AI search',
            'GitHub Copilot',
            'Cursor AI code editor',
            'Windsurf AI IDE',
            'Replit AI coding',
            'v0 by Vercel',
            'Bolt.new AI',
            'NotebookLM Google',
            'Midjourney AI art',
            'DALL-E OpenAI',
            'Stable Diffusion',
            'RunwayML AI video',
            'ElevenLabs voice AI',
            'Jasper AI writing',
            'Copy.ai content',
            'Grammarly AI writing'
        ];
        
        $populated = 0;
        
        foreach ($aiTools as $toolQuery) {
            // Check if tool already exists
            $searchName = '%' . explode(' ', $toolQuery)[0] . '%';
            $stmt = $this->db->prepare("SELECT id FROM circular_tools WHERE name LIKE ?");
            $stmt->execute([$searchName]);
            
            if ($stmt->fetch()) {
                continue; // Skip if already exists
            }
            
            // Search DuckDuckGo for tool info
            $data = $this->searchDuckDuckGo($toolQuery);
            
            if ($data && !empty($data['AbstractText'])) {
                $name = $data['Heading'] ?: explode(' ', $toolQuery)[0];
                $description = $data['AbstractText'];
                $url = $data['AbstractURL'] ?: '';
                
                // Categorize based on keywords
                $category = $this->categorizeToolFromDescription($description, $toolQuery);
                
                // Insert into database
                $stmt = $this->db->prepare("INSERT INTO circular_tools 
                    (name, category, url, description, strengths, best_for, pricing_tier, popularity_score) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                
                if ($stmt->execute([
                    $name,
                    $category,
                    $url,
                    substr($description, 0, 500),
                    'Auto-discovered via DuckDuckGo',
                    $category,
                    'Unknown',
                    50
                ])) {
                    $populated++;
                    error_log("Circular: Added tool - $name");
                }
            }
            
            // Rate limiting - be respectful to DuckDuckGo
            usleep(500000); // 0.5 second delay
        }
        
        return $populated;
    }
    
    /**
     * Categorize tool based on description keywords
     */
    private function categorizeToolFromDescription($description, $query)
    {
        $desc = strtolower($description . ' ' . $query);
        
        if (strpos($desc, 'code') !== false || strpos($desc, 'programming') !== false) {
            return 'coding';
        } elseif (strpos($desc, 'search') !== false || strpos($desc, 'research') !== false) {
            return 'search';
        } elseif (strpos($desc, 'image') !== false || strpos($desc, 'art') !== false || strpos($desc, 'design') !== false) {
            return 'creative';
        } elseif (strpos($desc, 'video') !== false) {
            return 'video';
        } elseif (strpos($desc, 'voice') !== false || strpos($desc, 'audio') !== false) {
            return 'audio';
        } elseif (strpos($desc, 'writing') !== false || strpos($desc, 'content') !== false) {
            return 'writing';
        } elseif (strpos($desc, 'chat') !== false || strpos($desc, 'assistant') !== false) {
            return 'chatbot';
        } else {
            return 'general';
        }
    }
    
    /**
     * Analyze query and classify it
     * Returns: query_type, complexity, recommended_tools
     */
    public function analyzeQuery($query)
    {
        // Fallback to simple keyword-based classification (Groq may not be available)
        return $this->simpleQueryClassification($query);
    }
    
    /**
     * Simple keyword-based query classification (fallback)
     * WHY: Maps user queries to tool categories based on keywords and intent
     */
    private function simpleQueryClassification($query)
    {
        $lower = strtolower($query);
        
        // Determine primary query type based on keywords
        $type = 'general';
        if (preg_match('/(code|program|develop|build|implement|api|database|function|class|debug|refactor)/i', $query)) {
            $type = 'coding';
        } elseif (preg_match('/(research|find|discover|latest|breakthrough|study|analyze|investigate|explore)/i', $query)) {
            $type = 'research';
        } elseif (preg_match('/(create|design|draw|paint|generate|image|art|logo|mockup|visual)/i', $query)) {
            $type = 'creative';
        } elseif (preg_match('/(learn|explain|understand|teach|tutorial|how|guide|what is)/i', $query)) {
            $type = 'learning';
        } elseif (preg_match('/(write|content|blog|article|copy|email|marketing|story|poem)/i', $query)) {
            $type = 'writing';
        } elseif (preg_match('/(automate|workflow|process|integrate|connect|sync)/i', $query)) {
            $type = 'automation';
        } elseif (preg_match('/(analyze|data|metric|statistic|trend|pattern|report)/i', $query)) {
            $type = 'data_analysis';
        }
        
        // Determine complexity based on query length and keywords
        $complexity = 'simple';
        if (strlen($query) > 150) {
            $complexity = 'complex';
        } elseif (strlen($query) > 50) {
            $complexity = 'medium';
        }
        
        // WHY: return multiple categories so getRecommendedTools can find more relevant tools
        $categories = [$type];
        
        // Add secondary categories based on keywords
        if (preg_match('/(ai|machine learning|neural|model|algorithm)/i', $query)) {
            $categories[] = 'research';
        }
        if (preg_match('/(api|integration|connect|plugin)/i', $query)) {
            $categories[] = 'coding';
        }
        
        return [
            'query_type' => $type,
            'complexity' => $complexity,
            'keywords' => explode(' ', $query),
            'recommended_categories' => $categories
        ];
    }
    
    /**
     * Get recommended tools based on query analysis
     * WHY: Returns tools from primary categories first, then related categories if needed
     */
    public function getRecommendedTools($analysis, $limit = 5)
    {
        $categories = $analysis['recommended_categories'] ?? ['general'];
        $queryType = $analysis['query_type'] ?? 'general';
        
        // Map query types to related tool categories for better recommendations
        $relatedCategories = [
            'research' => ['search', 'chatbot'],
            'coding' => ['chatbot'],
            'creative' => ['chatbot'],
            'learning' => ['chatbot', 'search'],
            'writing' => ['chatbot'],
            'automation' => ['coding', 'chatbot'],
            'data_analysis' => ['research', 'chatbot'],
            'general' => ['chatbot']
        ];
        
        // Build list of categories to search: primary first, then related
        $searchCategories = $categories;
        if (isset($relatedCategories[$queryType])) {
            $searchCategories = array_merge($searchCategories, $relatedCategories[$queryType]);
        }
        // Remove duplicates while preserving order
        $searchCategories = array_unique($searchCategories);
        
        $tools = [];
        $toolIds = []; // Track IDs to avoid duplicates
        
        // WHY: search primary categories first for most relevant tools
        foreach ($searchCategories as $category) {
            if (count($tools) >= $limit) break;
            
            $stmt = $this->db->prepare("SELECT * FROM circular_tools 
                WHERE category = ? 
                ORDER BY popularity_score DESC 
                LIMIT ?");
            $stmt->execute([$category, $limit]);
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Avoid duplicate tools
                if (!in_array($row['id'], $toolIds)) {
                    $tools[] = $row;
                    $toolIds[] = $row['id'];
                    if (count($tools) >= $limit) break;
                }
            }
        }
        
        // Fallback: if no tools found, get any tools from database
        if (empty($tools)) {
            $stmt = $this->db->prepare("SELECT * FROM circular_tools 
                ORDER BY popularity_score DESC 
                LIMIT ?");
            $stmt->execute([$limit]);
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $tools[] = $row;
            }
        }
        
        return array_slice($tools, 0, $limit);
    }
    
    /**
     * Process user query - main orchestration method
     */
    public function processQuery($query, $userId = null)
    {
        try {
            // Validate input
            if (empty($query)) {
                return [
                    'success' => false,
                    'message' => 'Query cannot be empty'
                ];
            }
            
            // 1. Analyze query
            $analysis = $this->analyzeQuery($query);
            
            // 2. Generate base answer using Groq (call parent's generateResponse method)
            $baseAnswer = $this->generateResponse($query);
            
            // 3. Get recommended tools based on analysis
            $recommendedTools = $this->getRecommendedTools($analysis);
            
            // 4. Create session for tracking
            $sessionId = $this->createSession($userId, $query, $analysis);
            
            // 5. Save messages to session history
            $this->saveMessage($sessionId, 'user', $query);
            $this->saveMessage($sessionId, 'assistant', $baseAnswer);
            
            // 6. Return orchestrated response with all components
            return [
                'success' => true,
                'session_id' => $sessionId,
                'analysis' => $analysis,
                'base_answer' => $baseAnswer,
                'recommended_tools' => $recommendedTools,
                'message' => 'Query processed successfully'
            ];
            
        } catch (Exception $e) {
            // Log error with context for debugging
            error_log("Circular processQuery error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
            
            // Return user-friendly error
            return [
                'success' => false,
                'message' => 'Failed to process query. Please try again.',
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Create new session
     */
    private function createSession($userId, $query, $analysis)
    {
        $stmt = $this->db->prepare("INSERT INTO circular_sessions 
            (user_id, title, query, query_type, complexity) 
            VALUES (?, ?, ?, ?, ?)");
        
        $title = substr($query, 0, 100);
        $stmt->execute([
            $userId,
            $title,
            $query,
            $analysis['query_type'],
            $analysis['complexity']
        ]);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Save message to session
     */
    private function saveMessage($sessionId, $role, $content, $toolName = null, $toolUrl = null)
    {
        $stmt = $this->db->prepare("INSERT INTO circular_messages 
            (session_id, role, content, tool_name, tool_url) 
            VALUES (?, ?, ?, ?, ?)");
        
        return $stmt->execute([
            $sessionId,
            $role,
            $content,
            $toolName,
            $toolUrl
        ]);
    }
    
    /**
     * Get session history
     */
    public function getSessionHistory($sessionId)
    {
        $stmt = $this->db->prepare("SELECT * FROM circular_messages WHERE session_id = ? ORDER BY created_at ASC");
        $stmt->execute([$sessionId]);
        
        $messages = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $messages[] = $row;
        }
        
        return $messages;
    }
    
    /**
     * Get all tools from database
     */
    public function getAllTools()
    {
        $stmt = $this->db->query("SELECT * FROM circular_tools ORDER BY popularity_score DESC");
        $tools = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tools[] = $row;
        }
        
        return $tools;
    }
}
?>
