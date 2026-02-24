# Magic Link Authentication Setup Guide

## Overview

This guide explains how to set up passwordless authentication using magic links for the Wheelder platform. Magic links replace traditional password-based login with secure, time-limited email tokens.

## Architecture

### Components

1. **MagicLinkController** (`pool/libs/controllers/MagicLinkController.php`)
   - Generates secure tokens
   - Stores tokens in database
   - Validates tokens and creates sessions
   - Cleans up expired tokens

2. **Email Services** (`pool/libs/services/EmailService.php`)
   - SMTP (default, works with DigitalOcean)
   - SendGrid API
   - Mailgun API
   - Pluggable interface for adding more providers

3. **Endpoints**
   - `/auth/magic-link-request` — POST endpoint to request magic link
   - `/auth/verify?token=...` — GET endpoint to verify token and create session
   - `/login` — Updated login page with email-only form

4. **Database**
   - `magic_links` table — Stores tokens, expiration, usage tracking

## Setup Instructions

### Step 1: Database Migration

Run the database migration to create the `magic_links` table:

```
Visit: http://localhost/sqlite_setup?action=cr
```

This will create the `magic_links` table with the following schema:

```sql
CREATE TABLE magic_links (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    email TEXT NOT NULL,
    token TEXT NOT NULL UNIQUE,
    expires_at TEXT NOT NULL,
    used INTEGER DEFAULT 0,
    used_at TEXT,
    ip_address TEXT,
    user_agent TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
)
```

### Step 2: Configure Environment Variables

Copy `.env.example` to `.env` and configure your email service:

```bash
cp .env.example .env
```

Edit `.env` with your actual values.

### Step 3: Choose Email Provider

#### Option A: SMTP (Recommended for DigitalOcean)

```env
EMAIL_PROVIDER=smtp
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USERNAME=your-email@gmail.com
SMTP_PASSWORD=your-app-password
SMTP_FROM_EMAIL=noreply@wheelder.com
SMTP_FROM_NAME=Wheelder
```

**For Gmail:**
1. Enable 2-Factor Authentication
2. Generate App Password: https://myaccount.google.com/apppasswords
3. Use the 16-character password in `SMTP_PASSWORD`

**For DigitalOcean App Platform:**
- Use your SMTP credentials from DigitalOcean's email service
- Set `SMTP_HOST` and `SMTP_PORT` accordingly

#### Option B: SendGrid

```env
EMAIL_PROVIDER=sendgrid
SENDGRID_API_KEY=SG.xxxxxxxxxxxxx
SENDGRID_FROM_EMAIL=noreply@wheelder.com
SENDGRID_FROM_NAME=Wheelder
```

Get API key: https://app.sendgrid.com/settings/api_keys

#### Option C: Mailgun

```env
EMAIL_PROVIDER=mailgun
MAILGUN_API_KEY=key-xxxxxxxxxxxxx
MAILGUN_DOMAIN=mg.wheelder.com
MAILGUN_FROM_EMAIL=noreply@wheelder.com
MAILGUN_FROM_NAME=Wheelder
```

Get API key: https://app.mailgun.com/app/account/security/api_keys

### Step 4: Load Environment Variables

Add this to your PHP bootstrap (e.g., `top.php` or `index.php`):

```php
// Load .env file
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            putenv(trim($key) . '=' . trim($value));
        }
    }
}
```

## How It Works

### User Login Flow

1. **User visits `/login`**
   - Sees email-only form (no password field)
   - Enters email address

2. **User submits email**
   - JavaScript sends POST to `/auth/magic-link-request`
   - Server validates email
   - Server generates 256-bit secure token
   - Server stores token in `magic_links` table with 15-minute expiration
   - Server sends email with magic link

3. **User receives email**
   - Email contains clickable link: `/auth/verify?token=...`
   - Link is valid for 15 minutes
   - User clicks link

4. **User clicks magic link**
   - Browser visits `/auth/verify?token=...`
   - Server validates token:
     - Checks token exists in database
     - Checks token hasn't expired
     - Checks token hasn't been used
   - Server marks token as used (prevents replay attacks)
   - Server creates authenticated session
   - Server redirects to `/dashboard`

### Security Features

| Feature | Purpose |
|---------|---------|
| **256-bit tokens** | Cryptographically secure, impossible to guess |
| **15-minute expiration** | Limits window for token interception |
| **One-time use** | Tokens marked as used after verification, prevents replay |
| **IP tracking** | Logs IP address for audit trail |
| **User-Agent tracking** | Logs browser for suspicious activity detection |
| **Rate limiting** | Max 5 requests per IP per 15 minutes |
| **CSRF protection** | Login form includes CSRF token |
| **Session regeneration** | New session ID after authentication prevents fixation |
| **HttpOnly cookies** | Session cookies not accessible to JavaScript |

## API Reference

### POST /auth/magic-link-request

**Request:**
```
POST /auth/magic-link-request
Content-Type: application/x-www-form-urlencoded

email=user@example.com
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Magic link sent to user@example.com. Check your inbox (valid for 15 minutes).",
  "email": "user@example.com"
}
```

**Error Response (400):**
```json
{
  "success": false,
  "error": "Invalid email address."
}
```

**Rate Limited Response (429):**
```json
{
  "error": "Too many requests. Please wait 15 minutes before trying again."
}
```

