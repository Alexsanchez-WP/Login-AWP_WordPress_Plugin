<?php


if (!function_exists('awp_incude_files')) {

    function awp_incude_files()
    {
        global $wpdb;

        $query = "SELECT * FROM {$wpdb->prefix}login_awp";

        wp_localize_script(
            'loginJS',
            'login_imagenes',
            array(
                'logo' => true,
                'sliders' => array(plugins_url("assets/img/slider.jpg", __DIR__), plugins_url("assets/img/slider.jpg", __DIR__)),
            )
        );
    }

    add_action('login_enqueue_scripts', 'awp_incude_files', 10);
}