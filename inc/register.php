<?php



if (!defined('ABSPATH')) die();

if (!function_exists('register_plugin_login_awp')) {
    function register_plugin_login_awp()
    {

        global $wpdb;

        $query = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}login_awp (
         ID INT(11) NOT NULL AUTO_INCREMENT,
         logo VARCHAR(200) NULL,
         sliders TEXT NULL,
         date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
         PRIMARY KEY (ID) );";

        $wpdb->query($query);
    }
}