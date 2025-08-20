<?php

$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/apps/edu/controllers/ProfileController.php';
$profile = new ProfileController();

$user_id = $_GET['ui']=null;
if(!isset($_GET['ui'])){
    $user_id = $_SESSION['user_id'];
}
//
//write below code without sanitization
$details=$profile->view($user_id);
$profile_photo = $details['profile_image'];
$first_name=$details['first_name'];
$last_name=$details['last_name'];
$phone=$details['phone'];
$email=$details['email'];
$state=$details['state'];
$bio= $details['bio'];
$city=$details['city'];
$zip_code=$details['zip_code'];
$address=$details['address'];


include $path . '/apps/edu/ui/layouts/nav.php';

?>

<style>
.postform {
    display: none;
}
.op-0 {
    opacity: 0;
    width: inherit;
}
</style>
<section class="py-60 ">

    <form action="" method="post" enctype="multipart/form-data">
           
        <div class="container-lg">
            <div class="row p-3">
        
        
                <div class="col-md-8 m-auto bordered shadow">
                    <div class="d-flex flex-column gap-2">
                    
                        <div class="d-flex gap-4">
                            <div class="user-pic w-100 h-100 overflow-hidden ofit flex-shrink-0 ">
                                <img src="<?php echo $profile_photo; ?>" alt="image" width="200" height="180" class="rounded-circle" />
                            </div>
                            
                        </div>
                        <div class="d-flex flex-column gap-1">
                            <label for="" class="text-black fs-14 fw-600">First Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" value="<?php echo $first_name; ?>"
                                required placeholder="Enter Your Name" disabled />
                        </div>
                            <div class="d-flex flex-column gap-1">
                            <label for="" class="text-black fs-14 fw-600">Last Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" value="<?php echo $last_name; ?>"
                                required placeholder="Enter Your Name" disabled />
                        </div>
                        <div class="d-flex flex-column gap-1">
                            <label for="" class="text-black fs-14 fw-600">Phone Number <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="phone" value="<?php echo $phone; ?>"
                                required placeholder="Enter Your Phone" disabled />
                        </div>
                        <div class="d-flex flex-column gap-1">
                            <label for="" class="text-black fs-14 fw-600">Email <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="email" value="<?php echo $email; ?>"
                                required placeholder="Enter Your Email" disabled />
                        </div>
                        <div class="d-flex flex-column gap-1">
                            <label for="" class="text-black fs-14 fw-600">State <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="state" value=" <?php echo $state; ?> "
                                required placeholder="Enter Your State Name" disabled />
                        </div>
                        <div class="d-flex flex-column gap-1">
                            <label for="" class="text-black fs-14 fw-600">City <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="city" value="<?php echo $city; ?>"
                                required placeholder="Enter Your City Name" disabled />
                        </div>
                        <div class="d-flex flex-column gap-1">
                            <label for="" class="text-black fs-14 fw-600">Zip Code <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="zip_code"
                                value="<?php echo $zip_code; ?>" required
                                placeholder="Enter Your Zip Code" disabled />
                        </div>
                        <div class="d-flex flex-column gap-1">
                            <label for="" class="text-black fs-14 fw-600">Address <span
                                    class="text-danger">*</span></label>
                            
                            <input type="text" name="address" value="<?php echo $address; ?> " disabled>
                        </div>
                        <div class="d-flex flex-column gap-1">
                            <label for="" class="text-black fs-14 fw-600">Bio <span
                                    class="text-danger">*</span></label>
                            <textarea name="bio" id="" cols="30" rows="10" disabled><?php echo $bio; ?></textarea>
                            
                        </div>
                        <div class="d-flex flex-column gap-1">
                            <?php
                             echo $profile->reviews($user_id);
                            ?>
                            </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </form>

</section>

<?php
include $path . '/apps/edu/ui/layouts/footer.php';

?>
