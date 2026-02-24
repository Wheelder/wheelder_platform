<?php
// Include top.php to get base path functions
$path = dirname(dirname(__DIR__)); // Goes from pool/auth to project root
require_once $path . '/top.php';

$dapp= $_GET['dp'] ?? Null OR '';
if($dapp == ''){
    $dapp = 'main';
}

// Generate CSRF token — prevents cross-site request forgery on login form
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
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

                        <!-- Magic Link Authentication Form -->
                        <div id="magicLinkForm">
                            <p class="text-muted text-center mb-4">Enter your email to receive a secure magic link</p>
                            <form id="emailForm">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
                                <input type="hidden" name="dapp" value="<?php echo $dapp; ?>" />
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="your@email.com" required />
                                    <small class="text-muted">We'll send you a secure link to sign in</small>
                                </div>

                                <div id="errorMessage" class="alert alert-danger" style="display:none;"></div>
                                <div id="successMessage" class="alert alert-success" style="display:none;"></div>

                                <button type="submit" class="btn btn-primary w-100 mb-3" id="submitBtn">
                                    <span id="btnText">Send Magic Link</span>
                                    <span id="btnSpinner" style="display:none;">
                                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                        Sending...
                                    </span>
                                </button>
                            </form>
                        </div>

                        <hr class="my-3">
                        <p class="text-center text-muted small">
                            No password needed. We'll send you a secure link via email.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.15.0/bootstrap-icons.min.js"></script>
    <script>
        // Magic Link Form Handler
        // WHY: Sends email to /auth/magic-link-request endpoint for passwordless login
        document.getElementById('emailForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const email = document.getElementById('email').value.trim();
            const errorMsg = document.getElementById('errorMessage');
            const successMsg = document.getElementById('successMessage');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');

            // Hide previous messages
            errorMsg.style.display = 'none';
            successMsg.style.display = 'none';

            // Validate email
            if (!email || !email.includes('@')) {
                errorMsg.textContent = 'Please enter a valid email address.';
                errorMsg.style.display = 'block';
                return;
            }

            // Show loading state
            submitBtn.disabled = true;
            btnText.style.display = 'none';
            btnSpinner.style.display = 'inline';

            try {
                // Send request to magic link endpoint
                const formData = new FormData();
                formData.append('email', email);

                // Use window.location to construct proper endpoint URL
                const protocol = window.location.protocol; // http: or https:
                const host = window.location.host;         // localhost:80 or example.com
                const pathname = window.location.pathname; // /wheelder/login or /login
                
                // Extract base path from current pathname
                const pathParts = pathname.split('/');
                let basePath = '';
                
                // If we're in a subdirectory (e.g., /wheelder/login), extract /wheelder
                if (pathParts.length > 2 && pathParts[1] !== 'login') {
                    basePath = '/' + pathParts[1];
                }
                
                // Try routing endpoint first, fallback to direct file
                const endpoint = protocol + '//' + host + basePath + '/auth/magic-link-request';
                const fallbackEndpoint = protocol + '//' + host + basePath + '/auth_magic_link_request.php';

                console.log('Sending magic link request to:', endpoint);

                let response;
                let data;
                let lastError;

                // Try primary endpoint first
                try {
                    response = await fetch(endpoint, {
                        method: 'POST',
                        body: formData
                    });

                    console.log('Response status:', response.status);

                    // Check if response is valid JSON
                    try {
                        data = await response.json();
                    } catch (parseError) {
                        console.error('Failed to parse JSON from primary endpoint:', parseError);
                        const text = await response.text();
                        console.error('Response text:', text);
                        lastError = 'Invalid response from primary endpoint';
                        throw new Error(lastError);
                    }
                } catch (primaryError) {
                    console.warn('Primary endpoint failed, trying fallback:', primaryError);
                    
                    // Retry with fallback endpoint
                    try {
                        response = await fetch(fallbackEndpoint, {
                            method: 'POST',
                            body: formData
                        });

                        console.log('Fallback response status:', response.status);

                        try {
                            data = await response.json();
                        } catch (parseError) {
                            console.error('Failed to parse JSON from fallback endpoint:', parseError);
                            const text = await response.text();
                            console.error('Fallback response text:', text);
                            throw new Error('Server returned invalid response from fallback');
                        }
                    } catch (fallbackError) {
                        console.error('Both endpoints failed:', fallbackError);
                        throw fallbackError;
                    }
                }

                if (response.ok && data.success) {
                    // Dev mode: show clickable link directly (no email server on localhost)
                    if (data.magic_link) {
                        successMsg.innerHTML = `
                            <strong>✓ Dev Mode — Click to log in:</strong><br>
                            <a href="${data.magic_link}" style="word-break:break-all;">${data.magic_link}</a>
                        `;
                    } else {
                        successMsg.innerHTML = `
                            <strong>✓ Check your email!</strong><br>
                            We've sent a magic link to <strong>${email}</strong>.<br>
                            The link is valid for 15 minutes.
                        `;
                    }
                    successMsg.style.display = 'block';
                    document.getElementById('emailForm').reset();
                } else {
                    // Error — show error message
                    errorMsg.textContent = data.error || 'Failed to send magic link. Please try again.';
                    errorMsg.style.display = 'block';
                }
            } catch (error) {
                console.error('Network error details:', error);
                errorMsg.textContent = 'Network error. Please check your connection and try again.';
                errorMsg.style.display = 'block';
            } finally {
                // Restore button state
                submitBtn.disabled = false;
                btnText.style.display = 'inline';
                btnSpinner.style.display = 'none';
            }
        });
    </script>

    <!-- Include your existing scripts -->
</body>
</html>
