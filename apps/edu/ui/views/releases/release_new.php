<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Wheelder Circular – Proof of Concept</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    :root {
      --bg: #05060a;
      --bg-elevated: #0c0f18;
      --accent: #f5b544;
      --accent-soft: rgba(245, 181, 68, 0.12);
      --text-main: #f7f7fb;
      --text-muted: #a3a7c2;
      --border-subtle: #1b2030;
      --radius-lg: 14px;
      --radius-md: 10px;
      --shadow-soft: 0 18px 45px rgba(0, 0, 0, 0.55);
      --font-main: system-ui, -apple-system, BlinkMacSystemFont, "SF Pro Text",
        "Segoe UI", sans-serif;
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      min-height: 100vh;
      font-family: var(--font-main);
      background: radial-gradient(circle at top, #15192b 0, #05060a 55%);
      color: var(--text-main);
      display: flex;
      justify-content: center;
      padding: 32px 16px;
    }

    .page {
      width: 100%;
      max-width: 960px;
    }

    .top-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 18px;
      gap: 12px;
    }

    .top-left {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
      color: var(--text-muted);
    }

    .logo-dot {
      width: 9px;
      height: 9px;
      border-radius: 999px;
      background: radial-gradient(circle at 30% 20%, #ffe9b0, #f5b544);
      box-shadow: 0 0 12px rgba(245, 181, 68, 0.7);
    }

    .top-right a {
      font-size: 13px;
      color: var(--text-muted);
      text-decoration: none;
      padding: 6px 10px;
      border-radius: 999px;
      border: 1px solid rgba(255, 255, 255, 0.06);
      background: rgba(5, 6, 10, 0.7);
      backdrop-filter: blur(10px);
      transition: background 0.18s ease, border-color 0.18s ease, color 0.18s ease;
    }

    .top-right a:hover {
      background: rgba(245, 181, 68, 0.08);
      border-color: rgba(245, 181, 68, 0.6);
      color: #ffe9b0;
    }

    .card {
      background: radial-gradient(circle at top left, #171b2b 0, #05060a 55%);
      border-radius: 22px;
      border: 1px solid rgba(255, 255, 255, 0.06);
      box-shadow: var(--shadow-soft);
      padding: 26px 24px 28px;
      position: relative;
      overflow: hidden;
    }

    .card::before {
      content: "";
      position: absolute;
      inset: -40%;
      background:
        radial-gradient(circle at 0 0, rgba(245, 181, 68, 0.12), transparent 55%),
        radial-gradient(circle at 100% 0, rgba(93, 196, 255, 0.12), transparent 55%);
      opacity: 0.7;
      pointer-events: none;
    }

    .card-inner {
      position: relative;
      z-index: 1;
    }

    .badge-row {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-bottom: 14px;
    }

    .badge {
      font-size: 11px;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      padding: 4px 9px;
      border-radius: 999px;
      border: 1px solid rgba(255, 255, 255, 0.08);
      color: var(--text-muted);
      background: rgba(5, 6, 10, 0.7);
      backdrop-filter: blur(10px);
    }

    .badge-accent {
      border-color: rgba(245, 181, 68, 0.7);
      background: var(--accent-soft);
      color: #ffe9b0;
    }

    h1 {
      margin: 0 0 4px;
      font-size: 26px;
      letter-spacing: 0.02em;
    }

    .subtitle {
      font-size: 14px;
      color: var(--text-muted);
      margin-bottom: 14px;
    }

    .meta {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      font-size: 12px;
      color: var(--text-muted);
      margin-bottom: 18px;
    }

    .meta span {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 4px 9px;
      border-radius: 999px;
      border: 1px solid rgba(255, 255, 255, 0.06);
      background: rgba(5, 6, 10, 0.7);
    }

    .meta-dot {
      width: 6px;
      height: 6px;
      border-radius: 999px;
      background: #4ade80;
    }

    .content {
      border-radius: var(--radius-lg);
      border: 1px solid var(--border-subtle);
      background: rgba(5, 6, 10, 0.85);
      padding: 18px 16px 18px;
      font-size: 14px;
      line-height: 1.6;
      color: #e3e4f3;
    }

    .content h2 {
      font-size: 17px;
      margin: 18px 0 6px;
    }

    .content h3 {
      font-size: 15px;
      margin: 16px 0 4px;
    }

    .content p {
      margin: 6px 0 8px;
    }

    .content ol,
    .content ul {
      margin: 6px 0 10px 20px;
      padding-left: 4px;
    }

    .content li {
      margin: 3px 0;
    }

    .pill-row {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
      margin: 8px 0 4px;
    }

    .pill {
      font-size: 11px;
      padding: 3px 8px;
      border-radius: 999px;
      border: 1px solid rgba(255, 255, 255, 0.06);
      color: var(--text-muted);
    }

    .footer-meta {
      margin-top: 16px;
      padding-top: 10px;
      border-top: 1px dashed rgba(255, 255, 255, 0.08);
      font-size: 12px;
      color: var(--text-muted);
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }

    .footer-meta span {
      padding: 3px 8px;
      border-radius: 999px;
      border: 1px solid rgba(255, 255, 255, 0.06);
      background: rgba(5, 6, 10, 0.7);
    }

    @media (max-width: 640px) {
      body {
        padding: 20px 12px;
      }

      .card {
        padding: 20px 16px 22px;
      }

      h1 {
        font-size: 22px;
      }
    }
  </style>
</head>
<body>
  <div class="page">
    <div class="top-bar">
      <div class="top-left">
        <div class="logo-dot"></div>
        <span>Wheelder Circular • Release Notes</span>
      </div>
      <div class="top-right">
        <a href="#">Back to Center</a>
      </div>
    </div>

    <section class="card">
      <div class="card-inner">
        <div class="badge-row">
          <span class="badge badge-accent">New</span>
          <span class="badge">Proof of Concept</span>
          <span class="badge">AI Orchestration</span>
        </div>

        <h1>Wheelder Circular</h1>
        <div class="subtitle">
          The Ultimate Invention of the AI Age — The Master of All AI Innovation Tools
        </div>

        <div class="meta">
          <span><span class="meta-dot"></span>Version 1.0-poc</span>
          <span>Release Date: March 5, 2026</span>
          <span>Status: Proof of Concept, Rapidly Evolving</span>
          <span>License: Freemium (Coming Soon)</span>
        </div>

        <div class="content">
          <p>
            This is a Proof of Concept (PoC) — a very new and early-stage innovation.
            Wheelder Circular is immature by design at this stage. It is a committed,
            rapidly evolving project with a clear vision and an ambitious roadmap.
            What you see today is the seed of something much larger: the operating
            system for the AI age.
          </p>

          <h2>What Is Wheelder Circular?</h2>
          <p>
            Wheelder Circular is a meta-AI orchestrator, a unified home where every
            brilliant AI platform, chatbot, agent, automation framework, and creative
            engine comes together under one roof.
          </p>
          <p>
            We live in a golden age of AI. Incredible tools have been built by
            extraordinary teams around the world: DeepSeek, Kimi2, Qwen, ChatGPT,
            Claude, Gemini, Grok, OpenClaw, n8n, Claude Code, Claude Cowork and many
            more. Each of these platforms is remarkable on its own. Wheelder Circular
            celebrates all of them by creating the first unified orchestration layer
            that brings their collective power to every user through a single,
            intelligent interface.
          </p>
          <p>
            Circular analyzes what you need, gives you an immediate answer, and then
            recommends the best combination of these amazing tools for your specific
            task, all while keeping your conversation and context intact. It is the
            orchestrator that amplifies the brilliance of the entire AI ecosystem.
          </p>

          <h2>How It Works</h2>
          <ol>
            <li>You ask a question inside Circular.</li>
            <li>Circular provides an immediate, helpful base answer powered by Groq LLM.</li>
            <li>It recommends the best external AI tools for your specific task, ranked by relevance.</li>
            <li>It shows you step-by-step instructions for how to use each recommended tool.</li>
            <li>Your conversation, context, and workflow history stay preserved as your home base.</li>
          </ol>
          <p>
            Circular intelligently classifies every query by type (coding, research,
            creative, learning, writing, automation, data analysis), determines
            complexity, and maps it to the right tools across the entire AI landscape.
          </p>

          <h2>The AI Tools We Celebrate and Connect</h2>
          <p>
            Circular's tool database spans every major category of AI innovation:
          </p>

          <h3>Chatbots and Reasoning</h3>
          <p>
            ChatGPT by OpenAI, Claude and Claude Cowork by Anthropic, Gemini by
            Google, Grok by xAI, DeepSeek, Kimi2 by Moonshot AI, Qwen by Alibaba,
            Baichuan, Yi by 01.AI, ChatGLM by Zhipu AI, Ernie Bot by Baidu.
          </p>

          <h3>Coding and Development</h3>
          <p>
            GitHub Copilot, Cursor, Windsurf (Cascade), v0 by Vercel, Replit,
            Bolt.new, OpenClaw.
          </p>

          <h3>Research and Knowledge</h3>
          <p>
            Tavily, NotebookLM by Google, Consensus.
          </p>

          <h3>Creative and Design</h3>
          <p>
            Midjourney, DALL-E by OpenAI, Stable Diffusion by Stability AI, Pollinations.
          </p>

          <h3>Writing and Content</h3>
          <p>
            Jasper, Copy.ai, Grammarly.
          </p>

          <h3>Audio, Voice, and Video</h3>
          <p>
            ElevenLabs, RunwayML.
          </p>

          <h3>Agent Frameworks</h3>
          <p>
            LangChain, LangGraph, CrewAI, AutoGen.
          </p>

          <h3>Automation Platforms</h3>
          <p>
            n8n, Zapier, Make.
          </p>

          <h3>Open Source Ecosystem</h3>
          <p>
            Hugging Face, Ollama, vLLM, LocalAI — and 50+ more tools continuously
            being added.
          </p>
          <p>
            Every one of these tools represents extraordinary innovation. Circular
            exists to help users get the most out of all of them.
          </p>

          <h2>Key Features</h2>
          <h3>Smart Tool Recommendations</h3>
          <p>
            Smart recommendations of up to 5 tools per query, ranked by relevance,
            with direct visit links.
          </p>

          <h3>Query Classification</h3>
          <p>
            “Explain quantum computing” routes to NotebookLM, Tavily, and ChatGPT.
            “Build a REST API” routes to GitHub Copilot, Cursor, and Claude Code.
            “Design a logo” routes to Midjourney, DALL-E, and Stable Diffusion.
            “Build a data pipeline” routes to n8n, LangChain, and Make.
          </p>

          <h3>Session Management</h3>
          <p>
            Full conversation history across multiple queries, follow-up questions
            within the same session, and context preserved for multi-step workflows.
          </p>

          <h3>Workflow Deepening</h3>
          <p>
            The Deepen button generates step-by-step workflows showing which tool to
            use at each step, with instructions for tool integration, up to 7 depth
            levels.
          </p>

          <h3>Unified Interface</h3>
          <p>
            One clean interface for all AI interactions with a sidebar for history,
            real-time recommendations, and direct links to every tool.
          </p>

          <h2>Appreciation and Acknowledgments</h2>
          <p>
            Wheelder Circular would not exist without the extraordinary open-source
            community and the visionary companies that have made AI accessible to
            everyone. We express our deepest gratitude.
          </p>

          <h3>To Groq and NVIDIA</h3>
          <p>
            Thank you for providing blazing-fast LLM inference infrastructure. Groq's
            LPU technology powers the real-time responses at the heart of Circular.
            NVIDIA's GPU ecosystem has made the entire AI revolution possible. Your
            commitment to speed and accessibility is the backbone of what we have
            built.
          </p>

          <h3>To Meta and Ollama</h3>
          <p>
            Thank you to Meta for releasing the Llama family of models as open source,
            fundamentally democratizing access to world-class language models. Thank
            you to Ollama for making it effortless to run these models locally. The
            Llama models are the engine inside Circular's intelligence.
          </p>

          <h3>To Pollinations</h3>
          <p>
            Thank you for providing open-source AI image generation APIs that make
            creative AI accessible to everyone without friction.
          </p>

          <h3>To Hugging Face</h3>
          <p>
            Thank you for building the home of open-source AI. Your model hub,
            datasets, and community have accelerated AI development for millions of
            developers worldwide.
          </p>

          <h3>To LangChain, LangGraph, and n8n</h3>
          <p>
            Thank you for creating the definitive frameworks for LLM-powered
            applications, multi-agent systems, and open-source workflow automation.
            Circular builds on the principles you pioneered.
          </p>

          <h3>To vLLM, LocalAI, and the broader open-source community</h3>
          <p>
            Thank you to every contributor, maintainer, and researcher who has
            published a paper, released a model, shared a dataset, or written
            documentation. Tools like CrewAI, AutoGen, Haystack, txtai, PrivateGPT,
            Jan, LM Studio, and GPT4All have collectively built the ecosystem that
            makes Circular possible.
          </p>

          <h3>To the AI platform pioneers</h3>
          <p>
            Thank you to OpenAI for ChatGPT, to Anthropic for Claude and Claude
            Cowork, to Google for Gemini and NotebookLM, to xAI for Grok, to DeepSeek
            for advancing the frontiers of reasoning, to Moonshot AI for Kimi2, to
            Alibaba for Qwen, to Baidu for Ernie, to Zhipu AI for ChatGLM, to 01.AI
            for Yi, to GitHub for Copilot, to Vercel for v0, to Replit, Cursor,
            Windsurf, Midjourney, Stability AI, ElevenLabs, RunwayML, Jasper, Copy.ai,
            Grammarly, Zapier, Make, and to every AI company and team building tools
            that move humanity forward. Your innovations inspire everything we do.
            Wheelder Circular exists to amplify and honor the work of every one of
            you.
          </p>

          <h2>About Wheelder</h2>
          <p>
            Wheelder is building the infrastructure for the AI age. The mission is to
            make AI accessible, coordinated, and productive for everyone. Wheelder
            Circular does not replace any tool. It elevates all of them — like father.
            Every platform that exists, and every platform yet to be built, finds its
            place inside the Circular ecosystem.
          </p>
          <p>
            This is a Proof of Concept today. It is the operating system for the AI
            age tomorrow.
          </p>

          <div class="footer-meta">
            <span>Version: 1.0-poc</span>
            <span>Release Date: March 5, 2026</span>
            <span>Status: Proof of Concept, Rapidly Evolving</span>
            <span>License: Freemium (Coming Soon)</span>
          </div>
        </div>
      </div>
    </section>
  </div>
</body>
</html>
