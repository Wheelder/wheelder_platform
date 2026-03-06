# Wheelder Circular - Meta-AI Orchestrator

## Overview

Wheelder Circular is a meta-AI orchestrator that acts as a central hub connecting all AI platforms. Instead of being locked into one platform, users can ask questions in Circular and get:

1. **Base answer** from Groq LLM
2. **Query analysis** (type, complexity, keywords)
3. **Tool recommendations** - which external AI tools are best for this task
4. **How to use them** - step-by-step instructions
5. **Session tracking** - keep all work in one place

## Problem Solved

**Platform dependency** - Every AI platform wants users attached to their ecosystem. Users miss benefits of other platforms.

**Wheelder Circular solves this** by being the wheel (circular) that connects all spokes (tools) together, keeping the user at the center.

## Architecture

### Core Components

1. **CircularController** - Meta-AI orchestration engine
   - Query classification (coding/research/creative/learning/automation)
   - Complexity assessment (simple/medium/complex)
   - Tool recommendation engine
   - Session management

2. **Database Schema**
   - `circular_sessions` - User sessions with query metadata
   - `circular_messages` - Conversation history
   - `circular_tools` - AI tools knowledge base
   - `circular_workflows` - Multi-step workflows

3. **DuckDuckGo Integration**
   - Auto-populates tools database
   - Fetches latest AI tool information
   - No API key required (free)

4. **UI**
   - Two-panel chat interface (copied from /center)
   - Left: Base answer from Groq
   - Right: Tool recommendations with links and instructions

## Installation

### Step 1: Access Setup Page

Visit: `http://localhost/circular/setup`

This will:
- Initialize the database
- Create all tables
- Auto-populate 20+ AI tools using DuckDuckGo API

### Step 2: Verify Setup

The setup page will show:
- ✓ Database initialized
- ✓ Tables created
- ✓ Tools populated (count)
- Sample tools list

### Step 3: Start Using

Visit: `http://localhost/circular`

## How It Works

### User Flow

1. **User asks a question**
   ```
   "How can I build a React app with AI code generation?"
   ```

2. **Circular analyzes the query**
   - Type: `coding`
   - Complexity: `medium`
   - Keywords: `react`, `ai`, `code generation`

3. **Circular provides base answer**
   - Uses Groq Llama 3.1 to generate comprehensive answer
   - Formatted with markdown (lists, code blocks, headings)

4. **Circular recommends tools**
   - GitHub Copilot (for inline code completion)
   - Cursor AI (for AI-powered IDE)
   - v0 by Vercel (for React component generation)
   - Claude Code (for complex refactoring)

5. **Circular provides instructions**
   - How to use each tool
   - When to use which tool
   - Links to get started

6. **User executes workflow**
   - Goes to recommended tool
   - Does the work
   - Comes back to Circular
   - Pastes results
   - Circular synthesizes everything

### API Endpoints

#### POST /circular/ajax

**Actions:**

1. **ask** - Process new query
   ```json
   {
     "ask": true,
     "query": "Your question here",
     "csrf_token": "..."
   }
   ```
   
   Response:
   ```json
   {
     "success": true,
     "answer": "Base answer HTML",
     "session_id": "conv_xxx",
     "analysis": {
       "query_type": "coding",
       "complexity": "medium",
       "keywords": ["react", "ai"]
     },
     "recommended_tools": [
       {
         "name": "GitHub Copilot",
         "category": "coding",
         "url": "https://github.com/features/copilot",
         "description": "...",
         "how_to_use": "..."
       }
     ]
   }
   ```

2. **deepen** - Get workflow suggestions
   ```json
   {
     "deepen": true,
     "session_id": "conv_xxx",
     "original_question": "...",
     "csrf_token": "..."
   }
   ```

3. **populate_tools** - Auto-populate tools database
   ```json
   {
     "populate_tools": true,
     "csrf_token": "..."
   }
   ```

4. **get_tools** - Retrieve all tools
   ```json
   {
     "get_tools": true,
     "csrf_token": "..."
   }
   ```

5. **get_session** - Retrieve session history
   ```json
   {
     "get_session": true,
     "session_id": "conv_xxx",
     "csrf_token": "..."
   }
   ```

## Database Schema

