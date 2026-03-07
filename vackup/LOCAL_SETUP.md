# Vackup Local Version Setup Guide

## Overview

This guide helps you set up Vackup on your local development machine (Windows/XAMPP) to backup your local projects.

---

## Prerequisites

- XAMPP installed with PHP 8.0+ and PDO extension
- Local Wheelder installation at `C:\xampp\htdocs\wheelder`
- OneDrive and/or Google Drive desktop apps (optional, for cloud sync)

---

## Setup Steps

### 1. Database Setup

The database is already created. If you need to reset it:

**Visit:** `http://localhost/wheelder/vackup/setup?action=cr`

This creates the SQLite database at:
```
C:\xampp\htdocs\wheelder\vackup\config\vackup.db
```

### 2. Configure Default Storage Paths

**Visit:** `http://localhost/wheelder/vackup/settings`

Set your default storage locations:

**Local Storage Path:**
```
C:\Users\abdul\OneDrive\Services-Projects\Vackups
```

**OneDrive Path (optional):**
```
C:\Users\abdul\OneDrive\Services-Projects\Vackups
```

**Google Drive Path (optional):**
```
C:\Users\abdul\Google Drive\Vackups
```

### 3. Create Your First Project

**Visit:** `http://localhost/wheelder/vackup/projects/new`

**Example Project Configuration:**

**Project Name:** `wheelder-dev`

**Project Path:**
```
C:\xampp\htdocs\wheelder
```

**Description:**
```
Wheelder platform - local development version
```

**Local Storage Path:**
```
C:\Users\abdul\OneDrive\Services-Projects\Vackups
```

**Auto-copy to OneDrive:** ✓ (checked)

**Exclude Patterns:**
```
node_modules
.git
vendor
*.log
.env
vackup/config/vackup.db
```

### 4. Create Your First Vackup

**Visit:** `http://localhost/wheelder/vackup`

1. Select your project from the sidebar
2. Enter version: `1.0`
3. Enter label: `Initial local setup`
4. Add release notes (optional)
5. Click **"Create Vackup"**

---

## Local Project Examples

### Example 1: Wheelder Platform
```
Name: wheelder-dev
Path: C:\xampp\htdocs\wheelder
Storage: C:\Users\abdul\OneDrive\Services-Projects\Vackups
```

### Example 2: Autowork Agent
```
Name: autowork-beta
Path: C:\Users\abdul\OneDrive\Services-Projects\Autowork-agent
Storage: C:\Users\abdul\OneDrive\Services-Projects\Vackups
```

### Example 3: Custom Project
```
Name: my-project
Path: C:\Projects\my-project
Storage: C:\Users\abdul\OneDrive\Services-Projects\Vackups
```

---

## Cloud Sync Setup

### OneDrive Sync
1. Install OneDrive desktop app
2. Ensure `C:\Users\abdul\OneDrive` is syncing
3. Vackup will copy files to `OneDrive\Services-Projects\Vackups`
4. Files automatically sync to cloud

### Google Drive Sync
1. Install Google Drive desktop app
2. Configure sync folder at `C:\Users\abdul\Google Drive`
3. Enable auto-copy in project settings
4. Files automatically sync to cloud

---

## Naming Convention

Vackups are named:
```
{project}-v{version}-{label}.zip
```

Examples:
- `wheelder-dev-v1.0-Initial-local-setup.zip`
- `autowork-beta-v7.0-Landing-page-added.zip`
- `my-project-v2.5-Bug-fixes-completed.zip`

---

## Version Numbering

**Minor Updates (0.1, 0.2, 1.1, 1.2):**
- Bug fixes
- Small features
- UI tweaks

**Major Updates (1.0, 2.0, 3.0):**
- New major features
- Breaking changes
- Major milestones

---

## GitHub Integration (Optional)

To auto-create GitHub releases:

1. Generate a Personal Access Token:
   - Go to: https://github.com/settings/tokens
   - Click "Generate new token (classic)"
   - Select scope: `repo` (full control)
   - Copy the token

2. Add to project settings:
   - **GitHub Repository:** `username/repo-name`
   - **GitHub Token:** `ghp_xxxxxxxxxxxxx`
   - **Auto-push to GitHub:** ✓ (checked)

3. When you create a vackup, it will:
   - Create a GitHub release
   - Tag it with version (e.g., `v1.0`)
   - Add your release notes

---

## Troubleshooting

### Issue: "Permission denied" when creating vackup
**Solution:** Ensure storage directory is writable:
```
Right-click folder → Properties → Security → Edit → Allow "Full control"
```

### Issue: "Project directory not found"
**Solution:** Verify project path exists and is accessible:
```
Check: C:\xampp\htdocs\wheelder (or your project path)
```

### Issue: OneDrive files not syncing
**Solution:** 
1. Check OneDrive is running (system tray icon)
2. Verify folder is inside OneDrive sync directory
3. Check OneDrive settings → Account → Choose folders

---

## Best Practices

1. **Regular Backups:** Create vackups after completing features
2. **Meaningful Labels:** Use descriptive labels (e.g., "Auth system completed")
3. **Release Notes:** Document what changed in each version
4. **Version Incrementing:** Follow semantic versioning
5. **Cloud Sync:** Enable OneDrive/Google Drive for automatic cloud backup

---

## File Structure

```
C:\xampp\htdocs\wheelder\vackup\
├── config\
│   ├── vackup.db          # SQLite database (local projects)
│   ├── database.php       # Database connection
│   ├── config.php         # Configuration
│   └── setup.php          # Database migrations
├── includes\
│   ├── VackupEngine.php   # Core backup logic
│   ├── GitHubClient.php   # GitHub integration
│   ├── StorageManager.php # Multi-storage handler
│   └── NoteGenerator.php  # Release notes
├── index.php              # Dashboard
├── projects.php           # Project management
├── settings.php           # Global settings
└── LOCAL_SETUP.md         # This file
```

---

## URLs

- **Dashboard:** `http://localhost/wheelder/vackup`
- **Projects:** `http://localhost/wheelder/vackup/projects`
- **New Project:** `http://localhost/wheelder/vackup/projects/new`
- **Settings:** `http://localhost/wheelder/vackup/settings`
- **History:** `http://localhost/wheelder/vackup/history`

---

## Differences: Local vs Web Version

| Feature | Local Version | Web Version |
|---------|--------------|-------------|
| URL | `localhost/wheelder/vackup` | `wheelder.com/vackup` |
| File Access | `C:\xampp\htdocs\...` | `/var/www/...` |
| Database | `vackup.db` (local) | `vackup.db` (production) |
| Projects | Local development projects | Production projects |
| Storage | Local + OneDrive/GDrive sync | Server storage |
| Use Case | Development backups | Production backups |

**Important:** These are **separate installations** with **separate databases**. Projects created in one won't appear in the other.

---

## Next Steps

1. ✅ Complete database setup
2. ✅ Configure storage paths
3. ✅ Create first project
4. ✅ Create first vackup
5. ✅ Verify backup in storage folder
6. ✅ Check OneDrive sync (if enabled)

---

**Need Help?** Check the main README at `vackup/README.md`
