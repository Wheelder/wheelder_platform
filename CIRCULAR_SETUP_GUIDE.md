# Wheelder Circular - Setup Guide

## Prerequisites

### 1. Enable SQLite3 Extension

Wheelder Circular uses SQLite3 for the tools database. You need to enable this extension in PHP.

#### For XAMPP Users:

1. Open `php.ini` file (usually in `C:\xampp\php\php.ini`)
2. Find the line: `;extension=sqlite3`
3. Remove the semicolon to uncomment it: `extension=sqlite3`
4. Save the file
5. Restart Apache in XAMPP Control Panel

#### For Other PHP Installations:

1. Locate your `php.ini` file (run `php --ini` to find it)
2. Uncomment `extension=sqlite3`
3. Restart your web server

#### Verify SQLite3 is Enabled:

Run this command:
```bash
php -m | grep sqlite3
```

You should see `sqlite3` in the output.

Or run the test script:
```bash
php test_circular_minimal.php
```

### 2. Groq API Key

Circular uses Groq API for AI responses. You need a free API key:

1. Visit https://console.groq.com
2. Sign up for a free account
3. Generate an API key
4. Add it to `apps/edu/ui/views/circular/config.local.php`:

```php
<?php
define('GROQ_API_KEY', 'your-groq-api-key-here');
define('GROQ_API_ENDPOINT', 'https://api.groq.com/openai/v1/chat/completions');
?>
```

## Installation Steps

### Step 1: Verify Prerequisites

Run the minimal test:
```bash
cd c:\xampp\htdocs\wheelder
php test_circular_minimal.php
```

Expected output:
```
✓ SQLite3 extension is enabled
✓ SQLite3 database working correctly
✓ DuckDuckGo API working
```

### Step 2: Initialize Database

Visit in your browser:
```
http://localhost/circular/setup.php
```

This will:
- Create the SQLite database (`circular.db`)
- Create all required tables
- Auto-populate 20+ AI tools using DuckDuckGo API

Expected output:
```
✓ Database initialized successfully
✓ Tables created: circular_sessions, circular_messages, circular_tools, circular_workflows
✓ Successfully populated 15-20 AI tools
```

### Step 3: Start Using Circular

Visit:
```
http://localhost/circular
```

## Usage

### Basic Query

1. Type your question in the text area
2. Click "Ask" or press Enter
3. Circular will:
   - Analyze your query (type, complexity)
   - Generate a base answer using Groq
   - Recommend the best AI tools for your task
   - Show how to use each tool

### Example Queries

**Learning:**
```
Explain quantum computing
```

**Coding:**
```
How can I build a REST API with authentication?
```

**Creative:**
```
Create a logo for my startup
```

**Research:**
```
What are the latest developments in AI?
```

### Workflow Generation

Click "Deepen" to get a step-by-step workflow with tool recommendations.

## Troubleshooting

### SQLite3 Not Found

**Error:** `Class "SQLite3" not found`

**Solution:**
1. Enable SQLite3 extension in php.ini
2. Restart Apache/web server
3. Verify with `php -m | grep sqlite3`

### Database Permission Error

**Error:** `unable to open database file`

**Solution:**
1. Check folder permissions on `apps/edu/ui/views/circular/`
2. Ensure Apache/PHP can write to this directory
3. On Windows, right-click folder → Properties → Security → Edit → Add write permissions

### DuckDuckGo Rate Limiting

**Error:** Empty results from DuckDuckGo

**Solution:**
- DuckDuckGo has rate limits
- Wait 30-60 seconds between setup runs
- The setup script includes 0.5s delay between requests
- Some queries naturally return empty results

### Groq API Error

**Error:** `Failed to generate answer`

**Solution:**
1. Verify API key in `config.local.php`
2. Check Groq API quota at https://console.groq.com
3. Ensure API key has correct permissions
4. Check error logs for detailed error message

### Empty Tools Database

**Error:** No tools showing in recommendations

**Solution:**
1. Run setup.php again
2. Manually populate tools:
   ```php
   POST /circular/ajax
   {
     "populate_tools": true,
     "csrf_token": "..."
   }
   ```
3. Check database file exists: `apps/edu/ui/views/circular/circular.db`

## Manual Tool Addition

If DuckDuckGo auto-population fails, you can manually add tools to the database:

```php
<?php
require_once 'apps/edu/ui/views/circular/CircularController.php';
$circular = new CircularController();

// Get database handle
$db = new SQLite3('apps/edu/ui/views/circular/circular.db');

// Add a tool manually
$stmt = $db->prepare("INSERT INTO circular_tools 
    (name, category, url, description, strengths, best_for, pricing_tier, popularity_score) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bindValue(1, 'ChatGPT', SQLITE3_TEXT);
$stmt->bindValue(2, 'chatbot', SQLITE3_TEXT);
$stmt->bindValue(3, 'https://chat.openai.com', SQLITE3_TEXT);
$stmt->bindValue(4, 'AI chatbot by OpenAI', SQLITE3_TEXT);
$stmt->bindValue(5, 'General conversation, writing, coding', SQLITE3_TEXT);
$stmt->bindValue(6, 'General purpose AI assistance', SQLITE3_TEXT);
$stmt->bindValue(7, 'Free tier available', SQLITE3_TEXT);
$stmt->bindValue(8, 100, SQLITE3_INTEGER);

$stmt->execute();
echo "Tool added successfully\n";
?>
```

## Architecture Overview

```
User Query
    ↓
CircularController::processQuery()
    ↓
1. analyzeQuery() → Classify type & complexity
    ↓
2. generateAnswer() → Base answer from Groq
    ↓
3. getRecommendedTools() → Match to tools DB
    ↓
4. Return orchestrated response
    ↓
Frontend displays:
    - Base answer (left panel)
    - Tool recommendations (right panel)
    - Workflow steps (on deepen)
```

## Database Schema

### circular_sessions
Stores user sessions with query metadata

### circular_messages
Conversation history for each session

### circular_tools
AI tools knowledge base (auto-populated)

### circular_workflows
Multi-step workflows for complex tasks

## API Endpoints

### POST /circular/ajax

**Actions:**
- `ask` - Process new query
- `deepen` - Get workflow
- `populate_tools` - Auto-populate database
- `get_tools` - List all tools
- `get_session` - Get session history

## Performance

- Query analysis: ~1-2s
- Tool lookup: <100ms
- DuckDuckGo search: ~500ms per tool
- Total response: 2-3s

## Security

- CSRF token validation
- Rate limiting (10 req/60s)
- SQL injection prevention
- XSS protection
- Session-based auth ready

## Next Steps

1. ✅ Enable SQLite3
2. ✅ Run setup.php
3. ✅ Start using /circular
4. 📝 Customize tool categories
5. 📝 Add more tools manually
6. 📝 Fine-tune query classification
7. 📝 Build MCP integrations (future)

## Support

Check these files for more info:
- `WHEELDER_CIRCULAR_README.md` - Full documentation
- `test_circular_minimal.php` - Diagnostic script
- Error logs in Apache/PHP logs

---

**Wheelder Circular** - Your meta-AI orchestrator connecting all AI tools.
