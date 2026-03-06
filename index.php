<?php
// Enable error reporting for development (disable for production)
// Security: Only show errors in development, not production
if ($_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false || strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/error.log');
}

session_start();

require_once 'Router.php';

$router = new Router(); // Now auto-detects base path

// Shareable demo key that must always be present when the root URL is used (prod/staging)
$rootDemoKey = '20eae05c4f4e';
// Local developers need to hit /lesson without the forced demo key redirect — detect localhost automatically.
$isLocalDev = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1'], true);

//include_once 'top.php';

// --- Define routes cleanly using inline functions ---


$router->route('/', function() use ($rootDemoKey, $isLocalDev) {
    // WHY: root redirects to /circular (the meta-AI orchestrator experience) with the demo key.
    // This ensures all users see the latest Circular UI with tool recommendations.
    if ($isLocalDev) {
        // Dev-mode toggle — skip the redirect entirely so localhost/wheelder/circular stays reachable.
        require 'apps/edu/ui/views/circular/record.php';
        return;
    }

    $query = $_GET;

    $keyMissing = empty($query['key']);
    $keyMismatch = !$keyMissing && !hash_equals($rootDemoKey, $query['key']);

    if ($keyMissing || $keyMismatch) {
        // Preserve any other query params while forcing the required key.
        $query['key'] = $rootDemoKey;
        $redirectQs = http_build_query($query);
        // WHY: redirect to /circular so users see the meta-AI orchestrator experience.
        header('Location: /circular' . ($redirectQs ? ('?' . $redirectQs) : ''), true, 302);
        exit;
    }

    // WHY: serve /circular directly so the meta-AI orchestrator is available immediately.
    require 'apps/edu/ui/views/circular/record.php';
});

// Optional: legacy landing page remains reachable for marketing screenshots
$router->route('/landing', function() {
    require 'landing.php';
});

$router->route('/login', function() {
    require 'pool/auth/login.php';
});

// --- Secondary login with password (alternative to magic link) ---
$router->route('/login2', function() {
    require 'pool/auth/login2.php';
});

// --- Password reset page ---
$router->route('/password-reset', function() {
    require 'pool/auth/password_reset.php';
});

// --- Signup disabled: single-user system, accounts created by code only ---
$router->route('/signup', function() {
    http_response_code(404);
    echo '<h1>404 Not Found</h1>';
    exit;
});


// --- Profile setup page ---
$router->route('/profile_setup', function() {
    if (empty($_SESSION['user_id'])) {
        http_response_code(404);
        echo '<h1>404 Not Found</h1>';
        exit;
    }
    require 'pool/auth/profile.php';
});

// --- Magic Link Authentication Routes ---
$router->route('/auth/magic-link-request', function() {
    require 'pool/auth/magic_link_request.php';
});

$router->route('/auth/verify', function() {
    require 'pool/auth/magic_link_verify.php';
});

$router->route('/auth/magic-link-test', function() {
    require 'pool/auth/magic_link_test.php';
});

$router->route('/auth/test-endpoint', function() {
    require 'pool/auth/test_endpoint.php';
});

$router->route('/log_api', function() {
    require 'pool/api/logsAPI.php';
});

// --- Login2 API endpoint ---
$router->route('/api/login2', function() {
    require 'pool/auth/login2_handler.php';
});

// --- Password reset API endpoints ---
$router->route('/api/password-reset-request', function() {
    require 'pool/auth/password_reset_request_handler.php';
});

$router->route('/api/password-reset', function() {
    require 'pool/auth/password_reset_handler.php';
});

// --- Initialize login2 system (admin only) ---
$router->route('/init-login2', function() {
    require 'pool/auth/init_login2.php';
});

$router->route('/logout', function() {
    require 'pool/auth/logout.php';
});

$router->route('/terms', function() {
    require 'pool/auth/terms.php';
});

$router->route('/help', function() {
    require 'pool/help/helpdesk.php';
});

$router->route('/privacy', function() {
    require 'pool/auth/privacy.php';
});

// --- Admin routes — auth required to prevent unauthorized DB manipulation ---
$router->route('/setup', function() {
    if (empty($_SESSION['user_id'])) {
        http_response_code(404);
        echo '<h1>404 Not Found</h1>';
        exit;
    }
    require 'pool/config/db_setup.php';
});

