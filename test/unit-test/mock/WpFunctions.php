<?php

/**
 * Mock WordPress functions for testing
 */

// Mock storage for options
$GLOBALS['wp_options'] = [];

// Mock storage for site options
$GLOBALS['wp_site_options'] = [];

// Mock storage for styles/scripts
$GLOBALS['wp_enqueued_styles'] = [];
$GLOBALS['wp_enqueued_scripts'] = [];
$GLOBALS['wp_localized_scripts'] = [];

// Plugin paths and URLs
if (!function_exists('plugin_dir_url')) {
    function plugin_dir_url($file) {
        return 'https://example.com/wp-content/plugins/' . basename(dirname($file)) . '/';
    }
}

if (!function_exists('plugin_dir_path')) {
    function plugin_dir_path($file) {
        return dirname($file) . '/';
    }
}

if (!function_exists('plugin_basename')) {
    function plugin_basename($file) {
        return basename(dirname($file)) . '/' . basename($file);
    }
}

// Options API
if (!function_exists('get_option')) {
    function get_option($option, $default = false) {
        return $GLOBALS['wp_options'][$option] ?? $default;
    }
}

if (!function_exists('update_option')) {
    function update_option($option, $value, $autoload = null) {
        $GLOBALS['wp_options'][$option] = $value;
        return true;
    }
}

if (!function_exists('delete_option')) {
    function delete_option($option) {
        if (isset($GLOBALS['wp_options'][$option])) {
            unset($GLOBALS['wp_options'][$option]);
            return true;
        }
        return false;
    }
}

// Site options (multisite)
if (!function_exists('get_site_option')) {
    function get_site_option($option, $default = false) {
        return $GLOBALS['wp_site_options'][$option] ?? $default;
    }
}

if (!function_exists('update_site_option')) {
    function update_site_option($option, $value) {
        $GLOBALS['wp_site_options'][$option] = $value;
        return true;
    }
}

if (!function_exists('delete_site_option')) {
    function delete_site_option($option) {
        if (isset($GLOBALS['wp_site_options'][$option])) {
            unset($GLOBALS['wp_site_options'][$option]);
            return true;
        }
        return false;
    }
}

// Scripts and styles
if (!function_exists('wp_enqueue_style')) {
    function wp_enqueue_style($handle, $src = '', $deps = [], $ver = false, $media = 'all') {
        $GLOBALS['wp_enqueued_styles'][$handle] = [
            'src' => $src,
            'deps' => $deps,
            'ver' => $ver,
            'media' => $media
        ];
        return true;
    }
}

if (!function_exists('wp_enqueue_script')) {
    function wp_enqueue_script($handle, $src = '', $deps = [], $ver = false, $in_footer = false) {
        $GLOBALS['wp_enqueued_scripts'][$handle] = [
            'src' => $src,
            'deps' => $deps,
            'ver' => $ver,
            'in_footer' => $in_footer
        ];
        return true;
    }
}

if (!function_exists('wp_localize_script')) {
    function wp_localize_script($handle, $object_name, $l10n) {
        $GLOBALS['wp_localized_scripts'][$handle] = [
            'object_name' => $object_name,
            'data' => $l10n
        ];
        return true;
    }
}

// Translation functions
if (!function_exists('__')) {
    function __($text, $domain = 'default') {
        return $text;
    }
}

if (!function_exists('_e')) {
    function _e($text, $domain = 'default') {
        echo $text;
    }
}

// AJAX functions
if (!function_exists('wp_create_nonce')) {
    function wp_create_nonce($action = -1) {
        return 'test_nonce_' . $action;
    }
}

if (!function_exists('wp_verify_nonce')) {
    function wp_verify_nonce($nonce, $action = -1) {
        return $nonce === 'test_nonce_' . $action;
    }
}

// Admin URL function
if (!function_exists('admin_url')) {
    function admin_url($path = '') {
        return 'https://example.com/wp-admin/' . $path;
    }
}
