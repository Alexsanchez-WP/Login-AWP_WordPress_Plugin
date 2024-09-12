<?php

declare(strict_types=1);

/**
 * Plugin Name: Login AWP
 * Plugin URI: https://wordpress.org/plugins/login-awp
 * Description: This plugin modifies the login area for WordPress admin
 * Version: 2.0.0
 * Requires at least: 5.4
 * Requires PHP: 7.4
 * Author: AWP-Software
 * Author URI: https://github.com/AWP-Software
 * Text Domain: login_awp
 * Domain Path: /languages
 * License: GPLv2
 * Released under the GNU General Public License (GPL)
 * https://www.gnu.org/licenses/gpl-3.0.html
 */


if (!defined(constant_name: 'ABSPATH')) {
    die('You are not allowed to call this page directly.');
}

define('LOGIN_AWP_DIR_URL', plugin_dir_url(__FILE__));
define('LOGIN_AWP_DIR_PATH', plugin_dir_path(__FILE__));
define('LOGIN_AWP_DOMAIN', 'login_awp');

require_once __DIR__ . '/vendor/autoload.php';

use Login\Awp\Register;

Register::load();
