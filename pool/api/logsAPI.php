<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Use __DIR__ to get the current file's directory and navigate relatively
$path = dirname(__DIR__); // Goes from pool/api to pool
include $path . '/libs/controllers/LogsController.php';

$log = new LogsController();

// Handle direct access (GET request without POST data)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($_POST)) {
    // Return JSON response indicating this is an API endpoint
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
    exit();
}

if (isset($_POST['sw'])) {

  $sw = $_POST['role'];
  $ud = $_POST['ud'];
  $log->update_role($ud, $sw);
  //session_unset();
  //session_destroy();

  $res = $log->secure_login($ud);
  $pass = $res->fetch_assoc();
  if ($pass) {


    $_SESSION["vaild"] = $pass['id'];
    $_SESSION["name"] = $pass['first_name'] . "" . $pass['last_name'];
    $_SESSION["password"] = $pass['password'];
    $_SESSION["pwd"] = $pass['password'];
    $_SESSION["userid"] = $pass['id'];
    $_SESSION["id"] = $pass['id'];
    $_SESSION['user_type'] = $pass['user_type'];
    $_SESSION["email"] = $pass['email'];
    $_SESSION["country"] = $pass['country'];
    $_SESSION["profile_image"] = $pass['profile_image'];
    $_SESSION["phone"] = $pass['phone'];
    $_SESSION["business_type"] = $pass['business_type'];
    $_SESSION["log"] = 1;
    $_SESSION["role"] = $pass['role'];
    $role = $pass['role'];

    $_SESSION['default_app'] = $pass['default_app']; // Assuming 'default_app' is in the database
    $_SESSION['dapp'] = $pass['default_app'];
    // Redirect based on default app
    switch ($_SESSION["default_app"]) {
      case 1:
        header("Location: /edu"); // Redirect to education app
        break;
      case 2:
        header("Location: /work"); // Redirect to work app
        break;
      case 3:
        header("Location: /personal"); // Redirect to personal app
        break;
      default:
        header("Location: /"); // Redirect to home page if default app is not set
        break;
    }

    exit();



    $log->alert_redirect("Welcome Back", "/work");
  }
}

if (isset($_POST['login'])) {

  $email = $_POST['email'] ?? '' or Null;
  $_SESSION['email_temp'] = $email;
  $dapp = $_POST['dapp'] ?? '' or Null;
  $password = $_POST['password'] ?? '' or Null;
  $result = $log->login($email, $password);
  $verified = $log->email_verification_checkup($email);

  //if ($verified) {

    $pass = $result->fetch_assoc();

    if ($pass) {

      // Set session variables
      $_SESSION = array_merge($_SESSION, $pass);
      $_SESSION['user_type'] = $pass['user_type'];
      $_SESSION['user_id'] = $pass['id'];
      $_SESSION['auth'] = $pass['id'];
      $_SESSION['password'] = $pass['password'];
      $_SESSION['dob'] = $pass['dob'];
      $_SESSION['country'] = $pass['country'];
      $_SESSION['profile_image'] = $pass['profile_image'];
      $_SESSION['phone'] = $pass['phone'];
      $_SESSION['business_type'] = 'individual';
      $_SESSION['log'] = 1;
      $_SESSION['first_name'] = $pass['first_name'];
      $_SESSION['last_name'] = $pass['last_name'];
      $_SESSION['role'] = $pass['role'];
      $_SESSION['sub_role'] = $pass['sub_role'];

      $_SESSION['full_name'] = $pass['first_name'] . " " . $pass['last_name'];

      $_SESSION['default_app'] = $pass['default_app']; // Assuming 'default_app' is in the database
      $_SESSION['dapp'] = $pass['default_app'];
      $selected_topics = $pass['selected_topics'];

      if ($selected_topics != null or $selected_topics != '') {


        // Redirect based on default app
        if ($dapp == null or $dapp == '') {

          switch ($_SESSION['dapp']) {
            case 1:
              header("Location: /learn"); // Redirect to personal app  
              break;
            case 2:
              header("Location: /learn"); // Redirect to education app
              break;
            case 3:
              header("Location: /learn"); // Redirect to work app
              break;
            case 4:
              header("Location: /learn"); // Redirect to work app
              break;
            default:
              header("Location: /learn"); // Redirect to home page if default app is not set
              break;
          }


        } else {

          switch ($_SESSION['dapp']) {
            case 1:
              header("Location: /learn"); // Redirect to personal app  
              break;
            case 2:
              header("Location: /learn"); // Redirect to education app
              break;
            case 3:
              header("Location: /learn"); // Redirect to work app
              break;
            default:
              header("Location: /learn"); // Redirect to home page if default app is not set
              break;
          }
        }

      } else {

        header("Location: /learn");

      }


      exit();


    /*
    } else {
      $otp = $log->verify($_SESSION['email_temp']);
      $log->alert_redirect("Please verify your email, new code is sent", "/verification");
    }
    */

  } else {
    // Handle users without an account
    $log->alert_redirect("Make sure you have an account here", "/signup");
  }

}

if (isset($_POST['ref_signup'])) {
  $ref = $_POST['ref'];
  $email = $_POST['email'];
  $email = strtolower($email);

  if ($log->check_user($email)) {
    $log->alert_redirect("Use another email, already exists", "/signup");
    exit();
  } else {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $default_app = $_POST['default_app'];
    $referringCode = null;
    //encrypt password

    $password = $_POST['password'];

    $profile_status = 0;
    $signup = $log->store($first_name, $last_name, $email, $referringCode, $password, $profile_status, $default_app);
    if ($signup) {
      $verify = $log->verify($email);
      $_SESSION['email'] = $email;
      if ($verify) {
        $log->alert_redirect("Please verify your email, OTP code is sent", "/verification");
      } else {
        $log->alert_redirect("Something went wrong, try again", "/signup");
      }
    } else {
      $log->alert_redirect("Something went wrong, try again later", "/signup");
    }
  }
}

