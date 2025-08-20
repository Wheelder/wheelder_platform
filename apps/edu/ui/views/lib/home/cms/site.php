<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/apps/edu/controllers/BlogController.php';
$blog = new BlogController();
$blog->check_auth();


?>
<!DOCTYPE html>
<html>
<head>
    <title>Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">

        <table class="table table-striped table-responsive">
            <thead>
                <tr>
                <td>#</td>
                <td>IP Address</td>
                <td>Country</td>
                <td>city</td>
                <td>Latitude</td>
                <td>Longitude</td>
                <td>Timezone</td>
                <td>Date</td>
                </tr>
            </thead>
            <tbody>
                <?php 
                 $blog->display_website_traffic();
                ?>
                    
                
            </tbody>
        </table>
    

    </div>
</body>
</html>
