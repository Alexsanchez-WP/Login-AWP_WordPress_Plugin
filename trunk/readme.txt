=== Login AWP ===
Contributors: alexanderwp
Donate link: https://www.paypal.com/donate/?hosted_button_id=32A55GWU7JKY4
Tags: login, custom login, login page, login branding, style builder, admin branding
Requires at least: 5.4
Tested up to: 6.8
Stable tag: 3.2.0
Requires PHP: 7.4
Support link: https://awp-software.com/docs-category/login-awp-plugin/
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Login AWP allows you to fully customize the WordPress login screen with themes, logos, background images, and a visual style builder — no coding required.

== Description ==

**Login AWP** enhances the default WordPress login page with powerful customization features:

- Add a custom background image with transitions
- Display your website's logo automatically
- Choose from pre-designed login themes
- Customize colors, fonts, borders, and spacing using the visual Style Builder
- Fully responsive and retina-ready design
- Seamless integration under the WordPress "Appearance" menu
- Lightweight, fast, and compatible with multisite
- Translation-ready (includes Spanish)
- User-friendly feedback system for continuous improvement
- Plugin deactivation/deletion feedback to help us improve

Whether you manage a client site, a brand, or a community platform, Login AWP lets you deliver a professional first impression — effortlessly.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/login-awp/` directory, or install the plugin through the WordPress Plugin Directory.
2. Activate the plugin through the "Plugins" menu.
3. Navigate to **Appearance → Login AWP** to start customizing your login screen.

== Frequently Asked Questions ==

= Can I change the background image? =  
Yes. Go to **Appearance → Login AWP**, then select the "Background" tab to upload your image.

= What happens if I don't set a logo? =  
WordPress will use the default logo. You can upload a custom logo from the plugin settings.

= My background image is slow to load. What should I do? =  
Use optimized images under 500KB in JPG or WEBP format for better performance.

= How do I apply a pre-designed theme? =  
In the plugin settings, navigate to the "Themes" tab. Click a theme to preview and activate it.

= Can I fully customize styles? =  
Yes! Use the "Style Builder" tab to visually change colors, fonts, borders, spacing, and more.

= How can I provide feedback about this plugin? =
After using the plugin for a short period, administrators will see a feedback notification where they can leave a review. You can also provide feedback when deactivating or deleting the plugin. This helps us improve Login AWP.

= The login page isn't loading my custom styles. What should I do? =
Ensure your browser's cache is cleared. If the issue persists, try disabling other plugins that might conflict with login page customizations.

= I'm getting an error when trying to send feedback while deactivating/deleting the plugin. What should I do? =
In version 3.2.0, we've fixed an issue that could cause AJAX errors when submitting feedback. Make sure you're using the latest version of the plugin. If the issue persists, you can safely skip the feedback step.

== Screenshots ==

1. Login page with default WordPress logo.
2. Login page with custom logo.
3. Plugin location in the Appearance menu.
4. Background image upload interface.
5. Pre-designed theme selector.
6. Style Builder customization interface.
7. Feedback modal when deactivating or deleting plugin.

== Changelog ==

= 3.2.0 =
* FIXED: Resolved AJAX error during plugin deletion feedback submission
* FIXED: Corrected issue with feedback submission pointing to incorrect domain
* IMPROVED: Enhanced error handling for feedback email and webhook submissions
* IMPROVED: Better fallbacks for email delivery when feedback settings are incomplete
* SECURITY: Improved data sanitization for feedback submissions

= 3.1.0 =
* NEW: Added user feedback system for administrators to help improve the plugin
* NEW: Added deactivation/deletion feedback modal to collect improvement suggestions
* FIXED: Login page JavaScript loading issue on specific server configurations
* FIXED: Deletion process now correctly captures user feedback
* IMPROVED: Enhanced compatibility with older PHP versions
* IMPROVED: Better handling of modal dialogs with event delegation
* FIXED: Various minor styling issues 

= 3.0.0 =
* NEW: Theme selection system with pre-designed layouts
* NEW: Visual Style Builder for advanced customization
* NEW: Custom fonts, colors, spacing, and border settings

= 2.1.0 =
* NEW: Status notifications for image updates
* Added: Spanish language translation

= 2.0.0 =
* NEW: Plugin moved to Appearance menu for easier access
* NEW: Logo and background image customization

= 1.1.0 =
* FIX: Automatically display custom site icon on login page if available

= 1.0.0 =
* Initial stable release

== Upgrade Notice ==

= 3.2.0 =
This update fixes an important issue with the feedback system when deactivating or deleting the plugin. The previous version could generate AJAX errors during feedback submission, which has been resolved. We recommend all users update to this version.

= 3.1.0 =
This update adds a user feedback system for both administrators and when deactivating/deleting the plugin. We've also fixed a JavaScript loading issue and improved compatibility with older PHP versions. We recommend all users update for a better experience.
