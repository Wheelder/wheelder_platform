<!DOCTYPE html>
<html lang="en">
<head>
    <title>Wheeleder</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="/ui/assets/images/f-icon.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
<script src="http://itsugestion.com/dev/workhouse/assets/js/virtual-select.min.js"></script>

    <link rel="stylesheet" href="/ui/assets/css/custom-style.css" type="text/css" />
    <link rel="stylesheet" href="/ui/assets/sass/main.css" type="text/css" />
    <link rel="stylesheet" href="/ui/assets/sass/otherstyle.css" type="text/css" />
    <link rel="stylesheet" href="/ui/assets/sass/landing.css" type="text/css" />

    <!-- select2-bootstrap4-theme -->
    <link rel="stylesheet" href="/ui/assets/css/virtual-select.min.css" type="text/css" />


</head>
<style>
.successmsg {
    text-align: center;
    position: fixed;
    top: 80px;
    z-index: 998;
    right: 10px;
}

.light-blue{
    background-color: #0066ff;
    color: #fff;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #000;
    margin-bottom: 10px;
}
</style>

<body>
<div class="container py-3">
  <main>
    <div class="row row-cols-1 row-cols-md-3 mb-3 text-center">
      <div class="col">
        <div class="card mb-4 rounded-3 shadow-sm">
          <div class="card-header py-3">
            <h4 class="my-0 fw-normal">Free</h4>
          </div>
          <div class="card-body">
            <h1 class="card-title pricing-card-title">$0<small class="text-body-secondary fw-light">/mo</small></h1>
            <ul class="list-unstyled mt-3 mb-4">
              <li>Basic Notes</li>
              <li>No Videos</li>
              <li>Full Screen Ads</li>
              <li>No Tutor</li>
            
            </ul>
            <button type="button" class="w-100 btn btn-lg btn-outline-primary">Sign up for free</button>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card mb-4 rounded-3 shadow-sm">
          <div class="card-header py-3">
            <h4 class="my-0 fw-normal">Pro</h4>
          </div>
          <div class="card-body">
            <h1 class="card-title pricing-card-title">$5<small class="text-body-secondary fw-light">/mo</small></h1>
            <ul class="list-unstyled mt-3 mb-4">
             <li>Standared Notes</li>
              
              <li>Videos</li>
              <li>No Tutor</li>
            
              <li>Small Ads</li>
              
            </ul>
            <button type="button" class="w-100 btn btn-lg btn-outline-primary">Sign up for free</button>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card mb-4 rounded-3 shadow-sm border-primary">
          <div class="card-header py-3 text-bg-primary border-primary">
            <h4 class="my-0 fw-normal">Master</h4>
          </div>
          <div class="card-body">
            <h1 class="card-title pricing-card-title">$9.9<small class="text-body-secondary fw-light">/mo</small></h1>
            <ul class="list-unstyled mt-3 mb-4">
            <ul class="list-unstyled mt-3 mb-4">
            <li>Advanced Notes</li>
              <li>Videos</li>
                <li>Tutor</li>
              <li>Projects</li>
              <li>No Ads</li>
              
            </ul>
            <button type="button" class="w-100 btn btn-lg btn-outline-primary">Sign up for free</button>
            </ul>
            
          </div>
        </div>
      </div>
    </div>


  </main>

    </div>
        

<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path.'/apps/edu/ui/layouts/footer.php';


?>