$router->route('/sqlite_setup', function() {
    if (empty($_SESSION['user_id'])) {
        http_response_code(404);
        echo '<h1>404 Not Found</h1>';
        exit;
    }
    require 'pool/config/sqlite_setup.php';
});

$router->route('/edu_db_setup', function() {
    if (empty($_SESSION['user_id'])) {
        http_response_code(404);
        echo '<h1>404 Not Found</h1>';
        exit;
    }
    require 'apps/edu/api/dbAPI.php';
});


$router->route('/dev', function() {
    if (empty($_SESSION['user_id'])) {
        http_response_code(404);
        echo '<h1>404 Not Found</h1>';
        exit;
    }
    require 'pool/dev/vackup/versions.php';
});

$router->route('/backup', function() {
    if (empty($_SESSION['user_id'])) {
        http_response_code(404);
        echo '<h1>404 Not Found</h1>';
        exit;
    }
    require 'pool/config/backup.php';
});

$router->route('/main', function() {
    require 'default.php';
});





// Verification page — requires active email session to prevent abuse
$router->route('/verification', function() {
    if (empty($_SESSION['email'])) {
        http_response_code(404);
        echo '<h1>404 Not Found</h1>';
        exit;
    }
    require 'pool/auth/verification.php';
});

// --- Dashboard — generate shareable access links for the Learn app ---
$router->route('/dashboard', function() {
    // WHY: Dashboard should expose the latest CMS2 experience so admins reach lesson builder directly.
    require 'apps/edu/ui/views/lessons/cms2/index.php';
});

// --- LAB and Blog sections ---

$router->route('/demo', function() {
    require 'apps/edu/ui/views/learn/backup/record.php';
});

// Center app — redirect to /circular for now
$router->route('/center', function() {
    header('Location: /circular');
    exit;
});

// AJAX endpoint for the /demo page — handles Ask and Deepen requests without page reload
$router->route('/demo/ajax', function() {
    require 'apps/edu/ui/views/learn/backup/ajax_handler.php';
});

$router->route('/center/ajax', function() {
    require 'apps/edu/ui/views/center/ajax_handler.php';
});

// TTS endpoint — converts text to natural speech using Edge TTS neural voices
$router->route('/demo/tts', function() {
    require 'apps/edu/ui/views/learn/backup/tts_proxy.php';
});

$router->route('/center/tts', function() {
    require 'apps/edu/ui/views/center/tts_proxy.php';
});

// --- Legacy /learn redirects — 301 so old bookmarks and shared links still work ---
$router->route('/learn', function() {
    // Preserve query string (?key=, ?view=) so shared access links keep working
    $qs = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
    header('Location: ' . url('/demo') . $qs, true, 301);
    exit;
});
$router->route('/learn/ajax', function() {
    header('Location: ' . url('/demo/ajax'), true, 301);
    exit;
});
$router->route('/learn/tts', function() {
    header('Location: ' . url('/demo/tts'), true, 301);
    exit;
});


$router->route('/note', function() {
    require 'apps/edu/ui/views/notes/app.php';
});

$router->route('/note/cms', function() {
    require 'apps/edu/ui/views/notes/cms/list.php';
});

$router->route('/note/cms/learn', function() {
    require 'apps/edu/ui/views/notes/cms/note_g.php';
});

$router->route('/search', function() {
    require 'apps/edu/ui/views/search/search.php';
});

$router->route('/la', function() {
    require 'apps/edu/ui/views/learn/app_main.php';
});

$router->route('/chat', function() {
    require 'apps/edu/ui/views/learn/backup/record.php';
});

$router->route('/blog', function() {
    require 'apps/edu/ui/views/blogs/app.php';
});

$router->route('/blog/cms', function() {
    require 'apps/edu/ui/views/blogs/cms/list.php';
});
$router->route('/blog/cms_new', function() {
    require 'apps/edu/ui/views/blogs/cms-new/list.php';
});
$router->route('/blog/cms/create', function() {
    require 'apps/edu/ui/views/blogs/cms/create.php';
});

