<?php
/**
 * Authentication API Handler
 * Handles login, signup, verification, password reset, and profile management
 */

// Start output buffering to prevent header issues
ob_start();

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Error handling - log errors but don't display in production
$isLocalhost = (
    $_SERVER['HTTP_HOST'] === 'localhost' || 
    strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false || 
    strpos($_SERVER['HTTP_HOST'], 'localhost') !== false
);

if (!$isLocalhost) {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../../error.log');
}

// Improved path resolution for both localhost and production
$basePath = dirname(dirname(__DIR__)); // Go up from pool/api to project root
$controllerPath = $basePath . '/pool/libs/controllers/LogsController.php';

if (!file_exists($controllerPath)) {
    // Fallback to DOCUMENT_ROOT if relative path doesn't work
    $docRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
    $host = $_SERVER['HTTP_HOST'] ?? '';
    
    if ($host === "localhost" || strpos($host, '127.0.0.1') !== false) {
        $controllerPath = $docRoot . '/wheelder/pool/libs/controllers/LogsController.php';
    } else {
        $controllerPath = $docRoot . '/pool/libs/controllers/LogsController.php';
    }
}

if (!file_exists($controllerPath)) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'System configuration error. Please contact support.'
    ]);
    error_log("LogsController not found at: " . $controllerPath);
    exit();
}

require_once $controllerPath;

// Include top.php for centralized url() helper that detects project base path
require_once dirname(dirname(__DIR__)) . '/top.php';

try {
    $log = new LogsController();
} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to initialize authentication system.'
    ]);
    error_log("LogsController initialization error: " . $e->getMessage());
    exit();
}

// Handle direct access (GET request without POST data)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($_POST)) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'This is an API endpoint. Please use POST method with appropriate parameters.',
        'available_endpoints' => [
            'login' => 'POST with email, password, dapp',
            'signup' => 'POST with first_name, last_name, email, password',
            'verify' => 'POST with otp',
            'forgot_password' => 'POST with email',
            'reset_pass' => 'POST with new_pass',
            'complete_profile' => 'POST with profile data',
            'select_topics' => 'POST with topics and user'
        ]
    ]);
    ob_end_flush();
    exit();
}

// Handle role update and secure login
if (isset($_POST['sw']) && isset($_POST['role']) && isset($_POST['ud'])) {
    try {
        $sw = $_POST['role'];
        $ud = intval($_POST['ud']);
        
        if ($ud <= 0) {
            throw new Exception('Invalid user ID');
        }
        
        $log->update_role($ud, $sw);
        $res = $log->secure_login($ud);
        
        if ($res && $res->num_rows > 0) {
            $pass = $res->fetch_assoc();
            
            if ($pass) {
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);
                
                $_SESSION["vaild"] = $pass['id'];
                $_SESSION["name"] = ($pass['first_name'] ?? '') . " " . ($pass['last_name'] ?? '');
                $_SESSION["userid"] = $pass['id'];
                $_SESSION["id"] = $pass['id'];
                $_SESSION['user_type'] = $pass['user_type'] ?? null;
                $_SESSION["email"] = $pass['email'] ?? '';
                $_SESSION["country"] = $pass['country'] ?? '';
                $_SESSION["profile_image"] = $pass['profile_image'] ?? '';
                $_SESSION["phone"] = $pass['phone'] ?? '';
                $_SESSION["business_type"] = $pass['business_type'] ?? '';
                $_SESSION["log"] = 1;
                $_SESSION["role"] = $pass['role'] ?? '';
                $_SESSION['default_app'] = $pass['default_app'] ?? 1;
                $_SESSION['dapp'] = $pass['default_app'] ?? 1;
                
                // Redirect based on default app
                $defaultApp = intval($_SESSION["default_app"]);
                switch ($defaultApp) {
                    case 1:
                        header("Location: " . url('/edu'));
                        break;
                    case 2:
                        header("Location: " . url('/work'));
                        break;
                    case 3:
                        header("Location: " . url('/personal'));
                        break;
                    default:
                        header("Location: " . url('/'));
                        break;
                }
                ob_end_flush();
                exit();
            }
        }
    } catch (Exception $e) {
        error_log("Role update error: " . $e->getMessage());
        $log->alert_redirect("An error occurred. Please try again.", "/login");
        exit();
    }
}

