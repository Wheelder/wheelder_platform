# Wheelder Platform - Deployment Guide

## Overview
This guide explains how to deploy the Wheelder platform to a production server, including the new login2 secondary authentication system.

## Prerequisites
- Ubuntu/Debian Linux server
- nginx web server
- PHP 8.0+ with PHP-FPM
- Git
- SQLite3 (for database)

## Production Deployment Steps

### 1. Clone Repository
```bash
cd /var/www
git clone https://github.com/abbaays/wheelder_platform.git wheelder
cd wheelder
```

### 2. Set Permissions
```bash
# Set proper permissions for web server
sudo chown -R www-data:www-data /var/www/wheelder
sudo chmod -R 755 /var/www/wheelder
sudo chmod -R 775 /var/www/wheelder/pool/config  # For database files
```

### 3. Configure nginx

Copy the nginx configuration from `nginx.conf` to your nginx sites-available directory:

```bash
sudo cp /var/www/wheelder/nginx.conf /etc/nginx/sites-available/wheelder
sudo ln -s /etc/nginx/sites-available/wheelder /etc/nginx/sites-enabled/wheelder
```

Edit `/etc/nginx/sites-available/wheelder` and update:
- `server_name` to your domain (e.g., `wheelder.com www.wheelder.com`)
- `root` to `/var/www/wheelder`
- `fastcgi_pass` to match your PHP-FPM socket (usually `/var/run/php-fpm.sock` or `/var/run/php8.2-fpm.sock`)
- SSL certificate paths if using HTTPS

### 4. Test nginx Configuration
```bash
sudo nginx -t
```

### 5. Reload nginx
```bash
sudo systemctl reload nginx
```

### 6. Initialize Database
```bash
cd /var/www/wheelder
php setup_login2_password.php
```

This will:
- Create the `password` column in the `users` table
- Create the `remember_tokens` table
- Create the `password_reset_tokens` table
- Set the initial password for `regrowup2025@gmail.com`

### 7. Set Up Automatic Deployments

The `deploy.php` script allows automatic deployments via webhook. To use it:

1. Update the `$TOKEN` in `deploy.php` to a strong random string:
```php
$TOKEN = "your-strong-random-token-here";
```

2. Create a webhook on GitHub:
   - Go to Settings → Webhooks
   - Add webhook URL: `https://wheelder.com/deploy.php?token=your-strong-random-token-here`
   - Select "Just the push event"
   - Ensure "Active" is checked

3. Or manually trigger deployment:
```bash
curl "https://wheelder.com/deploy.php?token=your-strong-random-token-here"
```

## New Features - Login2 System

### Overview
The login2 system provides password-based authentication as an alternative to magic links.

**Hardcoded Email:** `regrowup2025@gmail.com`  
**Initial Password:** `Wheelder@2025!Secure`

### Routes
- `/login2` - Password login page
- `/password-reset` - Password reset page
- `/api/login2` - Login API endpoint
- `/api/password-reset-request` - Password reset request API
- `/api/password-reset` - Password reset confirmation API

### Features
- ✓ Password-based authentication
- ✓ "Remember Me" functionality (30 days)
- ✓ Password reset with email verification
- ✓ SQL injection protection (prepared statements)
- ✓ XSS protection (input escaping)
- ✓ CSRF protection (token validation)
- ✓ Rate limiting (failed login attempts)
- ✓ Secure password hashing (bcrypt, cost=12)
- ✓ Session fixation prevention (ID regeneration)

### Database Tables
The following tables are automatically created:

1. **users** (modified)
   - Added `password` column for bcrypt hashes

2. **remember_tokens**
   - Stores hashed remember-me tokens
   - 30-day expiration
   - IP address and user agent tracking

3. **password_reset_tokens**
   - Stores hashed password reset tokens
   - 60-minute expiration
   - Marked as used after reset

### Security Measures

