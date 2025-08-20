<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/apps/edu/controllers/BlogController.php';
$blog = new BlogController();
//$blog->check_auth();

//include $path . '/apps/edu/ui/layouts/nav.php';

// Function to send email
$key=$_GET['key'] OR NULL;


if($key=="email"){
    

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insert'])) {
    // Retrieve form data
    $from_email = $_POST['from_email'];
    $subject = $_POST['title'];
    $to_email = $_POST['to_email'];
    $message = $_POST['content'];
    $blog->send_email($to_email,$message,$subject);
   

    // Your email content
    //$email_content = "Title: $title <br> Content: $content";

    // Recipient email (change this to your desired email address)
    //$to_email = $_POST['to_email'];

    // Email subject
    //$subject = $title;

    // Send email
    /*

    $res=$blog->sending_email($to_email, $from_email,$content,$subject);
    if($res){
        $blog->alert_redirect('Message sent successfully.', '/email');
    }
    else{
        $blog->alert_redirect('Message sending failed.', '/email');
    }
    */
    ?>
    <section class="bg-light py-1">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-8 mx-auto">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title mb-4 fs-3">Send Email</h2>
                        <form action="" method="post">
                        
                            <div class="mb-3">
                                <label for="from_email" class="form-label">From</label>
                                <input type="email" class="form-control" name="from_email" placeholder="From" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="from_email" class="form-label">To</label>
                                <input type="email" class="form-control" name="to_email" placeholder="To" required>
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">Subject</label>
                                <input type="text" class="form-control" name="title" placeholder="Enter the title" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="content" class="form-label">Message</label>
                                <textarea class="form-control" name="content" rows="15" cols="5" required></textarea>
                            </div>
                            <br>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary" name="insert">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


    <?php
}
}else{
    ?>
<!DOCTYPE html>
<html>

<head>
    <title>Wheeleder</title>
    <link rel="icon" href="storage/website/logo/mainlogo_144x144.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        .header {
            text-align: center;
            padding: 50px;
            background-color: rgba(248, 249, 250, 0.7);
            border: 1px solid #ddd;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }

        .content {
            margin: 50px;
            background-color: rgba(255, 255, 255, 0.7);
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            padding: 20px;
            text-align: center;
        }

        .footer {
            text-align: center;
            padding: 20px;
            background-color: rgba(248, 249, 250, 0.7);
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            padding: 20px;
            text-align: center;
        }

        .bg-video {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            z-index: -1;
        }

        .row {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }

        .column {
            flex: 1;
            max-width: 20%;
            padding: 10px;
            text-align: center;
        }

        .column img {
            width: 100%;
            height: auto;
        }

        .column p {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    



    <div class="content">
    <h1>Wheeleder</h1>
          
          <img src="storage/website/logo/mainlogo_512x512.png" alt="Innovation Lab Logo" class="img-fluid"
              style="max-width: 400px; height: auto;"><br>
              
              <h2>Website Is Under Construction</h2>
    </div>


    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>
    <?php
}
?>
