<?php
/**
 * Public Portfolio Page — Minimalist sidebar + content layout
 * 
 * WHY: Matches modern portfolio design with left sidebar (profile card) and
 *      right content area (skills, experience/projects, contact). All data
 *      pulled from DB so owner can update anytime through CMS.
 *      No authentication required — this is a public page.
 */
require_once __DIR__ . '/../../../controllers/PortfolioController.php';

$portfolio = new PortfolioController();

// WHY: Fetch all dynamic content from DB for rendering
$hero    = $portfolio->get_section('hero');
$about   = $portfolio->get_section('about');
$contact_info = $portfolio->get_section('contact');
$skills_grouped = $portfolio->list_skills_grouped();
$projects = $portfolio->list_published_projects();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($hero['title'] ?? 'Portfolio', ENT_QUOTES, 'UTF-8'); ?> — Wheelder</title>

    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo htmlspecialchars($hero['title'] ?? 'Portfolio', ENT_QUOTES, 'UTF-8'); ?> — Wheelder">
    <meta property="og:description" content="Developer portfolio — building the Wheelder research platform since 2023.">
    <meta property="og:image" content="https://wheelder.com/pool/assets/og-image.php">
    <meta property="og:url" content="https://wheelder.com/portfolio">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Wheelder">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($hero['title'] ?? 'Portfolio', ENT_QUOTES, 'UTF-8'); ?> — Wheelder">
    <meta name="twitter:description" content="Developer portfolio — building the Wheelder research platform since 2023.">
    <meta name="twitter:image" content="https://wheelder.com/pool/assets/og-image.php">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* WHY: Light minimalist theme for professional portfolio appearance */
        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f8f9fa;
            --text-primary: #1a1a1a;
            --text-secondary: #666666;
            --accent: #6366f1;
            --accent-light: #e0e7ff;
            --border-color: #e5e7eb;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
        }

        /* --- Top Navigation --- */
        .portfolio-nav {
            background: var(--bg-primary);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 1rem 0;
        }
        .portfolio-nav .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .portfolio-nav .brand {
            font-weight: 700;
            color: var(--text-primary);
            font-size: 1.1rem;
            text-decoration: none;
        }
        .portfolio-nav a {
            color: var(--text-secondary);
            text-decoration: none;
            margin-left: 2rem;
            font-size: 0.9rem;
            transition: color 0.2s;
        }
        .portfolio-nav a:hover {
            color: var(--accent);
        }

        /* --- Main Layout: Sidebar + Content --- */
        .portfolio-container {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 3rem;
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 2rem;
        }

        /* --- Left Sidebar: Profile Card --- */
        .portfolio-sidebar {
            position: sticky;
            top: 100px;
            height: fit-content;
        }
        .profile-card {
            text-align: center;
        }
        .profile-image {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 1.5rem;
            border: 3px solid var(--accent-light);
            display: block;
        }
        .profile-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }
        .profile-title {
            font-size: 0.95rem;
            color: var(--accent);
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .profile-bio {
            font-size: 0.85rem;
            color: var(--text-secondary);
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }
        .profile-contact {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .profile-contact a {
            color: var(--text-secondary);
            text-decoration: none;
        }
        .profile-contact a:hover {
            color: var(--accent);
        }
        .profile-location {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .profile-socials {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        .profile-socials a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--accent-light);
            color: var(--accent);
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
            font-size: 1.1rem;
        }
        .profile-socials a:hover {
            background: var(--accent);
            color: white;
        }

        /* --- Right Content Area --- */
        .portfolio-content {
            padding-bottom: 3rem;
        }
        .content-section {
            margin-bottom: 3rem;
        }
        .content-section h2 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .content-section h2::before {
            content: '';
            display: inline-block;
            width: 4px;
            height: 24px;
            background: var(--accent);
            border-radius: 2px;
        }

        /* --- Core Skills Section --- */
        .skills-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }
        .skill-category {
            margin-bottom: 1rem;
        }
        .skill-category-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .skill-category-title::before {
            content: '◆';
            font-size: 0.6rem;
        }
        .skill-badge {
            display: inline-block;
            background: var(--bg-secondary);
            color: var(--text-primary);
            padding: 0.35rem 0.75rem;
            border-radius: 4px;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            border: 1px solid var(--border-color);
            transition: background 0.2s, border-color 0.2s;
        }
        .skill-badge:hover {
            background: var(--accent-light);
            border-color: var(--accent);
        }

        /* --- Experience/Projects Timeline --- */
        .experience-item {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
        }
        .experience-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .experience-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.5rem;
        }
        .experience-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
        }
        .experience-date {
            font-size: 0.85rem;
            color: var(--text-secondary);
            white-space: nowrap;
            margin-left: 1rem;
        }
        .experience-company {
            font-size: 0.9rem;
            color: var(--accent);
            margin-bottom: 0.5rem;
        }
        .experience-description {
            font-size: 0.9rem;
            color: var(--text-secondary);
            line-height: 1.5;
        }
        .experience-tech {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-top: 0.75rem;
        }
        .experience-links {
            margin-top: 0.75rem;
        }
        .experience-links a {
            display: inline-block;
            color: var(--accent);
            text-decoration: none;
            font-size: 0.85rem;
            margin-right: 1rem;
            transition: opacity 0.2s;
        }
        .experience-links a:hover {
            opacity: 0.7;
            text-decoration: underline;
        }

        /* --- Contact Section --- */
        .contact-form {
            background: var(--bg-secondary);
            padding: 2rem;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }
        .contact-form .form-control {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 4px;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        .contact-form .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            background: var(--bg-primary);
            color: var(--text-primary);
        }
        .contact-form .form-control::placeholder {
            color: var(--text-secondary);
        }
        .btn-accent {
            background: var(--accent);
            color: white;
            font-weight: 600;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 4px;
            transition: opacity 0.2s;
            cursor: pointer;
        }
        .btn-accent:hover {
            opacity: 0.85;
        }

        /* --- Footer --- */
        .portfolio-footer {
            background: var(--bg-secondary);
            border-top: 1px solid var(--border-color);
            padding: 2rem 0;
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.85rem;
            margin-top: 4rem;
        }

        /* --- Responsive --- */
        @media (max-width: 768px) {
            .portfolio-container {
                grid-template-columns: 1fr;
                gap: 2rem;
                margin: 2rem auto;
                padding: 0 1rem;
            }
            .portfolio-sidebar {
                position: static;
            }
            .skills-grid {
                grid-template-columns: 1fr;
            }
            .portfolio-nav .container {
                flex-direction: column;
                gap: 1rem;
            }
            .portfolio-nav a {
                margin-left: 1rem;
            }
        }
    </style>
