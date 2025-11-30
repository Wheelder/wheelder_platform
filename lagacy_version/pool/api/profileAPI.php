<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/apps/work/controllers/ProfileController.php';
$profile = new ProfileController();

if(isset($_POST['submit_review'])){
    
    if($profile->add_review()){

        //$profile->alert_redirect('Review Added Successfully',"/ui/profile/profile.php?ui=".$_POST['reviewee'].");
        echo "Review Added Successfully";
    }else{
        echo "Review Not Added Successfully";
        //$profile->alert_redirect('Review Not Added','/ui/profile/users_reviews.php?reviewer='.$_POST['reviewer'].'&reviewee='.$_POST['reviewee'].'&ct='.$_POST['contract'].'');
    }
    
}





?>