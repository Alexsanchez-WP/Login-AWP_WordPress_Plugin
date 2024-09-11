<?php

/**
 * Include files and send in to loginJS.js script
 * @since 1.0.0
 * @version 1.1.0
 */

if (!defined('ABSPATH')) die();

if (!function_exists('login_awp_incude_files')) {

    function login_awp_incude_files()
    {
        wp_localize_script(
            'loginJS',
            'login_imagenes',
            array(
                'logo' => esc_url( get_site_icon_url() ),
                'sliders' => esc_url( plugin_dir_url(__DIR__) ),
            )
        );
    }
    add_action('login_enqueue_scripts', 'login_awp_incude_files', 10);
}
