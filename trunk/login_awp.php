<?php

declare(strict_types=1);

/**
 * Plugin Name: Login AWP
 * Plugin URI: https://wordpress.org/plugins/login-awp
 * Description: This plugin modifies the login area for WordPress admin
 * Version: 3.1.0
 * Requires at least: 5.4
 * Requires PHP: 7.4
 * Author: AWP-Software
 * Author URI: https://github.com/AWP-Software
 * Text Domain: login-awp
 * Domain Path: /languages
 * License: GPLv2
 * Released under the GNU General Public License (GPL)
 * https://www.gnu.org/licenses/gpl-3.0.html
 */

if (!defined('ABSPATH')) {
    die('You are not allowed to call this page directly.');
}
require_once __DIR__ . '/vendor/autoload.php';

use Login\Awp\Register;


// Activation hook to store activation time for the review notice
register_activation_hook(__FILE__, 'login_awp_activation_hook');

/**
 * Store plugin activation time for the review notice.
 */
function login_awp_activation_hook() {
    if (false === get_option('login_awp_activation_date')) {
        add_option('login_awp_activation_date', time());
    }
    // Also add the option for dismissal tracking, default to not dismissed
    if (false === get_option('login_awp_review_notice_dismissed')) {
        add_option('login_awp_review_notice_dismissed', '0');
    }
    
    // Initialize feedback options
    if (false === get_option('login_awp_feedback_email')) {
        add_option('login_awp_feedback_email', get_option('admin_email'));
    }
    if (false === get_option('login_awp_feedback_webhook')) {
        add_option('login_awp_feedback_webhook', '');
    }
}

// Register deactivation feedback hook
register_deactivation_hook(__FILE__, 'login_awp_deactivation_hook');

/**
 * Track plugin deactivation for the feedback popup.
 */
function login_awp_deactivation_hook() {
    // This function intentionally left empty
    // The popup is handled via JavaScript before deactivation
}

$register = new Register(
    plugin_dir_url(__FILE__),
    plugin_dir_path(__FILE__),
    dirname(plugin_basename(__FILE__)) . '/languages'
);
$register->load();
