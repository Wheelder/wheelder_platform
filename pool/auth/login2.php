<?php
// WHY: Secondary login page with password authentication
// Provides alternative to magic link system for the hardcoded email "regrowup2025@gmail.com"

// Include top.php to get base path functions
$path = dirname(dirname(__DIR__)); // Goes from pool/auth to project root
require_once $path . '/top.php';

// WHY: Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// WHY: Generate CSRF token to prevent cross-site request forgery on login form
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// WHY: Check if user is already logged in — redirect to dashboard
if (!empty($_SESSION['auth']) && !empty($_SESSION['log']) && $_SESSION['log'] == 1) {
    header('Location: ' . url('/dashboard'));
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login — Wheelder</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="<?php echo url('/images/f-icon.png'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* WHY: Custom styling for login card and form elements */
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            max-width: 420px;
            width: 100%;
            padding: 20px;
        }
        
        .login-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header h2 {
            color: #333;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: #666;
            font-size: 14px;
            margin: 0;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }
        
        .form-control {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-check {
            margin-bottom: 20px;
        }
        
        .form-check-input {
            width: 18px;
            height: 18px;
            margin-top: 3px;
            cursor: pointer;
            border-color: #ddd;
        }
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .form-check-label {
            margin-left: 8px;
            cursor: pointer;
            color: #666;
            font-size: 14px;
        }
        
        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 15px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .alert {
            border-radius: 6px;
            border: none;
            margin-bottom: 20px;
            display: none;
        }
        
        .alert-danger {
            background-color: #fee;
            color: #c33;
        }
        
        .alert-success {
            background-color: #efe;
            color: #3c3;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .login-footer a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        .login-divider {
            text-align: center;
            margin: 20px 0;
            color: #999;
            font-size: 13px;
        }
        
        .spinner-border-sm {
            width: 16px;
            height: 16px;
            border-width: 2px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- WHY: Login header with title and subtitle -->
            <div class="login-header">
                <h2>Welcome Back</h2>
                <p>Sign in with your password</p>
            </div>

            <!-- WHY: Alert messages for errors and success -->
            <div id="errorMessage" class="alert alert-danger"></div>
            <div id="successMessage" class="alert alert-success"></div>

            <!-- WHY: Login form with email and password fields -->
            <form id="loginForm">
                <!-- WHY: CSRF token to prevent cross-site request forgery -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>" />

                <!-- WHY: Email field (read-only, hardcoded to regrowup2025@gmail.com) -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        type="email" 
                        class="form-control" 
                        id="email" 
                        name="email" 
                        value="regrowup2025@gmail.com" 
                        readonly 
                        required 
                    />
                    <small class="text-muted d-block mt-2">Only this email can log in</small>
                </div>

                <!-- WHY: Password field with show/hide toggle -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div style="position: relative;">
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password" 
                            name="password" 
                            placeholder="Enter your password" 
                            required 
                            autocomplete="current-password"
                        />
                        <!-- WHY: Toggle button to show/hide password -->
                        <button 
                            type="button" 
                            class="btn btn-sm" 
                            id="togglePassword" 
                            style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: none; background: none; color: #667eea;"
                        >
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- WHY: Remember me checkbox for persistent login -->
                <div class="form-check">
                    <input 
                        type="checkbox" 
                        class="form-check-input" 
                        id="rememberMe" 
                        name="remember_me"
                    />
                    <label class="form-check-label" for="rememberMe">
                        Remember me for 30 days
                    </label>
                </div>

                <!-- WHY: Login button with loading state -->
                <button type="submit" class="btn-login" id="submitBtn">
                    <span id="btnText">Sign In</span>
                    <span id="btnSpinner" style="display:none;">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Signing in...
                    </span>
                </button>
            </form>

            <!-- WHY: Footer with links to password reset and magic link login -->
            <div class="login-footer">
                <a href="<?php echo url('/password-reset'); ?>">Forgot your password?</a>
                <div class="login-divider">or</div>
                <a href="<?php echo url('/login'); ?>">Use magic link instead</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // WHY: Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function(e) {
            e.preventDefault();
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // WHY: Handle login form submission
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');
            const errorMessage = document.getElementById('errorMessage');
            const successMessage = document.getElementById('successMessage');

            // WHY: Hide previous messages
            errorMessage.style.display = 'none';
            successMessage.style.display = 'none';

            // WHY: Disable button and show loading state
            submitBtn.disabled = true;
            btnText.style.display = 'none';
            btnSpinner.style.display = 'inline';

            try {
                // WHY: Send login request to API endpoint
                const formData = new FormData(this);
                const response = await fetch('<?php echo url('/api/login2'); ?>', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // WHY: Show success message and redirect
                    successMessage.textContent = data.message || 'Login successful! Redirecting...';
                    successMessage.style.display = 'block';
                    
                    // WHY: Redirect to dashboard after 1 second
                    setTimeout(() => {
                        window.location.href = '<?php echo url('/dashboard'); ?>';
                    }, 1000);
                } else {
                    // WHY: Show error message
                    errorMessage.textContent = data.error || 'Login failed. Please try again.';
                    errorMessage.style.display = 'block';
                    
                    // WHY: Re-enable button
                    submitBtn.disabled = false;
                    btnText.style.display = 'inline';
                    btnSpinner.style.display = 'none';
                }
            } catch (error) {
                // WHY: Handle network errors
                console.error('Login error:', error);
                errorMessage.textContent = 'An error occurred. Please try again.';
                errorMessage.style.display = 'block';
                
                // WHY: Re-enable button
                submitBtn.disabled = false;
                btnText.style.display = 'inline';
                btnSpinner.style.display = 'none';
            }
        });
    </script>
</body>
</html>
