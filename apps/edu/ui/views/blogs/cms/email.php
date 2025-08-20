<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/apps/edu/controllers/BlogController.php';
$blog = new BlogController();
//$blog->check_auth();

include $path . '/apps/edu/ui/layouts/nav.php';

// Function to send email



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
}

?>

<section class="bg-light py-1">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-8 mx-auto">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title mb-4 fs-3">Post Note</h2>
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
include $path . '/apps/edu/ui/layouts/footer.php';
?>