### GET /auth/verify?token=...

**Request:**
```
GET /auth/verify?token=abc123def456...
```

**Success Response:**
- Redirects to `/dashboard` with authenticated session

**Error Response:**
- Shows error page with message:
  - "No token provided."
  - "Link not found or already used."
  - "Link has expired. Request a new one."
  - "User not found."

## Database Queries

### View pending magic links
```sql
SELECT * FROM magic_links WHERE used = 0 AND expires_at > datetime('now');
```

### View used magic links
```sql
SELECT * FROM magic_links WHERE used = 1;
```

### Clean up expired tokens (run via cron)
```sql
DELETE FROM magic_links WHERE expires_at < datetime('now') AND used = 0;
```

## Maintenance

### Periodic Cleanup

Add this to a cron job (runs daily):

```php
<?php
require_once 'pool/libs/controllers/MagicLinkController.php';
$magicLink = new MagicLinkController();
$magicLink->cleanupExpiredTokens();
echo "Cleaned up expired magic links\n";
?>
```

### Monitoring

Check for suspicious activity:

```sql
-- Find IPs with many failed attempts
SELECT ip_address, COUNT(*) as attempts 
FROM magic_links 
WHERE created_at > datetime('now', '-1 hour')
GROUP BY ip_address 
HAVING COUNT(*) > 10;
```

## Troubleshooting

### Emails not sending

1. **Check environment variables:**
   ```bash
   php -r "echo getenv('SMTP_HOST');"
   ```

2. **Check email service logs:**
   - Gmail: https://myaccount.google.com/security
   - SendGrid: https://app.sendgrid.com/email_activity
   - Mailgun: https://app.mailgun.com/app/logs

3. **Test SMTP connection:**
   ```php
   $host = getenv('SMTP_HOST');
   $port = getenv('SMTP_PORT');
   $conn = @fsockopen($host, $port, $errno, $errstr, 5);
   if ($conn) {
       echo "SMTP connection successful\n";
       fclose($conn);
   } else {
       echo "SMTP connection failed: $errstr\n";
   }
   ```

### Token validation fails

1. **Check token expiration:**
   ```sql
   SELECT token, expires_at, datetime('now') as now 
   FROM magic_links 
   WHERE token = 'your-token';
   ```

2. **Check if token was already used:**
   ```sql
   SELECT used, used_at FROM magic_links WHERE token = 'your-token';
   ```

3. **Check database connection:**
   - Verify `wheelder.db` file exists
   - Check file permissions (should be readable/writable)

### Session not created

1. **Check session configuration:**
   ```php
   echo ini_get('session.save_path');
   echo ini_get('session.cookie_httponly');
   ```

2. **Check user exists in database:**
   ```sql
   SELECT id, email FROM users WHERE email = 'user@example.com';
   ```

## Migration from Password-Based Login

### For Existing Users

1. **Keep password field in database** (for backward compatibility)
2. **Update login page** to use magic links (done)
3. **Disable password login** in `pool/api/logsAPI.php`:
   ```php
   // Comment out or remove the password login handler
   // if (isset($_POST['login'])) { ... }
   ```

4. **Optional: Send reset emails to users**
   - Notify users of new passwordless login
   - No action needed from users

### For New Users

- New users created via magic link automatically
- No password field needed

## Advanced Configuration

### Custom Email Templates

Edit `pool/libs/services/EmailService.php` and modify the `buildEmailHtml()` methods to customize email appearance.

### Custom Token Length

In `MagicLinkController.php`:
```php
private $tokenLength = 32;  // Change to 64 for 512-bit tokens
```

### Custom Expiration Time

In `MagicLinkController.php`:
```php
private $expiryMinutes = 15;  // Change to 30 for 30-minute links
```

### Adding New Email Providers

1. Create new class implementing `IEmailService`:
   ```php
   class MyEmailService implements IEmailService {
       public function sendMagicLink($email, $magicLink, $expiryMinutes) {
           // Implementation
       }
   }
   ```

2. Register in `MagicLinkController::initEmailService()`:
   ```php
   if ($provider === 'myservice') {
       $this->emailService = new MyEmailService();
   }
   ```

## Testing

### Manual Test

1. Visit `http://localhost/login`
2. Enter email: `test@example.com`
3. Click "Send Magic Link"
4. Check email for link
5. Click link in email
6. Should be redirected to dashboard with session

### Automated Test

```php
<?php
require_once 'pool/libs/controllers/MagicLinkController.php';

$magicLink = new MagicLinkController();

// Test 1: Request magic link
$result = $magicLink->requestMagicLink('test@example.com');
assert($result['success'] === true);
echo "✓ Magic link requested\n";

// Test 2: Verify token (would need to extract from email in real test)
// $result = $magicLink->verifyMagicLink($token);
// assert($result['success'] === true);
// echo "✓ Magic link verified\n";

echo "All tests passed!\n";
?>
```

## Support

For issues or questions:
1. Check error logs: `error.log`
2. Check email service logs
3. Review database for token records
4. Enable debug logging in `MagicLinkController.php`

## References

- [OWASP Passwordless Authentication](https://owasp.org/www-community/Passwordless_Authentication)
- [Magic Links Best Practices](https://www.ory.sh/magic-links-passwordless-authentication/)
- [DigitalOcean App Platform Email](https://docs.digitalocean.com/products/app-platform/)
