<?php
    // Include url() helper for consistent redirects across environments
    require_once dirname(dirname(__DIR__)) . '/top.php';
    session_destroy();
    header("Location: " . url('/'));
/*
if(isset($_GET['act'])){
    $act=$_GET['act'];
    if($act=="out"){
        session_destroy();
        header("Location: " . url('/index.php'));
    }else if($act=="login"){
        header("Location: /apps/work/ui/views/auth/login.php");
    }else if($act=="signup"){
        header("Location: /apps/work/ui/views/auth/signup.php");
    }    
}
*/