### circular_sessions
```sql
CREATE TABLE circular_sessions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    title TEXT,
    query TEXT,
    query_type TEXT,
    complexity TEXT,
    status TEXT DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### circular_messages
```sql
CREATE TABLE circular_messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    session_id INTEGER,
    role TEXT,
    content TEXT,
    tool_name TEXT,
    tool_url TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (session_id) REFERENCES circular_sessions(id)
);
```

### circular_tools
```sql
CREATE TABLE circular_tools (
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
);
```

### circular_workflows
```sql
CREATE TABLE circular_workflows (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    session_id INTEGER,
    steps TEXT,
    current_step INTEGER DEFAULT 0,
    status TEXT DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (session_id) REFERENCES circular_sessions(id)
);
```

## Tool Categories

- **coding** - GitHub Copilot, Cursor, Windsurf, Replit
- **chatbot** - ChatGPT, Claude, Gemini, Grok
- **search** - Perplexity, NotebookLM
- **creative** - Midjourney, DALL-E, Stable Diffusion
- **video** - RunwayML
- **audio** - ElevenLabs
- **writing** - Jasper, Copy.ai, Grammarly
- **general** - Multi-purpose tools

## MVP Features (V1)

✅ **Query classification** - Analyze user intent
✅ **Base answer generation** - Groq LLM integration
✅ **Tool recommendation** - Match query to best tools
✅ **DuckDuckGo integration** - Auto-populate tools database
✅ **Session management** - Track conversations
✅ **AJAX interface** - No page reloads
✅ **CSRF protection** - Secure requests
✅ **Rate limiting** - 10 requests per 60 seconds

## Roadmap

### V2 - Enhanced Discovery
- Real-time search for latest tools
- User ratings and reviews
- Tool usage analytics
- Richer tool profiles

### V3 - Plugin System
- API bridges to 2-3 popular tools
- Auto-execute simple tasks
- Result aggregation

### V4 - MCP Integration
- Bi-directional data flow
- Wheelder MCP server
- Tool connectors

### V5 - Custom Model
- Fine-tuned routing model
- Learn from user interactions
- Personalized recommendations

## Technical Stack

- **Backend**: PHP 8+, SQLite
- **Frontend**: Bootstrap 5, Vanilla JS
- **AI Engine**: Groq API (Llama 3.1)
- **Search**: DuckDuckGo API (free, no key)
- **Database**: SQLite (circular.db)

## Security

- CSRF token validation
- Rate limiting (10 req/60s per session)
- SQL injection prevention (prepared statements)
- XSS protection (htmlspecialchars)
- Session-based authentication ready

## Performance

- Query analysis: ~1-2s (Groq API)
- Tool lookup: <100ms (SQLite)
- DuckDuckGo search: ~500ms per tool
- Total response time: 2-3s

## Files Structure

```
apps/edu/ui/views/circular/
├── CircularController.php    # Meta-AI orchestration engine
├── ajax_handler.php           # AJAX endpoint
├── record.php                 # Main UI
├── setup.php                  # Database initialization
├── circular.db                # SQLite database
└── config.local.php           # Configuration
```

## Configuration

Edit `config.local.php`:

```php
<?php
// Groq API configuration (inherited from parent AppController)
define('GROQ_API_KEY', 'your-groq-api-key');
define('GROQ_API_ENDPOINT', 'https://api.groq.com/openai/v1/chat/completions');

// Circular-specific settings
define('CIRCULAR_MAX_TOOLS_PER_QUERY', 5);
define('CIRCULAR_AUTO_POPULATE_ON_INIT', true);
?>
```

## Usage Examples

### Example 1: Learning
**Query:** "Explain quantum computing"

**Circular Response:**
- Base answer: Comprehensive explanation
- Recommended tools:
  - NotebookLM (for research and note-taking)
  - Perplexity (for deep dive research)
  - ChatGPT (for follow-up questions)

### Example 2: Coding
**Query:** "Build a REST API with authentication"

**Circular Response:**
- Base answer: Architecture overview
- Recommended tools:
  - Cursor AI (for code generation)
  - GitHub Copilot (for inline completion)
  - Claude Code (for security review)

### Example 3: Creative
**Query:** "Create a logo for my startup"

**Circular Response:**
- Base answer: Design principles
- Recommended tools:
  - Midjourney (for AI art generation)
  - DALL-E (for specific styles)
  - Stable Diffusion (for local generation)

## Troubleshooting

### Tools database is empty
Run: `http://localhost/circular/setup`

### DuckDuckGo search failing
- Check internet connection
- Verify no firewall blocking
- DuckDuckGo API has rate limits (be respectful)

### Groq API errors
- Verify API key in config.local.php
- Check Groq API quota
- See error logs for details

### Database errors
- Check file permissions on circular.db
- Ensure SQLite extension is enabled in PHP
- Run setup.php to recreate tables

## Contributing

Wheelder Circular is designed to be extensible:

1. **Add new tool categories** - Edit `categorizeToolFromDescription()`
2. **Add new AI providers** - Extend `CircularController`
3. **Add new search sources** - Implement alongside DuckDuckGo
4. **Improve query classification** - Enhance `analyzeQuery()`

## License

Part of the Wheelder platform.

## Support

For issues or questions:
1. Check error logs
2. Review database for tool records
3. Test with setup.php
4. Enable debug logging in CircularController

---

**Wheelder Circular** - Making human life a little easier by connecting all AI tools in one place.
