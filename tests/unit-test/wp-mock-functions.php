<?php

/**
 * Funciones mock para simular el comportamiento de WordPress durante las pruebas
 */

// Funciones básicas de WordPress
if (!function_exists('add_action')) {
    function add_action($hook, $callback, $priority = 10, $accepted_args = 1) {
        // Solo simula la función, no hace nada
        return true;
    }
}

if (!function_exists('add_filter')) {
    function add_filter($hook, $callback, $priority = 10, $accepted_args = 1) {
        // Solo simula la función, no hace nada
        return true;
    }
}

if (!function_exists('wp_enqueue_style')) {
    function wp_enqueue_style($handle, $src = '', $deps = array(), $ver = false, $media = 'all') {
        // Solo simula la función, no hace nada
        return true;
    }
}

if (!function_exists('wp_enqueue_script')) {
    function wp_enqueue_script($handle, $src = '', $deps = array(), $ver = false, $in_footer = false) {
        // Solo simula la función, no hace nada
        return true;
    }
}

if (!function_exists('wp_localize_script')) {
    function wp_localize_script($handle, $object_name, $l10n) {
        // Solo simula la función, no hace nada
        return true;
    }
}

if (!function_exists('get_option')) {
    function get_option($option, $default = false) {
        // Proporciona valores predeterminados para opciones específicas
        static $options = array();
        return $options[$option] ?? $default;
    }
}

if (!function_exists('add_option')) {
    function add_option($option, $value = '', $deprecated = '', $autoload = 'yes') {
        // Solo simula la función, no hace nada
        return true;
    }
}

if (!function_exists('update_option')) {
    function update_option($option, $value, $autoload = null) {
        // Solo simula la función, no hace nada
        return true;
    }
}

if (!function_exists('esc_url')) {
    function esc_url($url, $protocols = null, $_context = 'display') {
        // Simplemente devuelve la URL sin modificar para las pruebas
        return $url;
    }
}

if (!function_exists('load_plugin_textdomain')) {
    function load_plugin_textdomain($domain, $deprecated = false, $plugin_rel_path = false) {
        // Solo simula la función, no hace nada
        return true;
    }
}

if (!function_exists('get_site_icon_url')) {
    function get_site_icon_url($size = 512, $url = '', $blog_id = 0) {
        // Devuelve una URL de muestra para las pruebas
        return 'https://example.com/default-icon.png';
    }
}

if (!function_exists('flush_rewrite_rules')) {
    function flush_rewrite_rules() {
        // Solo simula la función, no hace nada
        return true;
    }
}

/**
 * Función helper para simular la función set_current_screen de WordPress
 */

if (!function_exists('set_current_screen')) {
    function set_current_screen($screen) {
        global $current_screen;
        
        if (!$current_screen) {
            $current_screen = new stdClass();
        }
        
        $current_screen->id = $screen;
        $current_screen->base = $screen;
        return $current_screen;
    }
}

if (!function_exists('get_current_screen')) {
    function get_current_screen() {
        global $current_screen;
        return $current_screen;
    }
}

/**
 * Simulación de la clase para el plugin_dir_path
 */
if (!function_exists('plugin_dir_path')) {
    function plugin_dir_path($file) {
        return dirname($file) . '/';
    }
}

/**
 * Simulación para la función is_plugin_active
 */
if (!function_exists('is_plugin_active')) {
    function is_plugin_active($plugin) {
        return true;
    }
}

/**
 * Simulación para get_plugins
 */
if (!function_exists('get_plugins')) {
    function get_plugins() {
        return [
            'login-awp/login_awp.php' => [
                'Name' => 'Login AWP',
                'Version' => '3.2.1'
            ],
            'another-plugin/another-plugin.php' => [
                'Name' => 'Another Plugin',
                'Version' => '1.0.0'
            ]
        ];
    }
}

/**
 * Simulación para current_user_can
 */
if (!function_exists('current_user_can')) {
    function current_user_can($capability) {
        return true;
    }
}

/**
 * Simulación para admin_url
 */
if (!function_exists('admin_url')) {
    function admin_url($path = '') {
        return 'http://example.com/wp-admin/' . $path;
    }
}

/**
 * Simulación para wp_get_current_user
 */
if (!function_exists('wp_get_current_user')) {
    function wp_get_current_user() {
        $user = new stdClass;
        $user->user_email = 'admin@example.com';
        return $user;
    }
}

/**
 * Simulación para home_url
 */
if (!function_exists('home_url')) {
    function home_url($path = '') {
        return 'http://example.com/' . ltrim($path, '/');
    }
}

/**
 * Simulación para get_bloginfo
 */
if (!function_exists('get_bloginfo')) {
    function get_bloginfo($show = '') {
        $info = [
            'version' => '6.2',
            'name' => 'Test Site',
            'url' => 'http://example.com'
        ];
        
        return $info[$show] ?? '';
    }
}

/**
 * Simulación para current_time
 */
if (!function_exists('current_time')) {
    function current_time($type) {
        return date('Y-m-d H:i:s');
    }
}

/**
 * Simulación para wp_remote_post
 */
if (!function_exists('wp_remote_post')) {
    function wp_remote_post($url, $args = []) {
        return ['response' => ['code' => 200]];
    }
}

/**
 * Simulación para is_wp_error
 */
if (!function_exists('is_wp_error')) {
    function is_wp_error($thing) {
        return false;
    }
}

/**
 * Simulación para wp_remote_retrieve_response_code
 */
if (!function_exists('wp_remote_retrieve_response_code')) {
    function wp_remote_retrieve_response_code($response) {
        return $response['response']['code'] ?? 200;
    }
}

/**
 * Simulación para wp_json_encode
 */
if (!function_exists('wp_json_encode')) {
    function wp_json_encode($data) {
        return json_encode($data);
    }
}

/**
 * Simulación para wp_mail
 */
if (!function_exists('wp_mail')) {
    function wp_mail($to, $subject, $message, $headers = '', $attachments = []) {
        return true;
    }
}

/**
 * Simulación para add_query_arg
 */
if (!function_exists('add_query_arg')) {
    function add_query_arg($args, $url = '') {
        if (empty($url) && isset($_SERVER['REQUEST_URI'])) {
            $url = $_SERVER['REQUEST_URI'];
        }
        
        return $url . '?' . http_build_query($args);
    }
}

/**
 * Simulación para add_settings_section
 */
if (!function_exists('add_settings_section')) {
    function add_settings_section($id, $title, $callback, $page) {
        return true;
    }
}

/**
 * Simulación para add_settings_field
 */
if (!function_exists('add_settings_field')) {
    function add_settings_field($id, $title, $callback, $page, $section, $args = []) {
        return true;
    }
}

/**
 * Simulación para register_setting
 */
if (!function_exists('register_setting')) {
    function register_setting($option_group, $option_name, $args = []) {
        return true;
    }
}

/**
 * Simulación para wp_die
 */
if (!function_exists('wp_die')) {
    function wp_die($message = '', $title = '', $args = []) {
        return;
    }
}

/**
 * Simulación para wp_safe_redirect
 */
if (!function_exists('wp_safe_redirect')) {
    function wp_safe_redirect($location, $status = 302) {
        return true;
    }
}

/**
 * Simulación para wp_get_referer
 */
if (!function_exists('wp_get_referer')) {
    function wp_get_referer() {
        return 'http://example.com/wp-admin/';
    }
}

/**
 * Define adicionales opciones de WordPress
 */
define('AWP_LOGIN_THEME_OPTION', 'awp_login_theme');
define('AWP_LOGIN_CUSTOM_STYLES_OPTION', 'awp_login_custom_styles');