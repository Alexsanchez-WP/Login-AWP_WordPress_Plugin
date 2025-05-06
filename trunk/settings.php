<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// ================================================
// Email and webhook endpoints
// ================================================
if (!defined('AWP_LOGIN_FEEDBACK_EMAIL')) {
    define('AWP_LOGIN_FEEDBACK_EMAIL', 'support@awp-software.com');
}

if (!defined('AWP_LOGIN_FEEDBACK_WEBHOOK')) {
    define('AWP_LOGIN_FEEDBACK_WEBHOOK', 'https://telemetry.awp-software.com/feedback');
}

// ================================================
// Plugin option names
// ================================================

// General settings options
if (!defined('AWP_LOGIN_LOGO_OPTION')) {
    define('AWP_LOGIN_LOGO_OPTION', 'login_awp_logo_url');
}

if (!defined('AWP_LOGIN_BACKGROUND_OPTION')) {
    define('AWP_LOGIN_BACKGROUND_OPTION', 'login_awp_background_url');
}

// Theme-related options
if (!defined('AWP_LOGIN_THEME_OPTION')) {
    define('AWP_LOGIN_THEME_OPTION', 'login_awp_selected_theme');
}

if (!defined('AWP_LOGIN_CUSTOM_STYLES_OPTION')) {
    define('AWP_LOGIN_CUSTOM_STYLES_OPTION', 'login_awp_custom_styles');
}

// Activation and review options
if (!defined('AWP_LOGIN_ACTIVATION_DATE_OPTION')) {
    define('AWP_LOGIN_ACTIVATION_DATE_OPTION', 'login_awp_activation_date');
}

if (!defined('AWP_LOGIN_REVIEW_DISMISSED_OPTION')) {
    define('AWP_LOGIN_REVIEW_DISMISSED_OPTION', 'login_awp_review_notice_dismissed');
}

// Feedback options
if (!defined('AWP_LOGIN_FEEDBACK_EMAIL_OPTION')) {
    define('AWP_LOGIN_FEEDBACK_EMAIL_OPTION', 'login_awp_feedback_email');
}

if (!defined('AWP_LOGIN_FEEDBACK_WEBHOOK_OPTION')) {
    define('AWP_LOGIN_FEEDBACK_WEBHOOK_OPTION', 'login_awp_feedback_webhook');
}
