<?php
// WHY: Standalone hardcoded release page for Wheelder Circular
// No database dependency — content is embedded directly in this file
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wheelder Circular — The Master of All AI Innovation Tools</title>

    <meta property="og:title" content="Wheelder Circular — The Master of All AI Innovation Tools">
    <meta property="og:description" content="Wheelder Circular is a Proof of Concept for the ultimate AI age invention — a meta-AI orchestrator that unifies DeepSeek, Kimi2, Qwen, LangChain, n8n, ChatGPT, Claude, Gemini, Grok, Perplexity, and 50+ more tools into one intelligent interface.">
    <meta property="og:url" content="https://wheelder.com/release_new">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Wheelder">
    <meta name="twitter:card" content="summary_large_image">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #212529;
            --secondary-color: #6c757d;
            --accent-color: #0d6efd;
        }

        body {
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        .dark-mode {
            background-color: #1a1a1a;
            color: #e0e0e0;
        }

        .dark-mode .navbar {
            background-color: #0d0d0d !important;
        }

        .dark-mode .card {
            background-color: #2a2a2a;
            color: #e0e0e0;
            border-color: #444;
        }

        .dark-mode .release-item {
            background-color: #2a2a2a;
            border-color: #444;
        }

        .dark-mode .release-content {
            background-color: #1e1e1e;
            color: #e0e0e0;
        }

        .navbar {
            background-color: var(--primary-color);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .releases-sidebar {
            max-height: 80vh;
            overflow-y: auto;
        }

        .release-item {
            padding: 1rem;
            margin-bottom: 0.5rem;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: #fff;
        }

        .release-item:hover {
            background-color: #f8f9fa;
            border-color: var(--accent-color);
            transform: translateX(4px);
        }

        .release-item.active {
            background-color: var(--accent-color);
            color: white;
            border-color: var(--accent-color);
        }

        .release-item .version-badge {
            display: inline-block;
            background-color: #e9ecef;
            color: #212529;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-right: 0.5rem;
        }

        .release-item.active .version-badge {
            background-color: rgba(255,255,255,0.3);
            color: white;
        }

        .release-date {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .release-item.active .release-date {
            color: rgba(255,255,255,0.8);
        }

        .release-content {
            background-color: #fff;
            border-radius: 8px;
            padding: 2rem;
            min-height: 500px;
        }

        .release-header {
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 1.5rem;
            margin-bottom: 2rem;
        }

        .release-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .release-meta {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .release-version {
            background-color: var(--accent-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 600;
        }

        .release-timestamp {
            color: var(--secondary-color);
            font-size: 0.95rem;
        }

        .release-description {
            font-size: 1.1rem;
            color: var(--secondary-color);
            margin-bottom: 2rem;
            font-style: italic;
        }

        .release-body {
            line-height: 1.8;
        }

        .release-body h2 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .release-body h3 {
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .release-body p {
            margin-bottom: 1rem;
        }

        .release-body ul, .release-body ol {
            margin-bottom: 1rem;
            padding-left: 2rem;
        }

        .release-body li {
            margin-bottom: 0.5rem;
        }

        @media (max-width: 768px) {
            .release-title {
                font-size: 1.75rem;
            }

            .release-content {
                padding: 1rem;
            }

            .releases-sidebar {
                max-height: 300px;
                margin-bottom: 2rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <i class="fas fa-rocket"></i> Wheelder Releases
            </a>
            <div class="d-flex gap-2">
                <a href="/center" class="btn btn-sm btn-outline-light">
                    <i class="fas fa-arrow-left"></i> Back to Center
                </a>
                <?php if (!empty($_SESSION['user_id'])): ?>
                    <a href="/releases/cms" class="btn btn-sm btn-outline-light">
                        <i class="fas fa-edit"></i> Manage
                    </a>
                <?php endif; ?>
                <button class="btn btn-sm btn-outline-light" id="darkModeToggle">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="fas fa-list"></i> All Releases</h5>
                    </div>
                    <div class="releases-sidebar">
                        <div class="release-item active">
                            <span class="version-badge">1.0-poc</span>
                            <div class="fw-bold">Wheelder Circular — The Master of All AI Innovation Tools</div>
                            <div class="release-date">
                                <i class="fas fa-calendar"></i> Mar 05, 2026
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="col-lg-9">
                <div class="release-content">
                    <div class="release-header">
                        <h1 class="release-title">Wheelder Circular — The Master of All AI Innovation Tools</h1>
                        <div class="release-meta">
                            <span class="release-version">v1.0-poc</span>
                            <span class="release-timestamp">
                                <i class="fas fa-calendar-alt"></i> March 05, 2026
                            </span>
                        </div>
                    </div>

                    <p class="release-description">Wheelder Circular is a Proof of Concept for the ultimate AI age invention — a meta-AI orchestrator that unifies DeepSeek, Kimi2, Qwen, LangChain, n8n, ChatGPT, Claude, Gemini, Grok, Perplexity, and 50+ more tools into one intelligent interface. Early stage, committed, and rapidly evolving.</p>

                    <div class="release-body">

                        <h2>Wheelder Circular</h2>
                        <h3>The Ultimate Invention of the AI Age</h3>
                        <h3>The Master of All AI Innovation Tools</h3>

                        <p>This is a Proof of Concept (PoC) — a very new and early-stage innovation. Wheelder Circular is immature by design at this stage. It is a committed, rapidly evolving project with a clear vision and an ambitious roadmap. What you see today is the seed of something much larger: the operating system for the AI age.</p>

                        <h2>What Is Wheelder Circular?</h2>

                        <p>Wheelder Circular is a meta-AI orchestrator, a unified home where every brilliant AI platform, chatbot, agent, automation framework, and creative engine comes together under one roof.</p>

                        <p>We live in a golden age of AI. Incredible tools have been built by extraordinary teams around the world: DeepSeek, Kimi2, Qwen, ChatGPT, Claude, Gemini, Grok, OpenClaw, n8n, Claude Code, Claude Cowork and many more. Each of these platforms is remarkable on its own. Wheelder Circular celebrates all of them by creating the first unified orchestration layer that brings their collective power to every user through a single, intelligent interface.</p>

                        <p>Circular analyzes what you need, gives you an immediate answer, and then recommends the best combination of these amazing tools for your specific task, all while keeping your conversation and context intact. It is the orchestrator that amplifies the brilliance of the entire AI ecosystem.</p>

                        <h2>How It Works</h2>

                        <ol>
                            <li>You ask a question inside Circular</li>
                            <li>Circular provides an immediate, helpful base answer powered by Groq LLM</li>
                            <li>It recommends the best external AI tools for your specific task, ranked by relevance</li>
                            <li>It shows you step-by-step instructions for how to use each recommended tool</li>
                            <li>Your conversation, context, and workflow history stay preserved as your home base</li>
                        </ol>

                        <p>Circular intelligently classifies every query by type (coding, research, creative, learning, writing, automation, data analysis), determines complexity, and maps it to the right tools across the entire AI landscape.</p>

                        <h2>The AI Tools We Celebrate and Connect</h2>

                        <p>Circular's tool database spans every major category of AI innovation:</p>

                        <ul>
                            <li><strong>Chatbots and Reasoning:</strong> ChatGPT by OpenAI, Claude and Claude Cowork by Anthropic, Gemini by Google, Grok by xAI, DeepSeek, Kimi2 by Moonshot AI, Qwen by Alibaba, Baichuan, Yi by 01.AI, ChatGLM by Zhipu AI, Ernie Bot by Baidu</li>
                            <li><strong>Coding and Development:</strong> GitHub Copilot, Cursor, Windsurf (Cascade), v0 by Vercel, Replit, Bolt.new, OpenClaw</li>
                            <li><strong>Research and Knowledge:</strong> Tavily, NotebookLM by Google, Consensus</li>
                            <li><strong>Creative and Design:</strong> Midjourney, DALL-E by OpenAI, Stable Diffusion by Stability AI, Pollinations</li>
                            <li><strong>Writing and Content:</strong> Jasper, Copy.ai, Grammarly</li>
                            <li><strong>Audio, Voice, and Video:</strong> ElevenLabs, RunwayML</li>
                            <li><strong>Agent Frameworks:</strong> LangChain, LangGraph, CrewAI, AutoGen</li>
                            <li><strong>Automation Platforms:</strong> n8n, Zapier, Make</li>
                            <li><strong>Open Source Ecosystem:</strong> Hugging Face, Ollama, vLLM, LocalAI</li>
                            <li>And 50+ more tools continuously being added.</li>
                        </ul>

                        <p>Every one of these tools represents extraordinary innovation. Circular exists to help users get the most out of all of them.</p>

                        <h2>Key Features</h2>

                        <p><strong>Smart Tool Recommendations:</strong> 5 tools per query, ranked by relevance, with direct visit links.</p>

                        <p><strong>Query Classification:</strong> &ldquo;Explain quantum computing&rdquo; routes to NotebookLM, Tavily, and ChatGPT. &ldquo;Build a REST API&rdquo; routes to GitHub Copilot, Cursor, and Claude Code. &ldquo;Design a logo&rdquo; routes to Midjourney, DALL-E, and Stable Diffusion. &ldquo;Build a data pipeline&rdquo; routes to n8n, LangChain, and Make.</p>

                        <p><strong>Session Management:</strong> Full conversation history across multiple queries. Follow-up questions within the same session. Context preserved for multi-step workflows.</p>

                        <p><strong>Workflow Deepening:</strong> The Deepen button generates step-by-step workflows showing which tool to use at each step, with instructions for tool integration, up to 7 depth levels.</p>

                        <p><strong>Unified Interface:</strong> One clean interface for all AI interactions with a sidebar for history, real-time recommendations, and direct links to every tool.</p>

                        <h2>Appreciation and Acknowledgments</h2>

                        <p>Wheelder Circular would not exist without the extraordinary open-source community and the visionary companies that have made AI accessible to everyone. We express our deepest gratitude.</p>

                        <p><strong>To Groq and NVIDIA:</strong> Thank you for providing blazing-fast LLM inference infrastructure. Groq's LPU technology powers the real-time responses at the heart of Circular. NVIDIA's GPU ecosystem has made the entire AI revolution possible. Your commitment to speed and accessibility is the backbone of what we have built.</p>

                        <p><strong>To Meta and Ollama:</strong> Thank you to Meta for releasing the Llama family of models as open source, fundamentally democratizing access to world-class language models. Thank you to Ollama for making it effortless to run these models locally. The Llama models are the engine inside Circular's intelligence.</p>

                        <p><strong>To Pollinations:</strong> Thank you for providing open-source AI image generation APIs that make creative AI accessible to everyone without friction.</p>

                        <p><strong>To Hugging Face:</strong> Thank you for building the home of open-source AI. Your model hub, datasets, and community have accelerated AI development for millions of developers worldwide.</p>

                        <p><strong>To LangChain, LangGraph, and n8n:</strong> Thank you for creating the definitive frameworks for LLM-powered applications, multi-agent systems, and open-source workflow automation. Circular builds on the principles you pioneered.</p>

                        <p><strong>To vLLM, LocalAI, and the broader open-source community:</strong> Thank you to every contributor, maintainer, and researcher who has published a paper, released a model, shared a dataset, or written documentation. Tools like CrewAI, AutoGen, Haystack, txtai, PrivateGPT, Jan, LM Studio, and GPT4All have collectively built the ecosystem that makes Circular possible.</p>

                        <p><strong>To the AI platform pioneers:</strong> Thank you to OpenAI for ChatGPT, to Anthropic for Claude and Claude Cowork, to Google for Gemini and NotebookLM, to xAI for Grok, to DeepSeek for advancing the frontiers of reasoning, to Moonshot AI for Kimi2, to Alibaba for Qwen, to Baidu for Ernie, to Zhipu AI for ChatGLM, to 01.AI for Yi, to GitHub for Copilot, to Vercel for v0, to Replit, Cursor, Windsurf, Midjourney, Stability AI, ElevenLabs, RunwayML, Jasper, Copy.ai, Grammarly, Zapier, Make, and to every AI company and team building tools that move humanity forward. Your innovations inspire everything we do. Wheelder Circular exists to amplify and honor the work of every one of you.</p>

                        <h2>About Wheelder</h2>

                        <p>Wheelder is building the infrastructure for the AI age. The mission is to make AI accessible, coordinated, and productive for everyone. Wheelder Circular does not replace any tool. It elevates all of them. Like father. Every platform that exists, and every platform yet to be built, finds its place inside the Circular ecosystem.</p>

                        <p>This is a Proof of Concept today. It is the operating system for the AI age tomorrow.</p>

                        <p>
                            <strong>Version:</strong> 1.0-poc<br>
                            <strong>Release Date:</strong> March 5, 2026<br>
                            <strong>Status:</strong> Proof of Concept, Rapidly Evolving<br>
                            <strong>License:</strong> Freemium (Coming Soon)
                        </p>

                        <p><em>The future of AI orchestration is here...</em></p>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const darkModeToggle = document.getElementById('darkModeToggle');
        const htmlElement = document.documentElement;

        if (localStorage.getItem('darkMode') === 'enabled') {
            htmlElement.classList.add('dark-mode');
        }

        darkModeToggle.addEventListener('click', function() {
            htmlElement.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', htmlElement.classList.contains('dark-mode') ? 'enabled' : 'disabled');
        });
    </script>
</body>
</html>
