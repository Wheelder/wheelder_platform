<?php
$path= $_SERVER['DOCUMENT_ROOT'];
include $path.'/pool/libs/controllers/Controller.php';
require $path . '/pool/libs/composer/vendor/autoload.php';

class SettingsController extends Controller{
  
    public function __construct(){
        parent::__construct();
        
      
    }

    public function update_profile()
    {
      $upload_dir ="/api";
      $image=basename($_FILES['photo']['name']);
      move_uploaded_file($_FILES['photo']['tmp_name'], $_FILES['photo']['name']);
      $profile_image="$upload_dir/$image";
      $user_id = $_SESSION['user_id'];
      $first_name=$_POST['first_name'];
      $last_name=$_POST['last_name'];
      $phone=$_POST['phone'];
      $email=$_POST['email'];
      $state=$_POST['state'];
      $time_zone=$_POST['time_zone'];
      $city=$_POST['city'];
      $zip_code=$_POST['zip_code'];
      $address=$_POST['address'];
  

        $sql ="UPDATE users SET first_name='$first_name',last_name='$last_name',phone='$phone',email='$email',
        state='$state',city='$city',zip_code='$zip_code',address='$address',time_zone='$time_zone',
        profile_image='$profile_image' WHERE id='$user_id'";

        $results = $this->run_query($sql);

        return $results;

    }

    public function skip()
    {
    
      $user_id = $_SESSION['user_id'];
  
      $profile_status=-1;
  

        $sql ="UPDATE users SET profile_status='$profile_status' WHERE id='$user_id'";

        $results = $this->run_query($sql);

        return $results;

    }

    public function complete_profile()
    {
      $upload_dir ="/api";
      $image=basename($_FILES['photo']['name']);
      move_uploaded_file($_FILES['photo']['tmp_name'], $_FILES['photo']['name']);
      $profile_image="$upload_dir/$image";
      $user_id = $_SESSION['user_id'];
      $first_name=$_POST['first_name'];
      $last_name=$_POST['last_name'];
      $phone=$_POST['phone'];
      $email=$_POST['email'];
      $state=$_POST['state'];
      $country=$_POST['country'];
      $dob=$_POST['dob'];
      $role = $_POST['role'];
      $sub_role = NULL;
      $time_zone=$_POST['time_zone'];
      $city=$_POST['city'];
      $bio=$_POST['bio'];
      $zip_code= $_POST['postal_code'];
      $currency=$_POST['currency'];
      $address=$_POST['address'];
      $profile_status=1;
  

        $sql ="UPDATE users SET first_name='$first_name',last_name='$last_name',country='$country',dob='$dob',phone='$phone',email='$email',
        state='$state',city='$city',zip_code='$zip_code',role='$role',sub_role='$sub_role',bio='$bio',currency='$currency',address='$address',time_zone='$time_zone',
        profile_image='$profile_image',profile_status='$profile_status' WHERE id='$user_id'";

        $results = $this->run_query($sql);

        return $results;

    }

    public function notifications()
    {
    
      $email_notif=$_POST['email_noti'];
      $sms_notif=$_POST['sms_noti'];
      $user_id = $_SESSION['user_id'];
      $noti_posts=$_POST['noti_posts'];
      
      $noti_jobs=$_POST['noti_jobs'];
    
      $noti_blogs=$_POST['noti_blogs'];

      $sql="INSERT INTO notifications(user_id,email_noti,sms_noti,noti_posts,noti_jobs,noti_blogs)
      values('$user_id','$email_notif','$sms_notif','$noti_posts','$noti_jobs','$noti_blogs')";
      $result=$this->run_query($sql);
      if($result){
        $this->alert_redirect('Notifications Updated Successfully','/apps/work/ui/views/settings/settings.php?form=notifications');
      }else{
        $this->alert_redirect('Notifications Update Failed','/apps/work/ui/views/settings/settings.php?form=notifications');
      }

    
        
      
      

      

    }

    public function view($user_id)
    {

        $sql = "SELECT * FROM users WHERE id='$user_id'";
        $result = $this->run_query($sql);
        $res=$result->fetch_assoc();
        return $res;
    }

    public function db(){
        return $this->run_query;
    }

    public function add_companyInfo(){
      $user_id=$_SESSION['user_id'];
      $company_name=$_POST['company_name'];
      $company_details=$_POST['company_details'];
      $industry=$_POST['industry'];
      $phone=$_POST['company_phone'];
      $email=$_POST['company_email'];
      $time_zone=$_POST['time_zone'];
      $address=$_POST['company_address'];
      $vat_id=$_POST['vat_id'];
      $industry=$_POST['industry'];
      $logo=move_uploaded_file($_FILES['logo']['tmp_name'], $_FILES['logo']['name']);

      $sql="INSERT INTO company_profiles(user_id,company_name,description,industry,time_zone,company_phone,company_email,company_address,vat_id,logo)
      VALUES('$user_id','$company_name','$company_details','$industry','$time_zone','$phone','$email','$address','$vat_id','$logo')";
      $result=$this->run_query($sql);
      if($result){
        return true;
      }else{
        return false;
      }
    }

    public function user_details($uid){
        $sql="SELECT * FROM users WHERE id='$uid'";
        $result=$this->run_query($sql);
        $row=$result->fetch_assoc();
        return $row;
    }

