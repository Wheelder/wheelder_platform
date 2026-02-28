<?php
// Enable error reporting for debugging
// Remove these lines in production
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

// Get project folder dynamically
$projectFolder = basename(dirname(__DIR__));

//$projectFolder="https://wheelederapps.azurewebsites.net";

$basePath =  $projectFolder;
//$basePath = __DIR__;
//echo $basePath;

//echo $basePath;

// Debugging: Print basePath
// Remove this in production
//echo 'Base Path: ' . $basePath;

require_once $_SERVER['DOCUMENT_ROOT']. '/pool/libs/Router.php';

$router = new Router($basePath);
include_once 'top.php';
// Assuming you have a session variable 'isLoggedIn' to check if the user is logged in
// This is just an example; replace with your actual login/session check

// Your existing routing code
if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true) {
    $dapp=$router->return_defaultApp($_SESSION['default_app']);
    $router->route('/$dapp', 'default.php');
} else {
    $router->route('/', 'default.php');
}

//platform routes
$router->route('/', 'default.php'); 

$router->route('/login_2024', 'pool/auth/login.php');

$router->route('/signup_2024', 'pool/auth/signup.php');

$router->route('/topics', 'pool/auth/topics.php');

$router->route('/terms', 'pool/auth/terms.php');

$router->route('/help', 'pool/help/helpdesk.php');

$router->route('/privacy', 'pool/auth/privacy.php');

$router->route('/setup', 'pool/config/db_setup.php');

$router->route('/edu_db_setup', 'apps/edu/api/dbAPI.php');

$router->route('/per_db_setup', 'apps/personal/api/dbAPI.php');

$router->route('/work_db_setup', 'apps/work/api/dbAPI.php');

$router->route('/dev', 'pool/dev/vackup/versions.php');

$router->route('/backup', 'pool/config/backup.php');

$router->route('/main', 'default.php');

$router->route('/verification', 'pool/auth/verification.php');

$router->route('/log_api', 'pool/api/logsAPI.php');


$router->route('/forgot_pass', 'pool/auth/forgot_password.php');

$router->route('/reset_pass', 'pool/auth/reset_pass.php');

$router->route('/invite', 'pool/auth/invite.php');

$router->route('/logout', 'pool/auth/logout.php');


//Routes for lab
$router->route('/lab', 'apps/edu/ui/views/blogs/app.php');


$router->route('/email', 'apps/edu/ui/views/blogs/cms/email.php');

$router->route('/lab/', 'apps/edu/ui/views/blogs/app.php');

$router->route('/blog', 'apps/edu/ui/views/blogs/app.php');

$router->route('/blog/cms', 'apps/edu/ui/views/blogs/cms/list.php');

$router->route('/blog/cms/', 'apps/edu/ui/views/blogs/cms/list.php');

$router->route('/blog/cms/create', 'apps/edu/ui/views/blogs/cms/create.php');

$router->route('/blog/cms/edit', 'apps/edu/ui/views/blogs/cms/edit.php');

$router->route('/blog/cms/delete', 'apps/edu/ui/views/blogs/cms/delete.php');

$router->route('/blog/', 'apps/edu/ui/views/blogs/app.php');

$router->route('/stolen_ideas', 'apps/edu/ui/views/blogs/app.php');

$router->route('/stolen_ideas/', 'apps/edu/ui/views/blogs/app.php');

$router->route('/issues', 'apps/edu/ui/views/blogs/app.php');

$router->route('/issues/', 'apps/edu/ui/views/blogs/app.php');


//Routes for lib
$router->route('/lib', 'apps/lib/ui/views/home/app.php');

$router->route('/ads', 'apps/lib/ui/views/home/ads.php');

$router->route('/lib/', 'apps/lib/ui/views/home/app.php');

$router->route('/pricing', 'apps/edu/ui/views/subscription/pricing.php');

$router->route('/profile', 'apps/lib/ui/views/profile/profile.php');

$router->route('/settings', 'apps/lib/ui/views/settings/settings.php');


//Routes for search

$router->route('/edu_assets', 'apps/edu/ui/assets');

$router->route('/edu_note', 'apps/edu/api/noteAPI.php');

$router->route('/edu_blog', '/apps/edu/api/blogAPI.php');

$router->route('/edu_img_api', 'apps/edu/api/imgAPI.php');

$router->route('/edu_search_api', 'apps/edu/api/open_ai.php');

$router->route('/teach_api', 'apps/edu/api/teachAPI.php');


//$router->route('/dmo/', 'apps/edu/ui/views/blogs/demo.php');

//$router->route('/dmo', 'apps/edu/ui/views/blogs/demo.php');



//routes for personal app assets
$router->route('/per_task', 'apps/personal/api/taskAPI.php');

$router->route('/personal_assets', 'apps/personal/ui/assets');

$router->route('/task', 'apps/personal/ui/views/home/task.php');


//routes for work app assets
$router->route('/mbid', 'apps/work/api/autowork.php');

$router->route('/finance', 'apps/fincycle/index.php');

//$router->route('/pricing', 'pool/auth/pricing.php');

$router->route('/work/services/elite', 'apps/work/ui/views/services/home/elite.php');

$router->route('/work/services/history', 'apps/work/ui/views/services/home/bids.php');

$router->route('/work/services/home', 'apps/work/ui/views/services/home/home.php');

$router->route('/work', 'apps/work/ui/views/services/home/home.php');

$router->route('/personal', 'apps/personal/ui/views/home/app.php');

$router->route('/profile_setup', 'pool/auth/profile.php');

$router->handleRequest(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
