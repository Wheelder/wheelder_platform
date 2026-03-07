# Vackup Platform - Complete Technical Briefing for Claude Code

## Executive Summary

**Product Name:** Vackup (Version Control + Backup)  
**Purpose:** Automated version control and backup system for project management  
**Current Status:** Production deployment with persistent OPcache bug on settings page  
**Priority Issue:** `Fatal error: Call to undefined function getSetting()` on production server

---

## Critical Bug Details

### **Primary Issue**
- **Error:** `Fatal error: Uncaught Error: Call to a member function prepare() on null in /var/www/environments/production/wheelder.com/vackup/settings.php:35`
- **Root Cause:** OPcache serving old cached version of `settings.php` despite file updates
- **Affected URLs:** 
  - `https://wheelder.com/vackup/settings`
  - `https://wheelder.com/vackup?project=1`

### **What We've Tried**
1. ✅ Refactored `settings.php` to define functions before use
2. ✅ Restarted PHP-FPM service multiple times
3. ✅ Restarted Nginx
4. ✅ Used `touch` to update file timestamp
5. ✅ Git reset --hard to force file update
6. ❌ OPcache still serving old version

### **File Status**
- Local file: Correct (functions defined at top)
- Production file: Correct (verified via SSH cat/sed)
- Production cache: **WRONG** (serving old version)

---

## Architecture Overview

### **Technology Stack**
- **Backend:** PHP 8.3+ with PDO
- **Database:** SQLite (separate `vackup.db` per environment)
- **Frontend:** Bootstrap 5, Font Awesome
- **Version Control:** Git (GitHub integration via API)
- **Cloud Storage:** OneDrive/Google Drive (folder sync)
- **Web Server:** Nginx + PHP-FPM
- **Caching:** OPcache (causing current issue)

### **Deployment Environments**

#### **Local Development**
- **Path:** `C:\xampp\htdocs\wheelder\vackup`
- **URL:** `http://localhost/wheelder/vackup`
- **Database:** `C:\xampp\htdocs\wheelder\vackup\config\vackup.db`
- **OS:** Windows (XAMPP)
- **Purpose:** Backup local development projects

#### **Production**
- **Path:** `/var/www/environments/production/wheelder.com/vackup`
- **URL:** `https://wheelder.com/vackup`
- **Database:** `/var/www/environments/production/wheelder.com/vackup/config/vackup.db`
- **OS:** Linux (DigitalOcean Droplet)
- **Server:** Nginx + PHP 8.3-FPM
- **SSH:** `ssh -i C:\Users\abdul\.ssh\DO-Sandbox root@143.110.237.21`

---

## File Structure

```
vackup/
├── config/
│   ├── vackup.db              # SQLite database (gitignored)
│   ├── database.php           # PDO connection wrapper
│   ├── config.php             # Constants and autoloader
│   └── setup.php              # Database migrations
├── includes/
│   ├── VackupEngine.php       # Core backup/zip logic
│   ├── GitHubClient.php       # GitHub API integration
│   ├── StorageManager.php     # Multi-storage handler
│   └── NoteGenerator.php      # Release notes generator
├── index.php                  # Dashboard (project vackup creation)
├── projects.php               # Project list
├── projects_new.php           # Create new project
├── projects_edit.php          # Edit project
├── history.php                # Global vackup history
├── settings.php               # **BUGGY FILE** - Global settings
├── README.md                  # Main documentation
├── LOCAL_SETUP.md             # Local version setup guide
└── CLAUDE_CODE_BRIEFING.md    # This file
```

---

## Database Schema

### **Tables**

#### **projects**
```sql
CREATE TABLE projects (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE,
    project_path TEXT NOT NULL,
    description TEXT,
    local_storage_path TEXT,
    onedrive_path TEXT,
    gdrive_path TEXT,
    github_repo TEXT,
    github_token TEXT,
    exclude_patterns TEXT,
    auto_copy_local INTEGER DEFAULT 1,
    auto_copy_onedrive INTEGER DEFAULT 0,
    auto_copy_gdrive INTEGER DEFAULT 0,
    auto_push_github INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

#### **vackups**
```sql
CREATE TABLE vackups (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    project_id INTEGER NOT NULL,
    version TEXT NOT NULL,
    label TEXT NOT NULL,
    description TEXT,
    notes TEXT,
    zip_filename TEXT NOT NULL,
    zip_size INTEGER,
    zip_path TEXT,
    files_count INTEGER DEFAULT 0,
    local_copied INTEGER DEFAULT 0,
    onedrive_copied INTEGER DEFAULT 0,
    gdrive_copied INTEGER DEFAULT 0,
    github_pushed INTEGER DEFAULT 0,
    github_release_url TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
);
```

#### **settings**
```sql
CREATE TABLE settings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    key TEXT NOT NULL UNIQUE,
    value TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

