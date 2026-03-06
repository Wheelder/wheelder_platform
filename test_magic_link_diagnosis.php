<?php
/**
 * Magic Link Email Sending Diagnosis Script
 * Tests email service configuration and identifies why emails are failing
 */

// Load environment variables
require_once __DIR__ . '/pool/config/env_loader.php';

echo "=== Magic Link Email Service Diagnosis ===\n\n";

// 1. Check if .env file exists
echo "[1] Checking .env file...\n";
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    echo "✓ .env file exists\n";
} else {
    echo "✗ .env file NOT found at $envFile\n";
    echo "  ACTION: Copy .env.example to .env and configure email provider\n";
    exit(1);
}

// 2. Check EMAIL_PROVIDER setting
echo "\n[2] Checking EMAIL_PROVIDER configuration...\n";
$provider = getenv('EMAIL_PROVIDER') ?: 'smtp';
echo "EMAIL_PROVIDER = '$provider'\n";

if ($provider === 'smtp') {
    echo "\n[3] Checking SMTP configuration...\n";
    $smtpHost = getenv('SMTP_HOST');
    $smtpPort = getenv('SMTP_PORT');
    $smtpUsername = getenv('SMTP_USERNAME');
    $smtpPassword = getenv('SMTP_PASSWORD');
    $smtpFromEmail = getenv('SMTP_FROM_EMAIL');
    
    echo "SMTP_HOST = " . ($smtpHost ? "'$smtpHost'" : "NOT SET") . "\n";
    echo "SMTP_PORT = " . ($smtpPort ? "'$smtpPort'" : "NOT SET") . "\n";
    echo "SMTP_USERNAME = " . ($smtpUsername ? "'$smtpUsername'" : "NOT SET") . "\n";
    echo "SMTP_PASSWORD = " . ($smtpPassword ? "'***'" : "NOT SET") . "\n";
    echo "SMTP_FROM_EMAIL = " . ($smtpFromEmail ? "'$smtpFromEmail'" : "NOT SET") . "\n";
    
    if (empty($smtpUsername) || empty($smtpPassword)) {
        echo "\n✗ SMTP credentials are missing!\n";
        echo "  ACTION: Set SMTP_USERNAME and SMTP_PASSWORD in .env\n";
        exit(1);
    }
    
    // Test SMTP connection
    echo "\n[4] Testing SMTP connection...\n";
    $conn = @fsockopen($smtpHost, $smtpPort, $errno, $errstr, 5);
    if ($conn) {
        echo "✓ SMTP connection successful to $smtpHost:$smtpPort\n";
        fclose($conn);
    } else {
        echo "✗ SMTP connection failed: $errstr (errno: $errno)\n";
        echo "  ACTION: Check SMTP_HOST and SMTP_PORT, or verify firewall allows outbound SMTP\n";
        exit(1);
    }
    
} elseif ($provider === 'resend') {
    echo "\n[3] Checking Resend configuration...\n";
    $resendKey = getenv('RESEND_API_KEY');
    $resendFromEmail = getenv('RESEND_FROM_EMAIL');
    
    echo "RESEND_API_KEY = " . ($resendKey ? "'***'" : "NOT SET") . "\n";
    echo "RESEND_FROM_EMAIL = " . ($resendFromEmail ? "'$resendFromEmail'" : "NOT SET") . "\n";
    
    if (empty($resendKey)) {
        echo "\n✗ RESEND_API_KEY is missing!\n";
        echo "  ACTION: Set RESEND_API_KEY in .env\n";
        echo "  Get key from: https://resend.com/api-keys\n";
        exit(1);
    }
    
} elseif ($provider === 'sendgrid') {
    echo "\n[3] Checking SendGrid configuration...\n";
    $sendgridKey = getenv('SENDGRID_API_KEY');
    $sendgridFromEmail = getenv('SENDGRID_FROM_EMAIL');
    
    echo "SENDGRID_API_KEY = " . ($sendgridKey ? "'***'" : "NOT SET") . "\n";
    echo "SENDGRID_FROM_EMAIL = " . ($sendgridFromEmail ? "'$sendgridFromEmail'" : "NOT SET") . "\n";
    
    if (empty($sendgridKey)) {
        echo "\n✗ SENDGRID_API_KEY is missing!\n";
        echo "  ACTION: Set SENDGRID_API_KEY in .env\n";
        exit(1);
    }
}

// 5. Check if EmailService can be loaded
echo "\n[5] Checking EmailService classes...\n";
try {
    require_once __DIR__ . '/vendor/autoload.php';
    require_once __DIR__ . '/pool/libs/services/EmailService.php';
    echo "✓ EmailService loaded successfully\n";
} catch (Exception $e) {
    echo "✗ Failed to load EmailService: " . $e->getMessage() . "\n";
    exit(1);
}

// 6. Check if MagicLinkController can be loaded
echo "\n[6] Checking MagicLinkController...\n";
try {
    require_once __DIR__ . '/pool/libs/controllers/MagicLinkController.php';
    echo "✓ MagicLinkController loaded successfully\n";
} catch (Exception $e) {
    echo "✗ Failed to load MagicLinkController: " . $e->getMessage() . "\n";
    exit(1);
}

// 7. Test magic link request
echo "\n[7] Testing magic link request...\n";
try {
    $magicLink = new MagicLinkController();
    $testEmail = 'test@example.com';
    $result = $magicLink->requestMagicLink($testEmail);
    
    if ($result['success']) {
        echo "✓ Magic link request successful\n";
        echo "  Message: " . $result['message'] . "\n";
        if (!empty($result['magic_link'])) {
            echo "  Dev mode link: " . $result['magic_link'] . "\n";
        }
    } else {
        echo "✗ Magic link request failed\n";
        echo "  Error: " . $result['message'] . "\n";
        
        // Additional diagnostics
        echo "\n[8] Additional Diagnostics...\n";
        echo "  - Check PHP error log for detailed error messages\n";
        echo "  - Check email service provider logs\n";
        echo "  - Verify email credentials are correct\n";
        echo "  - Check if outbound email port is blocked by firewall\n";
    }
} catch (Exception $e) {
    echo "✗ Exception during magic link test: " . $e->getMessage() . "\n";
    echo "  Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Diagnosis Complete ===\n";
?>