// Handle login
if (isset($_POST['login'])) {
    try {
        // Fixed syntax: use proper null coalescing
        $email = $_POST['email'] ?? '';
        $dapp = $_POST['dapp'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Validate required fields
        if (empty($email) || empty($password)) {
            $log->alert_redirect("Email and password are required", "/login");
            exit();
        }
        
        $email = strtolower(trim($email));
        $_SESSION['email_temp'] = $email;
        
        $result = $log->login($email, $password);
        
        // Check if login returned false (invalid credentials)
        if ($result === false) {
            $log->alert_redirect("Invalid email or password", "/login");
            exit();
        }
        
        // Check if result is a valid mysqli_result object
        if ($result && $result->num_rows > 0) {
            $pass = $result->fetch_assoc();
            
            if ($pass) {
                // Set session variables
                $_SESSION = array_merge($_SESSION, $pass);
                $_SESSION['user_type'] = $pass['user_type'] ?? null;
                $_SESSION['user_id'] = $pass['id'];
                $_SESSION['auth'] = $pass['id'];
                $_SESSION['dob'] = $pass['dob'] ?? null;
                $_SESSION['country'] = $pass['country'] ?? '';
                $_SESSION['profile_image'] = $pass['profile_image'] ?? '';
                $_SESSION['phone'] = $pass['phone'] ?? '';
                $_SESSION['business_type'] = 'individual';
                $_SESSION['log'] = 1;
                $_SESSION['first_name'] = $pass['first_name'] ?? '';
                $_SESSION['last_name'] = $pass['last_name'] ?? '';
                $_SESSION['role'] = $pass['role'] ?? '';
                $_SESSION['sub_role'] = $pass['sub_role'] ?? '';
                $_SESSION['full_name'] = ($pass['first_name'] ?? '') . " " . ($pass['last_name'] ?? '');
                $_SESSION['default_app'] = $pass['default_app'] ?? 2;
                $_SESSION['dapp'] = $pass['default_app'] ?? 2;
                
                $selected_topics = $pass['selected_topics'] ?? null;
                
                // Fixed logic: use && instead of or, and proper null/empty check
                if (!empty($selected_topics) && $selected_topics !== null && $selected_topics !== '') {
                    // Redirect based on default app
                    $dappValue = !empty($dapp) ? intval($dapp) : intval($_SESSION['dapp']);
                    
                    switch ($dappValue) {
                        case 1:
                        case 2:
                        case 3:
                        case 4:
                            header("Location: " . url('/dashboard'));
                            break;
                        default:
                            header("Location: " . url('/dashboard'));
                            break;
                    }
                } else {
                    header("Location: " . url('/dashboard'));
                }
                
                ob_end_flush();
                exit();
            }
        } else {
            // Handle users without an account or invalid credentials
            // Redirect to login (not signup) — signup is disabled in this single-user system
            $log->alert_redirect("Invalid email or password.", "/login");
            exit();
        }
    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
        $log->alert_redirect("An error occurred during login. Please try again.", "/login");
        exit();
    }
}

// --- Signup disabled: single-user system, accounts created by code only ---
// Both ref_signup and signup POST handlers are blocked to prevent unauthorized registration.
if (isset($_POST['ref_signup']) || isset($_POST['signup'])) {
    error_log("Blocked signup attempt from IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
    $log->alert_redirect("Registration is disabled.", "/login");
    exit();
}

// Handle forgot password
if (isset($_POST['forgot_password'])) {
    try {
        $email = strtolower(trim($_POST['email'] ?? ''));
        
        if (empty($email)) {
            $log->alert_redirect("Email is required", "/forgot_pass");
            exit();
        }
        
        $check = $log->check_user($email);
        
        // Fixed logic: use && instead of or, and proper empty check
        if (!empty($check) && $check !== null && $check !== '') {
            $verify = $log->verify($email);
            $_SESSION['email'] = $email;
            
            if ($verify) {
                $log->redirect("/forgot_pass?u=$check");
                exit(); // Stop execution after redirect to prevent fall-through
            } else {
                $log->alert_redirect("Failed to send verification code. Please try again.", "/forgot_pass");
                exit();
            }
        } else {
            $log->alert_redirect("You don't have an account with this email", "/signup");
            exit();
        }
    } catch (Exception $e) {
        error_log("Forgot password error: " . $e->getMessage());
        $log->alert_redirect("An error occurred. Please try again.", "/forgot_pass");
        exit();
    }
}

// Handle reset password
if (isset($_POST["reset_pass"])) {
    try {
        $new_pass = $_POST['new_pass'] ?? '';
        $email = $_SESSION['email'] ?? '';
        
        if (empty($new_pass)) {
            $log->alert_redirect("New password is required", "/forgot_pass");
            exit();
        }
        
        if (empty($email)) {
            $log->alert_redirect("Session expired. Please try again.", "/forgot_pass");
            exit();
        }
        
        if ($log->reset_password($email, $new_pass)) {
            $log->alert_redirect("Password Reset Successfully", "/login");
            exit(); // Stop execution after redirect to prevent fall-through
        } else {
            $log->alert_redirect("Something went wrong, try again", "/forgot_pass");
            exit();
        }
    } catch (Exception $e) {
        error_log("Reset password error: " . $e->getMessage());
        $log->alert_redirect("An error occurred. Please try again.", "/forgot_pass");
        exit();
    }
}

// Handle auth check
if (isset($_POST['check'])) {
    $auth = $_SESSION['vaild'] ?? null;
    if (empty($auth)) {
        echo 'NO';
    } else {
        echo 'YES';
    }
    ob_end_flush();
    exit();
}

// Handle action requests
if (isset($_POST['act'])) {
    $act = $_POST['act'] ?? '';
    if ($act == "logout") {
        session_destroy();
        header("Location: " . url('/'));
        ob_end_flush();
        exit();
    } else if ($act == "login") {
        header("Location: " . url('/login'));
        ob_end_flush();
        exit();
    } else if ($act == "signup") {
        header("Location: " . url('/signup'));
        ob_end_flush();
        exit();
    }
}

// Handle email verification
if (isset($_POST['verify'])) {
    try {
        $otp = $_POST['otp'] ?? '';
        $email = $_SESSION['email'] ?? '';
        
        if (empty($otp)) {
            $log->alert_redirect("OTP code is required", "/verification");
            exit();
        }
        
        if (empty($email)) {
            $log->alert_redirect("Session expired. Please try again.", "/verification");
            exit();
        }
        
        $stored_otp = $log->get_otp($email);
        
        if ($otp == $stored_otp) {
            $log->email_verified($email);
            $log->alert_redirect("Successfully Verified", "/login");
            exit(); // Stop execution after redirect to prevent fall-through
        } else {
            $log->alert_redirect("Invalid Code Try again", "/verification");
            exit();
        }
    } catch (Exception $e) {
        error_log("Verification error: " . $e->getMessage());
        $log->alert_redirect("An error occurred. Please try again.", "/verification");
        exit();
    }
}

// Handle resend OTP
if (isset($_POST['resend'])) {
    try {
        // Fixed: Get email from session, not undefined variable
        $email = $_SESSION['email'] ?? '';
        
        if (empty($email)) {
            $log->alert_redirect("Session expired. Please try again.", "/verification");
            exit();
        }
        
        $verify = $log->verify($email);
        
        if ($verify) {
            $log->alert_redirect("A New Code is Sent", "/verification");
            exit(); // Stop execution after redirect to prevent fall-through
        } else {
            $log->alert_redirect("Failed to send code. Please try again.", "/verification");
            exit();
        }
    } catch (Exception $e) {
        error_log("Resend OTP error: " . $e->getMessage());
        $log->alert_redirect("An error occurred. Please try again.", "/verification");
        exit();
    }
}

// Handle complete profile
if (isset($_POST['complete_profile'])) {
    try {
        $first_name = $_POST['first_name'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $country = $_POST['country'] ?? '';
        $city = $_POST['city'] ?? '';
        $address = $_POST['address'] ?? '';
        $postal_code = $_POST['postal_code'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $role = $_POST['role'] ?? '';
        $dob = $_POST['dob'] ?? '';
        $state = $_POST['state'] ?? '';
        $user_id = intval($_POST['user'] ?? 0);
        
        if ($user_id <= 0) {
            throw new Exception('Invalid user ID');
        }
        
        $full_name = $first_name . " " . $last_name;
        $business_type = "individual";
        $type = "express";
        
        // Validate date format
        if (!empty($dob)) {
            $dob_ar = explode("-", $dob);
            if (count($dob_ar) === 3) {
                $year = $dob_ar[0];
                $month = $dob_ar[1];
                $day = $dob_ar[2];
            } else {
                throw new Exception('Invalid date format');
            }
        } else {
            $year = $month = $day = null;
        }
        
        $settings = $log;
        $currency = null;
        
        $fp_ch = $settings->fp_check($email);
        
        if (!$fp_ch) {
            $stripe_connect_id = $settings->stripe_connect_account($first_name, $last_name, $email, $country, $type, $year, $month, $day, $business_type);
            $stripe_customer_id = $settings->stripe_customer($full_name, $email);
            $card_token = null;
            $source_id = null;
            $settings->user_fp($user_id, $email, $stripe_connect_id, $stripe_customer_id, $source_id, $card_token);
        }
        
        $res = $settings->complete_profile();
        
        if ($res) {
            if (method_exists($settings, 're_log')) {
                $settings->re_log($user_id);
            }
            $log->alert_redirect("Profile Completed Successfully", "/blog");
            exit(); // Stop execution after redirect to prevent fall-through
        } else {
            $log->alert_redirect("Failed, Try again", "/profile_setup");
            exit();
        }
    } catch (Exception $e) {
        error_log("Complete profile error: " . $e->getMessage());
        $log->alert_redirect("An error occurred. Please try again.", "/profile_setup");
        exit();
    }
}

// Handle select topics
if (isset($_POST['select_topics'])) {
    try {
        $selected_topics = $_POST['topics'] ?? [];
        $uid = intval($_POST['user'] ?? 0);
        
        if ($uid <= 0) {
            $log->alert_redirect("Invalid user ID", "/topics");
            exit();
        }
        
        if (empty($selected_topics) || !is_array($selected_topics)) {
            $log->alert_redirect("Please select at least one topic", "/topics");
            exit();
        }
        
        if ($log->update_selected_topics($uid, $selected_topics)) {
            $log->alert_redirect("Topics Saved Successfully", "/blog");
            exit(); // Stop execution after redirect to prevent fall-through
        } else {
            $log->alert_redirect("Failed. Try Again", "/topics");
            exit();
        }
    } catch (Exception $e) {
        error_log("Select topics error: " . $e->getMessage());
        $log->alert_redirect("An error occurred. Please try again.", "/topics");
        exit();
    }
}

// If no POST action matched, return error
http_response_code(400);
header('Content-Type: application/json');
echo json_encode([
    'status' => 'error',
    'message' => 'Invalid request. No valid action specified.'
]);
ob_end_flush();
exit();
