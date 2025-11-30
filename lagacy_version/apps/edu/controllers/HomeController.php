<?php

include 'Controller.php';

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index_client()
    {
        //$users = $this->fetchUsers($role);
        //$this->displayUserFeed($users);
    //    $this->freelancers();
        $this->fetchContracts();
    }

    public function index()
    {
        $jobs = $this-> fetchJobs();
        $this->displayJobsFeed($jobs);
    }

    public function contractFeeder()
    {
      $contracts = $this->fetchContracts();
      $this->displayContractsFeed($contracts);

    }

    //Create gigFeeder function

    public function gigFeeder()
    {
      $gigs = $this->fetchGigs();
      $this->displayGigsFeed($gigs);
      

    }

    private function fetchUsers($role)
    {
        // Fetch users from the database based on role
        $sql = "SELECT * FROM users WHERE role = '$role' ORDER BY date_created DESC";
        $result = $this->run_query($sql);
    
        $users = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
    
        return $users;
    }

    private function fetchJobs()
{
    // Fetch jobs from the database
    $sql = "SELECT * FROM jobs ORDER BY date_created DESC";
    $result = $this->run_query($sql);

    $jobs = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $jobs[] = $row;
        }
    }

    return $jobs;
}

    

    private function displayUserFeed($users)
{
    ?>
    <style>
        .profile-image {
            width: 190px;
            height: 190px;
            object-fit: cover;
        }
    </style>
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div id="user-feed m-5 shadow">
                    <?php foreach ($users as $user): ?>
                        <div class="card m-5">
                            <div class="row g-0">
                                <div class="col-md-2">
                                    <?php if (!empty($user['profile_image'])): ?>
                                        <img src="<?php echo $user['profile_image']; ?>" class="profile-image" alt="Profile Image">
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-2"></div>
                                

                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <a href="/apps/work/ui/views/profile/profile.php?ui=<?php echo $user['id']; ?>">
                                                <?php echo $user['first_name'] . ' ' . $user['last_name']; ?>
                                            </a>
                                        </h5>
                                        <p class="card-text"><?php echo $user['bio']; ?></p>
                                        <p class="card-text">
                                            <small class="text-muted">Joined: <?php echo $user['date_created']; ?></small>
                                        </p>
                                
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}


private function displayJobsFeed($jobs)
{
    ?>
    <style>
        .job-card {
            margin-bottom: 20px;
        }
    </style>
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div id="jobs-feed">
                    <?php foreach ($jobs as $job): ?>
                        <div class="card job-card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $job['job_title']; ?></h5>
                                <p class="card-title"><strong>Category:</strong> <?php echo $job['company']; ?></p>
                                <p class="card-title"><strong>Location:</strong> <?php echo $job['location']; ?></p>
                                <p class="card-title"><strong>Job Type:</strong> <?php echo $job['industry']; ?></p>
                                <p class="card-title"><strong>Salary Range:</strong> <?php echo $job['salary']; ?></p>
                                <p class="card-title"><strong>Availability:</strong> <?php echo $job['job_description']; ?></p>
                                <!-- Add any other details you want to display -->
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}


private function displayContractsFeed($contracts)
{
    ?>
    <style>
        .contract-card {
            margin-bottom: 20px;
        }
    </style>
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div id="contracts-feed">
                    <?php foreach ($contracts as $contract): ?>
                        <div class="card contract-card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $contract['title']; ?></h5>
                                <p class="card-text"><strong>Duration:</strong> <?php echo $contract['duration']; ?></p>
                                <p class="card-text"><strong>Availability:</strong> <?php echo $contract['availability']; ?></p>
                                <p class="card-text"><strong>Price:</strong> <?php echo $contract['price']; ?></p>
                                <p class="card-text"><strong>Skills:</strong> <?php echo $contract['skills']; ?></p>
                                <p class="card-text"><strong>Description:</strong> <?php echo $contract['description']; ?></p>
                                <p class="card-text"><strong>Currency:</strong> <?php echo $contract['currency']; ?></p>
                                <p class="card-text"><strong>Status:</strong> <?php echo $contract['status']; ?></p>
                                <p class="card-text"><strong>Final Status:</strong> <?php echo $contract['final_status']; ?></p>
                                <p class="card-text"><strong>Awarded To:</strong> <?php echo $contract['awarded_to']; ?></p>
                                <p class="card-text"><strong>FR Status:</strong> <?php echo $contract['fr_status']; ?></p>
                                <!-- Add any other details you want to display -->
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

private function fetchContracts()
{
    // Fetch contracts from the database
    $sql = "SELECT * FROM contracts ORDER BY date_created DESC";
    $result = $this->run_query($sql);

    $contracts = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $contracts[] = $row;
        }
    }

    return $contracts;
}