#### SQL Injection Prevention
All database queries use prepared statements with parameterized queries:
```php
$stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param('s', $email);
$stmt->execute();
```

#### XSS Prevention
All user input is escaped before output:
```php
echo htmlspecialchars($userInput, ENT_QUOTES, 'UTF-8');
```

#### CSRF Protection
All forms include CSRF tokens that are validated server-side:
```php
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    exit;
}
```

#### Rate Limiting
Failed login attempts are tracked by IP address:
- 5 failed attempts = 15-minute lockout
- Password reset requests limited to 3 per hour per IP

#### Password Security
- Minimum 8 characters
- Hashed with bcrypt (cost=12)
- Never stored in plain text
- Reset tokens are hashed before storage

## Testing the Login2 System

### Test on Production
1. Navigate to `https://wheelder.com/login2`
2. Email: `regrowup2025@gmail.com` (pre-filled, read-only)
3. Password: `Wheelder@2025!Secure`
4. Click "Sign In"
5. Should redirect to `/dashboard`

### Test Password Reset
1. Navigate to `https://wheelder.com/password-reset`
2. Click "Send Reset Link"
3. Check email (or development log) for reset link
4. Follow reset link and set new password
5. Log in with new password

### Test Remember Me
1. Log in with "Remember me for 30 days" checked
2. Close browser
3. Return to site
4. Should be automatically logged in

## Troubleshooting

### 404 Errors on Routes
**Issue:** Routes like `/login2` return 404  
**Solution:** Ensure nginx is configured to route all requests to `index.php` using the `try_files` directive

### Database Errors
**Issue:** "Database connection failed"  
**Solution:** 
1. Ensure SQLite database file has proper permissions:
   ```bash
   sudo chmod 666 /var/www/wheelder/pool/config/wheelder.db
   ```
2. Check that PHP has write access to the database directory

### Email Not Sending
**Issue:** Password reset emails not received  
**Solution:**
1. In development mode, reset links are logged to `error.log`
2. In production, ensure mail server is configured:
   ```bash
   sudo apt-get install postfix
   ```
3. Check mail logs:
   ```bash
   tail -f /var/log/mail.log
   ```

### PHP-FPM Socket Error
**Issue:** "connect() to unix:/var/run/php-fpm.sock failed"  
**Solution:**
1. Find the correct PHP-FPM socket:
   ```bash
   find /var/run -name "php*-fpm.sock"
   ```
2. Update nginx configuration with correct socket path
3. Reload nginx:
   ```bash
   sudo systemctl reload nginx
   ```

## Monitoring

### Check Application Logs
```bash
tail -f /var/www/wheelder/error.log
```

### Monitor nginx Access
```bash
tail -f /var/log/nginx/access.log
```

### Monitor PHP-FPM
```bash
sudo systemctl status php-fpm
```

## Updating the Application

### Pull Latest Changes
```bash
cd /var/www/wheelder
git pull origin main
```

### Automatic Deployment via Webhook
The `deploy.php` script will automatically run `git pull` when triggered.

### Manual Deployment
```bash
curl "https://wheelder.com/deploy.php?token=your-token-here"
```

## Security Checklist

- [ ] Update `$TOKEN` in `deploy.php` to a strong random string
- [ ] Enable HTTPS with valid SSL certificate
- [ ] Set proper file permissions (755 for directories, 644 for files)
- [ ] Disable directory listing in nginx
- [ ] Set strong database password if using MySQL
- [ ] Configure firewall to allow only necessary ports (80, 443)
- [ ] Enable automatic security updates
- [ ] Set up log rotation for application logs
- [ ] Monitor failed login attempts
- [ ] Regularly backup database

## Support

For issues or questions, refer to:
- Application logs: `/var/www/wheelder/error.log`
- nginx logs: `/var/log/nginx/access.log`
- GitHub Issues: https://github.com/abbaays/wheelder_platform/issues

---

**Last Updated:** 2026-02-26  
**Version:** 1.0  
**Status:** Production Ready
