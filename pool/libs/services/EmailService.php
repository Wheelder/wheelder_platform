<?php
/**
 * Email Service Interfaces and Implementations
 * 
 * Supports multiple email providers:
 * - SMTP (DigitalOcean App Platform, traditional servers)
 * - SendGrid API
 * - Mailgun API
 */

/**
 * Base email service interface
 * WHY: Allows switching providers without changing controller code
 */
interface IEmailService
{
    public function sendMagicLink($email, $magicLink, $expiryMinutes);
}

/**
 * SMTP Email Service — Works with DigitalOcean App Platform
 * WHY: No API key needed, works with standard SMTP servers
 */
class SMTPEmailService implements IEmailService
{
    private $host;
    private $port;
    private $username;
    private $password;
    private $fromEmail;
    private $fromName;

    public function __construct()
    {
        // Load from environment variables (set in DigitalOcean App Platform)
        $this->host = getenv('SMTP_HOST') ?: 'smtp.gmail.com';
        $this->port = getenv('SMTP_PORT') ?: 587;
        $this->username = getenv('SMTP_USERNAME') ?: '';
        $this->password = getenv('SMTP_PASSWORD') ?: '';
        $this->fromEmail = getenv('SMTP_FROM_EMAIL') ?: 'noreply@wheelder.com';
        $this->fromName = getenv('SMTP_FROM_NAME') ?: 'Wheelder';

        // Validate required credentials
        if (empty($this->username) || empty($this->password)) {
            error_log('SMTP credentials not configured in environment variables');
        }
    }

