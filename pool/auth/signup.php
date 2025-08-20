<!DOCTYPE html>
<html lang="en">
<head>
    <title>Wheeleder</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="images/f-icon.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
    
    <!-- Include your existing styles -->

</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center pt-2 pb-2 min-vh-100">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body shadow">
                        <!-- Wheeleder heading -->
                        <h4 class="mb-3 text-center">Sign Up</h4>
                        <div class="or-separator mb-3"></div>
                        <hr>
                        
                        

                        <!-- Login buttons in one row 
                        <div class="mb-3 d-flex justify-content-around">
                            <a href="/signup/facebook" class="btn btn-primary btn-sm">With Facebook</a>
                            <a href="/signup/google" class="btn btn-danger btn-sm">With Google</a>
                            <a href="/signup/apple" class="btn btn-dark btn-sm">With Apple</a>
                            <a href="/signup/microsoft" class="btn btn-outline-primary btn-sm">Microsoft</a>
                        </div>-->

                        <form method="POST" action="/log_api">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    
                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" required />
                                    <div class="invalid-feedback">Please enter your first name.</div>
                                </div>
                                <div class="col-md-6">
                                    
                                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" required />
                                    <div class="invalid-feedback">Please enter your last name.</div>
                                </div>
                            </div>
                            <div class="mb-3">
                            
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required />
                                <div class="invalid-feedback">Please enter a valid email.</div>
                            </div>
                            <div class="mb-3">
                                
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required />
                                <div class="invalid-feedback">Please enter your password.</div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="termsAndConditions" name="termsAndConditions" required>
                                <label class="form-check-label" for="termsAndConditions">
                                    I agree to the <a href="/terms" target="_blank">Terms and Conditions</a> and <a href="/privacy" target="_blank">Privacy Policy</a>
                                </label>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <button class="btn btn-primary" onclick="return checkpassword();" type="submit" name="signup">Sign up</button>
                                <a href="/login" class="btn btn-secondary">Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Include your existing scripts -->

</body>
</html>