private function displayGigsFeed($gigs)
{
    ?>
    <style>
        .gig-card {
            margin-bottom: 20px;
        }
    </style>
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div id="gigs-feed">
                    <?php foreach ($gigs as $gig): ?>
                        <div class="card gig-card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $gig['gigtitle']; ?></h5>
                                <p class="card-text"><strong>Category:</strong> <?php echo $gig['category']; ?></p>
                                <p class="card-text"><strong>Subcategory:</strong> <?php echo $gig['subcategory']; ?></p>
                                <p class="card-text"><strong>Location:</strong> <?php echo $gig['location']; ?></p>
                                <p class="card-text"><strong>Basic Package:</strong> <?php echo $gig['basicpackage']; ?></p>
                                <p class="card-text"><strong>Standard Package:</strong> <?php echo $gig['standardpackage']; ?></p>
                                <p class="card-text"><strong>Premium Package:</strong> <?php echo $gig['premiumpackage']; ?></p>
                                <p class="card-text"><strong>Basic Description:</strong> <?php echo $gig['basicdescribe']; ?></p>
                                <p class="card-text"><strong>Standard Description:</strong> <?php echo $gig['standardescribe']; ?></p>
                                <p class="card-text"><strong>Premium Description:</strong> <?php echo $gig['premiumdescribe']; ?></p>
                                <p class="card-text"><strong>Basic Delivery Time:</strong> <?php echo $gig['basic_delieverytime']; ?></p>
                                <p class="card-text"><strong>Standard Delivery Time:</strong> <?php echo $gig['standard_delieverytime']; ?></p>
                                <p class="card-text"><strong>Premium Delivery Time:</strong> <?php echo $gig['premium_delieverytime']; ?></p>
                                <p class="card-text"><strong>Basic Revisions:</strong> <?php echo $gig['basic_revisions']; ?></p>
                                <p class="card-text"><strong>Standard Revisions:</strong> <?php echo $gig['standard_revisions']; ?></p>
                                <p class="card-text"><strong>Premium Revisions:</strong> <?php echo $gig['premium_revisions']; ?></p>
                                <p class="card-text"><strong>Basic Double:</strong> <?php echo $gig['basicdouble']; ?></p>
                                <p class="card-text"><strong>Standard Double:</strong> <?php echo $gig['standarddouble']; ?></p>
                                <p class="card-text"><strong>Premium Double:</strong> <?php echo $gig['premiumdouble']; ?></p>
                                <p class="card-text"><strong>Basic High:</strong> <?php echo $gig['basichigh']; ?></p>
                                <p class="card-text"><strong>Standard High:</strong> <?php echo $gig['standardhigh']; ?></p>
                                <p class="card-text"><strong>Premium High:</strong> <?php echo $gig['premiumhigh']; ?></p>
                                <p class="card-text"><strong>Basic Price:</strong> <?php echo $gig['basic_price']; ?></p>
                                <p class="card-text"><strong>Standard Price:</strong> <?php echo $gig['standard_price']; ?></p>
                                <p class="card-text"><strong>Premium Price:</strong> <?php echo $gig['premium_price']; ?></p>
                                <p class="card-text"><strong>Step 2 Description:</strong> <?php echo $gig['step2_description']; ?></p>
                                <p class="card-text"><strong>Questions:</strong> <?php echo $gig['questions']; ?></p>
                                <p class="card-text"><strong>Answers:</strong> <?php echo $gig['answers']; ?></p>
                                <p class="card-text"><strong>Requirement:</strong> <?php echo $gig['requirement']; ?></p>
                                <p class="card-text"><strong>Gig Images:</strong> <?php echo $gig['gigimages']; ?></p>
                                <p class="card-text"><strong>Gig Video:</strong> <?php echo $gig['gigsvideo']; ?></p>
                                <p class="card-text"><strong>Gig Status:</strong> <?php echo $gig['gig_status']; ?></p>
                                <!-- Add any other details you want to display -->
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

private function fetchGigs()
{
    // Fetch gigs from the database
    $sql = "SELECT * FROM gigs ORDER BY date_created DESC";
    $result = $this->run_query($sql);

    $gigs = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $gigs[] = $row;
        }
    }

    return $gigs;
}


//Write a function in 

public function freelancers(){

    

    $url = "https://www.freelancer.com/api/users/0.1/users/directory/?query=logodesign";
    
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    $headers = array(
       "freelancer-oauth-v1: dX5EHYqIIHugbzWJXsyxEAcOlmKvex",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    // For debug only!
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
    $resp = curl_exec($curl);
    curl_close($curl);
    
    $data = json_decode($resp, true);
    
    // Display user data in a user portal format
    if ($data && isset($data['result']) && isset($data['result']['users'])) {
        $users = $data['result']['users'];
        
    

?>
            <div class="row">
            <div class="col-md-8 ">
                <div id="user-feed m-5 shadow">
                    <?php foreach ($users as $user): ?>
                        <div class="card m-5">
                            <div class="row g-0">
                                <div class="col-md-2">
                                    <?php if (!empty($user['profile_image'])): ?>
                                        <img src="<?php echo $user['profile_image']; ?>" class="profile-image" alt="Profile Image">
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-2"></div>
                                

                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <a href="https://www.freelancer.com/u/<?php echo $user['username']; ?>">
                                                <?php echo $user['display_name']; ?>
                                            </a>
                                        </h5>
                                    
                                        <p class="card-text">
                                        
                                        </p>
                                
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>







        <?php
        
    } else {
        echo "Error retrieving user data.";
    }
    
    
    

}

    
    
}
