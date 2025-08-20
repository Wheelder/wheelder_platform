<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/apps/work/controllers/SettingsController.php';
$settings = new SettingsController();
$settings->authCheck();

include $path . '/config.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");






if(isset($_POST['company_profile'])){

    
  if($settings->add_companyInfo()){
    $settings->alert_redirect("Company profile updated successfully","/apps/work/ui/views/settings/settings.php");
  }
  else{
    echo '<script>alert("Try again")</script>';
  }
}


if(isset($_POST['profile_setup'])){

  $res=$settings->update_profile();
  if($res){
    $settings->alert_redirect("Profile updated successfully","/apps/work/ui/views/settings/settings.php");
  }else{
    echo '<script>alert("Try again")</script>';
  }
  
}




if(isset($_POST['complete_profile'])){

  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $email = $_POST['email'];
  $country = $_POST['country']=null;
  $currency= $settings->currencies($country);
  $city = $_POST['city']=null;
  $address = $_POST['address'];
  $postal_code = $_POST['postal_code'];
  $phone = $_POST['phone'];
  $role = $_POST['role'];
  $dob=$_POST['dob'];
  $email=$_POST['email'];
  $state=$_POST['state'];
  $full_name=$first_name." ".$last_name;
  $business_type="individual";
  $type="express";
  $dob_ar=explode("-",$dob);
  $year=$dob_ar[0];
  $month=$dob_ar[1];
  $day=$dob_ar[2];
  $user_id = $_POST['user'];


  $fp_ch=$settings->fp_check($email);

   if(!$fp_ch){

    $stripe_connect_id=$settings->stripe_connect_account($first_name, $last_name, $email, $country, $type,$year, $month, $day,$business_type);
  
    $stripe_customer_id=$settings->stripe_customer($full_name,$email);
  
    $card_token=null;
  
    //$source_id=$settings->create_source($currency, $email);
    $source_id=null;
  
    $settings->user_fp($user_id,$email,$stripe_connect_id,$stripe_customer_id,$source_id,$card_token);
    
  }

  $res=$settings->complete_profile();

  if($res){
  //  $settings->alert_redirect("Profile Completed Successfully","/apps/work/ui/views/home/home.php");
    $settings->re_log($user_id);
    
  }else{
    echo '<script>alert("Failed, Try again")</script>';
  }
  
}

if(isset($_POST['update_security'])){
  $user_id=$_SESSION['user_id'];
  $password=$_POST['password'];
  $old_password=$_SESSION['password'];

  $current_password=$_POST['current_password'];
  if( $current_password==$old_password){
      $settings->update_security($user_id,$password);
  }else{
    echo '<script>alert("Try again ")</script>';
  }


}




if(isset($_POST['notifications'])){
  

  $settings->notifications();


}


