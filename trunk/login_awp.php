<?php

declare(strict_types=1);

/**
 * Plugin Name: Login AWP
 * Plugin URI: https://wordpress.org/plugins/login-awp
 * Description: This plugin modifies the login area for WordPress admin
 * Version: 3.2.1
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

// Define plugin constants (previously in settings.php)
if (!defined('AWP_LOGIN_FEEDBACK_EMAIL')) {
    define('AWP_LOGIN_FEEDBACK_EMAIL', 'support@awp-software.com');
}

if (!defined('AWP_LOGIN_FEEDBACK_WEBHOOK')) {
    define('AWP_LOGIN_FEEDBACK_WEBHOOK', 'https://telemetry.awp-software.com/feedback');
}

require_once __DIR__ . '/vendor/autoload.php';
// settings.php is now integrated directly in this file

use Login\Awp\Register;

$register = new Register(
    plugin_dir_url(__FILE__),
    plugin_dir_path(__FILE__),
    dirname(plugin_basename(__FILE__)) . '/languages'
);
$register->load();
