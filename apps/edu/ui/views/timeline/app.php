<?php
// WHY: /timeline — Public Innovation Timeline & Proof Page
// Displays verifiable evidence of Wheelder's innovation history
// No auth required — this is a public proof page for sharing

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// WHY: load the hash manifest data from the project root
$hashManifestPath = dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/HASH_MANIFEST.md';
$hashManifestExists = file_exists($hashManifestPath);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Innovation Timeline — Wheelder</title>

    <!-- Open Graph -->
    <meta property="og:title" content="Wheelder Innovation Timeline — Built Since 2023">
    <meta property="og:description" content="Verifiable proof of innovation: git commits, cryptographic hashes, and timestamps dating back to October 2023.">
    <meta property="og:image" content="https://wheelder.com/pool/assets/og-image.png">
    <meta property="og:url" content="https://wheelder.com/timeline">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Wheelder">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Wheelder Innovation Timeline — Built Since 2023">
    <meta name="twitter:description" content="Verifiable proof of innovation: git commits, cryptographic hashes, and timestamps dating back to October 2023.">
    <meta name="twitter:image" content="https://wheelder.com/pool/assets/og-image.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --bg-primary: #0a0a0a;
            --bg-secondary: #141414;
            --bg-card: #1a1a1a;
            --border-color: #2a2a2a;
            --text-primary: #e0e0e0;
            --text-secondary: #888;
            --accent: #0d6efd;
            --accent-glow: rgba(13, 110, 253, 0.3);
            --success: #28a745;
            --warning: #ffc107;
            --info: #17a2b8;
            --timeline-line: #333;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: var(--bg-primary);
            color: var(--text-primary);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
        }

        /* Navbar */
        .navbar {
            background: var(--bg-secondary) !important;
            border-bottom: 1px solid var(--border-color);
            backdrop-filter: blur(10px);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
            letter-spacing: 0.5px;
        }

        /* Hero */
        .hero {
            text-align: center;
            padding: 5rem 1rem 3rem;
            background: linear-gradient(180deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);
            border-bottom: 1px solid var(--border-color);
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #fff 0%, #0d6efd 50%, #fff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero .subtitle {
            font-size: 1.25rem;
            color: var(--text-secondary);
            max-width: 700px;
            margin: 0 auto 2rem;
        }

        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 3rem;
            flex-wrap: wrap;
        }

        .hero-stat {
            text-align: center;
        }

        .hero-stat .number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--accent);
        }

        .hero-stat .label {
            font-size: 0.85rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Evidence callout */
        .evidence-callout {
            background: linear-gradient(135deg, rgba(13,110,253,0.1), rgba(13,110,253,0.05));
            border: 1px solid rgba(13,110,253,0.3);
            border-left: 4px solid var(--accent);
            border-radius: 8px;
            padding: 1.25rem 1.5rem;
            margin: 1.5rem 0;
            font-size: 0.95rem;
        }

        .evidence-callout.warning {
            background: linear-gradient(135deg, rgba(255,193,7,0.1), rgba(255,193,7,0.05));
            border-color: rgba(255,193,7,0.3);
            border-left-color: var(--warning);
        }

        .evidence-callout.success {
            background: linear-gradient(135deg, rgba(40,167,69,0.1), rgba(40,167,69,0.05));
            border-color: rgba(40,167,69,0.3);
            border-left-color: var(--success);
        }

        .evidence-callout code {
            background: rgba(255,255,255,0.1);
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            font-size: 0.85em;
            color: #ffd700;
        }

        /* Timeline */
        .timeline-section {
            padding: 3rem 0;
        }

        .timeline-section h2 {
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 3rem;
            color: var(--text-primary);
        }

        .timeline {
            position: relative;
            max-width: 900px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--timeline-line);
            transform: translateX(-50%);
        }

        .timeline-era {
            position: relative;
            margin-bottom: 3rem;
        }

        .timeline-era:last-child {
            margin-bottom: 0;
        }

        /* Alternating left/right */
        .timeline-era:nth-child(odd) .timeline-card {
            margin-left: auto;
            margin-right: calc(50% + 2rem);
        }

        .timeline-era:nth-child(even) .timeline-card {
            margin-left: calc(50% + 2rem);
        }

        .timeline-dot {
            position: absolute;
            left: 50%;
            top: 2rem;
            width: 16px;
            height: 16px;
            background: var(--accent);
            border: 3px solid var(--bg-primary);
            border-radius: 50%;
            transform: translateX(-50%);
            z-index: 2;
            box-shadow: 0 0 10px var(--accent-glow);
        }

        .timeline-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            width: calc(50% - 3rem);
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .timeline-card:hover {
            border-color: var(--accent);
            box-shadow: 0 4px 20px var(--accent-glow);
        }

        .timeline-date {
            font-size: 0.85rem;
            color: var(--accent);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }

        .timeline-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .timeline-desc {
            font-size: 0.95rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        /* Evidence badges */
        .evidence-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .badge-evidence {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.3rem 0.6rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-git { background: rgba(40,167,69,0.2); color: #5fd07e; border: 1px solid rgba(40,167,69,0.3); }
        .badge-sql { background: rgba(255,193,7,0.2); color: #ffd700; border: 1px solid rgba(255,193,7,0.3); }
        .badge-drive { background: rgba(66,133,244,0.2); color: #6fadff; border: 1px solid rgba(66,133,244,0.3); }
        .badge-github { background: rgba(255,255,255,0.1); color: #ccc; border: 1px solid rgba(255,255,255,0.2); }
        .badge-deploy { background: rgba(138,43,226,0.2); color: #c77dff; border: 1px solid rgba(138,43,226,0.3); }
        .badge-archive { background: rgba(255,140,0,0.2); color: #ffb347; border: 1px solid rgba(255,140,0,0.3); }

        /* Proof toggle */
        .proof-toggle {
            cursor: pointer;
            color: var(--accent);
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .proof-toggle:hover {
            text-decoration: underline;
        }

        .proof-details {
            display: none;
            margin-top: 0.75rem;
            padding: 0.75rem;
            background: rgba(0,0,0,0.3);
            border-radius: 8px;
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
            font-size: 0.8rem;
            color: var(--text-secondary);
            word-break: break-all;
        }

        .proof-details.show {
            display: block;
        }

        .proof-details .proof-row {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 0.35rem;
        }

        .proof-details .proof-label {
            color: var(--text-secondary);
            min-width: 80px;
            flex-shrink: 0;
        }

        .proof-details .proof-value {
            color: #5fd07e;
            word-break: break-all;
        }

        .proof-details .proof-value a {
            color: var(--accent);
            text-decoration: none;
        }

        .proof-details .proof-value a:hover {
            text-decoration: underline;
        }

        /* Key Evidence section */
        .key-evidence {
            padding: 4rem 0;
            background: var(--bg-secondary);
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
        }

        .key-evidence h2 {
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 2rem;
        }

        .evidence-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 1.5rem;
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .evidence-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
        }

        .evidence-card h4 {
            font-size: 1.1rem;
            margin-bottom: 0.75rem;
            color: var(--text-primary);
        }

        .evidence-card p {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-bottom: 0;
        }

        .evidence-card .icon {
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
        }

        /* Hash Verification Table */
        .hash-section {
            padding: 4rem 0;
        }

        .hash-section h2 {
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .hash-section .section-desc {
            text-align: center;
            color: var(--text-secondary);
            max-width: 700px;
            margin: 0 auto 2rem;
            font-size: 0.95rem;
        }

        .hash-table-wrapper {
            max-width: 1100px;
            margin: 0 auto;
            overflow-x: auto;
            padding: 0 1rem;
        }

        .hash-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        .hash-table th {
            background: var(--bg-secondary);
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.75rem;
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 2px solid var(--border-color);
            position: sticky;
            top: 0;
        }

        .hash-table td {
            padding: 0.6rem 1rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .hash-table tr:hover td {
            background: rgba(13,110,253,0.05);
        }

        .hash-table .hash-value {
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
            font-size: 0.8rem;
            color: #5fd07e;
        }

        .hash-table .milestone {
            background: rgba(13,110,253,0.08);
        }

        .hash-table .milestone td:first-child {
            border-left: 3px solid var(--accent);
        }

        .hash-filter {
            max-width: 1100px;
            margin: 0 auto 1rem;
            padding: 0 1rem;
        }

        .hash-filter input {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
            font-size: 0.9rem;
        }

        .hash-filter input::placeholder {
            color: var(--text-secondary);
        }

        .hash-filter input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 2px var(--accent-glow);
        }

        /* Verification section */
        .verification {
            padding: 4rem 0;
            background: var(--bg-secondary);
            border-top: 1px solid var(--border-color);
        }

        .verification h2 {
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 2rem;
        }

        .verify-steps {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .verify-step {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 2rem;
            align-items: flex-start;
        }

        .verify-step-num {
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            background: var(--accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .verify-step-content h4 {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .verify-step-content p {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .verify-step-content code {
            display: block;
            background: rgba(0,0,0,0.4);
            padding: 0.75rem 1rem;
            border-radius: 6px;
            font-size: 0.85rem;
            color: #5fd07e;
            overflow-x: auto;
        }

        /* Footer */
        .timeline-footer {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--text-secondary);
            font-size: 0.85rem;
            border-top: 1px solid var(--border-color);
        }

        .timeline-footer a {
            color: var(--accent);
            text-decoration: none;
        }

        .timeline-footer a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 { font-size: 2rem; }
            .hero-stats { gap: 1.5rem; }
            .hero-stat .number { font-size: 1.8rem; }

            .timeline::before { left: 1.5rem; }

            .timeline-dot {
                left: 1.5rem;
            }

            .timeline-era:nth-child(odd) .timeline-card,
            .timeline-era:nth-child(even) .timeline-card {
                margin-left: 3.5rem;
                margin-right: 0;
                width: calc(100% - 3.5rem);
            }

            .evidence-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">
            <i class="fas fa-history"></i> Wheelder Timeline
        </a>
        <div class="d-flex gap-2">
            <a href="/center" class="btn btn-sm btn-outline-light">
                <i class="fas fa-arrow-left"></i> Center
            </a>
            <a href="/releases" class="btn btn-sm btn-outline-light">
                <i class="fas fa-rocket"></i> Releases
            </a>
        </div>
    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <h1>Innovation Timeline</h1>
    <p class="subtitle">
        A verifiable record of Wheelder's development history &mdash; from the first database
        entries in October 2023 to the multi-app research platform deployed today.
        Every claim below is backed by git commits, SQL timestamps, and cryptographic file hashes.
    </p>
    <div class="hero-stats">
        <div class="hero-stat">
            <div class="number">2023</div>
            <div class="label">First Evidence</div>
        </div>
        <div class="hero-stat">
            <div class="number">45</div>
            <div class="label">Archived Snapshots</div>
        </div>
        <div class="hero-stat">
            <div class="number">166+</div>
            <div class="label">Git Commits</div>
        </div>
        <div class="hero-stat">
            <div class="number">7</div>
            <div class="label">Repositories</div>
        </div>
    </div>
</section>

<!-- Key Evidence Callouts -->
<section class="key-evidence">
    <h2>Key Evidence</h2>
    <div class="evidence-grid">
        <div class="evidence-card">
            <div class="icon" style="color: #ffd700;">
                <i class="fas fa-database"></i>
            </div>
            <h4>SQL Timestamps: October 2023</h4>
            <p>
                Database backup contains records with Unix timestamp
                <code>1697997845</code> &mdash; October 22, 2023. The users table,
                topics table, and content rows all carry creation dates from
                October 21&ndash;22, 2023, proving the platform existed and
                was actively used at that time.
            </p>
        </div>

        <div class="evidence-card">
            <div class="icon" style="color: #5fd07e;">
                <i class="fas fa-code-branch"></i>
            </div>
            <h4>First Git Commit: March 2024</h4>
            <p>
                The <code>relearn</code> repository's initial commit
                (March 18, 2024) includes the complete <code>learn.php</code>
                file with a dual-panel layout (<code>#answerDiv</code> +
                <code>#imageDiv</code>), OpenAI integration, keyword extraction,
                and browser Text-to-Speech &mdash; all in a single commit,
                proving this code was written before it was version-controlled.
            </p>
        </div>

        <div class="evidence-card">
            <div class="icon" style="color: #ff6b6b;">
                <i class="fas fa-robot"></i>
            </div>
            <h4>Deprecated AI Model Hardcoded</h4>
            <p>
                The original code uses OpenAI model
                <code>gpt-3.5-turbo-16k-0613</code> &mdash; a snapshot model
                released June 13, 2023 and deprecated June 13, 2024. This
                proves the code was written during that window. The API key
                format (<code>sk-</code> prefix) further confirms pre-2024
                origin, as OpenAI switched to <code>sk-proj-</code> in early 2024.
            </p>
        </div>

        <div class="evidence-card">
            <div class="icon" style="color: #c77dff;">
                <i class="fas fa-fingerprint"></i>
            </div>
            <h4>Organic Brand Evolution</h4>
            <p>
                The original code uses the misspelling
                <code>"Wheeleder"</code> as the brand name. This was later
                corrected to <code>"Wheelder"</code>. This kind of organic
                evolution &mdash; complete with typos &mdash; is characteristic
                of original work, not copying.
            </p>
        </div>
    </div>
</section>

<!-- Timeline -->
<section class="timeline-section">
    <h2>Development Timeline</h2>

    <div class="timeline">

        <!-- Era 1: October 2023 -->
        <div class="timeline-era">
            <div class="timeline-dot"></div>
            <div class="timeline-card">
                <div class="timeline-date">October 2023</div>
                <div class="timeline-title">Project Genesis &mdash; Database Backups</div>
                <div class="timeline-desc">
                    SQL backup files contain user registrations, topic entries, and content
                    rows all timestamped October 21&ndash;22, 2023. The platform was built
                    using PHP + MySQL with a multi-app architecture (edu, blog, notes).
                </div>
                <div class="evidence-badges">
                    <span class="badge-evidence badge-sql"><i class="fas fa-database"></i> SQL Timestamp</span>
                    <span class="badge-evidence badge-archive"><i class="fas fa-archive"></i> ZIP Archive</span>
                </div>
                <div class="proof-toggle" onclick="toggleProof(this)">
                    <i class="fas fa-chevron-right"></i> View proof details
                </div>
                <div class="proof-details">
                    <div class="proof-row">
                        <span class="proof-label">Unix Time:</span>
                        <span class="proof-value">1697997845 (Oct 22, 2023 18:24:05 UTC)</span>
                    </div>
                    <div class="proof-row">
                        <span class="proof-label">Tables:</span>
                        <span class="proof-value">users, topics, notes — all with Oct 2023 created_at</span>
                    </div>
                    <div class="proof-row">
                        <span class="proof-label">AI Model:</span>
                        <span class="proof-value">gpt-3.5-turbo-16k-0613 (released Jun 2023, deprecated Jun 2024)</span>
                    </div>
                    <div class="proof-row">
                        <span class="proof-label">Brand Name:</span>
                        <span class="proof-value">"Wheeleder" (original misspelling)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Era 2: March 2024 -->
        <div class="timeline-era">
            <div class="timeline-dot"></div>
            <div class="timeline-card">
                <div class="timeline-date">March 2024</div>
                <div class="timeline-title">First Git Commit &mdash; Full Platform</div>
                <div class="timeline-desc">
                    The <code>relearn</code> repository's initial commit contains the complete
                    dual-panel learn.php with OpenAI integration, image generation,
                    keyword extraction, and browser TTS. A complete app committed whole
                    proves it was developed before being version-controlled.
                </div>
                <div class="evidence-badges">
                    <span class="badge-evidence badge-git"><i class="fas fa-code-branch"></i> Git Commit</span>
                    <span class="badge-evidence badge-github"><i class="fab fa-github"></i> GitHub</span>
                </div>
                <div class="proof-toggle" onclick="toggleProof(this)">
                    <i class="fas fa-chevron-right"></i> View proof details
                </div>
                <div class="proof-details">
                    <div class="proof-row">
                        <span class="proof-label">Repo:</span>
                        <span class="proof-value"><a href="https://github.com/abbaays/relearn" target="_blank">github.com/abbaays/relearn</a></span>
                    </div>
                    <div class="proof-row">
                        <span class="proof-label">Date:</span>
                        <span class="proof-value">March 18, 2024</span>
                    </div>
                    <div class="proof-row">
                        <span class="proof-label">Key File:</span>
                        <span class="proof-value">apps/edu/ui/views/notes/cms/learn.php</span>
                    </div>
                    <div class="proof-row">
                        <span class="proof-label">Features:</span>
                        <span class="proof-value">Dual-panel (#answerDiv + #imageDiv), OpenAI API, keyword extraction, speechSynthesis TTS</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Era 3: August 2025 -->
        <div class="timeline-era">
            <div class="timeline-dot"></div>
            <div class="timeline-card">
                <div class="timeline-date">August 2025</div>
                <div class="timeline-title">Platform Rewrite &mdash; PHP + Python</div>
                <div class="timeline-desc">
                    Complete platform rewrite. The learn app was extracted and rebuilt
                    with proper MVC architecture. A parallel Python/FastAPI backend
                    was prototyped. The blog CMS, note system, and search were all rebuilt.
                </div>
                <div class="evidence-badges">
                    <span class="badge-evidence badge-git"><i class="fas fa-code-branch"></i> Git Commit</span>
                    <span class="badge-evidence badge-drive"><i class="fab fa-google-drive"></i> Google Drive</span>
                    <span class="badge-evidence badge-archive"><i class="fas fa-archive"></i> ZIP Archive</span>
                </div>
                <div class="proof-toggle" onclick="toggleProof(this)">
                    <i class="fas fa-chevron-right"></i> View proof details
                </div>
                <div class="proof-details">
                    <div class="proof-row">
                        <span class="proof-label">Archives:</span>
                        <span class="proof-value">#3 wheelder-learn app-updated.zip (Aug 20, 2025)</span>
                    </div>
                    <div class="proof-row">
                        <span class="proof-label">MD5:</span>
                        <span class="proof-value">f7abb0e8711284deb02257e2ebf1d36f</span>
                    </div>
                    <div class="proof-row">
                        <span class="proof-label">Repo:</span>
                        <span class="proof-value"><a href="https://github.com/abbaays/wheelder_platform" target="_blank">wheelder_platform</a></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Era 4: November 2025 -->
        <div class="timeline-era">
            <div class="timeline-dot"></div>
            <div class="timeline-card">
                <div class="timeline-date">November 2025</div>
                <div class="timeline-title">Production Deployment &mdash; DigitalOcean</div>
                <div class="timeline-desc">
                    Blog system restored (create, edit, delete), database connection
                    overhauled, auto-deployment pipeline created, and the platform
                    was deployed to DigitalOcean cloud for the first time.
                </div>
                <div class="evidence-badges">
                    <span class="badge-evidence badge-git"><i class="fas fa-code-branch"></i> Git Commit</span>
                    <span class="badge-evidence badge-deploy"><i class="fas fa-cloud"></i> Deployed</span>
                    <span class="badge-evidence badge-archive"><i class="fas fa-archive"></i> 7 ZIP Archives</span>
                </div>
                <div class="proof-toggle" onclick="toggleProof(this)">
                    <i class="fas fa-chevron-right"></i> View proof details
                </div>
                <div class="proof-details">
                    <div class="proof-row">
                        <span class="proof-label">Archives:</span>
                        <span class="proof-value">#6 to #11 (V2.0 through V2.4 + final backup)</span>
                    </div>
                    <div class="proof-row">
                        <span class="proof-label">Key MD5:</span>
                        <span class="proof-value">#10 dbf3eea33aebb4a87f62154e4231c163 (V2.4-Deployed)</span>
                    </div>
                    <div class="proof-row">
                        <span class="proof-label">Server:</span>
                        <span class="proof-value">DigitalOcean droplet — LAMP stack</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Era 5: February 2026 - Circular Search -->
        <div class="timeline-era">
            <div class="timeline-dot"></div>
            <div class="timeline-card">
                <div class="timeline-date">February 6, 2026</div>
                <div class="timeline-title">Circular Search &amp; Deep Research</div>
                <div class="timeline-desc">
                    Introduced Circular Search &mdash; a depth-based research system
                    (levels 1-7) that progressively deepens understanding of a topic.
                    Combined with the existing dual-panel layout for a unified
                    ask-and-explore experience.
                </div>
                <div class="evidence-badges">
                    <span class="badge-evidence badge-git"><i class="fas fa-code-branch"></i> Git Commit</span>
                    <span class="badge-evidence badge-archive"><i class="fas fa-archive"></i> ZIP Archive</span>
                </div>
                <div class="proof-toggle" onclick="toggleProof(this)">
                    <i class="fas fa-chevron-right"></i> View proof details
                </div>
                <div class="proof-details">
                    <div class="proof-row">
                        <span class="proof-label">Archive:</span>
                        <span class="proof-value">#16 wheelder-V2.8-Circular Search and the app layout is updated.zip</span>
                    </div>
                    <div class="proof-row">
                        <span class="proof-label">MD5:</span>
                        <span class="proof-value">e6ade3cbf94c6f78226ac009bc7cf6d2</span>
                    </div>
                    <div class="proof-row">
                        <span class="proof-label">Key File:</span>
                        <span class="proof-value">apps/edu/ui/views/center/ajax_handler.php (depth levels 1-7)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Era 6: February 2026 - Research Threads -->
        <div class="timeline-era">
            <div class="timeline-dot"></div>
            <div class="timeline-card">
                <div class="timeline-date">February 7, 2026</div>
                <div class="timeline-title">Research Threads &amp; Wheelder-Lib</div>
                <div class="timeline-desc">
                    Research threads allow users to maintain ongoing research conversations.
                    The Wheelder-Lib proof of concept was completed &mdash; a standalone
                    library version of the core research engine.
                </div>
                <div class="evidence-badges">
                    <span class="badge-evidence badge-git"><i class="fas fa-code-branch"></i> Git Commit</span>
                    <span class="badge-evidence badge-archive"><i class="fas fa-archive"></i> 5 ZIP Archives</span>
                </div>
                <div class="proof-toggle" onclick="toggleProof(this)">
                    <i class="fas fa-chevron-right"></i> View proof details
                </div>
                <div class="proof-details">
                    <div class="proof-row">
                        <span class="proof-label">Archives:</span>
                        <span class="proof-value">#17 V2.9 through #22 V3.4</span>
                    </div>
                    <div class="proof-row">
                        <span class="proof-label">Key MD5:</span>
                        <span class="proof-value">#19 0c06cf1fcaa48e00dafcef4b7a364e93 (Research Threads)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Era 7: February 8, 2026 - TTS -->
        <div class="timeline-era">
            <div class="timeline-dot"></div>
            <div class="timeline-card">
                <div class="timeline-date">February 8, 2026</div>
                <div class="timeline-title">Text-to-Speech &amp; Cloud Deployment</div>
                <div class="timeline-desc">
                    Upgraded TTS from browser speechSynthesis to Edge TTS neural voices.
                    The complete platform was deployed to DigitalOcean with TTS proxy support.
                </div>
                <div class="evidence-badges">
                    <span class="badge-evidence badge-git"><i class="fas fa-code-branch"></i> Git Commit</span>
                    <span class="badge-evidence badge-deploy"><i class="fas fa-cloud"></i> Deployed</span>
                    <span class="badge-evidence badge-archive"><i class="fas fa-archive"></i> ZIP Archive</span>
                </div>
                <div class="proof-toggle" onclick="toggleProof(this)">
                    <i class="fas fa-chevron-right"></i> View proof details
                </div>
                <div class="proof-details">
                    <div class="proof-row">
                        <span class="proof-label">Archive:</span>
                        <span class="proof-value">#23 wheelder-V3.5-Text-to-Speech voice to updated.zip</span>
                    </div>
                    <div class="proof-row">
                        <span class="proof-label">MD5:</span>
                        <span class="proof-value">705cfdfe4e0ddc8ca7d328f862d72778</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Era 8: February 11, 2026 - Extreme Focus -->
        <div class="timeline-era">
            <div class="timeline-dot"></div>
            <div class="timeline-card">
                <div class="timeline-date">February 11, 2026</div>
                <div class="timeline-title">Extreme Focus Feature</div>
                <div class="timeline-desc">
                    A new approach to generated answers &mdash; the "Extreme Focus"
                    feature concentrates the AI output into a refined, distilled response
                    that highlights exactly what the user needs.
                </div>
                <div class="evidence-badges">
                    <span class="badge-evidence badge-git"><i class="fas fa-code-branch"></i> Git Commit</span>
                    <span class="badge-evidence badge-archive"><i class="fas fa-archive"></i> ZIP Archive</span>
                </div>
                <div class="proof-toggle" onclick="toggleProof(this)">
                    <i class="fas fa-chevron-right"></i> View proof details
                </div>
                <div class="proof-details">
                    <div class="proof-row">
                        <span class="proof-label">Archive:</span>
                        <span class="proof-value">#29 wheelder-V4.1-Extreme focus on the generated answer feature</span>
                    </div>
                    <div class="proof-row">
                        <span class="proof-label">MD5:</span>
                        <span class="proof-value">9af1bcca8c8946d2ebfe499ac2a9ed34</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Era 9: February 21-23, 2026 - Edu + Center App -->
        <div class="timeline-era">
            <div class="timeline-dot"></div>
            <div class="timeline-card">
                <div class="timeline-date">February 21-23, 2026</div>
                <div class="timeline-title">Lessons App &amp; Center App</div>
                <div class="timeline-desc">
                    The Edu-Lessons app was created with an AI-powered lesson generator
                    and publish system. The Center App was built as the primary
                    unified interface combining research, chat, and content creation.
                </div>
                <div class="evidence-badges">
                    <span class="badge-evidence badge-git"><i class="fas fa-code-branch"></i> Git Commit</span>
                    <span class="badge-evidence badge-deploy"><i class="fas fa-cloud"></i> Deployed</span>
                    <span class="badge-evidence badge-archive"><i class="fas fa-archive"></i> 5 ZIP Archives</span>
                </div>
                <div class="proof-toggle" onclick="toggleProof(this)">
                    <i class="fas fa-chevron-right"></i> View proof details
                </div>
                <div class="proof-details">
                    <div class="proof-row">
                        <span class="proof-label">Archives:</span>
                        <span class="proof-value">#33 V4.5 through #39 V5.1</span>
                    </div>
                    <div class="proof-row">
                        <span class="proof-label">Key MD5:</span>
                        <span class="proof-value">#37 87c1199b65223b34e3154e84e2c6cab1 (Center App created)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Era 10: February 25-28, 2026 - Current -->
        <div class="timeline-era">
            <div class="timeline-dot"></div>
            <div class="timeline-card">
                <div class="timeline-date">February 25-28, 2026</div>
                <div class="timeline-title">Spinning Prompt, Releases Portal &amp; Magic Link Auth</div>
                <div class="timeline-desc">
                    The prompt interface was redesigned with the spinning prompt modal.
                    A public releases portal was added for sharing feature announcements.
                    The authentication system was upgraded with Magic Link passwordless login.
                </div>
                <div class="evidence-badges">
                    <span class="badge-evidence badge-git"><i class="fas fa-code-branch"></i> Git Commit</span>
                    <span class="badge-evidence badge-deploy"><i class="fas fa-cloud"></i> Deployed</span>
                    <span class="badge-evidence badge-archive"><i class="fas fa-archive"></i> 4 ZIP Archives</span>
                </div>
                <div class="proof-toggle" onclick="toggleProof(this)">
                    <i class="fas fa-chevron-right"></i> View proof details
                </div>
                <div class="proof-details">
                    <div class="proof-row">
                        <span class="proof-label">Archives:</span>
                        <span class="proof-value">#41 v5.3 through #45 v5.6</span>
                    </div>
                    <div class="proof-row">
                        <span class="proof-label">Key MD5:</span>
                        <span class="proof-value">#41 540b19ca8714b83ef406f3a7f0f53f72 (Spinning Prompt)</span>
                    </div>
                    <div class="proof-row">
                        <span class="proof-label">Key MD5:</span>
                        <span class="proof-value">#44 0923fb53296784be7feebb35eb4e818b (Magic Link)</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Hash Verification Table -->
<section class="hash-section">
    <h2>Archive Hash Verification</h2>
    <p class="section-desc">
        Every ZIP archive has a cryptographic MD5 hash. Run the command below on any
        file to verify it matches. These hashes also match the checksums stored by
        Google Drive and OneDrive server-side.
    </p>

    <div class="hash-filter">
        <input type="text" id="hashSearch" placeholder="Search archives (e.g. Circular, V3.5, February)..." oninput="filterHashes()">
    </div>

    <div class="hash-table-wrapper">
        <table class="hash-table" id="hashTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Archive</th>
                    <th>Date</th>
                    <th>Size</th>
                    <th>MD5 Hash</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // WHY: milestone archives get highlighted in the table
                $milestones = [1, 2, 16, 17, 19, 23, 29, 37, 41, 44];
                $archives = [
                    [1, 'wheeleder_latest_update_and_backup_04_02_2024.zip', '2024-11-18', '192.8 MB', '25a90cf1cb61fce57fd8c6f5dd24a7f9'],
                    [2, 'relearning-master (1).zip', '2025-08-13', '60.9 MB', 'b4b2a126b971fab629ccb7ea25fa8c9d'],
                    [3, 'wheelder-learn app-updated.zip', '2025-08-20', '107 MB', 'f7abb0e8711284deb02257e2ebf1d36f'],
                    [4, 'wheelder-blog app and blog cms is updated.zip', '2025-08-22', '107.1 MB', '4928b5f50f9bfca4a78b4e5183ade51f'],
                    [5, 'wheelder-default blog is updated.zip', '2025-08-22', '107.1 MB', '656ba11c0808878799a2546509bf2fdf'],
                    [6, 'wheelder-V2.0-signup and login system...restored.zip', '2025-11-20', '107.3 MB', 'ffd9a05640b6b102b0b93da05ff816ba'],
                    [7, 'wheelder-V2.1-blog post edit is restored.zip', '2025-11-20', '107.3 MB', 'dfa60ffa3c8c507c64d0ee43bea6faff'],
                    [8, 'wheelder-V2.2-blog post delete function is restored.zip', '2025-11-20', '107.3 MB', 'f3907c18e0e306974b27601c0e8f377b'],
                    [9, 'wheelder-V2.3-Blog app database connection updated.zip', '2025-11-20', '107.3 MB', 'f27068ff3d2955b44290ea486286ffb5'],
                    [10, 'wheelder-V2.4-Blog deployed + autodeployment + dynamic DB.zip', '2025-11-20', '107.4 MB', 'dbf3eea33aebb4a87f62154e4231c163'],
                    [11, 'wheelder-final-backup-before the upgrade-Nov-29-2025.zip', '2025-11-29', '107.6 MB', '8949832565d3b5554f8b3dbbf0ba4976'],
                    [12, 'wheelder_platform-Latest Backup Dec 26 -25.zip', '2025-12-26', '54.6 MB', '457999434b25bd9c12a2bce76bd5c65e'],
                    [13, 'wheelder-V2.5-Learn app is updated - debugged.zip', '2026-02-05', '110.1 MB', '1b7f70eefd33e468a1f2bdabcc667998'],
                    [14, 'wheelder-V2.6-Chatbot text generation is functional.zip', '2026-02-05', '110.1 MB', 'd8a66fff40d85f0d05e6b0abac308c8d'],
                    [15, 'wheelder-V2.7-App layout is updated.zip', '2026-02-06', '110.1 MB', '839274d60178257a4338debe8b690aee'],
                    [16, 'wheelder-V2.8-Circular Search and app layout updated.zip', '2026-02-06', '110.4 MB', 'e6ade3cbf94c6f78226ac009bc7cf6d2'],
                    [17, 'wheelder-V2.9-Wheelder-Lib-PoC is completed.zip', '2026-02-06', '110.4 MB', 'd243f6535c3bb8e52d87cfbe9dd54618'],
                    [18, 'wheelder-V3.0-PoC completed with all features.zip', '2026-02-07', '110.4 MB', 'cd8debb432267811ab7ad355594a147c'],
                    [19, 'wheelder-V3.1-Research threads are updated.zip', '2026-02-07', '110.5 MB', '0c06cf1fcaa48e00dafcef4b7a364e93'],
                    [20, 'wheelder-V3.2-Backup before upgrade for release.zip', '2026-02-07', '110.5 MB', 'cdb53fcc2311f815602acc64346d67d2'],
                    [21, 'wheelder-V3.3-Upgraded.zip', '2026-02-07', '110.5 MB', '9b85cb89993da960f37533f99a56d478'],
                    [22, 'wheelder-V3.4-App layout has updated.zip', '2026-02-07', '110.5 MB', '899710df6ffbdcb751bee5f6e3e9d7b9'],
                    [23, 'wheelder-V3.5-Text-to-Speech voice updated.zip', '2026-02-08', '111.6 MB', '705cfdfe4e0ddc8ca7d328f862d72778'],
                    [24, 'wheelder-V3.6-App updated and deployed to cloud-DO.zip', '2026-02-08', '111.7 MB', '729838b60614eb697da68a6a9fd30c45'],
                    [25, 'wheelder-V3.7-Upgraded deployed version.zip', '2026-02-08', '111.8 MB', '8126c21c8130d839469ced59209af091'],
                    [26, 'wheelder-V3.8-Deployed version is updated.zip', '2026-02-10', '111.8 MB', '13504167fa394edb9b6e51d1e717b321'],
                    [27, 'wheelder-V3.9-Session expiration expanded to non-expire.zip', '2026-02-10', '111.9 MB', 'b6ebad048d472bd21f8e4bb5dba6f6c8'],
                    [28, 'wheelder-v4.0-App is functional.zip', '2026-02-10', '111.9 MB', '92e0bcf206f7bb62a6d3c8ae33ad2545'],
                    [29, 'wheelder-V4.1-Extreme focus on generated answer feature.zip', '2026-02-11', '112 MB', '9af1bcca8c8946d2ebfe499ac2a9ed34'],
                    [30, 'wheelder-V4.2-Layout Updated.zip', '2026-02-11', '112.2 MB', '45e47c94533f9e3149ab37b73e229154'],
                    [31, 'wheelder-V4.3-Image Generation upgraded.zip', '2026-02-12', '112.2 MB', 'e266a2fbb5d9e54a030b31fb560452e6'],
                    [32, 'wheelder-V4.4-Demo prepared and ready to share.zip', '2026-02-12', '112.4 MB', 'e92cbb4a4a688d105ddaa3fc7ffd00de'],
                    [33, 'wheelder-V4.5-Edu Lessons App is created.zip', '2026-02-21', '112.5 MB', 'e3c714d098c64c6e0df648fc0031a327'],
                    [34, 'wheelder-V4.6-Lesson Generator is functional.zip', '2026-02-22', '112.5 MB', '0ad9d4a7807e3c13f10b9878091142e0'],
                    [35, 'wheelder-V4.7-Publish button is functional.zip', '2026-02-22', '112.5 MB', 'def4717745c4e5d32548b597428eca47'],
                    [36, 'wheelder-V4.8-Lesson App upgraded with proper view.zip', '2026-02-23', '112.5 MB', '6496a93e5ff092b4ee5b3a19dc94f3f7'],
                    [37, 'wheelder-V4.9-Center App is created.zip', '2026-02-23', '112.7 MB', '87c1199b65223b34e3154e84e2c6cab1'],
                    [38, 'wheelder-V5.0-Layout is updated.zip', '2026-02-23', '112.8 MB', 'b50fbeedfe646ab6587ef45a73e9aeb8'],
                    [39, 'wheelder-V5.1-Updated the center app.zip', '2026-02-24', '112.8 MB', 'f6ee124010bb6ade676522393712e544'],
                    [40, 'wheelder-V5.2-Updated Apps Edu Deployed.zip', '2026-02-24', '113 MB', '3076da77fce9194fcf69a1b0b924bd0b'],
                    [41, 'wheelder-v5.3-Prompt box updated to new ways.zip', '2026-02-25', '113.3 MB', '540b19ca8714b83ef406f3a7f0f53f72'],
                    [42, 'wheelder-v5.4-Releases portal added.zip', '2026-02-26', '113.4 MB', '51c996db7edb4103592f3c49f37cdd77'],
                    [43, 'wheelder-updated.zip', '2026-02-26', '113.6 MB', 'd3108e16207804a937c0639360e89f11'],
                    [44, 'wheelder-v5.5-Login system updated with Magic Link.zip', '2026-02-28', '113.7 MB', '0923fb53296784be7feebb35eb4e818b'],
                    [45, 'wheelder-v5.6-Backup before upgrade.zip', '2026-02-28', '113.7 MB', '20d5e75ac072e5fc364e621cbdd227a6'],
                ];

                foreach ($archives as $a) {
                    $isMilestone = in_array($a[0], $milestones) ? ' milestone' : '';
                    $num = htmlspecialchars($a[0]);
                    $name = htmlspecialchars($a[1]);
                    $date = htmlspecialchars($a[2]);
                    $size = htmlspecialchars($a[3]);
                    $hash = htmlspecialchars($a[4]);
                    echo "<tr class=\"archive-row{$isMilestone}\" data-search=\"{$name} {$date}\">";
                    echo "<td>{$num}</td>";
                    echo "<td>{$name}</td>";
                    echo "<td>{$date}</td>";
                    echo "<td>{$size}</td>";
                    echo "<td class=\"hash-value\">{$hash}</td>";
                    echo "</tr>\n";
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Google Drive Evidence -->
<section class="key-evidence">
    <h2>Google Drive Evidence</h2>
    <p style="text-align:center; color:var(--text-secondary); max-width:700px; margin:0 auto 2rem; font-size:0.95rem;">
        Five milestone snapshots are independently stored on Google Drive. Google records the
        <strong>original upload date</strong> and an <strong>MD5 checksum</strong> server-side &mdash;
        neither can be altered after upload.
    </p>

    <div class="evidence-grid" style="grid-template-columns: 1fr;">
        <div class="evidence-card" style="max-width:1100px; margin:0 auto; width:100%;">
            <table class="hash-table" style="margin:0;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>File</th>
                        <th>Upload Date</th>
                        <th>Size</th>
                        <th>Links</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><strong>wheeleder_files_sorted.zip</strong><br><span style="color:var(--text-secondary); font-size:0.8rem;">Full multi-app platform with "Wheeleder" branding</span></td>
                        <td style="white-space:nowrap;"><strong>Jun 19, 2025</strong></td>
                        <td style="white-space:nowrap;">77.8 MB</td>
                        <td style="white-space:nowrap;">
                            <a href="https://drive.google.com/file/d/1sdiJ4zlSIr4APN8S8g7Btl08jghYiXZS/view?usp=drive_link" target="_blank" style="color:var(--accent); text-decoration:none; font-weight:600;"><i class="fas fa-eye"></i> View</a>
                            &nbsp;|&nbsp;
                            <a href="https://drive.google.com/uc?export=download&id=1sdiJ4zlSIr4APN8S8g7Btl08jghYiXZS" target="_blank" style="color:var(--accent); text-decoration:none; font-weight:600;"><i class="fas fa-download"></i> Download</a>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td><strong>wheeleder_latest_update_and_backup_04_02_2024.zip</strong><br><span style="color:var(--text-secondary); font-size:0.8rem;">Complete platform backup &mdash; "04_02_2024" refers to code version date</span></td>
                        <td style="white-space:nowrap;"><strong>Dec 1, 2024</strong></td>
                        <td style="white-space:nowrap;">192.8 MB</td>
                        <td style="white-space:nowrap;">
                            <a href="https://drive.google.com/file/d/140MW9j7RRoT43rfeYmNmrYfe3TrZ2Lww/view?usp=drive_link" target="_blank" style="color:var(--accent); text-decoration:none; font-weight:600;"><i class="fas fa-eye"></i> View</a>
                            &nbsp;|&nbsp;
                            <a href="https://drive.google.com/uc?export=download&id=140MW9j7RRoT43rfeYmNmrYfe3TrZ2Lww" target="_blank" style="color:var(--accent); text-decoration:none; font-weight:600;"><i class="fas fa-download"></i> Download</a>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td><strong>whl_project-Learn App renamed to ReLearnly...</strong><br><span style="color:var(--text-secondary); font-size:0.8rem;">Documents the exact moment "Learn App" was renamed to "ReLearnly"</span></td>
                        <td style="white-space:nowrap;"><strong>Dec 1, 2024</strong></td>
                        <td style="white-space:nowrap;">77 KB</td>
                        <td style="white-space:nowrap;">
                            <a href="https://drive.google.com/file/d/1nUjcYIyA5kHy4En5ECDn9sVXxbYpITdi/view?usp=drive_link" target="_blank" style="color:var(--accent); text-decoration:none; font-weight:600;"><i class="fas fa-eye"></i> View</a>
                            &nbsp;|&nbsp;
                            <a href="https://drive.google.com/uc?export=download&id=1nUjcYIyA5kHy4En5ECDn9sVXxbYpITdi" target="_blank" style="color:var(--accent); text-decoration:none; font-weight:600;"><i class="fas fa-download"></i> Download</a>
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td><strong>Relearn App-The Feature of Digging Deeper...</strong><br><span style="color:var(--text-secondary); font-size:0.8rem;">Early "Digging Deeper" feature &mdash; ancestor of Circular Search &amp; Deep Research</span></td>
                        <td style="white-space:nowrap;"><strong>Dec 2, 2024</strong></td>
                        <td style="white-space:nowrap;">85 KB</td>
                        <td style="white-space:nowrap;">
                            <a href="https://drive.google.com/file/d/1IbQMksPJ1rOtqFel1BDLomxMturf7IE0/view?usp=drive_link" target="_blank" style="color:var(--accent); text-decoration:none; font-weight:600;"><i class="fas fa-eye"></i> View</a>
                            &nbsp;|&nbsp;
                            <a href="https://drive.google.com/uc?export=download&id=1IbQMksPJ1rOtqFel1BDLomxMturf7IE0" target="_blank" style="color:var(--accent); text-decoration:none; font-weight:600;"><i class="fas fa-download"></i> Download</a>
                        </td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td><strong>ReLearnApp-double prompt entry input fields added...</strong><br><span style="color:var(--text-secondary); font-size:0.8rem;">Dual-prompt interface &mdash; ancestor of the Center App research interface</span></td>
                        <td style="white-space:nowrap;"><strong>Dec 1, 2024</strong></td>
                        <td style="white-space:nowrap;">77 KB</td>
                        <td style="white-space:nowrap;">
                            <a href="https://drive.google.com/file/d/1M7Awxe7NGoMwNPs62EawOVrK65FahHhN/view?usp=drive_link" target="_blank" style="color:var(--accent); text-decoration:none; font-weight:600;"><i class="fas fa-eye"></i> View</a>
                            &nbsp;|&nbsp;
                            <a href="https://drive.google.com/uc?export=download&id=1M7Awxe7NGoMwNPs62EawOVrK65FahHhN" target="_blank" style="color:var(--accent); text-decoration:none; font-weight:600;"><i class="fas fa-download"></i> Download</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div style="max-width:800px; margin:1.5rem auto 0; padding:0 1rem;">
        <div class="evidence-callout" style="background: rgba(66,133,244,0.1); border-left-color: #4285f4;">
            <strong><i class="fas fa-info-circle"></i> About the filenames:</strong><br>
            The filenames themselves are evidence. Archive #3&rsquo;s name literally says <em>&ldquo;Learn App renamed to ReLearnly&rdquo;</em> &mdash;
            documenting the name change as it happened. Archive #4 describes <em>&ldquo;The Feature of Digging Deeper into the topic... level by level&rdquo;</em> &mdash;
            this is the early implementation of what became <strong>Circular Search</strong> and <strong>Deep Research</strong>.
        </div>
    </div>

    <div style="max-width:800px; margin:2rem auto 0; padding:0 1rem;">
        <div class="evidence-callout">
            <strong><i class="fas fa-shield-alt"></i> Why Google Drive is strong evidence:</strong><br>
            Google records the <code>createdTime</code> (original upload date) and <code>md5Checksum</code>
            for every file. These are set by Google&rsquo;s servers at upload time and <strong>cannot be
            modified</strong> by the file owner. Anyone with a Google API key can independently verify
            the upload date and file integrity.
        </div>

        <div class="evidence-callout success" style="margin-top:1rem;">
            <strong><i class="fas fa-check-circle"></i> How to verify upload date &amp; hash:</strong><br>
            Use the Google Drive API with any of the File IDs above:
            <code style="display:block; margin-top:0.5rem; background:rgba(0,0,0,0.4); padding:0.5rem 0.75rem; border-radius:4px; font-size:0.85rem;">GET https://www.googleapis.com/drive/v3/files/{FILE_ID}?fields=name,createdTime,modifiedTime,md5Checksum&amp;key=YOUR_API_KEY</code>
            <span style="font-size:0.85rem; color:var(--text-secondary); display:block; margin-top:0.5rem;">
                The <code>createdTime</code> field shows when the file was first uploaded.
                The <code>md5Checksum</code> must match the hashes in the archive table above.
            </span>
        </div>
    </div>
</section>

<!-- How to Verify -->
<section class="verification">
    <h2>How to Verify</h2>
    <div class="verify-steps">
        <div class="verify-step">
            <div class="verify-step-num">1</div>
            <div class="verify-step-content">
                <h4>Verify Archive Integrity</h4>
                <p>Run this command on any ZIP file to confirm it matches the hash in the table above:</p>
                <code>(Get-FileHash "filename.zip" -Algorithm MD5).Hash</code>
            </div>
        </div>

        <div class="verify-step">
            <div class="verify-step-num">2</div>
            <div class="verify-step-content">
                <h4>Cross-Check with Google Drive</h4>
                <p>Google Drive stores MD5 checksums server-side. Use the Drive API to compare:</p>
                <code>GET https://www.googleapis.com/drive/v3/files/FILE_ID?fields=md5Checksum</code>
            </div>
        </div>

        <div class="verify-step">
            <div class="verify-step-num">3</div>
            <div class="verify-step-content">
                <h4>Inspect Git History</h4>
                <p>Clone the public repository and inspect commit dates and file contents:</p>
                <code>git log --format="%H %ai %s" --all</code>
            </div>
        </div>

        <div class="verify-step">
            <div class="verify-step-num">4</div>
            <div class="verify-step-content">
                <h4>Verify SQL Timestamps</h4>
                <p>Extract the SQL backup from archive #1 and check the created_at fields. Convert Unix timestamps:</p>
                <code>python -c "import datetime; print(datetime.datetime.utcfromtimestamp(1697997845))"
# Output: 2023-10-22 18:24:05</code>
            </div>
        </div>
    </div>
</section>

<!-- Full Evidence Pack -->
<section class="key-evidence" style="padding-top: 2rem;">
    <div style="max-width:800px; margin:0 auto; text-align:center;">
        <div class="evidence-callout success" style="text-align:center;">
            <strong><i class="fas fa-archive"></i> Complete Evidence Pack</strong><br>
            <p style="margin: 0.75rem 0;">
                For the full founding proof with screenshots, download links, verification instructions, and brand evolution history:
            </p>
            <a href="https://github.com/Wheelder/deep-research-founding-proof" target="_blank"
               style="display:inline-block; padding:0.6rem 1.5rem; background:var(--accent); color:#fff; text-decoration:none; border-radius:6px; font-weight:700; margin-top:0.5rem;">
                <i class="fab fa-github"></i> View Founding Proof on GitHub
            </a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="timeline-footer">
    <p>
        <strong>Wheelder</strong> &mdash; Research Platform &mdash; A Student Project Since 2020
    </p>
    <p style="margin-top: 0.5rem;">
        <a href="https://github.com/Wheelder/deep-research-founding-proof" target="_blank">Founding Proof</a>
        &nbsp;&bull;&nbsp;
        <a href="https://github.com/Wheelder/relearn" target="_blank">relearn (Legacy)</a>
        &nbsp;&bull;&nbsp;
        <a href="https://github.com/Wheelder/wheelder_platform" target="_blank">wheelder_platform</a>
        &nbsp;&bull;&nbsp;
        <a href="https://github.com/Wheelder/wheelder-releases" target="_blank">Releases History</a>
        &nbsp;&bull;&nbsp;
        <a href="/releases">Releases</a>
        &nbsp;&bull;&nbsp;
        <a href="/center">Center</a>
    </p>
</footer>

<script>
function toggleProof(el) {
    const details = el.nextElementSibling;
    const icon = el.querySelector('i');
    details.classList.toggle('show');
    if (details.classList.contains('show')) {
        icon.className = 'fas fa-chevron-down';
        el.innerHTML = '<i class="fas fa-chevron-down"></i> Hide proof details';
    } else {
        icon.className = 'fas fa-chevron-right';
        el.innerHTML = '<i class="fas fa-chevron-right"></i> View proof details';
    }
}

function filterHashes() {
    const query = document.getElementById('hashSearch').value.toLowerCase();
    const rows = document.querySelectorAll('.archive-row');
    rows.forEach(row => {
        const text = row.getAttribute('data-search').toLowerCase();
        row.style.display = text.includes(query) || query === '' ? '' : 'none';
    });
}
</script>

</body>
</html>
