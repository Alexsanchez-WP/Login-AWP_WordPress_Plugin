<?php

/**
 * Drop table and remove data
 * 
 * @since 1.0.1
 * @version 1.0.0
 */

if (!defined('WP_UNINSTALL_PLUGIN')) die();

global $wpdb;

$query = "DROP TABLE IF EXISTS {$wpdb->prefix}register_directory";
$wpdb->query($query);
