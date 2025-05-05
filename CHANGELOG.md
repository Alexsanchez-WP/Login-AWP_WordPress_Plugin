# Changelog

All notable changes to the Login AWP WordPress Plugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [3.2.1] - 2025-05-04

### Fixed
- Critical error when activating the plugin on certain hosting environments
- Issue with feedback popup not appearing on first plugin activation

### Improved
- Plugin activation process now properly initializes feedback options on first install
- Enhanced error handling during plugin activation
- Eliminated external settings.php dependency for better reliability
- Code structure reorganized for better maintainability

## [3.2.0] - 2025-04-15

### Fixed
- AJAX error during plugin deactivation feedback submission
- Issue with feedback submission pointing to incorrect domain

### Improved
- Enhanced error handling for feedback email and webhook submissions
- Improved fallbacks for email delivery when feedback settings are incomplete
- Enhanced data sanitization for feedback submissions

## [3.1.0] - 2025-03-10

### Added
- User feedback system for administrators to help improve the plugin
- Anonymous telemetry options with clear opt-out functionality

### Improved
- Enhanced compatibility with older PHP versions
- Better error handling across the plugin

### Fixed
- Various minor styling issues with login themes
- Border rendering issues in certain themes

## [3.0.0] - 2025-01-20

### Added
- Theme selection system with multiple pre-designed layouts
- Visual Style Builder for advanced customization
- Custom fonts, colors, spacing, and border settings
- Live preview of theme changes

### Changed
- Complete architecture overhaul for better performance
- Modernized codebase with improved class organization

## [2.1.0] - 2024-10-05

### Added
- Status notifications for image updates
- Spanish language translation (es_ES)

### Improved
- Enhanced image upload handling
- Better error messaging

## [2.0.0] - 2024-07-15

### Changed
- Plugin moved to Appearance menu for easier access
- Completely refactored codebase using OOP principles

### Added
- Logo and background image customization options
- Improved responsive behavior across devices
- Better compatibility with popular WordPress themes

## [1.1.0] - 2024-03-22

### Fixed
- Automatically display custom site icon on login page if available

### Improved
- CSS compatibility with WordPress 6.x
- Overall login page rendering

## [1.0.0] - 2024-01-10

### Added
- Initial stable release
- Custom login page styling
- Background image with transitions
- Logo customization
- Responsive design

[3.2.1]: https://github.com/AWP-Software/Login-AWP_WordPress_Plugin/compare/3.2.0...3.2.1
[3.2.0]: https://github.com/AWP-Software/Login-AWP_WordPress_Plugin/compare/3.1.0...3.2.0
[3.1.0]: https://github.com/AWP-Software/Login-AWP_WordPress_Plugin/compare/3.0.0...3.1.0
[3.0.0]: https://github.com/AWP-Software/Login-AWP_WordPress_Plugin/compare/2.1.0...3.0.0
[2.1.0]: https://github.com/AWP-Software/Login-AWP_WordPress_Plugin/compare/2.0.0...2.1.0
[2.0.0]: https://github.com/AWP-Software/Login-AWP_WordPress_Plugin/compare/1.1.0...2.0.0
[1.1.0]: https://github.com/AWP-Software/Login-AWP_WordPress_Plugin/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/AWP-Software/Login-AWP_WordPress_Plugin/releases/tag/1.0.0