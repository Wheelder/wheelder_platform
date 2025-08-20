<?php

$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/pool/libs/controllers/LogsController.php';

$settings = new LogsController();
//$settings->authCheck();

$uid = $_SESSION['user_id'];

$udetails = $settings->user_details($uid);

include $path . '/pool/assets/layouts/style.php';
?>

<div class="container pt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="tab-content rounded-circle">
                <div class="tab-pane fade show active" id="account" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Select Topics</h5>
                        </div>
                        <div class="card-body">
                            <form action="/log_api" method="POST">
                                <input type="hidden" name="user" value="<?php echo $uid; ?>">

                                <div class="row">
                                    <!-- 1st column of topics -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="d-flex flex-column gap-1">
                                                <label><input type="checkbox" name="topics[]" value="Technology"> Technology</label>
                                                <label><input type="checkbox" name="topics[]" value="Physics"> Physics</label>
                                                <label><input type="checkbox" name="topics[]" value="Science"> Science</label>
                                                <label><input type="checkbox" name="topics[]" value="Biology"> Biology</label>
                                                <label><input type="checkbox" name="topics[]" value="Mathematics"> Mathematics</label>
                                                <label><input type="checkbox" name="topics[]" value="Engineering"> Engineering</label>
                                                <label><input type="checkbox" name="topics[]" value="Chemistry"> Chemistry</label>
                                                <label><input type="checkbox" name="topics[]" value="Astronomy"> Astronomy</label>
                                                <label><input type="checkbox" name="topics[]" value="Geology"> Geology</label>
                                                <label><input type="checkbox" name="topics[]" value="Meteorology"> Meteorology</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 2nd column of topics -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="d-flex flex-column gap-1">
                                                <label><input type="checkbox" name="topics[]" value="Art"> Art</label>
                                                <label><input type="checkbox" name="topics[]" value="Literature"> Literature</label>
                                                <label><input type="checkbox" name="topics[]" value="History"> History</label>
                                                <label><input type="checkbox" name="topics[]" value="Philosophy"> Philosophy</label>
                                                <label><input type="checkbox" name="topics[]" value="Economics"> Economics</label>
                                                <label><input type="checkbox" name="topics[]" value="Political Science"> Political Science</label>
                                                <label><input type="checkbox" name="topics[]" value="Psychology"> Psychology</label>
                                                <label><input type="checkbox" name="topics[]" value="Sociology"> Sociology</label>
                                                <label><input type="checkbox" name="topics[]" value="Anthropology"> Anthropology</label>
                                                <label><input type="checkbox" name="topics[]" value="Law"> Law</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 3rd column of topics -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="d-flex flex-column gap-1">
                                                <label><input type="checkbox" name="topics[]" value="Medicine"> Medicine</label>
                                                <label><input type="checkbox" name="topics[]" value="Nursing"> Nursing</label>
                                                <label><input type="checkbox" name="topics[]" value="Pharmacy"> Pharmacy</label>
                                                <label><input type="checkbox" name="topics[]" value="Veterinary Science"> Veterinary Science</label>
                                                <label><input type="checkbox" name="topics[]" value="Dentistry"> Dentistry</label>
                                                <label><input type="checkbox" name="topics[]" value="Public Health"> Public Health</label>
                                                <label><input type="checkbox" name="topics[]" value="Nutrition"> Nutrition</label>
                                                <label><input type="checkbox" name="topics[]" value="Physiotherapy"> Physiotherapy</label>
                                                <label><input type="checkbox" name="topics[]" value="Biomedicine"> Biomedicine</label>
                                                <label><input type="checkbox" name="topics[]" value="Healthcare "> Healthcare</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 4th column of topics -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="d-flex flex-column gap-1">
                                                <label><input type="checkbox" name="topics[]" value="Computer Science"> Computer Science</label>
                                                <label><input type="checkbox" name="topics[]" value="Information Technology"> Information Technology</label>
                                                <label><input type="checkbox" name="topics[]" value="Cybersecurity"> Cybersecurity</label>
                                                <label><input type="checkbox" name="topics[]" value="Data Science"> Data Science</label>
                                                <label><input type="checkbox" name="topics[]" value="Artificial Intelligence"> Artificial Intelligence</label>
                                                <label><input type="checkbox" name="topics[]" value="Software Engineering"> Software Engineering</label>
                                                <label><input type="checkbox" name="topics[]" value="Network Engineering"> Network Engineering</label>
                                                <label><input type="checkbox" name="topics[]" value="Robotics"> Robotics</label>
                                                <label><input type="checkbox" name="topics[]" value="Web Development"> Web Development</label>
                                                <label><input type="checkbox" name="topics[]" value="Game Development"> Game Development</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <button type="submit" class="btn btn-primary" name="select_topics">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
