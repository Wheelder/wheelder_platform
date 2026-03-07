# Vackup — Version Control + Backup Platform

**Progressive Development System** built inside Wheelder.

Vackup automates the process of creating versioned backups of your projects, with automatic syncing to multiple storage destinations and GitHub releases.

## Features

- **Semantic Versioning**: Auto-increment versions (v1.0, v1.1, v2.0)
- **Multi-Storage**: Local, OneDrive, Google Drive (folder sync)
- **GitHub Integration**: Auto-create releases with version tags
- **Release Notes**: Markdown-based changelog generation
- **Multi-Project**: Manage multiple projects from one dashboard
- **Progressive Development**: Track your development journey

## Installation

1. **Setup Database**
   ```
   Navigate to: /vackup/setup?action=cr
   ```

2. **Create First Project**
   ```
   Navigate to: /vackup/projects/new
   ```

## Directory Structure

```
/vackup/
├── index.php              # Main dashboard
├── projects.php           # Projects list
├── projects_new.php       # Create project
├── projects_edit.php      # Edit project
├── history.php            # All vackups history
├── settings.php           # Global settings
├── config/
│   ├── config.php         # Configuration
│   ├── database.php       # SQLite connection
│   ├── setup.php          # Database migrations
│   └── vackup.db          # SQLite database
├── includes/
│   ├── VackupEngine.php   # Core backup logic
│   ├── GitHubClient.php   # GitHub API wrapper
│   ├── StorageManager.php # Multi-storage handler
│   └── NoteGenerator.php  # Release notes
└── README.md
```

## Naming Convention

Vackups are named following this pattern:
```
{project}-v{version}-{label}.zip
```

Examples:
- `autowork-beta-v7.0-Landing page added to platform.zip`
- `wheelder-v2.5-Auth system completed.zip`

## Storage Destinations

| Storage | Method | Auto-Sync |
|---------|--------|-----------|
| Local | Direct file copy | Yes |
| OneDrive | Folder path (syncs via OneDrive app) | Yes |
| Google Drive | Folder path (syncs via Drive app) | Yes |
| GitHub | API release creation | Yes |

## GitHub Integration

Uses Personal Access Token (simple, no OAuth):
1. Generate token at: https://github.com/settings/tokens
2. Required scopes: `repo` (full control)
3. Add token to project settings

## API

The platform uses form-based submissions. Future versions may include REST API endpoints.

## Database Schema

**projects**
- id, name, slug, project_path, description
- current_version, github_repo, github_token
- local_storage_path, onedrive_path, google_drive_path
- exclude_patterns, auto_push_github, auto_copy_onedrive, auto_copy_gdrive
- status, created_at, updated_at

**vackups**
- id, project_id, version, label, description, notes
- zip_filename, zip_size, zip_path
- github_commit_sha, github_tag, github_pushed
- onedrive_copied, gdrive_copied, local_copied
- files_count, created_at

**settings**
- id, key, value, created_at, updated_at

**release_notes**
- id, vackup_id, content, format, created_at

## Progressive Development Philosophy

Vackup embodies the Progressive Development methodology:

1. **Build incrementally** — Small, focused updates
2. **Document progress** — Every version tells a story
3. **Never lose work** — Multiple backup destinations
4. **Push boundaries** — Track how far you've come

---

*Built with Wheelder — 2022-2026*
