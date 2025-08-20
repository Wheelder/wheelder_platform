<?php
$path= $_SERVER['DOCUMENT_ROOT'];
include $path.'/pool/libs/controllers/Controller.php';


class ProfileController extends Controller
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
        $tagline = $pro['tagline'];
        $specialty = $pro['specialty'];
        $skills = $pro['skills'];
        $specialty_category = $pro['specialty_category'];
        $skills_category = $pro['skills_category'];
        $profile_image = $pro['profile_image'];

        if ($tagline == "" || $specialty == "" || $skills == "" || $specialty_category == "" || $skills_category == "" || $profile_image == "") {
            return false;
        } else {
            return true;
        }
    }






    public function setup($photo_name, $photo_tmp, $photo_size, $target_dir, $tagline, $specilty, $skills, $specialty_category, $skills_category, $profile_status, $user_id)
    {

        $file = $this->store_file($photo_name, $photo_tmp, $photo_size, $target_dir);

        $sql = "INSERT INTO profiles (user_id,profile_image,profile_status,tagline,specialty,skills,specialty_category,skills_category) 
        values ('$user_id','$file','$profile_status','$tagline','$specilty','$skills','$specialty_category','$skills_category');";

        $sql .= "UPDATE users SET profile_status='$profile_status',profile_image='$file' WHERE id='$user_id'";

        $results = $this->run_multi_query($sql);

        if ($results) {
            return true;
        } else {
            return false;
        }
    }

    public function update_profile($photo_name, $photo_tmp, $photo_size, $name, $phone, $email, $state, $city, $zip_code, $address, $user_id)
    {
        $profile_image = $this->store_file($photo_name, $photo_tmp, $photo_size);

        $sql = "UPDATE users SET name='$name',phone='$phone',email='$email',
        state='$state',city='$city',zip_code='$zip_code',address='$address',
        profile_image='$profile_image' WHERE id='$user_id'";
        $results = $this->run_query($sql);
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
    public function search($name)
    {
        $sql = "SELECT * FROM users WHERE name='$name'";
        $results = $this->run_query($sql);
        echo json_encode($results);
    }
    public function view($user_id)
    {

        $sql = "SELECT * FROM users WHERE id='$user_id'";
        $result = $this->run_query($sql);
        $res=$result->fetch_assoc();
        return $res;
    }

    public function add_review()
    {
        

        $reviewer = $_POST['reviewer'];
        $reviewee = $_POST['reviewee'];
        $review = $_POST['review'];
        $rating = $_POST['rating'];
        $contract = $_POST['contract'];
        $date_time = date('Y-m-d H:i:s');

        $sql = "INSERT INTO reviews(reviewer_id,reviewee_id,review,rating,date_time,contract_id)
        VALUES('$reviewer','$reviewee','$review','$rating','$date_time','$contract')";

        $results = $this->run_query($sql);

        if ($results) {
            return true;
        } else {
            return false;
        }
    }

    public function reviews($uid)
    {
        

        $sql = "SELECT * FROM reviews WHERE reviewee_id='$uid'";
        $results = $this->run_query($sql);
        if ($results->num_rows > 0) {
            while ($row = $results->fetch_array()) {
                echo '<div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="">
                        
                            
                        </div>
                        <div class="">
                        
                            <a href="">' . $this->get_contract_detail($row['reviewer_id']) . '</a>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-10">
                            <p>"
                                ' . $row['review'] . '
                            "</p>
                            <p class="">' . $this->get_name($row['reviewer_id']) . '</p>
                            <div class="clearfix"></div>
                            <p class="text-secondary text-right">' . $row['date_time'] . '</p>
                        </div>
                    </div>';
            }
        } else {
            return false;
        }
    }
/*
    public function get_profile_image($user_id)
    {
        $sql = "SELECT profile_image FROM profiles WHERE user_id='$user_id'";
        $results = $this->run_query($sql);
        $row = $results->fetch_assoc();
        return $row['profile_image'];
    }
*/

    public function get_name($user_id)
    {
        $sql = "SELECT * FROM users WHERE id='$user_id'";
        $results = $this->run_query($sql);
        $row = $results->fetch_assoc();
        return $row['first_name'].' '.$row['last_name'];
    }

    public function get_contract_detail($id)
    {
        $sql = "SELECT * FROM contracts WHERE awarded_to='$id' OR user_id='$id'";
        $results = $this->run_query($sql);
        $row = $results->fetch_assoc();
        return $row['title'];
    }
    
}