# Wheelder Changelog

All notable changes to Wheelder will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- **Dynamic Releases System** — New `/releases` page for publishing release notes, features, and innovations with rich media support (text, images, videos)
- **Releases CMS** — Admin interface at `/releases/cms` for managing release entries similar to the blog system
- **Spinning Prompt Modal** — Enhanced `/center` app with animated spinning modal for focused prompt writing
- **Spellcheck & Grammar** — Browser-native spell and grammar checking in the prompt modal textarea
- **Darker Backdrop** — Improved focus with darker modal backdrop (75% opacity) when prompt modal is open
- **Centered Textarea** — Prompt textarea repositioned to center of page for better visual hierarchy
- **Default Route Update** — Root path `/` now redirects to `/center` with demo key for new prompt modal experience

### Fixed
- **Dark Screen Bug** — Fixed issue where backdrop remained dark after closing prompt modal by using `removeAttribute('style')` instead of inline style setting
- **SMTP Timeout** — Added 10-second timeout to prevent 300-second hangs on blocked outbound SMTP ports
- **Magic Link Error Handling** — Improved error messages to hint about blocked SMTP ports on connection failure

### Changed
- **Prompt UI** — Removed Ask and Clear buttons below main textarea; moved to modal footer for cleaner layout
- **Modal Animations** — Prompt modal now spins 720° (two full rotations) on open with smooth scale and opacity transitions
- **Mobile Optimization** — Faster animations on mobile devices (0.6s vs 0.8s on desktop) for better perceived performance

### Technical
- **Database** — New `releases` table with JSON support for images and videos, timestamps, and publish status
- **ReleaseController** — New controller class following BlogController pattern for consistency
- **Accessibility** — Respects `prefers-reduced-motion` media query to disable animations for users with motion sensitivity
- **Dark Mode** — Full dark mode support for both releases page and CMS interface

## [1.0.0] - 2026-02-25

### Initial Release
- Core Wheelder learning platform with `/center` app for asking questions and deepening research
- Magic link authentication system
- Blog system for content management
- Text-to-speech powered by Microsoft Edge TTS
- Image generation via Pollinations API
- Circular/deep research feature for multi-level question exploration
- Dark mode toggle
- Responsive mobile-first design
- CSRF protection and session management

---

## How to Use This Changelog

### For Users
Visit https://wheelder.com/releases to see all published release notes with features, improvements, and bug fixes.

### For Developers
- Add new entries under `[Unreleased]` as you develop features
- When releasing a new version, create a new section with the version number and date
- Use categories: Added, Fixed, Changed, Deprecated, Removed, Security
- Link to related GitHub issues and PRs when applicable

### For GitHub
This changelog is automatically synced with the `/releases` page. Updates to this file are reflected on the web interface.

---

## Version History

- **v1.0.0** (2026-02-25) — Initial public release
- **v1.1.0** (2026-02-25) — Enhanced prompt modal with spinning animation and dark mode improvements
