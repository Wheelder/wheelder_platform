<?php
    session_destroy();
    header("Location: /");
/*
if(isset($_GET['act'])){
    $act=$_GET['act'];
    if($act=="out"){
        session_destroy();
        header("Location: /index.php");
    }else if($act=="login"){
        header("Location: /apps/work/ui/views/auth/login.php");
    }else if($act=="signup"){
        header("Location: /apps/work/ui/views/auth/signup.php");
    }    
}
*/