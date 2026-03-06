<?php
/**
 * Direct Circular Setup - No router dependency
 * Access directly: /apps/edu/ui/views/circular/setup_direct.php
 */

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Wheelder Circular - Setup</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        .status { margin: 20px 0; padding: 15px; border-radius: 4px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background: #0056b3; }
        .step { margin: 20px 0; }
        .step-number { display: inline-block; background: #007bff; color: white; width: 30px; height: 30px; border-radius: 50%; text-align: center; line-height: 30px; margin-right: 10px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>⚙️ Wheelder Circular - Setup</h1>
        
        <div class="step">
            <span class="step-number">1</span>
            <strong>Checking PHP Extensions...</strong>
        </div>
        
        <?php
        // Check PDO SQLite
        if (extension_loaded('pdo_sqlite')) {
            echo '<div class="status success">✓ PDO SQLite extension is enabled</div>';
        } else {
            echo '<div class="status error">✗ PDO SQLite extension is NOT enabled</div>';
            echo '<p>To enable it, uncomment <code>extension=pdo_sqlite</code> in php.ini and restart Apache.</p>';
        }
        
        // Check cURL
        if (extension_loaded('curl')) {
            echo '<div class="status success">✓ cURL extension is enabled</div>';
        } else {
            echo '<div class="status error">✗ cURL extension is NOT enabled</div>';
        }
        
        echo '<div class="step">';
        echo '<span class="step-number">2</span>';
        echo '<strong>Initializing Database...</strong>';
        echo '</div>';
        
        try {
            $dbPath = __DIR__ . '/circular.db';
            $db = new PDO('sqlite:' . $dbPath);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Create tables
            $db->exec("CREATE TABLE IF NOT EXISTS circular_sessions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER,
                title TEXT,
                query TEXT,
                query_type TEXT,
                complexity TEXT,
                status TEXT DEFAULT 'active',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )");
            
            $db->exec("CREATE TABLE IF NOT EXISTS circular_messages (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                session_id INTEGER,
                role TEXT,
                content TEXT,
                tool_name TEXT,
                tool_url TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (session_id) REFERENCES circular_sessions(id)
            )");
            
            $db->exec("CREATE TABLE IF NOT EXISTS circular_tools (
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
            
            $db->exec("CREATE TABLE IF NOT EXISTS circular_workflows (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                session_id INTEGER,
                steps TEXT,
                current_step INTEGER DEFAULT 0,
                status TEXT DEFAULT 'active',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (session_id) REFERENCES circular_sessions(id)
            )");
            
            echo '<div class="status success">✓ Database tables created successfully</div>';
            
            echo '<div class="step">';
            echo '<span class="step-number">3</span>';
            echo '<strong>Auto-Populating AI Tools (This may take 10-15 seconds)...</strong>';
            echo '</div>';
            
            // Quick tool population (without DuckDuckGo to speed up)
            $tools = [
                ['ChatGPT', 'chatbot', 'https://chat.openai.com', 'AI chatbot by OpenAI', 'General conversation, writing, coding'],
                ['Claude', 'chatbot', 'https://claude.ai', 'AI assistant by Anthropic', 'Long context, analysis, coding'],
                ['Google Gemini', 'chatbot', 'https://gemini.google.com', 'Google\'s AI model', 'Search integration, multimodal'],
                ['Grok', 'chatbot', 'https://grok.x.com', 'AI by xAI', 'Real-time information, reasoning'],
                ['Perplexity', 'search', 'https://perplexity.ai', 'AI search engine', 'Research, citations, sources'],
                ['GitHub Copilot', 'coding', 'https://github.com/features/copilot', 'AI code completion', 'Code generation, autocomplete'],
                ['Cursor', 'coding', 'https://cursor.sh', 'AI-powered code editor', 'Full IDE with AI'],
                ['Windsurf', 'coding', 'https://codeium.com/windsurf', 'Agentic IDE', 'Autonomous coding'],
                ['v0 by Vercel', 'coding', 'https://v0.dev', 'React component generator', 'UI generation'],
                ['Midjourney', 'creative', 'https://midjourney.com', 'AI art generation', 'Image creation, styles'],
                ['DALL-E', 'creative', 'https://openai.com/dall-e-3', 'OpenAI image generator', 'Detailed image generation'],
                ['Stable Diffusion', 'creative', 'https://stablediffusionweb.com', 'Open source image AI', 'Local generation'],
                ['ElevenLabs', 'audio', 'https://elevenlabs.io', 'AI voice synthesis', 'Natural speech, voices'],
                ['NotebookLM', 'research', 'https://notebooklm.google.com', 'Google research tool', 'Document analysis'],
                ['Jasper', 'writing', 'https://jasper.ai', 'AI writing assistant', 'Content creation'],
                ['Copy.ai', 'writing', 'https://copy.ai', 'Copywriting AI', 'Marketing content'],
                ['Grammarly', 'writing', 'https://grammarly.com', 'Writing assistant', 'Grammar, tone, clarity'],
                ['RunwayML', 'video', 'https://runwayml.com', 'AI video generation', 'Video editing, effects'],
                ['Replit', 'coding', 'https://replit.com', 'Online IDE with AI', 'Collaborative coding'],
                ['Bolt.new', 'coding', 'https://bolt.new', 'Full-stack AI builder', 'Web app generation']
            ];
            
            $added = 0;
            foreach ($tools as $tool) {
                try {
                    $stmt = $db->prepare("INSERT OR IGNORE INTO circular_tools 
                        (name, category, url, description, best_for, popularity_score) 
                        VALUES (?, ?, ?, ?, ?, ?)");
                    
                    if ($stmt->execute([
                        $tool[0],
                        $tool[1],
                        $tool[2],
                        $tool[3],
                        $tool[4],
                        80
                    ])) {
                        $added++;
                    }
                } catch (Exception $e) {
                    // Skip duplicates
                }
            }
            
            echo '<div class="status success">✓ Added ' . $added . ' AI tools to database</div>';
            
            // Verify
            $result = $db->query("SELECT COUNT(*) as count FROM circular_tools");
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $totalTools = $row['count'];
            
            echo '<div class="step">';
            echo '<span class="step-number">4</span>';
            echo '<strong>Verification</strong>';
            echo '</div>';
            
            echo '<div class="status success">✓ Setup Complete!</div>';
            echo '<p><strong>Total tools in database:</strong> ' . $totalTools . '</p>';
            
            echo '<div class="status info">';
            echo '<strong>Next Steps:</strong><br>';
            echo '1. Visit <a href="/circular" style="color: #0c5460; text-decoration: underline;"><code>http://localhost/circular</code></a> to use Wheelder Circular<br>';
            echo '2. Ask a question and get AI tool recommendations<br>';
            echo '3. Read <code>WHEELDER_CIRCULAR_README.md</code> for full documentation';
            echo '</div>';
            
        } catch (PDOException $e) {
            echo '<div class="status error">✗ Database Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        ?>
        
        <hr style="margin: 30px 0;">
        
        <h3>Sample Tools in Database:</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="background: #f9f9f9;">
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">Tool</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">Category</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">URL</th>
            </tr>
            <?php
            try {
                $result = $db->query("SELECT name, category, url FROM circular_tools LIMIT 10");
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    echo '<td style="border: 1px solid #ddd; padding: 10px;">' . htmlspecialchars($row['name']) . '</td>';
                    echo '<td style="border: 1px solid #ddd; padding: 10px;"><span style="background: #e7f3ff; padding: 2px 8px; border-radius: 3px;">' . htmlspecialchars($row['category']) . '</span></td>';
                    echo '<td style="border: 1px solid #ddd; padding: 10px;"><a href="' . htmlspecialchars($row['url']) . '" target="_blank" style="color: #007bff;">' . htmlspecialchars($row['url']) . '</a></td>';
                    echo '</tr>';
                }
            } catch (Exception $e) {
                echo '<tr><td colspan="3" style="padding: 10px; text-align: center;">No tools found</td></tr>';
            }
            ?>
        </table>
    </div>
</body>
</html>
