<?php

/**
 * Drop custom options
 *
 * @author AWP-Software
 * @since 2.0.0
 * @version 3.1.0
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die('You are not allowed to uninstall the plugin');
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/settings.php';

// Delete plugin settings
delete_option(AWP_LOGIN_LOGO_OPTION);
delete_option(AWP_LOGIN_BACKGROUND_OPTION);
delete_option(AWP_LOGIN_ACTIVATION_DATE_OPTION);
delete_option(AWP_LOGIN_REVIEW_DISMISSED_OPTION);

delete_site_option(AWP_LOGIN_LOGO_OPTION);
delete_site_option(AWP_LOGIN_BACKGROUND_OPTION);
delete_site_option(AWP_LOGIN_ACTIVATION_DATE_OPTION);
delete_site_option(AWP_LOGIN_REVIEW_DISMISSED_OPTION);

// Delete custom theme settings
delete_option(AWP_LOGIN_THEME_OPTION);
delete_option(AWP_LOGIN_CUSTOM_STYLES_OPTION);

delete_site_option(AWP_LOGIN_THEME_OPTION);
delete_site_option(AWP_LOGIN_CUSTOM_STYLES_OPTION);

// Delete feedback settings
delete_option(AWP_LOGIN_FEEDBACK_EMAIL_OPTION);
delete_option(AWP_LOGIN_FEEDBACK_WEBHOOK_OPTION);

delete_site_option(AWP_LOGIN_FEEDBACK_EMAIL_OPTION);
delete_site_option(AWP_LOGIN_FEEDBACK_WEBHOOK_OPTION);

