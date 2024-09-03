<?php

/**
 * Register plugin and create table
 * 
 * @author Alexsanchez-WP <alexsanchez.wp@gmail.com>
 * @since 2.0.0
 * @version 1.0.0
 * @global $wpdb
 */

if (!defined('ABSPATH')) die();

if (!function_exists('register_plugin_login_awp')) {
    function register_plugin_login_awp()
    {

        global $wpdb;

        $create = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}login_awp (
            slides TEXT NULL,
            overlay INT(3) NULL,
            transition VARCHAR(150) NULL,
            logo VARCHAR(150) NULL,
            delay INT(10) NULL,
            transitionDuration: INT(10) NULL
        );";

        $create = $wpdb->query($create);        
        $default_db_file = plugin_dir_path(__FILE__) . "default-db.php";

        if($create && file_exists($default_db_file)){

            require_once $default_db_file;
            
        }
    }
}


