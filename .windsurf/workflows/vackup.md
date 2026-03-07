---
description: How to use Vackup (Version Control + Backup) platform
---

# Vackup Workflow

Vackup is a Progressive Development system that combines version control with automated backups.

## Quick Start

1. **Setup Database**
   - Navigate to `/vackup/setup?action=cr` to create the SQLite database tables

2. **Create a Project**
   - Go to `/vackup/projects/new`
   - Enter project name and path (e.g., `C:\Projects\myapp`)
   - Configure storage destinations (Local, OneDrive, Google Drive)
   - Optionally add GitHub repo and token for auto-releases

3. **Create a Vackup**
   - Select project from dashboard
   - Enter version (auto-incremented, e.g., `1.0`, `1.1`)
   - Add label describing the feature/update
   - Click "Create Vackup"

## Naming Convention

Vackups follow this pattern:
```
{project}-v{version}-{label}.zip
```

Example: `autowork-beta-v7.0-Landing page added to platform.zip`

## Storage Destinations

- **Local**: Primary storage (default: OneDrive/Vackups folder)
- **OneDrive**: Auto-syncs via folder path
- **Google Drive**: Auto-syncs via folder path  
- **GitHub**: Creates release with version tag

## Routes

- `/vackup` - Dashboard
- `/vackup/projects` - Manage projects
- `/vackup/projects/new` - Create new project
- `/vackup/history` - View all vackups
- `/vackup/settings` - Global settings
