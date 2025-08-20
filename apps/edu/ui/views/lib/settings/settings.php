<?php
//session_start();
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/apps/lib/controllers/SettingsController.php';
/*
$settings = new SettingsController();
$settings->checkSessionAndRedirect();

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



$cp_details = $settings->company_details($uid);
$website = $cp_details['website'] = "";
$company_name = $cp_details['company_name'] = "";
$number_employee = $cp_details['number_employee'] = "";
$company_phone = $cp_details['company_phone'] = "";
$company_email = $cp_details['company_email'] = "";
$company_address = $cp_details['company_address'] = "";
$description = $cp_details['description'] = "";
$owner_id = $cp_details['user_id'] = "";
$time_zone = $cp_details['time_zone'] = "";
$industry = $cp_details['industry'] = "";
$logo = $cp_details['logo'] = "";
$vat_id = $cp_details['vat_id'] = "";



$role = $_SESSION['role'];
*/
//include $path . '/top.php';

include $path . '/apps/lib/ui/layouts/nav.php';
?>

<div class="container pt-3">

    <div class="row">
        <div class="col-md-5 col-xl-4">

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Settings</h5>
                </div>

                <div class="list-group list-group-flush" role="tablist">
                <a class="list-group-item list-group-item-action" data-toggle="list" href="/apps/lib/ui/views/settings/settings.php?form=app" role="tab">
                      App Settings
                    </a>
                    <a class="list-group-item list-group-item-action" data-toggle="list" href="/apps/lib/ui/views/settings/settings.php?form=profile" role="tab">
                      Profile
                    </a>
                    <a class="list-group-item list-group-item-action" data-toggle="list" href="/apps/lib/ui/views/settings/settings.php?form=security" role="tab">
                      Password
                    </a>
                    <a class="list-group-item list-group-item-action " data-toggle="list" href="/apps/lib/ui/views/settings/settings.php?form=company" role="tab">
                      Company
                    </a>
                
                    <a class="list-group-item list-group-item-action " data-toggle="list" href="/apps/lib/ui/views/settings/settings.php?form=notifications" role="tab">
                    Notifications
                    </a>
                    <a class="list-group-item list-group-item-action " data-toggle="list" href="/apps/lib/ui/views/settings/settings.php?form=resume" role="tab">
                    My Resume/CV
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-7 col-xl-8">
            <div class="tab-content">
            <?php
            $form = $_GET['form'];
            if ($form == null) {
                $form = 'profile';
            }
            switch ($form) {
                case 'app':
                    ?>
                            <div class="tab-pane fade show active" id="account" role="tabpanel">

        <div class="card">
            <div class="card-header">

                <h5 class="card-title mb-0">App Settings</h5>
            </div>
            <div class="card-body">
            <form  method="post" action="/api/settingsAPI.php" >



            <div class="row">
            <div class="col-12">
            <input type="text" id="bank_name" name="bank_name" class="form-control" value="" placeholder="Bank Name" aria-label="Bank Name">
             </div>
             <div class="col-12">
            <input type="text" id="account_name" name="account_name" class="form-control" value="" placeholder="Account Holder Name" aria-label="Account Holder Name">
             </div>
              <div class="col-12">
                <input type="text" id="account_no" name="account_no" class="form-control" value="" placeholder="Account No" aria-label="Account No">
              </div>
              <div class="col-6">
                <input type="text" id="transit_no" name="transit_no" class="form-control" placeholder="Transit No" value="" aria-label="Transit No">
              </div>


              <div class="col-6">
                <input type="text" id="institution_no" name="institution_no" class="form-control" placeholder="Institution No" value="" aria-label="Institution No">
              </div>
            </div>
            </br>
            <button class="btn btn-warning rounded-pill fw-bold py-2 px-5" type="submit"  name="add_bank">
            Add Bank 
          </button>
          </br>
            <br>
            </form>

            </div>
        </div>

        </div>
        </div>
        



                        <?php
                        break;
                case 'payouts':
                    ?>

                <div class="tab-pane fade show active" id="account" role="tabpanel">

                <div class="card">
                    <div class="card-header">

                        <h5 class="card-title mb-0">Payout Setup</h5>
                    </div>
                    <div class="card-body">
                    <form  method="post" action="/api/settingsAPI.php" >
    

    
                    <div class="row">
                    <div class="col-12">
                    <input type="text" id="bank_name" name="bank_name" class="form-control" value="" placeholder="Bank Name" aria-label="Bank Name">
                     </div>
                     <div class="col-12">
                    <input type="text" id="account_name" name="account_name" class="form-control" value="" placeholder="Account Holder Name" aria-label="Account Holder Name">
                     </div>
                      <div class="col-12">
                        <input type="text" id="account_no" name="account_no" class="form-control" value="" placeholder="Account No" aria-label="Account No">
                      </div>
                      <div class="col-6">
                        <input type="text" id="transit_no" name="transit_no" class="form-control" placeholder="Transit No" value="" aria-label="Transit No">
                      </div>
    

                      <div class="col-6">
                        <input type="text" id="institution_no" name="institution_no" class="form-control" placeholder="Institution No" value="" aria-label="Institution No">
                      </div>
                    </div>
                    </br>
                    <button class="btn btn-warning rounded-pill fw-bold py-2 px-5" type="submit"  name="add_bank">
                    Add Bank 
                  </button>
                  </br>
                    <br>
                    </form>

                    </div>
                </div>

                </div>
                </div>
                
                                   <?php
                                   break;


                case 'payments':
                    ?>
        
        
        
                                <div class="tab-pane fade show active" id="" role="tabpanel">    
        
        
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Payments</h5>
            
                                    </div>
                                    <div class="card-body">
        
                        <form action="/api/settingsAPI.php" method="post">
             
                                      <div class="row">
                                      <div class="col-12">
                                          <input type="text" id="card_name" name="card_name" class="form-control" placeholder="Name on Card " value="" aria-label="Card Name">
                                        </div>
                                      <div class="col-12">
                                      <input type="text" id="billing_address" name="billing_address" class="form-control" placeholder="Billing Address" value="" aria-label="Billing Addres">
                                    </div>
            
                                        <div class="col-12">
                                          <input type="text" id="card_number" name="card_number" class="form-control" placeholder="Card Number" value="" aria-label="Card Number">
                                        </div>
                
                                        <div class="col-6">
                                          <input type="text" id="card_expiry_mm" name="card_expiry_mm" class="form-control" placeholder="Card Expiry MM" value="" aria-label="Card Expiry MM">
                                        </div>
                                        <div class="col-6">
                                          <input type="text" id="card_expiry_yy" name="card_expiry_yy" class="form-control" placeholder="Card Expiry YY" value="" aria-label="Card Expiry YY">
                                        </div>
                                        <div class="col-6">
                                          <input type="text" id="card_cvv" name="card_cvv" class="form-control" placeholder="Card CVV" value="" aria-label="Card CVV">
                                        </div>
              
                                      <div class="d-grid mx-auto pt-3 pb-2">
                                        <button class="btn btn-warning rounded-pill fw-bold py-2 px-5" type="submit"  name="add_card">
                                          Add Card 
                                        </button>
                
                  
                                          </form>
        
        
                                    </div>
                                </div>
        
                                </div>
        
                        
        
        
        
                                                <?php
                                                break;
                case 'profile':
                    ?>

                                        <div class="tab-pane fade show active" id="account" role="tabpanel">

                                        <div class="card">
                                            <div class="card-header">
        
                                                <h5 class="card-title mb-0">Profile Setup</h5>
                                            </div>
                                            <div class="card-body">
                                                <form action="/api/settingsAPI.php" method="POST" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                        <div class="form-group">
                                                                <label for="Name">First Name</label>
                                                        
                                                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $first_name; ?>" placeholder="First Name">
                                                            </div>
                                                                <div class="form-group">
                                                                <label for="Name">Last Name</label>
                                                    
                                                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $last_name; ?>" placeholder="Last Name">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="Phone">Date of Birth</label>
                                                                <input type="date" class="form-control" id="dob" name="dob" value="<?php echo $dob; ?>" placeholder="DoB">
                                                            </div>
                                                    
                                                
                                                            <div class="form-group">
                                                                <label for="Phone">Phone#</label>
                                                                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $phone; ?>" placeholder="Phone#">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="Email">Email</label>
                                                                <input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>" placeholder="Email">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="State">State/Province</label>
                                                                <input type="text" class="form-control" id="state" name="state" value="<?php echo $state; ?>" placeholder="State/Province">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="City">City</label>
                                                                <input type="text" class="form-control" id="city" name="city" value="<?php echo $city; ?>" placeholder="City">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="City">Country</label>
                                                                <input type="text" class="form-control" id="country" name="country" value="<?php echo $country; ?>" placeholder="Country">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="Zip">Zip/Postal Code</label>
                                                                <input type="text" class="form-control" id="zip_code" name="zip_code" value="<?php echo $zip_code; ?>" placeholder="Zip/Postal Code">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="Address">Address</label>
                                                                <input type="text" class="form-control" id="address" name="address" value="<?php echo $address; ?>" placeholder="Apt# and Street Name">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="time_zone">Time Zone</label>
                                                                    <?php $settings->select_timezone(); ?>    
                                                            </div>
                    
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="text-center">
                                                                <img alt="Andrew Jones" src="https://bootdey.com/img/Content/avatar/avatar1.png" 
                                                                class="rounded-circle img-responsive mt-2" width="128" height="128">
                                                                <div class="mt-2">
                            
                                                                    <input type="file" name="photo" id="photo" class="form-control">
                                                                </div>
                        
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>

                                                    <button type="submit" class="btn btn-primary" name="profile_setup">Save changes</button>
                                                </form>

                                            </div>
                                        </div>

                                        </div>

                                                      <?php
                                                      break;
                case 'security':
                    ?>
                                        <div class="tab-pane fade show active" id="" role="tabpanel">    


                                        <div class="card">
                                            <div class="card-header">
                                                <div class="card-actions float-right">
                                                    <div class="dropdown show">
                

                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="#">Action</a>
                                                            <a class="dropdown-item" href="#">Another action</a>
                                                            <a class="dropdown-item" href="#">Something else here</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h5 class="card-title mb-0">Security</h5>
                                            </div>
                                            <div class="card-body">
                                            <form action="/api/settingsAPI.php" method="post">
                                                    <div class="row">
                                                        <div class="col-md-8 p-3">
                                                            <div class="form-group p-3">
                                                                <label for="inputUsername">Current Password</label>
                                                                <input type="password" class="form-control" name="current_password" id="current_password" placeholder="Current Password">
                                                            </div>

                                                            <div class="form-group p-3">
                                                                <label for="inputUsername">New Password</label>
                                                                <input type="password" class="form-control" name="password" id="password" placeholder="New Password">
                                                            </div>
                                                        </div>
            
                                                    </div>
                                                    <br/>

                                                    <button type="submit" class="btn btn-primary" name="update_security">Save changes</button>
                                                </form>

                                            </div>
                                        </div>

                                        </div>

                                    <?php
                                    break;
                case 'membership':

                    $settings->membership_plans();
                    break;

                case 'notifications':
                    ?>
                                        <div class="tab-pane fade show active" id="" role="tabpanel">    


                                        <div class="card">
                                            <div class="card-header">
                            
                                                <h5 class="card-title mb-0">Notifications</h5>
                                            </div>
                                            <div class="card-body">
                                            <form action="/api/settingsAPI.php" method="post">
                                                    <div class="row">
                                                        <div class="col-md-8 p-3">
                                                            <div class="form-group p-3">
                                                
                                                
                                                                <label for="Email">Email Notifications</label> 
                                                                <input type="email" class="form-control" name="email_noti" id="email_noti" placeholder="Email for Notifications">
                                                            </div>
                                                            <div class="form-group p-3">
                                                
                                                
                                                                <label for="Email">SMS Notifications</label> 
                                                                <input type="number" class="form-control" name="sms_noti" id="sms_noti" placeholder="SMS for Notifications">
                                                            </div>

                                                            <div class="form-group p-3">
                                                    
                  <input type="checkbox" class="btn-check" id="noti_posts" name="noti_posts" autocomplete="off">
                  <label class="btn btn-outline-primary" for="noti_posts">Turn On Notifications For New Posts </label>
                  <br />

                  <input type="checkbox" class="btn-check" id="noti_jobs" name="noti_jobs" autocomplete="off">
                  <label class="btn btn-outline-primary" for="noti_jobs">Turn On Notifications For New Jobs</label>
                  <br />

                  <input type="checkbox" class="btn-check" id="noti_blogs" name="noti_blogs" autocomplete="off">
                  <label class="btn btn-outline-primary" for="noti_blogs">Turn On Notifications For New Blogs</label>
                  <br />
         
                </div> 
                                                
                                                
                                                        </div>
            
                                                    </div>
                                                    <br/>

                                                    <button type="submit" class="btn btn-primary" name="notifications">Save changes</button>
                                                </form>

                                            </div>
                                        </div>

                                        </div>

                                    <?php
                                    break;

                case 'company':
                    ?>

                                                        <div class="tab-pane fade show active" id="account" role="tabpanel">
                
                                                        <div class="card">
                                                            <div class="card-header">
                        
                                                                <h5 class="card-title mb-0">Company Profile Setup</h5>
                                                            </div>
                                                            <div class="card-body">
        
                                                                <form action="/api/settingsAPI.php" method="POST" enctype="multipart/form-data">
                                                                    <div class="row">
                                                                        <div class="col-md-7">
                    
                                                                                <div class="form-group">
                                                                                <label for="Name">Company Name</label>
                                                                                <input type="text" name="company_name" class="form-control" value="<?php echo $company_name; ?>" required placeholder="Company Name" />
                                                                            </div>
                                                                            <div class="form-group">
                                                                            <label for="" class="text-black fs-14 fw-600">Company Details <span
                                                                            class="text-danger">*</span></label>
                                                                             <textarea name="company_details" class="form-control" id="company_details" > <?php echo $description; ?></textarea>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="Email">Email</label>
                                                                                <input type="email" name="company_email" class="form-control" value="<?php echo $company_email; ?>" required placeholder="Email" />
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="Phone">Phone</label>
                                                                                <input type="text" name="company_phone" class="form-control" value="<?php echo $company_phone; ?>" required placeholder="Phone" />
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="Address">Address</label>
                                                                                <input type="text" name="company_address" class="form-control" value="<?php echo $company_address; ?>" required placeholder="Address" />
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="VAT">VAT ID</label>
                                                                                <input type="text" name="vat_id" class="form-control" value="<?php echo $vat_id; ?>" required placeholder="VAT ID" />
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="time_zone">Time Zone</label>
                                                                                <select id="time_zone" name="time_zone" class="form-control">
                                                    
                                                                                    <option value="<?php echo $time_zone; ?>"><?php echo $time_zone; ?></option>
                                                                                            <?php echo $time_zone; ?>
                                
                                                                                       <option value="VST">VST GMT+7:00</option>
                                                                                   </select>

                                                                                    </div>
                                                                    
                                                                                    <div class="form-group">
                                                                                       <label for="time_zone">Time Zone</label>
                                                                               <?php $settings->select_timezone(); ?>    
                                                                            </div>
                                                                    
                                                                    
                                                                                    <div class="form-group">
                                                                                     <label for="" class="">Industry <span>
                                                                                        <select name="industry" class="form-control">
                                                                                            <option value="0"><?php echo $industry; ?></option>
                                                                                        <option value="0">Select Industry</option>
                                                                <option value="1">Agriculture</option>
                                                                <option value="2">Automotive</option>
                                                                <option value="3">Banking</option>
                                                                <option value="4">Construction</option>
                                                                <option value="5">Education</option>
                                                                <option value="6">Energy</option>
                                                                <option value="7">Entertainment</option>
                                                                <option value="8">Finance</option>
                                                                <option value="9">Food</option>
                                                                <option value="10">Health</option>
                                                                <option value="11">Hospitality</option>
                                                                <option value="12">Insurance</option>
                                                                <option value="13">Manufacturing</option>
                                                                <option value="14">Media</option>
                                                                <option value="15">Mining</option>
                                                                <option value="16">Real Estate</option>
                                                                <option value="17">Retail</option>
                                                                <option value="18">Technology</option>
                                                                <option value="19">Telecommunications</option>
                                                                </select>
                                                                </div>


                                    

                                    
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <div class="text-center">
                                                                                        <img alt="Andrew Jones" src="/ui/assets/images/default-logo.jpg" 
                                                                                        class="rounded-circle img-responsive mt-2" width="128" height="128">
                                                                                        <div class="mt-2">
                                            
                                                                                            <input type="file" name="logo" id="logo" class="form-control">
                                                                                        </div>
                                        
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <hr>
                
                                                                            <button type="submit" class="btn btn-primary" name="company_profile">Save changes</button>
                                                                        </form>
                
                                                                    </div>
                                                                </div>
                
                                                                </div>

                                                        <?php
                                                        break;

                case 'resume':
                    ?>
                                                  
        <section class=" py-60 freelancerprofile">
            <div class="container-lg">
                <div class="row">
                    <form method="POST" action="/api/resumesAPI.php" enctype="multipart/form-data">
                        <input type="hidden" name="status" value="1">              
                          <input type="hidden" name="id" value="">
                        <div class="col-12">
                            <div class="d-block bg-white p-4 rounded-3">
                                <div class="row gy-4">
                                    <div class="col-12">
                                        <h2 class="mytext-black fw-800 text-uppercase fs-28 fs-md-32">
                                            Cv page
                                        </h2>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex flex-column gap-2">
                                            <label for="" class="mytext-black fs-14 text-uppercase">Picture</label>

                                            <input type="file" name="pic"
                                                class="bg-white w-100 border border-seacondary border-opcity-25 p-2 fs-14 rounded-2">
                                
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex flex-column gap-2">
                                            <label for="" class="mytext-black fs-14 text-uppercase">Fisrt Name</label>
                                            <input type="text" name="firstname"
                                                value="" required
                                                class="bg-white w-100 border border-seacondary border-opcity-25 p-2 fs-14 rounded-2">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex flex-column gap-2">
                                            <label for="" class="mytext-black fs-14 text-uppercase">Last Name</label>
                                            <input type="text" name="lastname" required
                                                value=""
                                                class="bg-white w-100 border border-seacondary border-opcity-25 p-2 fs-14 rounded-2" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex flex-column gap-2">
                                            <label for="" class="mytext-black fs-14 text-uppercase">Date Of Birth</label>
                                            <input type="date" name="dob" required
                                                class="bg-white w-100 border border-seacondary border-opcity-25 p-2 fs-14 rounded-2 dob" />
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex flex-column gap-2">
                                            <label for="" class="mytext-black fs-14 text-uppercase">Address</label>
                                            <textarea name="address" required id="" cols="30" rows="6"
                                                placeholder="Type here....."
                                                class="bg-white w-100 border border-seacondary border-opcity-25 p-2 fs-14 rounded-2"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex flex-column gap-2">
                                            <label for="" class="mytext-black fs-14 text-uppercase">Professional Bio</label>
                                            <textarea name="description" required id="" cols="30" rows="6"
                                                placeholder="Type here....."
                                                class="bg-white w-100 border border-seacondary border-opcity-25 p-2 fs-14 rounded-2"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex flex-column gap-2">
                                            <label for="" class="mytext-black fs-14 text-uppercase">Skill</label>
                                            <input type="text" name="skill" required
                                                value=""
                                                class="bg-white w-100 border border-seacondary border-opcity-25 p-2 fs-14 rounded-2" />
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex flex-column gap-2">
                                            <label for="" class="mytext-black fs-14 text-uppercase">Education
                                                Qualification</label>
                                            <input type="text"
                                                value=""
                                                name="qualification" required
                                                class="bg-white w-100 border border-seacondary border-opcity-25 p-2 fs-14 rounded-2" />
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex flex-column gap-2">
                                            <label for="" class="mytext-black fs-14 text-uppercase">Work Experience</label>
                                            <input type="text" name="work_exp" required
                                                value=""
                                                class="bg-white w-100 border border-seacondary border-opcity-25 p-2 fs-14 rounded-2" />
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="d-flex flex-justify-end">
                                            <button type="submit" class="post-btn ms-auto" name="create_resume">
                                                Save details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>



                
                                                                      <?php
                                                                      break;
                default:
                    $settings->myInfo();
                    break;

            }

            ?>
            
                
            </div>
        </div>
    </div>

</div>

  <?php
  include $path . '/apps/lib/ui/layouts/footer.php';


  ?>