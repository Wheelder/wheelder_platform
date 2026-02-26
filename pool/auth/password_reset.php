<?php
// WHY: Password reset page for hardcoded email
// Allows user to reset password via email verification

// Include top.php to get base path functions
$path = dirname(dirname(__DIR__)); // Goes from pool/auth to project root
require_once $path . '/top.php';

// WHY: Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// WHY: Generate CSRF token to prevent cross-site request forgery
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// WHY: Check if user is already logged in — redirect to dashboard
if (!empty($_SESSION['auth']) && !empty($_SESSION['log']) && $_SESSION['log'] == 1) {
    header('Location: ' . url('/dashboard'));
    exit;
}

// WHY: Determine current step (request, verify, reset)
$step = $_GET['step'] ?? 'request';
$token = $_GET['token'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Password Reset — Wheelder</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="<?php echo url('/images/f-icon.png'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* WHY: Custom styling for password reset card */
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .reset-container {
            max-width: 420px;
            width: 100%;
            padding: 20px;
        }
        
        .reset-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
        }
        
        .reset-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .reset-header h2 {
            color: #333;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .reset-header p {
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
        
        .btn-reset {
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
        
        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-reset:disabled {
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
        
        .alert-info {
            background-color: #eef;
            color: #33c;
        }
        
        .reset-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .reset-footer a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }
        
        .reset-footer a:hover {
            text-decoration: underline;
        }
        
        .password-strength {
            margin-top: 8px;
            height: 4px;
            background: #eee;
            border-radius: 2px;
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
        }
        
        .strength-weak { background: #d32f2f; }
        .strength-fair { background: #f57c00; }
        .strength-good { background: #fbc02d; }
        .strength-strong { background: #388e3c; }
        
        .strength-text {
            font-size: 12px;
            margin-top: 4px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="reset-card">
            <!-- WHY: Reset header with title and subtitle -->
            <div class="reset-header">
                <h2>Reset Password</h2>
                <p>Secure your account with a new password</p>
            </div>

            <!-- WHY: Alert messages for errors and success -->
            <div id="errorMessage" class="alert alert-danger"></div>
            <div id="successMessage" class="alert alert-success"></div>
            <div id="infoMessage" class="alert alert-info"></div>

            <!-- WHY: Step 1: Request password reset -->
            <div id="requestStep" style="display: <?php echo $step === 'request' ? 'block' : 'none'; ?>">
                <form id="requestForm">
                    <!-- WHY: CSRF token to prevent cross-site request forgery -->
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>" />

                    <!-- WHY: Email field (read-only, hardcoded) -->
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
                        <small class="text-muted d-block mt-2">Only this email can reset password</small>
                    </div>

                    <!-- WHY: Submit button with loading state -->
                    <button type="submit" class="btn-reset" id="requestBtn">
                        <span id="requestBtnText">Send Reset Link</span>
                        <span id="requestBtnSpinner" style="display:none;">
                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            Sending...
                        </span>
                    </button>
                </form>

                <!-- WHY: Footer with link back to login -->
                <div class="reset-footer">
                    <a href="<?php echo url('/login2'); ?>">Back to Login</a>
                </div>
            </div>

            <!-- WHY: Step 2: Reset password with new password -->
            <div id="resetStep" style="display: <?php echo $step === 'reset' ? 'block' : 'none'; ?>">
                <form id="resetForm">
                    <!-- WHY: CSRF token to prevent cross-site request forgery -->
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>" />
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>" />

                    <!-- WHY: New password field with strength indicator -->
                    <div class="form-group">
                        <label for="newPassword" class="form-label">New Password</label>
                        <div style="position: relative;">
                            <input 
                                type="password" 
                                class="form-control" 
                                id="newPassword" 
                                name="new_password" 
                                placeholder="Enter new password" 
                                required 
                                autocomplete="new-password"
                                minlength="8"
                            />
                            <!-- WHY: Toggle button to show/hide password -->
                            <button 
                                type="button" 
                                class="btn btn-sm" 
                                id="toggleNewPassword" 
                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: none; background: none; color: #667eea;"
                            >
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <!-- WHY: Password strength indicator -->
                        <div class="password-strength">
                            <div class="password-strength-bar" id="strengthBar"></div>
                        </div>
                        <div class="strength-text" id="strengthText">Password strength: <span id="strengthLevel">weak</span></div>
                        <small class="text-muted d-block mt-2">Minimum 8 characters</small>
                    </div>

                    <!-- WHY: Confirm password field -->
                    <div class="form-group">
                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                        <div style="position: relative;">
                            <input 
                                type="password" 
                                class="form-control" 
                                id="confirmPassword" 
                                name="confirm_password" 
                                placeholder="Confirm new password" 
                                required 
                                autocomplete="new-password"
                            />
                            <!-- WHY: Toggle button to show/hide password -->
                            <button 
                                type="button" 
                                class="btn btn-sm" 
                                id="toggleConfirmPassword" 
                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: none; background: none; color: #667eea;"
                            >
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- WHY: Submit button with loading state -->
                    <button type="submit" class="btn-reset" id="resetBtn">
                        <span id="resetBtnText">Reset Password</span>
                        <span id="resetBtnSpinner" style="display:none;">
                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            Resetting...
                        </span>
                    </button>
                </form>

                <!-- WHY: Footer with link back to login -->
                <div class="reset-footer">
                    <a href="<?php echo url('/login2'); ?>">Back to Login</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // WHY: Toggle password visibility
        function setupPasswordToggle(toggleBtnId, inputId) {
            const toggleBtn = document.getElementById(toggleBtnId);
            if (!toggleBtn) return;

            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const input = document.getElementById(inputId);
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        }

        setupPasswordToggle('toggleNewPassword', 'newPassword');
        setupPasswordToggle('toggleConfirmPassword', 'confirmPassword');

        // WHY: Calculate password strength
        function calculatePasswordStrength(password) {
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;
            
            return strength;
        }

        // WHY: Update password strength indicator
        const newPasswordInput = document.getElementById('newPassword');
        if (newPasswordInput) {
            newPasswordInput.addEventListener('input', function() {
                const strength = calculatePasswordStrength(this.value);
                const bar = document.getElementById('strengthBar');
                const level = document.getElementById('strengthLevel');
                
                const widths = [0, 20, 40, 60, 80, 100];
                const levels = ['weak', 'fair', 'good', 'good', 'strong', 'strong'];
                const classes = ['strength-weak', 'strength-weak', 'strength-fair', 'strength-good', 'strength-strong', 'strength-strong'];
                
                bar.style.width = widths[strength] + '%';
                bar.className = 'password-strength-bar ' + classes[strength];
                level.textContent = levels[strength];
            });
        }

        // WHY: Handle request form submission
        const requestForm = document.getElementById('requestForm');
        if (requestForm) {
            requestForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const requestBtn = document.getElementById('requestBtn');
                const requestBtnText = document.getElementById('requestBtnText');
                const requestBtnSpinner = document.getElementById('requestBtnSpinner');
                const errorMessage = document.getElementById('errorMessage');
                const successMessage = document.getElementById('successMessage');
                const infoMessage = document.getElementById('infoMessage');

                // WHY: Hide previous messages
                errorMessage.style.display = 'none';
                successMessage.style.display = 'none';
                infoMessage.style.display = 'none';

                // WHY: Disable button and show loading state
                requestBtn.disabled = true;
                requestBtnText.style.display = 'none';
                requestBtnSpinner.style.display = 'inline';

                try {
                    // WHY: Send request to API endpoint
                    const formData = new FormData(this);
                    const response = await fetch('<?php echo url('/api/password-reset-request'); ?>', {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        // WHY: Show success message
                        successMessage.textContent = data.message || 'Reset link sent to your email!';
                        successMessage.style.display = 'block';
                        
                        // WHY: Clear form
                        this.reset();
                        
                        // WHY: Re-enable button
                        requestBtn.disabled = false;
                        requestBtnText.style.display = 'inline';
                        requestBtnSpinner.style.display = 'none';
                    } else {
                        // WHY: Show error message
                        errorMessage.textContent = data.error || 'Failed to send reset link. Please try again.';
                        errorMessage.style.display = 'block';
                        
                        // WHY: Re-enable button
                        requestBtn.disabled = false;
                        requestBtnText.style.display = 'inline';
                        requestBtnSpinner.style.display = 'none';
                    }
                } catch (error) {
                    // WHY: Handle network errors
                    console.error('Request error:', error);
                    errorMessage.textContent = 'An error occurred. Please try again.';
                    errorMessage.style.display = 'block';
                    
                    // WHY: Re-enable button
                    requestBtn.disabled = false;
                    requestBtnText.style.display = 'inline';
                    requestBtnSpinner.style.display = 'none';
                }
            });
        }

        // WHY: Handle reset form submission
        const resetForm = document.getElementById('resetForm');
        if (resetForm) {
            resetForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const newPassword = document.getElementById('newPassword').value;
                const confirmPassword = document.getElementById('confirmPassword').value;
                const resetBtn = document.getElementById('resetBtn');
                const resetBtnText = document.getElementById('resetBtnText');
                const resetBtnSpinner = document.getElementById('resetBtnSpinner');
                const errorMessage = document.getElementById('errorMessage');
                const successMessage = document.getElementById('successMessage');

                // WHY: Hide previous messages
                errorMessage.style.display = 'none';
                successMessage.style.display = 'none';

                // WHY: Validate passwords match
                if (newPassword !== confirmPassword) {
                    errorMessage.textContent = 'Passwords do not match.';
                    errorMessage.style.display = 'block';
                    return;
                }

                // WHY: Validate password strength
                if (newPassword.length < 8) {
                    errorMessage.textContent = 'Password must be at least 8 characters long.';
                    errorMessage.style.display = 'block';
                    return;
                }

                // WHY: Disable button and show loading state
                resetBtn.disabled = true;
                resetBtnText.style.display = 'none';
                resetBtnSpinner.style.display = 'inline';

                try {
                    // WHY: Send reset request to API endpoint
                    const formData = new FormData(this);
                    const response = await fetch('<?php echo url('/api/password-reset'); ?>', {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        // WHY: Show success message and redirect
                        successMessage.textContent = data.message || 'Password reset successfully! Redirecting to login...';
                        successMessage.style.display = 'block';
                        
                        // WHY: Redirect to login after 2 seconds
                        setTimeout(() => {
                            window.location.href = '<?php echo url('/login2'); ?>';
                        }, 2000);
                    } else {
                        // WHY: Show error message
                        errorMessage.textContent = data.error || 'Failed to reset password. Please try again.';
                        errorMessage.style.display = 'block';
                        
                        // WHY: Re-enable button
                        resetBtn.disabled = false;
                        resetBtnText.style.display = 'inline';
                        resetBtnSpinner.style.display = 'none';
                    }
                } catch (error) {
                    // WHY: Handle network errors
                    console.error('Reset error:', error);
                    errorMessage.textContent = 'An error occurred. Please try again.';
                    errorMessage.style.display = 'block';
                    
                    // WHY: Re-enable button
                    resetBtn.disabled = false;
                    resetBtnText.style.display = 'inline';
                    resetBtnSpinner.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