    public function company_details($uid){
      $sql="SELECT * FROM company_profiles WHERE id='$uid'";
      $result=$this->run_query($sql);
      $row=$result->fetch_assoc();
      return $row;
  }

    public function update_myinfo($user_id,$name,$email,$phone,$address){
    
        $sql="UPDATE users SET name='$name', email='$email', phone='$phone', address='$address' WHERE id='$user_id'";
        $result=$this->run_query($sql);
        if($result){
            echo '<script>alert("Account Updated Successfully")</script>';
            echo '<script>window.location.href="settings.php?form=myinfo"</script>';
        }else{
            echo '<script>alert("Account Update Failed")</script>';
            echo '<script>window.location.href="settings.php?form=myinfo"</script>';
        }
    }
    public function update_security($uid,$password){
    
      $sql="UPDATE users SET password='$password' WHERE id='$uid'";
      $result=$this->run_query($sql);
      if($result){
          echo '<script>alert("Security Updated Successfully")</script>';
          echo '<script>window.location.href="/apps/work/ui/views/settings/settings.php?form=security"</script>';
      }else{
          echo '<script>alert("Account Update Failed")</script>';
          echo '<script>window.location.href="/apps/work/ui/views/settings/settings.php?form=security"</script>';
      }
  }

  public function secure_login($uid){
    $sql = "SELECT * FROM  users WHERE id='$uid'";
    $result = $this->run_query($sql);
    return $result;
}

public function re_log($uid){

      
        $result=$this->secure_login($uid);
        $pass=$result->fetch_assoc();
        if ($pass) {
      
          $user_type=$pass['user_type'];
          $_SESSION["user"] = $pass['id'];
          $_SESSION["vaild"]=$pass['id'];
          $_SESSION["user_id"]=$pass['id'];
          $_SESSION["password"]=$pass['password'];
          $_SESSION["pwd"]=$pass['password'];
          $_SESSION["userid"]=$pass['id'];
          $_SESSION["dob"]=$pass['dob'];
          $_SESSION["id"]=$pass['id'];
          $_SESSION['user_type']=$pass['user_type'];
          $_SESSION["email"] = $pass['email'];
          $_SESSION["country"] = $pass['country'];
          $_SESSION["dob"] = $pass['dob'];
          $dob=$pass['dob'];
          $_SESSION["profile_image"] = $pass['profile_image'];
          $_SESSION["phone"] = $pass['phone'];
          $_SESSION["business_type"] = "individual";
          $_SESSION["log"] =   1;
          $_SESSION["first_name"] = $pass['first_name'];
          $_SESSION["last_name"] = $pass['last_name'];
          $_SESSION["full_name"] = $pass['first_name']." ".$pass['last_name'];
          $_SESSION["role"] = $pass['role'];
          $role=$pass['role'];
          $dob_ar=explode("-",$dob);
          $year=$dob_ar[0];
          $month=$dob_ar[1];
          $day=$dob_ar[2];
          $profile_status=$pass['profile_status'];
      
            if($profile_status==0){
              header("Location: /apps/work/ui/views/settings/complete_profile.php");
            }else{
              header("Location: /apps/work/ui/views/home/home.php");
              
            }
         
        }
          
}


  public function add_payout_method(){
    $user_id=$_SESSION['user_id'];
    $bank_name=$_POST['bank_name'];
    $account_name=$_POST['account_name'];
    $account_no=$_POST['account_no'];
    $transit_no=$_POST['transit_no'];
    $institution_no=$_POST['institution_no'];
    
    $sql="INSERT INTO payment_method_banks (user_id,bank_name,account_name,account_no,transit_no,institution_no) 
    VALUES ('$user_id','$bank_name','$account_name','$account_no','$transit_no','$institution_no')";
    $result=$this->run_query($sql);
    if($result){
      $this->alert_redirect("Payment Method Updated","/apps/work/ui/views/settings/settings.php?form=payouts");
    }else{
      $this->alert_redirect("Failed Try Again","/apps/work/ui/views/settings/settings.php?form=payouts");
    }
  }

  public function add_payment_card(){
    $user_id=$_SESSION['user_id'];
    $card_number=$_POST['card_number'];
    $card_name=$_POST['card_name'];
    $billing_address=$_POST['billing_address'];
    $card_expiry_mm=$_POST['card_expiry_mm'];
    $card_expiry_year=$_POST['card_expiry_yy'];
    $card_cvv=$_POST['card_cvv'];
  
  
      $sql="INSERT INTO payment_method_cards(user_id,card_no,card_name,billing_address,card_expiry_month,card_expiry_year,card_cvv) 
      VALUES('$user_id','$card_number','$card_name','$billing_address','$card_expiry_mm','$card_expiry_year','$card_cvv')";
      $result=$this->run_query($sql);
      if($result){
         $this->alert_redirect("Payment Method Updated","/apps/work/ui/views/settings/settings.php?form=payments");
      }else{
        $this->alert_redirect("Failed Try again","/apps/work/ui/views/settings/settings.php?form=payments");
      }
    
  }


  public function fp_check($email)
  {

      $sql = "SELECT * FROM financial_profile WHERE email='$email'";
      $result = $this->run_query($sql);
      $res=$result->fetch_assoc();
      if($res){
        return $res['id'];
      }else{
        return false;
      }
  }



}
?>