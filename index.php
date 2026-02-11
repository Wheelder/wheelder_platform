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

//include_once 'top.php';

// --- Define routes cleanly using inline functions ---


$router->route('/', function() {
    require 'landing.php';
});

$router->route('/login', function() {
    require 'pool/auth/login.php';
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

$router->route('/log_api', function() {
    require 'pool/api/logsAPI.php';
});

// --- Password reset disabled: single-user system, no self-service password reset ---
$router->route('/forgot_pass', function() {
    http_response_code(404);
    echo '<h1>404 Not Found</h1>';
    exit;
});

$router->route('/reset_pass', function() {
    http_response_code(404);
    echo '<h1>404 Not Found</h1>';
    exit;
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
    require 'apps/edu/ui/views/learn/backup/dashboard.php';
});

// --- LAB and Blog sections ---

$router->route('/demo', function() {
    require 'apps/edu/ui/views/learn/backup/record.php';
});

// AJAX endpoint for the /demo page — handles Ask and Deepen requests without page reload
$router->route('/demo/ajax', function() {
    require 'apps/edu/ui/views/learn/backup/ajax_handler.php';
});

// TTS endpoint — converts text to natural speech using Edge TTS neural voices
$router->route('/demo/tts', function() {
    require 'apps/edu/ui/views/learn/backup/tts_proxy.php';
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

$router->route('/edu_img_api', function() {
    require 'apps/edu/api/imgAPI.php';
});

$router->route('/edu_search_api', function() {
    require 'apps/edu/api/open_ai.php';
});

$router->route('/teach_api', function() {
    require 'apps/edu/api/teachAPI.php';
});

// --- Handle the current request ---
$router->handleRequest($_SERVER['REQUEST_URI']);

