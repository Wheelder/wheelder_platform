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

// Shareable demo key that must always be present when the root URL is used
$rootDemoKey = '20eae05c4f4e';

//include_once 'top.php';

// --- Define routes cleanly using inline functions ---


$router->route('/', function() use ($rootDemoKey) {
    // WHY: root should behave like /demo?key=... but keep the cleaner /?key= URL.
    $query = $_GET;

    $keyMissing = empty($query['key']);
    $keyMismatch = !$keyMissing && !hash_equals($rootDemoKey, $query['key']);

    if ($keyMissing || $keyMismatch) {
        // Preserve any other query params while forcing the required key.
        $query['key'] = $rootDemoKey;
        $redirectQs = http_build_query($query);
        header('Location: /' . ($redirectQs ? ('?' . $redirectQs) : ''), true, 302);
        exit;
    }

    // Serve the same experience as /demo internally so users never see /demo in the URL.
    require 'apps/edu/ui/views/learn/backup/record.php';
});

// Optional: legacy landing page remains reachable for marketing screenshots
$router->route('/landing', function() {
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

// Center app — cloned from /demo but served as the primary experience
$router->route('/center', function() {
    require 'apps/edu/ui/views/center/record.php';
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
// --- Lesson app routes (mirrors /blog structure) ---

$lessonAppPath = 'apps/edu/ui/views/lessons/app_new.php';
if (!file_exists($lessonAppPath)) {
    // WHY: prod is still on legacy file layout; fall back quietly so /lesson stays up.
    error_log('Lesson route fallback: missing ' . $lessonAppPath . ', using legacy app.php');
    $lessonAppPath = 'apps/edu/ui/views/lessons/app.php';
}

$router->route('/lesson', function() use ($lessonAppPath) {
    if (!file_exists($lessonAppPath)) {
        // Explicit guard surfaces clearer 500 instead of PHP warning when both files absent.
        http_response_code(500);
        echo 'Lesson route bootstrap missing. Please contact Wheelder support.';
        error_log('Lesson route fatal: unable to locate lesson view file at ' . $lessonAppPath);
        return;
    }
    // When the legacy app.php boots it expects LessonController.php under DOCUMENT_ROOT.
    // Make sure we drop a resolver var it can reuse instead of duplicating the logic there.
    $GLOBALS['lessonControllerPath'] = __DIR__ . '/apps/edu/controllers/LessonController.php';
    require $lessonAppPath;
});

$router->route('/lesson/cms', function() {
    require 'apps/edu/ui/views/lessons/cms/list.php';
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

// --- Handle the current request ---
$router->handleRequest($_SERVER['REQUEST_URI']);

