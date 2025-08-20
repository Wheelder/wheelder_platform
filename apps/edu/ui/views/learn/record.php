<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include 'AppController.php';

$note = new AppController();

//$note->check_auth();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReLearn</title>

    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <!-- Include your existing styles -->
   <link rel="stylesheet" href="style.css" />

  


</head>

<body>
    <header class="navbar navbar-dark sticky-top bg-dark  flex-md-nowrap p-0 shadow">

        <a class="navbar-brand col-md-1 col-lg-1 me-0 px-3 fs-6" href="/learn">ReLearn!</a>

        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>


        <div class="controls  form-control w-100 rounded-0 border-0">
            <!-- Icons with IDs -->
            <i id="start" class="fas fa-play"></i>
            &nbsp;
            <i id="pause" class="fas fa-pause"></i>
            &nbsp;
            <i id="resume" class="fas fa-step-forward"></i>
            |
            <i id="fullscreen" class="fas fa-expand"></i>
            |
            <i id="copyToClipboard" class="far fa-copy"></i>
            |
            <i id="printContent" class="fas fa-print"></i>
            |
            <i class="fas fa-share-alt" id="shareButton"></i>
            |
            <i id="increaseFontSize" class="fas fa-search-plus"></i>
            <i id="decreaseFontSize" class="fas fa-search-minus"></i>
            |
            <i id="darkMode" class="fas fa-moon"></i>
            &nbsp;
            <i id="lightMode" class="fas fa-sun"></i>

            </button>

        </div>

    </header>
    <div class="container mt-2">
        <div id="loadingScreen">
            <!-- You can add a loading spinner or text here -->
            Just Answering...
        </div>

        <div class="row">
      
                
            <div class="col-md-12 ">
           
                <div class="searchDiv">

                    <form action="" method="post">
                        
                         
                            <textarea  name="query"  rows="5" cols="153"><?php echo $_POST['query'] ?? null; ?></textarea>
                            
                            <button type="submit" class="btn btn-dark" name="ask">Ask</button>
                            &nbsp;
                            <button type="reset" class="btn btn-dark" >Clear</button>
                           
                        
                     </form>
                     &nbsp;
                 </div>
                 
                     <form action="" method="post">
                        
                                
                        <input type="hidden" class="form-control" id="deeper1" name="deeper1" value="<?php echo $_POST['query'] ?? null; ?>">

                        <button type="submit" class="btn btn-dark">Circular Search</button>
                        
                    
                    </form>

                
                </div>
               
            </div>

        </div>
   
        <div class="container mt-2">
        <div class="row mt-1">
    
            <?php 
            
            $note->getAllData();
            
            ?>

        </div>
      
    </div>

  

    <!-- Include Bootstrap JS and jQuery -->

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"
        integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"
        integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha"
        crossorigin="anonymous"></script>

    <!-- JavaScript Code -->
     <script src="app.js"></script>

    
   

</body>

</html>