if (isset($_POST['signup'])) {

  $email = $_POST['email'];
  $email = strtolower($email);

  if ($log->check_user($email)) {
    $log->alert_redirect("Use another email, already exists", "/signup");
    exit();
  } else {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    //$default_app = $_POST['default_app'];
    $default_app = 2;
    $referringCode = null;
    //encrypt passwor
    $password = $_POST['password'];

    $profile_status = 0;
    $signup = $log->store($first_name, $last_name, $email, $referringCode, $password, $profile_status, $default_app);
    if ($signup) {
      $log->alert_redirect("Login Now", "/login");
      //$verify = $log->verify($email);
      /*
      $_SESSION['email'] = $email;
      if ($verify) {
        $log->alert_redirect("Please verify your email, OTP code is sent", "/verification");
      } else {
        $log->alert_redirect("Something went wrong, try again", "/signup");
      }
        */
    } else {
      $log->alert_redirect("Something went wrong, try again later", "/signup");
    }
  }

}

if (isset($_POST['forgot_password'])) {


  $email = $_POST['email'];
  $email = strtolower($email);
  $check = $log->check_user($email);

  if ($check != null or $check != '') {


    $verify = $log->verify($email);
    $_SESSION['email'] = $email;
    if ($verify) {

      $log->redirect("/forgot_pass?u=$check");

    } else {
      echo "Error";
    }

  } else {

    $message = "You dont have an account with this email";
    $url = "/signup";

    $log->alert_redirect($message, $url);


  }


}
//reset password
if (isset($_POST["reset_pass"])) {

  $new_pass = $_POST['new_pass'];
  $email = $_SESSION['email'];
  if ($log->reset_password($email, $new_pass)) {
    $log->alert_redirect("Password Reset Successfully", "/login");
  } else {
    $log->alert_redirect("Something went wrong, try again", "/forgot_pass");
  }
}

if (isset($_POST['check'])) {


  $auth = $_SESSION['vaild'];
  if (($auth == null)) {
    echo 'NO';
  } else {
    echo 'YES';
  }

}

if (isset($_POST['act'])) {

  $act = $_POST['act'];
  if ($act == "logout") {
    session_destroy();
    header("Location: /index.php");
  } else if ($act == "login") {
    header("Location: /apps/work/ui/views/auth/login.php");
  } else if ($act == "signup") {
    header("Location: /apps/work/ui/views/auth/signup.php");
  }



}

if (isset($_POST['verify'])) {

  $otp = $_POST['otp'];
  $email = $_SESSION['email'];
  $stored_otp = $log->get_otp($email);
  //echo $stored_otp;
  if ($otp == $stored_otp) {

    $log->email_verified($email);

    $log->alert_redirect("Successfully Verified", "/login");

  } else {

    $log->alert_redirect("Invalid Code Try again", "/verification");
  }

}

if (isset($_POST['resend'])) {

  $verify = $log->verify($email);

  if ($verify) {

    $log->alert_redirect("A New Code is Sent", "/verification");
  } else {
    echo "Error";
  }
}

if (isset($_POST['complete_profile'])) {

  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $email = $_POST['email'];
  $country = $_POST['country'];
  //$currency= $settings->currencies($country);
  $currency = null;
  $city = $_POST['city'];
  $address = $_POST['address'];
  $postal_code = $_POST['postal_code'];
  $phone = $_POST['phone'];
  $role = $_POST['role'];
  $dob = $_POST['dob'];
  $email = $_POST['email'];
  $state = $_POST['state'];
  $full_name = $first_name . " " . $last_name;
  $business_type = "individual";
  $type = "express";
  $dob_ar = explode("-", $dob);
  $year = $dob_ar[0];
  $month = $dob_ar[1];
  $day = $dob_ar[2];
  $user_id = $_POST['user'];

  $settings = $log;

  $fp_ch = $settings->fp_check($email);

  if (!$fp_ch) {

    $stripe_connect_id = $settings->stripe_connect_account($first_name, $last_name, $email, $country, $type, $year, $month, $day, $business_type);

    $stripe_customer_id = $settings->stripe_customer($full_name, $email);

    $card_token = null;

    //$source_id=$settings->create_source($currency, $email);
    $source_id = null;

    $settings->user_fp($user_id, $email, $stripe_connect_id, $stripe_customer_id, $source_id, $card_token);

  }

  $res = $settings->complete_profile();

  if ($res) {
    //  $settings->alert_redirect("Profile Completed Successfully","/apps/work/ui/views/home/home.php");
    $settings->re_log($user_id);

  } else {
    echo '<script>alert("Failed, Try again")</script>';
  }

}

// Check if the selected_topics data is posted
if (isset($_POST['select_topics'])) {

  // Retrieve the selected topics from the POST data
  // 'topics' is the name of the checkbox field in the form
  //  $selected_topics = isset($_POST['topics']) ? $_POST['topics'] 

  $selected_topics = $_POST['topics'];

  $uid = $_POST['user'];

  // Assuming $log is your logging or database handling object and is already instantiated
  if ($log->update_selected_topics($uid, $selected_topics)) {
    // Redirect with a success message
    $log->alert_redirect("Topics Saved Successfully", "/lib");
  } else {
    // Handle the error case, such as if updating the database fails
    $log->alert_redirect("Failed. Try Again", "/topics");
  }
}


?>