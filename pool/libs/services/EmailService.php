<?php
// WHY: EmailService is loaded before MagicLinkController, so bootstrap .env first to access SMTP_* settings reliably.
$envLoaderPath = dirname(__DIR__, 2) . '/config/env_loader.php';
if (file_exists($envLoaderPath)) {
    require_once $envLoaderPath;
} else {
    // Fail fast so deployers know env vars were never loaded — prevents silent empty credentials.
    throw new RuntimeException('Env loader missing at ' . $envLoaderPath . '; cannot read SMTP credentials.');
}

// WHY: Composer autoload is required so PHPMailer classes can be discovered anywhere in the app tree.
$mailAutoload = dirname(__DIR__, 3) . '/vendor/autoload.php';
if (file_exists($mailAutoload)) {
    require_once $mailAutoload;
} else {
    throw new RuntimeException('Composer autoload missing at ' . $mailAutoload . ' — run composer install on this server.');
}

if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
    throw new RuntimeException('PHPMailer library not available — ensure composer install completed successfully.');
}

use PHPMailer\PHPMailer\Exception as PHPMailerException;
use PHPMailer\PHPMailer\PHPMailer;
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
            if (empty($this->username) || empty($this->password)) {
                // WHY: Gmail (and most SMTP providers) reject unauthenticated relays — fail fast with a diagnosable log.
                error_log('SMTP credentials missing; aborting magic link send.');
                return false;
            }

            $mailer = new PHPMailer(true);
            $mailer->isSMTP();
            $mailer->Host = $this->host;
            $mailer->Port = (int) $this->port;
            $mailer->SMTPAuth = true;
            $mailer->Username = $this->username;
            $mailer->Password = $this->password;
            // WHY: Google SMTP on port 587 requires STARTTLS; allow env override to disable only if explicitly set.
            $mailer->SMTPSecure = getenv('SMTP_SECURE') ?: PHPMailer::ENCRYPTION_STARTTLS;
            $mailer->CharSet = 'UTF-8';
            // WHY: Default PHPMailer timeout is 300s — if the SMTP port is blocked the request hangs until the browser gives up,
            // producing a misleading "Network error". A short timeout lets PHP return a proper JSON error instead.
            $mailer->Timeout = 10;

            $mailer->setFrom($this->fromEmail, $this->fromName);
            $mailer->addAddress($email);
            $mailer->Subject = 'Your Wheelder Magic Link';
            $mailer->isHTML(true);

            $bodyHtml = $this->buildEmailHtml($magicLink, $expiryMinutes);
            $mailer->Body = $bodyHtml;
            $mailer->AltBody = strip_tags($bodyHtml);

            $mailer->send();
            error_log("Magic link sent to $email via authenticated SMTP");
            return true;
        } catch (PHPMailerException $e) {
            // WHY: Connection-timeout errors usually mean the hosting provider blocks outbound SMTP ports.
            error_log('PHPMailer exception while sending magic link: ' . $e->getMessage());
            if (stripos($e->getMessage(), 'Could not connect') !== false) {
                error_log('SMTP hint: outbound port ' . $this->port . ' may be blocked by the hosting provider.');
            }
            return false;
        } catch (Exception $e) {
            error_log('SMTP runtime error: ' . $e->getMessage());
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

/**
 * Resend Email Service
 * WHY: Modern email API with high deliverability and simple integration (https://resend.com)
 */
class ResendEmailService implements IEmailService
{
    private $apiKey;
    private $fromEmail;
    private $fromName;

    public function __construct()
    {
        // WHY: read from env so the API key is never committed to source control
        $this->apiKey    = getenv('RESEND_API_KEY') ?: '';
        $this->fromEmail = getenv('RESEND_FROM_EMAIL') ?: 'onboarding@resend.dev';
        $this->fromName  = getenv('RESEND_FROM_NAME') ?: 'Wheelder';

        if (empty($this->apiKey)) {
            error_log('Resend API key not configured in RESEND_API_KEY env var');
        }
    }

    /**
     * Send magic link via Resend API
     * WHY: Single POST to https://api.resend.com/emails — no SDK required
     */
    public function sendMagicLink($email, $magicLink, $expiryMinutes)
    {
        try {
            if (empty($this->apiKey)) {
                error_log('Resend: API key missing — cannot send magic link.');
                return false;
            }

            $appName = getenv('APP_NAME') ?: 'Wheelder';

            $payload = json_encode([
                'from'    => "{$this->fromName} <{$this->fromEmail}>",
                'to'      => [$email],
                'subject' => "Your $appName Magic Link",
                'html'    => $this->buildEmailHtml($magicLink, $expiryMinutes, $appName)
            ]);

            $ch = curl_init('https://api.resend.com/emails');
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $payload,
                CURLOPT_HTTPHEADER     => [
                    'Authorization: Bearer ' . $this->apiKey,
                    'Content-Type: application/json'
                ],
                // WHY: short timeout so the user isn't stuck waiting if the API is down
                CURLOPT_TIMEOUT        => 15,
                CURLOPT_CONNECTTIMEOUT => 10
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if (curl_errno($ch)) {
                error_log('Resend cURL error: ' . curl_error($ch));
                curl_close($ch);
                return false;
            }
            curl_close($ch);

            // WHY: Resend returns HTTP 200 on success with a JSON body containing the email id
            if ($httpCode === 200) {
                error_log("Magic link sent to $email via Resend");
                return true;
            } else {
                error_log("Resend error (HTTP $httpCode): $response");
                return false;
            }
        } catch (Exception $e) {
            error_log('Resend exception: ' . $e->getMessage());
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
