<?php
    $dapp= $_GET['dp'] ?? Null OR '';
    if($dapp == ''){
        $dapp = 'main';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Wheeleder</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="images/f-icon.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
     <style>
        /* Custom styles for smaller login form */
        .login-card {
            max-width: 400px; /* Smaller width for the card */
        }
        .login-btn {
            width: 100%; /* Full width for login buttons */
        }
        /* Include your existing styles */
    </style>
</head>
<body>
    <div class="container ">
        <div class="row justify-content-center align-items-center pt-2 pb-2 min-vh-100">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body shadow">
                    
                        <!-- Wheeleder heading -->
                        <h4 class="mb-2 text-center">Login</h4>
                        
                        <!-- Login heading -->
                

                        <!-- Login with Facebook button 
                        <a href="/login/facebook" class="btn btn-primary btn-block mb-2" style="width:100%;">Login with Facebook</a><br>
                        
                        
                        <a href="/login/google" class="btn btn-danger btn-block mb-2" style="width:100%;">Login with Google</a><br>
                        
                
                        <a href="/login/apple" class="btn btn-dark btn-block mb-2" style="width:100%;">Login with Apple</a><br>
                        
                    
                        <a href="/login/microsoft" class="btn btn-outline-primary btn-block mb-2" style="width:100%;">Login with Microsoft</a>-->

                        <div class="or-separator mb-3"></div>
                        <hr>

                        <form method="POST" action="/log_api">
                            <input type="hidden" name="dapp" value="<?php echo $dapp; ?>" />   
                            <div class="mb-3">
                                
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required />
                                <div class="invalid-feedback">Please enter a valid email.</div>
                            </div>
                            <div class="mb-3">
                                
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required />
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback">Please enter your password.</div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
                                <label class="form-check-label" for="rememberMe">Remember Me</label>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <a href="/forgot_pass" class="text-primary">Forgot Password?</a>
                                <button class="btn btn-primary " type="submit" name="login">Login</button>
                            </div>
                            
                        </form>
                        <div class="text-center">
                            <p class="mb-0">Don't have an account? <a href="/signup" class="text-primary">Sign Up</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.15.0/bootstrap-icons.min.js"></script>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            var passwordInput = document.getElementById('password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.innerHTML = '<i class="bi bi-eye-slash"></i>';
            } else {
                passwordInput.type = 'password';
                this.innerHTML = '<i class="bi bi-eye"></i>';
            }
        });
    </script>

    <!-- Include your existing scripts -->
</body>
</html>