---

## Critical Code: settings.php

### **Current (Correct) Version**
```php
<?php
/**
 * Vackup - Global Settings
 * WHY: Manage global configuration for Vackup (storage paths, GitHub tokens)
 */

// WHY: Prevent session conflicts when included from router
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// WHY: Load database connection and config constants
require_once __DIR__ . '/config/config.php';

// WHY: Initialize database connection early to avoid scope issues
try {
    $db = VackupDatabase::getInstance();
} catch (Exception $e) {
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}

$message = '';
$messageType = '';

/**
 * Get setting value from database
 * WHY: Centralized settings retrieval with fallback to default
 * @param string $key Setting key
 * @param mixed $default Default value if not found
 * @return mixed Setting value or default
 */
function getSetting($key, $default = '') {
    global $db;
    try {
        $stmt = $db->prepare("SELECT value FROM settings WHERE key = :key");
        $stmt->bindValue(':key', $key, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['value'] : $default;
    } catch (Exception $e) {
        // WHY: Return default on error to prevent page break
        error_log("getSetting error for key '$key': " . $e->getMessage());
        return $default;
    }
}

/**
 * Set or update setting in database
 * WHY: Upsert pattern - update if exists, insert if new
 * @param string $key Setting key
 * @param string $value Setting value
 * @return bool Success status
 */
function setSetting($key, $value) {
    global $db;
    try {
        $existing = getSetting($key, null);
        if ($existing !== null) {
            $stmt = $db->prepare("UPDATE settings SET value = :value, updated_at = datetime('now') WHERE key = :key");
        } else {
            $stmt = $db->prepare("INSERT INTO settings (key, value) VALUES (:key, :value)");
        }
        $stmt->bindValue(':key', $key, PDO::PARAM_STR);
        $stmt->bindValue(':value', $value, PDO::PARAM_STR);
        return $stmt->execute();
    } catch (Exception $e) {
        error_log("setSetting error for key '$key': " . $e->getMessage());
        return false;
    }
}

// WHY: Handle form submission for saving settings
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = true;
    $success = $success && setSetting('default_local_storage', $_POST['default_local_storage'] ?? '');
    $success = $success && setSetting('default_onedrive_path', $_POST['default_onedrive_path'] ?? '');
    $success = $success && setSetting('default_gdrive_path', $_POST['default_gdrive_path'] ?? '');
    $success = $success && setSetting('github_default_token', $_POST['github_default_token'] ?? '');
    
    if ($success) {
        $message = 'Settings saved successfully';
        $messageType = 'success';
    } else {
        $message = 'Error saving some settings. Check error log.';
        $messageType = 'danger';
    }
}

// WHY: Load current settings for display in form
$settings = [
    'default_local_storage' => getSetting('default_local_storage', DEFAULT_LOCAL_STORAGE),
    'default_onedrive_path' => getSetting('default_onedrive_path', ''),
    'default_gdrive_path' => getSetting('default_gdrive_path', ''),
    'github_default_token' => getSetting('github_default_token', ''),
];
?>
<!DOCTYPE html>
<!-- HTML continues... -->
```

### **Old (Cached) Version Being Served**
The OPcache is serving a version where:
- Functions are defined AFTER they're called
- `$db` is not initialized before function calls
- Line 35 has the error

---

## Routing System

Vackup is integrated into Wheelder's main router at `index.php`:

```php
// Vackup routes
$router->add('/vackup/setup', function() {
    require __DIR__ . '/vackup/config/setup.php';
});

$router->add('/vackup/settings', function() {
    require __DIR__ . '/vackup/settings.php';
});

$router->add('/vackup/projects/new', function() {
    require __DIR__ . '/vackup/projects_new.php';
});

$router->add('/vackup/projects/edit', function() {
    require __DIR__ . '/vackup/projects_edit.php';
});

$router->add('/vackup/projects', function() {
    require __DIR__ . '/vackup/projects.php';
});

$router->add('/vackup/history', function() {
    require __DIR__ . '/vackup/history.php';
});

$router->add('/vackup', function() {
    require __DIR__ . '/vackup/index.php';
});
```