$router->route('/blog/cms/edit', function() {
    require 'apps/edu/ui/views/blogs/cms/edit.php';
});

$router->route('/blog/cms/delete', function() {
    require 'apps/edu/ui/views/blogs/cms/delete.php';
});

// Advanced CMS routes
$router->route('/blog/cms-new', function() {
    require 'apps/edu/ui/views/blogs/cms-new/list.php';
});

$router->route('/blog/cms-new/list', function() {
    require 'apps/edu/ui/views/blogs/cms-new/list.php';
});

$router->route('/blog/cms-new/create', function() {
    require 'apps/edu/ui/views/blogs/cms-new/create.php';
});

$router->route('/blog/cms-new/edit', function() {
    require 'apps/edu/ui/views/blogs/cms-new/edit.php';
});

$router->route('/blog/cms-new/view', function() {
    require 'apps/edu/ui/views/blogs/cms-new/view.php';
});

$router->route('/blog/cms-new/delete', function() {
    require 'apps/edu/ui/views/blogs/cms-new/delete.php';
});

// --- Releases / Changelog — dynamic system for features and innovations ---
// WHY: /releases displays published releases with rich media (text, images, videos)
// Similar to blog but focused on release notes and feature announcements
$router->route('/releases', function() {
    require 'apps/edu/ui/views/releases/app.php';
});

// WHY: /release_new is a standalone hardcoded release page (no database dependency)
$router->route('/release_new', function() {
    require 'apps/edu/ui/views/releases/release_new.php';
});

// WHY: /releases/cms allows authenticated admins to manage releases
$router->route('/releases/cms', function() {
    require 'apps/edu/ui/views/releases/cms.php';
});
// --- Lesson app routes (mirrors /blog structure) ---

$router->route('/lesson/cms', function() {
    require 'apps/edu/ui/views/lessons/cms/list.php';
});


$router->route('/edu', function() {
    require 'apps/edu/ui/views/lessons/app_new.php';
});

$router->route('/lesson/cms/create', function() {
    require 'apps/edu/ui/views/lessons/cms/create.php';
});

$router->route('/lesson/cms/edit', function() {
    require 'apps/edu/ui/views/lessons/cms/edit.php';
});

$router->route('/lesson/cms/delete', function() {
    require 'apps/edu/ui/views/lessons/cms/delete.php';
});

// --- Lesson CMS2 — AI-powered lesson generator (mirrors /demo layout) ---
$router->route('/lesson/cms2', function() {
    require 'apps/edu/ui/views/lessons/cms2/index.php';
});

$router->route('/lesson/cms2/ajax', function() {
    require 'apps/edu/ui/views/lessons/cms2/ajax.php';
});

// --- APIs for Education App ---
$router->route('/edu_assets', function() {
    require 'apps/edu/ui/assets';
});

$router->route('/edu_note', function() {
    require 'apps/edu/api/noteAPI.php';
});
$router->route('/edu_db', function() {
    require 'apps/edu/api/dbAPI.php';
});
$router->route('/edu_blog', function() {
    require 'apps/edu/api/blogAPI.php';
});
$router->route('/lesson_api', function() {
    require 'apps/edu/api/lessonAPI.php';
});

$router->route('/edu_img_api', function() {
    require 'apps/edu/api/imgAPI.php';
});

$router->route('/edu_search_api', function() {
    require 'apps/edu/api/open_ai.php';
});

$router->route('/teach_api', function() {
    require 'apps/edu/api/teachAPI.php';
});

// --- Timeline — public innovation proof & timeline page (no auth) ---
$router->route('/timeline', function() {
    require 'apps/edu/ui/views/timeline/app.php';
});

// --- Portfolio — public developer portfolio page (no auth) ---
$router->route('/portfolio', function() {
    require 'apps/edu/ui/views/portfolio/app.php';
});

// --- Portfolio CMS — admin routes (auth required inside each view) ---
$router->route('/portfolio/cms', function() {
    require 'apps/edu/ui/views/portfolio/cms/index.php';
});

$router->route('/portfolio/cms/sections', function() {
    require 'apps/edu/ui/views/portfolio/cms/sections.php';
});

