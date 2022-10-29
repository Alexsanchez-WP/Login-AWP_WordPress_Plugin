<?php

/**
 * Include files and send in to loginJS.js script
 * 
 * @version 1.0.0
 * @global $wpdb
 */

if (!defined('ABSPATH')) die();

if (!function_exists('awp_incude_files')) {

    function awp_incude_files()
    {
        global $wpdb;

        $query = "SELECT * FROM {$wpdb->prefix}register_directory";

        wp_localize_script(
            'loginJS',
            'login_imagenes',
             array(
                // 'logo' => "https://www.gruporeysa.com/wp-content/uploads/2021/08/Logo_GrupoReysa-05-04.png",
                'sliders' => plugin_dir_url(__DIR__),
            )
        );
    }
    add_action('login_enqueue_scripts', 'awp_incude_files', 10);
}
