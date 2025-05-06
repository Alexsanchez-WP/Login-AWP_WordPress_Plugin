<?php

declare(strict_types=1);

/**
 * Plugin Name: Login AWP
 * Plugin URI: https://wordpress.org/plugins/login-awp
 * Description: This plugin modifies the login area for WordPress admin
 * Version: 3.2.2
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
require_once __DIR__ . '/settings.php';


use Login\Awp\Register;

register_activation_hook(__FILE__, 'login_awp_activate');

function login_awp_activate() {

    add_option(AWP_LOGIN_ACTIVATION_DATE_OPTION, time());
    add_option(AWP_LOGIN_REVIEW_DISMISSED_OPTION, '0');
    add_option(AWP_LOGIN_FEEDBACK_EMAIL_OPTION, AWP_LOGIN_FEEDBACK_EMAIL);
    add_option(AWP_LOGIN_FEEDBACK_WEBHOOK_OPTION, AWP_LOGIN_FEEDBACK_WEBHOOK);
    
    flush_rewrite_rules();
}

$register = new Register(
    plugin_dir_url(__FILE__),
    plugin_dir_path(__FILE__),
    dirname(plugin_basename(__FILE__)) . '/languages'
);
$register->load();
