<?php
session_start();
$path=$_SERVER['DOCUMENT_ROOT'];
include $path.'/apps/work/controllers/AdminController.php';
$admin=new AdminController();

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");


if($_GET['act']=="create"){
    
        $input=file_get_contents("php://input");
        $decode=json_decode($input,true);

        $full_name=$decode['full_name'];
        $email=$decode['email'];
        $phone=$decode['phone'];
        $country=$decode['country'];
        $password=$decode['password'];
        $role=$decode['role'];
        $admin->add_user($full_name,$email,$phone,$country,$password,$role);
}


if($_GET['act']=="count"){
    
    $admin->count_users();
    
}
if($_GET['act']=="list"){
    
    $admin->all_users();
    
}

if($_GET['act']=="delete"){
    $id=$_GET['id'];
    $admin->user_delete($id);
}

if($_GET['act']=="edit"){
    $id=$_GET['id'];
   $admin->edit_user($id);

}

if($_GET['act']=="update"){

    $input=file_get_contents("php://input");
    $decode=json_decode($input,true);
    
        $id=$decode['id'];
        $name=$decode['name'];
        $age=$decode['age'];
        $country=$decode['country'];
        $admin->update_student($id,$name,$age,$country);
    
}


    


?>