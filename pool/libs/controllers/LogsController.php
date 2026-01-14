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
        $sql = "SELECT * FROM profiles WHERE user_id=?";
        $results = $this->run_query_prepared($sql, [$user_id]);
        if ($results && $results->num_rows > 0) {
            $pro = $results->fetch_assoc();
            $status = $pro['profile_status'];
            if ($status == 0) {
                return false;
            } else {
                return true;
            }
        }
        return false;
    }

    public function user_details($uid)
    {
        $sql = "SELECT * FROM users WHERE id=?";
        $result = $this->run_query_prepared($sql, [$uid]);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        }
        return false;
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


        $sql = "UPDATE users SET first_name=?,last_name=?,country=?,dob=?,phone=?,email=?,
        state=?,city=?,zip_code=?,role=?,sub_role=?,bio=?,currency=?,address=?,time_zone=?,
        profile_image=?,profile_status=? WHERE id=?";

        $results = $this->run_query_prepared($sql, [$first_name, $last_name, $country, $dob, $phone, $email,
        $state, $city, $zip_code, $role, $sub_role, $bio, $currency, $address, $time_zone,
        $profile_image, $profile_status, $user_id]);

        return $results;

    }
    //create a function to update the user select_topics

    public function update_selected_topics($user_id, $selected_topics)
    {
        // Assuming $selected_topics is an array of selected topic IDs/names
    
        // Convert the array to a comma-separated string for storing in the database
        $topics = implode(',', $selected_topics);
    
        // SQL query to update the user's selected topics using prepared statement
        $sql = "UPDATE users SET selected_topics = ? WHERE id = ?";
    
        // Execute the query and return results
        $results = $this->run_query_prepared($sql, [$topics, $user_id]);
    
        if($results !== false){
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
        // Hash password before storing
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO users (first_name,last_name,email,referral_code,password,profile_status,default_app) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
        $results = $this->run_query_prepared($sql, [$first_name, $last_name, $email, $ref, $hashed_password, $profile_status, $default_app]);
        if ($results !== false) {
            return true;
        } else {
            return false;
        }

    }

    public function ref_signup($first_name, $last_name, $role, $email, $ref, $password, $profile_status, $default_app)
    {
        // Hash password before storing
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO users (first_name,last_name,role,email,referral_code,password,profile_status,default_app) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $results = $this->run_query_prepared($sql, [$first_name, $last_name, $role, $email, $ref, $hashed_password, $profile_status, $default_app]);
        if ($results !== false) {
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
        $sql = "UPDATE users SET otp=? WHERE email=?";
        $res = $this->run_query_prepared($sql, [$otp, $email]);
        if ($res !== false) {
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
        $sql = "SELECT * FROM users WHERE email=?";
        $result = $this->run_query_prepared($sql, [$email]);
        if ($result && $result->num_rows > 0) {
            $res = $result->fetch_assoc();
            return $res['otp'];
        } else {
            return false;
        }
    }

    public function email_verified($email)
    {
        $sql = "UPDATE users SET email_verified='1' WHERE email=?";
        $res = $this->run_query_prepared($sql, [$email]);
        if ($res !== false) {
            return true;
        } else {
            return false;
        }
    }

    public function reset_password($email, $password)
    {
        // Hash password before storing
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $sql = "UPDATE users SET password=? WHERE email=?";
        $res = $this->run_query_prepared($sql, [$hashed_password, $email]);
        if ($res !== false) {
            return true;
        } else {
            return false;
        }
    }



    public function check_user($email)
    {
        $sql = "SELECT * FROM users WHERE email=?";
        $result = $this->run_query_prepared($sql, [$email]);
        if ($result && $result->num_rows > 0) {
            $res = $result->fetch_assoc();
            return $res['id'];
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
        // Hash password before storing
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $sql = "UPDATE users SET name=?,username=?,password=? WHERE id=?";
        $results = $this->run_query_prepared($sql, [$name, $username, $hashed_password, $id]);
        echo json_encode($results !== false);
    }

    public function secure_login($uid)
    {
        $sql = "SELECT * FROM users WHERE id=?";
        $result = $this->run_query_prepared($sql, [$uid]);
        return $result;
    }


    public function update_role($id, $role)
    {
        $sql = "UPDATE users SET role=? WHERE id=?";
        $result = $this->run_query_prepared($sql, [$role, $id]);
        if ($result !== false) {
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
        $sql = "SELECT * FROM users WHERE email=?";
        $result = $this->run_query_prepared($sql, [$email]);
        
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify password using password_verify
            if (password_verify($password, $user['password'])) {
                // Return result object for compatibility
                $conn = $this->connectDb();
                $sql2 = "SELECT * FROM users WHERE email=?";
                $stmt = $conn->prepare($sql2);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                return $stmt->get_result();
            } else {
                // Password doesn't match - return empty result
                return false;
            }
        }
        return false;

    }

    public function email_verification_checkup($email)
    {
        $sql = "SELECT * FROM users WHERE email=?";
        $result = $this->run_query_prepared($sql, [$email]);
        if ($result && $result->num_rows > 0) {
            $res = $result->fetch_assoc();
            if ($res['email_verified'] == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    public function fp_check($email)
    {
        $sql = "SELECT * FROM financial_profile WHERE email=?";
        $result = $this->run_query_prepared($sql, [$email]);
        if ($result && $result->num_rows > 0) {
            $res = $result->fetch_assoc();
            return $res['id'];
        } else {
            return false;
        }
    }


    public function checkUp($email)
    {
        $sql = "SELECT * FROM users WHERE email=?";
        $result = $this->run_query_prepared($sql, [$email]);
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