$router->route('/portfolio/cms/sections/edit', function() {
    require 'apps/edu/ui/views/portfolio/cms/sections_edit.php';
});

$router->route('/portfolio/cms/skills', function() {
    require 'apps/edu/ui/views/portfolio/cms/skills.php';
});

$router->route('/portfolio/cms/projects', function() {
    require 'apps/edu/ui/views/portfolio/cms/projects.php';
});

$router->route('/portfolio/cms/projects/create', function() {
    require 'apps/edu/ui/views/portfolio/cms/projects_create.php';
});

$router->route('/portfolio/cms/projects/edit', function() {
    require 'apps/edu/ui/views/portfolio/cms/projects_edit.php';
});

$router->route('/portfolio/cms/contacts', function() {
    require 'apps/edu/ui/views/portfolio/cms/contacts.php';
});

// --- Portfolio API — handles all portfolio CMS form submissions ---
$router->route('/portfolio_api', function() {
    require 'apps/edu/api/portfolioAPI.php';
});

if (!function_exists('renderLegacyView')) {
    function renderLegacyView(string $relativePath): void
    {
        static $legacyBasePath = null;

        if ($legacyBasePath === null) {
            require_once __DIR__ . '/apps/edu/ui/views/_legacy_2024/bootstrap.php';
            $legacyBasePath = $LEGACY_BASE_PATH ?? __DIR__;
        }

        $absolutePath = __DIR__ . '/' . ltrim($relativePath, '/');
        if (!file_exists($absolutePath)) {
            throw new RuntimeException('Legacy view not found: ' . $relativePath);
        }

        $previousDocRoot = $_SERVER['DOCUMENT_ROOT'] ?? null;
        $_SERVER['DOCUMENT_ROOT'] = $legacyBasePath;

        require $absolutePath;

        if ($previousDocRoot === null) {
            unset($_SERVER['DOCUMENT_ROOT']);
        } else {
            $_SERVER['DOCUMENT_ROOT'] = $previousDocRoot;
        }
    }
}

// ============================================================
// TEMPORARY: Versioned routes for reviewing all historical app versions
// WHY: lets us browse every generation side-by-side before restructuring
// REMOVE after restructuring is complete
// ============================================================

// --- legacy: April 2024 backup — the original "wheeleder" platform before it became "wheelder" ---
// WHY: these are extracted from the wheeleder_latest_update_and_backup_04_02_2024 repo
$router->route('/legacy/notes', function() {
    renderLegacyView('apps/edu/ui/views/_legacy_2024/notes/app.php');
});
$router->route('/legacy/notes/cms', function() {
    renderLegacyView('apps/edu/ui/views/_legacy_2024/notes/cms/list.php');
});
$router->route('/legacy/notes/cms/learn', function() {
    renderLegacyView('apps/edu/ui/views/_legacy_2024/notes/cms/learn.php');
});
$router->route('/legacy/notes/cms/note-g', function() {
    renderLegacyView('apps/edu/ui/views/_legacy_2024/notes/cms/note_g.php');
});
$router->route('/legacy/notes/cms/topic', function() {
    renderLegacyView('apps/edu/ui/views/_legacy_2024/notes/cms/topic.php');
});
$router->route('/legacy/notes/cms/view', function() {
    renderLegacyView('apps/edu/ui/views/_legacy_2024/notes/cms/view.php');
});
$router->route('/legacy/notes/cms/create', function() {
    renderLegacyView('apps/edu/ui/views/_legacy_2024/notes/cms/create.php');
});
$router->route('/legacy/notes/cms/edit', function() {
    renderLegacyView('apps/edu/ui/views/_legacy_2024/notes/cms/edit.php');
});
$router->route('/legacy/notes/cms/delete', function() {
    renderLegacyView('apps/edu/ui/views/_legacy_2024/notes/cms/delete.php');
});
$router->route('/legacy/blogs', function() {
    renderLegacyView('apps/edu/ui/views/_legacy_2024/blogs/app.php');
});
$router->route('/legacy/blogs/cms', function() {
    renderLegacyView('apps/edu/ui/views/_legacy_2024/blogs/cms/list.php');
});
$router->route('/legacy/blogs/cms/create', function() {
    renderLegacyView('apps/edu/ui/views/_legacy_2024/blogs/cms/create.php');
});
$router->route('/legacy/blogs/cms/edit', function() {
    renderLegacyView('apps/edu/ui/views/_legacy_2024/blogs/cms/edit.php');
});
$router->route('/legacy/blogs/cms/view', function() {
    renderLegacyView('apps/edu/ui/views/_legacy_2024/blogs/cms/view.php');
});
$router->route('/legacy/blogs/cms/delete', function() {
    renderLegacyView('apps/edu/ui/views/_legacy_2024/blogs/cms/delete.php');
});
$router->route('/legacy/blogs/cms/topic', function() {
    renderLegacyView('apps/edu/ui/views/_legacy_2024/blogs/cms/topic.php');
});
$router->route('/legacy/search', function() {
    renderLegacyView('apps/edu/ui/views/_legacy_2024/search/search.php');
});
$router->route('/legacy/profile', function() {
    renderLegacyView('apps/edu/ui/views/_legacy_2024/profile/profile.php');
});
$router->route('/legacy/settings', function() {
    renderLegacyView('apps/edu/ui/views/_legacy_2024/settings/settings.php');
});
$router->route('/legacy/pricing', function() {
    renderLegacyView('apps/edu/ui/views/_legacy_2024/subscription/pricing.php');
});
$router->route('/legacy/read', function() {
    renderLegacyView('apps/edu/ui/views/_legacy_2024/read/index.html');
});

