<?php
include 'Controller.php';


class LogsController extends Controller
{

    public function __construct()
    {
        parent::__construct();


    }

    public function profile_checkup($user_id)
    {
        $sql = "SELECT * FROM profiles WHERE user_id='$user_id'";
        $results = $this->run_query($sql);
        $pro = $results->fetch_assoc();
        $status = $pro['profile_status'];


        if ($status == 0) {
            return false;
        } else {
            return true;
        }
    }

    public function user_details($uid)
    {
        $sql = "SELECT * FROM users WHERE id='$uid'";
        $result = $this->run_query($sql);
        $row = $result->fetch_assoc();
        return $row;
    }


    public function complete_profile()
    {
        $upload_dir = "/api";
        $image = basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], $_FILES['photo']['name']);
        $profile_image = "$upload_dir/$image";
        $user_id = $_SESSION['user_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $state = $_POST['state'];
        $country = $_POST['country'];
        $dob = $_POST['dob'];
        $role = $_POST['role'];
        $sub_role = NULL;
        $time_zone = $_POST['time_zone'];
        $city = $_POST['city'];
        $bio = $_POST['bio'];
        $zip_code = $_POST['postal_code'];
        $currency = $_POST['currency'];
        $address = $_POST['address'];
        $profile_status = 1;


        $sql = "UPDATE users SET first_name='$first_name',last_name='$last_name',country='$country',dob='$dob',phone='$phone',email='$email',
        state='$state',city='$city',zip_code='$zip_code',role='$role',sub_role='$sub_role',bio='$bio',currency='$currency',address='$address',time_zone='$time_zone',
        profile_image='$profile_image',profile_status='$profile_status' WHERE id='$user_id'";

        $results = $this->run_query($sql);

        return $results;

    }
    //create a function to update the user select_topics

    public function update_selected_topics($user_id, $selected_topics)
    {
        // Assuming $selected_topics is an array of selected topic IDs/names
    
        // Convert the array to a comma-separated string for storing in the database
        $topics = implode(',', $selected_topics);

        //encode to json
        //$topics = json_encode($selected_topics);
    
        // Escape the user_id and topics_string to prevent SQL injection
        //$escaped_user_id = mysqli_real_escape_string($this->connectDb, $user_id);
        //$escaped_topics_string = mysqli_real_escape_string($this->connection, $topics_string);
    
        // SQL query to update the user's selected topics
        $sql = "UPDATE users SET selected_topics = '$topics' WHERE id = '$user_id'";
    
        // Execute the query and return results
        $results = $this->run_query($sql);
    
        if($results){
            return true;
        }else{
            return false;
        }
    }
    



    public function selected_topics($user_id, $selected_categories)
    {
        // Assuming $selected_categories is an array of selected category IDs/names
        // Convert the array to a JSON string for storing in the database
        $categories_json = json_encode($selected_categories);

        // Sanitize user_id and categories_json to prevent SQL injection
        $user_id = $this->db->real_escape_string($user_id);
        $categories_json = $this->db->real_escape_string($categories_json);

        // SQL query to update the user's selected categories
        $sql = "UPDATE users SET selected_categories = '$categories_json' WHERE id = '$user_id'";

        // Execute the query and return results
        $results = $this->run_query($sql);

        return $results;
    }




    public function store($first_name, $last_name, $email, $ref, $password, $profile_status, $default_app)
    {

        $sql = "INSERT INTO users (first_name,last_name,email,referral_code,password,profile_status,default_app) 
        VALUES ('$first_name','$last_name','$email','$ref','$password','$profile_status','$default_app')";
        $results = $this->run_query($sql);
        if ($results) {
            return true;
        } else {
            return false;
        }

    }

    public function ref_signup($first_name, $last_name, $role, $email, $ref, $password, $profile_status, $default_app)
    {

        $sql = "INSERT INTO users (first_name,last_name,role,email,referral_code,password,profile_status,default_app) 
        VALUES ('$first_name','$last_name','$role','$email','$ref','$password','$profile_status','$default_app')";
        $results = $this->run_query($sql);
        if ($results) {
            return true;
        } else {
            return false;
        }
    }

    public function otp()
    {

        return rand(100000, 999999);

    }


    public function verify($email)
    {
        $otp = $this->otp();
        $sql = "UPDATE users SET otp='$otp' WHERE email='$email'";
        $res = $this->run_query($sql);
        if ($res) {
            $this->otp_email($email, $otp);
            return true;
        } else {
            return false;
        }
    }

    public function otp_email($email, $otp)
    {

        $to = "$email";
        $subject = "Verify Email";

        $message = "
                   <html>
                   <head>
                   <title>Verify Email</title>
                   </head>
                   <body>
                   <h3>Hello There,</h3>
                   <p>Please verify your account and email by using the following code:</p>
                   <p>$otp</p>
                   <br>
                   Thanks,<br>
                   Wheeleder Team
                    </body>
                   </html>
                   ";

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";


        // More headers
        $headers .= 'From: <system@wheeleder.com>' . "\r\n";
        $headers .= 'Cc: app@wheeleder.com' . "\r\n";

        mail($to, $subject, $message, $headers);

    }


    public function get_otp($email)
    {
        $sql = "SELECT * FROM users WHERE email='$email'";
        $res = $this->run_query($sql);
        $res = $res->fetch_assoc();
        if ($res) {
            return $res['otp'];
        } else {
            return false;
        }
    }

    public function email_verified($email)
    {
        $sql = "UPDATE users SET email_verified='1' WHERE email='$email'";
        $res = $this->run_query($sql);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    public function reset_password($email, $password)
    {
        $sql = "UPDATE users SET password='$password' WHERE email='$email'";
        $res = $this->run_query($sql);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }



    public function check_user($email)
    {
        $sql = "SELECT * FROM  users WHERE email='$email'";
        $result = $this->run_query($sql);
        $res = $result->fetch_assoc();
        if ($result->num_rows > 0) {

            return $res['ui'];

        } else {
            return '';
        }
    }

    public function checkAuth()
    {
        $auth = $_SESSION['user'];
        if (!isset($auth)) {
            header("Location: ./index.php");
        }
    }

    public function get_rating($id)
    {
        $sql = "SELECT COUNT(rating) FROM users WHERE id='$id'";
        $results = $this->run_query($sql);
        $res = $results->fetch_assoc();
        return $results;
    }



    public function delete($id)
    {
        $sql = "DELETE FROM users WHERE id='$id'";
        $results = $this->run_query($sql);
        echo json_encode($results);
    }
    public function update($id, $name, $username, $password)
    {
        $sql = "UPDATE users SET name='$name',username='$username',password='$password' WHERE id='$id'";
        $results = $this->run_query($sql);
        echo json_encode($results);
    }

    public function secure_login($uid)
    {
        $sql = "SELECT * FROM  users WHERE id='$uid'";
        $result = $this->run_query($sql);
        return $result;
    }


    public function update_role($id, $role)
    {

        $sql = "UPDATE users SET role='$role' WHERE id='$id'";
        $result = $this->run_query($sql);
        if ($result) {
            return true;
        } else {
            return false;
        }

    }

    public function search($name)
    {
        $sql = "SELECT * FROM users WHERE name='$name'";
        $results = $this->run_query($sql);
        echo json_encode($results);
    }
    public function view()
    {
        $sql = "SELECT * FROM users";
        $results = $this->run_query($sql);
        $res = $results->fetch_assoc();
        return $res;
    }
    public function authCheck()
    {
        $auth = $_SESSION['user'];
        if (!isset($auth)) {
            header("Location: ./index.php");
        }
    }
    public function login($email, $password)
    {
        $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
        $result = $this->run_query($sql);
        return $result;

    }

    public function email_verification_checkup($email)
    {
        $sql = "SELECT * FROM users WHERE email='$email' ";
        $result = $this->run_query($sql);
        $res = $result->fetch_assoc();
        if ($res['email_verified'] == 1) {
            return true;

        } else {
            return false;
        }

    }

    public function fp_check($email)
    {

        $sql = "SELECT * FROM financial_profile WHERE email='$email'";
        $result = $this->run_query($sql);
        $res = $result->fetch_assoc();
        if ($res) {
            return $res['id'];
        } else {
            return false;
        }
    }


    public function checkUp($email)
    {
        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = $this->run_query($sql);
        return $result;

    }
    public function logout()
    {
        session_start();
        $_SESSION['log'] = 0;
        session_destroy();

        header("Location: ./index.php");
    }

}