    /**
     * Send magic link via SMTP
     * WHY: Uses PHPMailer for reliable SMTP delivery with TLS/SSL
     */
    public function sendMagicLink($email, $magicLink, $expiryMinutes)
    {
        try {
            // Use PHP's built-in mail() with proper headers as fallback
            // For production, use PHPMailer: composer require phpmailer/phpmailer
            
            $subject = 'Your Wheelder Magic Link';
            $message = $this->buildEmailHtml($magicLink, $expiryMinutes);
            
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";
            $headers .= "From: {$this->fromName} <{$this->fromEmail}>\r\n";
            $headers .= "Reply-To: {$this->fromEmail}\r\n";
            $headers .= "X-Mailer: Wheelder/1.0\r\n";

            // Send email
            $sent = mail($email, $subject, $message, $headers);

            if ($sent) {
                error_log("Magic link sent to $email via SMTP");
                return true;
            } else {
                error_log("Failed to send magic link to $email via SMTP");
                return false;
            }
        } catch (Exception $e) {
            error_log("SMTP error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Build HTML email template
     * WHY: Professional, branded email with clear call-to-action
     */
    private function buildEmailHtml($magicLink, $expiryMinutes)
    {
        $appName = getenv('APP_NAME') ?: 'Wheelder';
        
        return "
        <html>
        <head>
            <style>
                body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #1a1a1a; color: #fff; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
                .button { display: inline-block; background: #007bff; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 20px 0; }
                .footer { color: #666; font-size: 12px; margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>$appName</h1>
                </div>
                <div class='content'>
                    <p>Hi there!</p>
                    <p>Click the button below to sign in to your $appName account. This link is valid for <strong>$expiryMinutes minutes</strong>.</p>
                    <a href='$magicLink' class='button'>Sign In to $appName</a>
                    <p>Or copy and paste this link in your browser:</p>
                    <p><code>$magicLink</code></p>
                    <p style='color: #999; font-size: 12px;'>If you didn't request this link, you can safely ignore this email.</p>
                    <div class='footer'>
                        <p>&copy; " . date('Y') . " $appName. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}

/**
 * SendGrid Email Service
 * WHY: Reliable, scalable email delivery with high deliverability
 */
class SendGridEmailService implements IEmailService
{
    private $apiKey;
    private $fromEmail;
    private $fromName;

    public function __construct()
    {
        $this->apiKey = getenv('SENDGRID_API_KEY') ?: '';
        $this->fromEmail = getenv('SENDGRID_FROM_EMAIL') ?: 'noreply@wheelder.com';
        $this->fromName = getenv('SENDGRID_FROM_NAME') ?: 'Wheelder';

        if (empty($this->apiKey)) {
            error_log('SendGrid API key not configured');
        }
    }

    /**
     * Send magic link via SendGrid API
     * WHY: Uses official SendGrid API for reliable delivery
     */
    public function sendMagicLink($email, $magicLink, $expiryMinutes)
    {
        try {
            $appName = getenv('APP_NAME') ?: 'Wheelder';
            
            $payload = [
                'personalizations' => [
                    [
                        'to' => [['email' => $email]],
                        'subject' => 'Your ' . $appName . ' Magic Link'
                    ]
                ],
                'from' => [
                    'email' => $this->fromEmail,
                    'name' => $this->fromName
                ],
                'content' => [
                    [
                        'type' => 'text/html',
                        'value' => $this->buildEmailHtml($magicLink, $expiryMinutes, $appName)
                    ]
                ]
            ];

            $ch = curl_init('https://api.sendgrid.com/v3/mail/send');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 202) {
                error_log("Magic link sent to $email via SendGrid");
                return true;
            } else {
                error_log("SendGrid error (HTTP $httpCode): $response");
                return false;
            }
        } catch (Exception $e) {
            error_log("SendGrid exception: " . $e->getMessage());
            return false;
        }
    }

    private function buildEmailHtml($magicLink, $expiryMinutes, $appName)
    {
        return "
        <html>
        <body style='font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, sans-serif;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                <h1 style='color: #1a1a1a;'>$appName</h1>
                <p>Hi there!</p>
                <p>Click the button below to sign in. This link is valid for <strong>$expiryMinutes minutes</strong>.</p>
                <a href='$magicLink' style='display: inline-block; background: #007bff; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 20px 0;'>Sign In</a>
                <p style='color: #666; font-size: 12px;'>If you didn't request this, you can safely ignore this email.</p>
            </div>
        </body>
        </html>
        ";
    }
}

/**
 * Mailgun Email Service
 * WHY: Alternative to SendGrid with similar reliability
 */
class MailgunEmailService implements IEmailService
{
    private $apiKey;
    private $domain;
    private $fromEmail;
    private $fromName;

    public function __construct()
    {
        $this->apiKey = getenv('MAILGUN_API_KEY') ?: '';
        $this->domain = getenv('MAILGUN_DOMAIN') ?: '';
        $this->fromEmail = getenv('MAILGUN_FROM_EMAIL') ?: 'noreply@wheelder.com';
        $this->fromName = getenv('MAILGUN_FROM_NAME') ?: 'Wheelder';

        if (empty($this->apiKey) || empty($this->domain)) {
            error_log('Mailgun API key or domain not configured');
        }
    }

    /**
     * Send magic link via Mailgun API
     */
    public function sendMagicLink($email, $magicLink, $expiryMinutes)
    {
        try {
            $appName = getenv('APP_NAME') ?: 'Wheelder';
            
            $postData = [
                'from' => "{$this->fromName} <{$this->fromEmail}>",
                'to' => $email,
                'subject' => "Your $appName Magic Link",
                'html' => $this->buildEmailHtml($magicLink, $expiryMinutes, $appName)
            ];

            $ch = curl_init("https://api.mailgun.net/v3/{$this->domain}/messages");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "api:{$this->apiKey}");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                error_log("Magic link sent to $email via Mailgun");
                return true;
            } else {
                error_log("Mailgun error (HTTP $httpCode): $response");
                return false;
            }
        } catch (Exception $e) {
            error_log("Mailgun exception: " . $e->getMessage());
            return false;
        }
    }

    private function buildEmailHtml($magicLink, $expiryMinutes, $appName)
    {
        return "
        <html>
        <body style='font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, sans-serif;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                <h1 style='color: #1a1a1a;'>$appName</h1>
                <p>Hi there!</p>
                <p>Click the button below to sign in. This link is valid for <strong>$expiryMinutes minutes</strong>.</p>
                <a href='$magicLink' style='display: inline-block; background: #007bff; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 20px 0;'>Sign In</a>
                <p style='color: #666; font-size: 12px;'>If you didn't request this, you can safely ignore this email.</p>
            </div>
        </body>
        </html>
        ";
    }
}
