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
                        
                    
                        <form method="POST" action="/log_api" class="needs-validation" >
                        
                            <div class="mb-3">
                                <label for="email" class="form-label">OTP Code (Check your email):</label>
                                <input type="text" name="otp" id="otp" placeholder="Enter code" class="form-control">

                                <div class="invalid-feedback">Please enter a valid email.</div>
                            </div>
                            <div class="mb-3">
                                <!-- Your CAPTCHA code here -->
                            </div>
                            <div class="d-flex justify-content-center">
                                <button class="btn btn-primary" type="submit" name="verify">Submit</button>
                                <button class="btn btn-secondary" type="submit" name="resend">Resend</button>
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
