<?php

/**
 * Drop custom options
 *
 * @author AWP-Software
 * @since 2.0.0
 * @version 2.0.0
 */

if (!defined(constant_name: 'WP_UNINSTALL_PLUGIN')) {
    die('You are not allowed to uninstall the plugin');
}

require_once __DIR__ . '/vendor/autoload.php';

use Login\Awp\Admin\AdminRegister;

delete_option(option: AdminRegister::$imgLogoName);
delete_option(option: AdminRegister::$imgBackName);
delete_site_option(option: AdminRegister::$imgLogoName);
delete_site_option(option: AdminRegister::$imgBackName);
