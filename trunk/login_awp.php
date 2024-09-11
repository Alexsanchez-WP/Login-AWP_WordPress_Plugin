<?php

/**
 * Plugin Name: Login AWP
 * Plugin URI: https://wordpress.org/plugins/login-awp
 * Description: This plugin modifies the login area for WordPress admin
 * Version: 2.0.0
 * Requires at least: 5.4
 * Requires PHP: 7.4
 * Author: Alexsanchez-WP
 * Author URI: https://github.com/AWP-Software
 * Text Domain: login_awp
 * Domain Path: /languages
 * License: GPLv2
 * Released under the GNU General Public License (GPL)
 * https://www.gnu.org/licenses/gpl-3.0.html
 */


if (!defined('ABSPATH')) die();


if (!function_exists('login_awp_styles')) {

    function login_awp_styles()
    {
        wp_enqueue_style('vegasCSS', plugins_url('/public/css/vegas.min.css', __FILE__), array(), false);
        wp_enqueue_style('loginCSS', plugins_url('/public/css/loginStyles.css', __FILE__), array(), false);

        wp_enqueue_script('jquery');
        wp_enqueue_script('vegasJS', plugins_url('/public/js/vegas.min.js', __FILE__), array('jquery'), '2.5.4', true);
        wp_enqueue_script('loginJS', plugins_url('/public/js/loginJs.js', __FILE__), array('jquery'), '1.0.0', true);
    }

    add_action('login_enqueue_scripts', 'login_awp_styles', 1);
}

# Register plugin

require_once plugin_dir_path(__FILE__) . 'inc/register.php';
register_activation_hook(__FILE__, 'register_plugin_register_directory');

# Include consult logo site
require_once plugin_dir_path(__FILE__) . 'inc/media.php';
