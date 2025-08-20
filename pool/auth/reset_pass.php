<!DOCTYPE html>
<html lang="en">
<head>
    <title>Wheeleder </title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="images/f-icon.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
    
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        
                        <h3 class="text-center mb-4">Forgot Password</h3>
                        <form method="POST" action="/log_api" >
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Reset your password:</label>
                                <input type="text" class="form-control" id="new_pass" name="new_pass" placeholder="Email" required />
                                
                            </div>
                            <div class="mb-3">
                                <!-- Your CAPTCHA code here -->
                            </div>
                            <div class="d-flex justify-content-center">
                                <button class="btn btn-primary" type="submit" name="reset_pass">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
