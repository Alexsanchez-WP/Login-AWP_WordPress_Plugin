<?php

/**
 * Drop custom options
 *
 * @author AWP-Software
 * @since 2.0.0
 * @version 3.1.0
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die('You are not allowed to uninstall the plugin');
}

require_once __DIR__ . '/vendor/autoload.php';

use Login\Awp\Admin\AdminRegister;
use Login\Awp\Admin\ThemeManager;

// Delete plugin settings
delete_option(AdminRegister::$imgLogoName);
delete_option(AdminRegister::$imgBackName);
delete_option(AdminRegister::$activateDateOption);
delete_option(AdminRegister::$reviewNoticeDismissedOption);

delete_site_option(AdminRegister::$imgLogoName);
delete_site_option(AdminRegister::$imgBackName);
delete_site_option(AdminRegister::$activateDateOption);
delete_site_option(AdminRegister::$reviewNoticeDismissedOption);

// Delete custom theme settings
delete_option(ThemeManager::$themeOptionName);
delete_option(ThemeManager::$customStylesOptionName);

delete_site_option(ThemeManager::$themeOptionName);
delete_site_option(ThemeManager::$customStylesOptionName);