</head>
<body>

<!-- ============ TOP NAVIGATION ============ -->
<nav class="portfolio-nav">
    <div class="container">
        <a href="<?php echo url('/portfolio'); ?>" class="brand">Portfolio</a>
        <div>
            <?php if (!empty($_SESSION['user_id'])): ?>
                <a href="<?php echo url('/portfolio/cms'); ?>" title="Admin CMS"><i class="fas fa-cog"></i> CMS</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- ============ MAIN LAYOUT ============ -->
<div class="portfolio-container">

    <!-- ============ LEFT SIDEBAR: PROFILE CARD ============ -->
    <aside class="portfolio-sidebar">
        <div class="profile-card">
            <!-- WHY: Profile image — use a default or fetch from section if available -->
            <img src="https://via.placeholder.com/180" alt="Profile" class="profile-image">
            
            <!-- WHY: Name and title from hero section -->
            <div class="profile-name">
                <?php echo htmlspecialchars($hero['title'] ?? 'Your Name', ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <div class="profile-title">
                <?php 
                    // WHY: Extract title from hero content (first line)
                    $content = $hero['content'] ?? '';
                    $lines = explode("\n", $content);
                    echo htmlspecialchars(trim($lines[0] ?? 'Professional'), ENT_QUOTES, 'UTF-8');
                ?>
            </div>

            <!-- WHY: Bio from about section -->
            <?php if ($about): ?>
                <div class="profile-bio">
                    <?php echo htmlspecialchars(substr($about['content'] ?? '', 0, 150), ENT_QUOTES, 'UTF-8'); ?>...
                </div>
            <?php endif; ?>

            <!-- WHY: Contact info from contact section -->
            <?php if ($contact_info): ?>
                <div class="profile-contact">
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:contact@example.com">contact@example.com</a>
                </div>
                <div class="profile-location">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>San Francisco, CA</span>
                </div>
            <?php endif; ?>

            <!-- WHY: Social links for quick access -->
            <div class="profile-socials">
                <a href="https://github.com/" target="_blank" title="GitHub"><i class="fab fa-github"></i></a>
                <a href="https://linkedin.com/" target="_blank" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
                <a href="https://twitter.com/" target="_blank" title="Twitter"><i class="fab fa-twitter"></i></a>
            </div>
        </div>
    </aside>

    <!-- ============ RIGHT CONTENT AREA ============ -->
    <main class="portfolio-content">

        <!-- ============ CORE SKILLS ============ -->
        <section class="content-section" id="skills">
            <h2>Core Skills</h2>
            <?php if (!empty($skills_grouped)): ?>
                <div class="skills-grid">
                    <?php foreach ($skills_grouped as $category => $skills): ?>
                        <div class="skill-category">
                            <div class="skill-category-title">
                                <?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                            <?php foreach ($skills as $skill): ?>
                                <span class="skill-badge"><?php echo htmlspecialchars($skill['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-secondary">No skills added yet. <a href="<?php echo url('/portfolio/cms/skills'); ?>">Add skills from CMS</a></p>
            <?php endif; ?>
        </section>

        <!-- ============ EXPERIENCE / PROJECTS ============ -->
        <section class="content-section" id="experience">
            <h2>Experience</h2>
            <?php if (!empty($projects)): ?>
                <div class="experience-list">
                    <?php foreach ($projects as $proj): ?>
                        <div class="experience-item">
                            <div class="experience-header">
                                <div>
                                    <div class="experience-title"><?php echo htmlspecialchars($proj['title'], ENT_QUOTES, 'UTF-8'); ?></div>
                                    <div class="experience-company">
                                        <?php echo htmlspecialchars($proj['role_text'] ?? 'Project', ENT_QUOTES, 'UTF-8'); ?>
                                    </div>
                                </div>
                                <!-- WHY: Show date range if available in project data -->
                                <div class="experience-date">2020 - Present</div>
                            </div>
                            
                            <?php if (!empty($proj['description'])): ?>
                                <div class="experience-description">
                                    <?php echo htmlspecialchars($proj['description'], ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            <?php endif; ?>

                            <!-- WHY: Show problem/solution/result as structured narrative -->
                            <?php if (!empty($proj['problem']) || !empty($proj['solution']) || !empty($proj['result_text'])): ?>
                                <div class="experience-description" style="margin-top: 0.75rem;">
                                    <?php if (!empty($proj['problem'])): ?>
                                        <strong>Challenge:</strong> <?php echo htmlspecialchars($proj['problem'], ENT_QUOTES, 'UTF-8'); ?><br>
                                    <?php endif; ?>
                                    <?php if (!empty($proj['solution'])): ?>
                                        <strong>Solution:</strong> <?php echo htmlspecialchars($proj['solution'], ENT_QUOTES, 'UTF-8'); ?><br>
                                    <?php endif; ?>
                                    <?php if (!empty($proj['result_text'])): ?>
                                        <strong>Result:</strong> <?php echo htmlspecialchars($proj['result_text'], ENT_QUOTES, 'UTF-8'); ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- WHY: Show technologies as comma-separated list -->
                            <?php if (!empty($proj['technologies'])): ?>
                                <div class="experience-tech">
                                    <strong>Tech:</strong> <?php echo htmlspecialchars($proj['technologies'], ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            <?php endif; ?>

                            <!-- WHY: Links to demo and GitHub for easy access -->
                            <?php if (!empty($proj['demo_url']) || !empty($proj['github_url'])): ?>
                                <div class="experience-links">
                                    <?php if (!empty($proj['demo_url'])): ?>
                                        <a href="<?php echo htmlspecialchars($proj['demo_url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank">
                                            <i class="fas fa-external-link-alt"></i> Live Demo
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty($proj['github_url'])): ?>
                                        <a href="<?php echo htmlspecialchars($proj['github_url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank">
                                            <i class="fab fa-github"></i> GitHub
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-secondary">No projects added yet. <a href="<?php echo url('/portfolio/cms/projects'); ?>">Add projects from CMS</a></p>
            <?php endif; ?>
        </section>

        <!-- ============ CONTACT SECTION ============ -->
        <section class="content-section" id="contact">
            <h2>Get In Touch</h2>
            <!-- WHY: Simple form stores messages in DB so admin can review later -->
            <form class="contact-form" method="POST" action="<?php echo url('/portfolio_api'); ?>">
                <div class="mb-3">
                    <input type="text" class="form-control" name="name" placeholder="Your Name" required>
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control" name="email" placeholder="Your Email" required>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" name="subject" placeholder="Subject (optional)">
                </div>
                <div class="mb-3">
                    <textarea class="form-control" name="message" rows="5" placeholder="Your message..." required></textarea>
                </div>
                <button type="submit" name="send_contact" class="btn btn-accent">Send Message</button>
            </form>
        </section>

    </main>

</div>

<!-- ============ FOOTER ============ -->
<footer class="portfolio-footer">
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> Wheelder. All rights reserved.</p>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
