<?php
//session_start();
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/pool/libs/controllers/LogsController.php';

$settings = new LogsController();
//$settings->authCheck();

$uid = $_SESSION['user_id'];

$udetails = $settings->user_details($uid);
$first_name = $udetails['first_name'];
$last_name = $udetails['last_name'];
$email = $udetails['email'];
$phone = $udetails['phone'];
$dob = $udetails['dob'];
$address = $udetails['address'];
$city = $udetails['city'];
$country = $udetails['country'];
$state = $udetails['state'];
$time_zone = $udetails['time_zone'];
$zip_code = $udetails['zip_code'];
$profile_image = $udetails['profile_image'];
$profile_status = $udetails['profile_status'];

$role = $_SESSION['role'];

include $path . '/pool/assets/layouts/style.php';
?>


<div class="container pt-3">
    <div class="row">
    <div class="col-md-12">
            <div class="tab-content rounded-circle">
                <div class="tab-pane fade show active" id="account" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Profile Setup</h5>
                        </div>
                        <div class="card-body">
                            <form action="/log_api" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="user" value="<?php echo $_SESSION['user_id'];?>">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="text-center">
                                            <label for="photo">Profile Photo</label>
                                            <br>
                                            <img alt="Andrew Jones" src="https://bootdey.com/img/Content/avatar/avatar1.png" class="rounded-circle img-responsive mt-2" width="128" height="128">
                                            <div class="mt-2">
                                                <input type="file" name="photo" id="photo" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="Name">First Name</label>
                                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $first_name; ?>" placeholder="First Name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="Name">Last Name</label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $last_name; ?>" placeholder="Last Name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="Phone">Date of Birth</label>
                                            <input type="date" class="form-control" id="dob" name="dob" value="<?php echo $dob; ?>" placeholder="DoB" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="Phone">Phone#</label>
                                            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $phone; ?>" placeholder="Phone#" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="Email">Email</label>
                                            <input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>" placeholder="Email" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="State">Country</label>
                                            <input type="text" class="form-control" id="country" name="country" value="<?php echo $country; ?>" placeholder="Country" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="State">State/Province</label>
                                            <input type="text" class="form-control" id="state" name="state" value="<?php echo $state; ?>" placeholder="State/Province" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="City">City/Town</label>
                                            <input type="text" class="form-control" id="city" name="city" value="<?php echo $city; ?>" placeholder="City" required>
                                        </div>
                                        <div class="form-group">
                                            <div class="d-flex flex-column gap-1">
                                                <label for="role">Roles:</label>
                                                <select name="role" id="role" class="form-control" required>
                                                    <option value="o">Select Role</option>
                                                    <option value="Professional">Professional</option>
                                                    <option value="Client">Client</option>
                                                </select>
                                            </div>
                                        </div>
                                    
                                        
                                        <div class="form-group">
                                            <label for="Zip">Zip/Postal Code</label>
                                            <input type="text" class="form-control" id="postal_code" name="postal_code" value="<?php echo $zip_code; ?>" placeholder="Zip/Postal Code">
                                        </div>
                                        <div class="form-group">
                                            <label for="Address">Address</label>
                                            <input type="text" class="form-control" id="address" name="address" value="<?php echo $address; ?>" placeholder="Apt# and Street Name">
                                        </div>
                                    
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="time_zone">Write Bio Here</label>
                                            <textarea class="form-control" name="bio" id="bio" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <button type="submit" class="btn btn-primary" name="complete_profile">Save</button>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





