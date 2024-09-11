<?php

/**
 * Drop table and remove data
 * 
 * @author Alexsanchez-WP <alexsanchez.wp@gmail.com>
 * @since 2.0.0
 * @version 1.0.0
 * @global $wpdb
 */

if (!defined('WP_UNINSTALL_PLUGIN')) die();

global $wpdb;

$query = "DROP TABLE IF EXISTS {$wpdb->prefix}login_awp";
$wpdb->query($query);
