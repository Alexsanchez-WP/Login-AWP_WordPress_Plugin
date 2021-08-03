<?php


if (!defined('WP_UNINSTALL_PLUGIN')) die();

global $wpdb;

$query = "DROP TABLE IF EXISTS {$wpdb->prefix}login_awp";
$wpdb->query($query);