---

## OPcache Configuration

### **Current Settings (Production)**
```
opcache.revalidate_freq = 2
```

### **Issue**
Despite 2-second revalidation, OPcache is not detecting file changes. Possible causes:
1. File timestamp not updating properly
2. OPcache using inode-based caching
3. NFS or network filesystem issues
4. OPcache bug with `require` statements in router

---

## Proposed Solutions

### **Solution 1: Force OPcache Invalidation**
Create a deployment hook that invalidates specific files:
```php
opcache_invalidate('/var/www/environments/production/wheelder.com/vackup/settings.php', true);
```

### **Solution 2: Disable OPcache for Vackup Directory**
Add to PHP-FPM pool config or php.ini:
```ini
opcache.blacklist_filename=/etc/php/8.3/fpm/opcache-blacklist.txt
```

Then add to blacklist:
```
/var/www/environments/production/wheelder.com/vackup/
```

### **Solution 3: Use Alternative Function Scope**
Move functions to a separate included file that's loaded before use:
```php
// vackup/includes/SettingsHelpers.php
require_once __DIR__ . '/includes/SettingsHelpers.php';
```

### **Solution 4: Disable OPcache Validation**
Set `opcache.validate_timestamps = 0` and manually clear cache on deployment.

---

## Deployment Process

### **Git Remotes**
- **origin:** `https://github.com/abbaays/wheelder-dev.git`
- **proof:** `https://github.com/Wheelder/wheelder_platform.git`

### **Deployment Steps**
```bash
# Local
git add .
git commit -m "message"
git push origin main
git push proof main

# Production
ssh -i C:\Users\abdul\.ssh\DO-Sandbox root@143.110.237.21
cd /var/www/environments/production/wheelder.com
git pull origin main
systemctl restart php8.3-fpm
```

---

## Testing Requirements

### **Manual Test Props**

#### **1. Settings Page Load**
- **URL:** `https://wheelder.com/vackup/settings`
- **Expected:** Page loads without errors, shows form
- **Current:** Fatal error on line 35

#### **2. Settings Save**
- **Action:** Fill form, click "Save Settings"
- **Expected:** Success message, values persist
- **Current:** Cannot test (page won't load)

#### **3. Project Vackup Creation**
- **URL:** `https://wheelder.com/vackup?project=1`
- **Expected:** Form loads, can create vackup
- **Current:** Error about project directory not found

---

## User Requirements

### **Code Style**
- Procedural PHP (not OOP except for classes)
- Explicit error handling with try-catch
- Meaningful error messages
- Comments explain WHY, not WHAT
- Minimal changes only
- Follow existing Wheelder patterns

### **Constraints**
- Don't touch anything outside vackup directory
- Don't refactor architecture
- Don't modify unrelated files
- Maintain seamless codebase style

---

## Success Criteria

1. ✅ Settings page loads without errors
2. ✅ Can save and retrieve settings
3. ✅ Can create vackups for projects
4. ✅ OPcache properly invalidates on file changes
5. ✅ No breaking changes to existing functionality

---

## Additional Context

### **Previous Fixes**
1. Migrated from SQLite3 to PDO
2. Added session_status() checks
3. Fixed file permissions on production
4. Added directory validation in VackupEngine

### **Known Working Features**
- Database connection (PDO)
- Project CRUD operations
- Vackup creation (when project path is valid)
- GitHub integration
- Storage management

---

## Contact & Support

**Developer:** Abdul (abbaays)  
**Repository:** https://github.com/abbaays/wheelder-dev  
**Production:** https://wheelder.com  

---

## Next Steps for Claude Code

1. **Diagnose OPcache Issue**
   - Check PHP-FPM configuration
   - Review OPcache settings
   - Identify why file changes aren't detected

2. **Implement Permanent Fix**
   - Choose best solution from proposed options
   - Test on production
   - Document the fix

3. **Verify All Functionality**
   - Settings page loads
   - Settings save/retrieve works
   - Project vackup creation works
   - No regressions

4. **Clean Up**
   - Remove temporary debug files
   - Update documentation
   - Commit final changes

---

**Priority:** HIGH  
**Deadline:** ASAP  
**Impact:** Blocking production use of Vackup platform