// --- v0: Notes App (Aug 2025 — the earliest, inline CSS/JS) ---
$router->route('/v0/notes', function() {
    require 'apps/edu/ui/views/notes/app.php';
});
$router->route('/v0/notes/cms', function() {
    require 'apps/edu/ui/views/notes/cms/list.php';
});
// WHY: learn.php is the TRUE original AI chat — note_g.php is a near-identical copy
$router->route('/v0/notes/cms/learn', function() {
    require 'apps/edu/ui/views/notes/cms/learn.php';
});
$router->route('/v0/notes/cms/note-g', function() {
    require 'apps/edu/ui/views/notes/cms/note_g.php';
});
$router->route('/v0/notes/cms/topic', function() {
    require 'apps/edu/ui/views/notes/cms/topic.php';
});
$router->route('/v0/notes/cms/view', function() {
    require 'apps/edu/ui/views/notes/cms/view.php';
});
$router->route('/v0/notes/cms/create', function() {
    require 'apps/edu/ui/views/notes/cms/create.php';
});
$router->route('/v0/notes/cms/edit', function() {
    require 'apps/edu/ui/views/notes/cms/edit.php';
});
$router->route('/v0/notes/cms/delete', function() {
    require 'apps/edu/ui/views/notes/cms/delete.php';
});

// --- v1: Learn App (Aug 2025 — controller pattern, form POST, no AJAX) ---
$router->route('/v1/learn', function() {
    require 'apps/edu/ui/views/learn/app_main.php';
});

// --- v2: Learn Backup (Jan 2026 — AJAX two-panel chat, Ask + Deepen) ---
$router->route('/v2/learn', function() {
    require 'apps/edu/ui/views/learn/backup/record.php';
});

// --- v3: Center (Feb 2026 — current production, enhanced from v2) ---
$router->route('/v3/center', function() {
    require 'apps/edu/ui/views/center/record.php';
});

// --- Circular: Meta-AI Orchestrator (Mar 2026 — AI tool router and hub) ---
$router->route('/circular', function() {
    require 'apps/edu/ui/views/circular/record.php';
});

$router->route('/circular/ajax', function() {
    require 'apps/edu/ui/views/circular/ajax_handler.php';
});

$router->route('/circular/setup', function() {
    require 'apps/edu/ui/views/circular/setup.php';
});

$router->route('/circular/deepen', function() {
    require 'apps/edu/ui/views/circular/deepen.php';
});

$router->route('/circular/deepen-store', function() {
    require 'apps/edu/ui/views/circular/deepen_store.php';
});

// --- Handle the current request ---
$router->handleRequest($_SERVER['REQUEST_URI']);

