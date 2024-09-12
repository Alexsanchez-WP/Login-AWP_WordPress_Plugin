<?php

/**
 * Drop table and remove data
 *
 * @author AWP-Software
 * @since 2.0.0
 * @version 2.0.0
 * @global $wpdb
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die('You are not allowed to uninstall the plugin');
}

global $wpdb;

$query = "DROP TABLE IF EXISTS {$wpdb->prefix}login_awp";
$wpdb->query($query);
