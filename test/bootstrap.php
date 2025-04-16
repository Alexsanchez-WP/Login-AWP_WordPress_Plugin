<?php

/**
 * PHPUnit bootstrap file for Login AWP Plugin tests
 */

// Include composer autoloader
require_once dirname(__DIR__) . '/trunk/vendor/autoload.php';

// Define constants used by the plugin
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__DIR__) . '/trunk/');
}

if (!defined('WP_PLUGIN_DIR')) {
    define('WP_PLUGIN_DIR', dirname(__DIR__));
}

// Load mock WordPress functions
require_once __DIR__ . '/unit-test/mock/WpFunctions.php';
require_once __DIR__ . '/unit-test/mock/WpHooks.php';

// Add a custom autoloader for test classes
spl_autoload_register(function ($class) {
    // Base directory for test classes
    $base_dir = __DIR__ . '/unit-test/';

    // Convert namespace to file path
    $file = $base_dir . str_replace('\\', '/', $class) . '.php';

    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});
