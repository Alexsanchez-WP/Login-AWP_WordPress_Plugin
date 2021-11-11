<?php

/**
 * Register plugin and create table
 * 
 * @since 1.0.1
 * @version 1.0.0
 */

if (!defined('ABSPATH')) die();

if (!function_exists('register_plugin_register_directory')) {
    function register_plugin_register_directory()
    {

        global $wpdb;

        $query = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}register_directory (
         ID INT(11) NOT NULL AUTO_INCREMENT,
         meta_key VARCHAR(50) NULL,
         meta_value TEXT NULL,
         date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
         PRIMARY KEY (ID) );";

        $wpdb->query($query);
    }
}
