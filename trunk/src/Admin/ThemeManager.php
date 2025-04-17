<?php

declare(strict_types=1);

/**
 * Theme Manager for Login AWP
 *
 * @package Login\Awp\Admin
 * @author AWP-Software
 * @since 3.0.0
 * @version 3.0.0
 */

namespace Login\Awp\Admin;

class ThemeManager
{
    public string $dirUrl;
    public static string $themeOptionName = 'login_awp_selected_theme';
    public static string $customStylesOptionName = 'login_awp_custom_styles';
    private array $predefinedThemes = [];

    /**
     * Constructor
     *
     * @param string $dir_url Plugin directory URL
     */
    public function __construct(string $dir_url)
    {
        $this->dirUrl = $dir_url . 'assets/';
    }

    /**
     * Initialize the theme manager
     */
    public function load(): void
    {
        // Registrar eventos después de que WordPress esté completamente cargado
        add_action('admin_enqueue_scripts', array($this, 'enqueueAdminAssets'));
        add_action('wp_ajax_login_awp_preview_theme', array($this, 'ajaxPreviewTheme'));
        add_action('wp_ajax_login_awp_save_theme', array($this, 'ajaxSaveTheme'));
        
        // Inicializar los temas predefinidos
        add_action('init', array($this, 'initializePredefinedThemes'));
    }
    
    /**
     * Inicializa los temas predefinidos una vez que WordPress está completamente cargado
     * y las traducciones están disponibles
     */
    public function initializePredefinedThemes(): void
    {
        $this->predefinedThemes = $this->registerPredefinedThemes();
    }

    /**
     * Register and define predefined themes
     *
     * @return array Array of predefined themes
     */
    private function registerPredefinedThemes(): array
    {
        return [
            'default' => [
                'name' => __('Default', 'login-awp'),
                'description' => __('The default Login AWP style', 'login-awp'),
                'preview' => $this->dirUrl . 'img/previews/default.jpg',
                'colors' => [
                    'bg' => '#f0f0f1',
                    'container_bg' => 'rgba(255, 255, 255, 0.8)',
                    'text' => '#000000',
                    'button_bg' => '#4182E0',
                    'button_hover' => '#014F7F',
                    'links' => '#808080',
                ],
                'typography' => [
                    'font_family' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
                    'font_size' => '14px',
                ],
                'effects' => [
                    'border_radius' => '10px',
                    'box_shadow' => '0 2px 5px rgba(0, 0, 0, 0.1)',
                ],
            ],
            'corporate' => [
                'name' => __('Corporate', 'login-awp'),
                'description' => __('Professional and clean style for business sites', 'login-awp'),
                'preview' => $this->dirUrl . 'img/previews/corporate.jpg',
                'colors' => [
                    'bg' => '#ffffff',
                    'container_bg' => 'rgba(255, 255, 255, 0.9)',
                    'text' => '#333333',
                    'button_bg' => '#2c3e50',
                    'button_hover' => '#1a2530',
                    'links' => '#34495e',
                ],
                'typography' => [
                    'font_family' => '"Segoe UI", Roboto, "Helvetica Neue", sans-serif',
                    'font_size' => '14px',
                ],
                'effects' => [
                    'border_radius' => '4px',
                    'box_shadow' => '0 4px 10px rgba(0, 0, 0, 0.15)',
                ],
            ],
            'minimal' => [
                'name' => __('Minimal', 'login-awp'),
                'description' => __('Clean and minimalist design', 'login-awp'),
                'preview' => $this->dirUrl . 'img/previews/minimal.jpg',
                'colors' => [
                    'bg' => '#fafafa',
                    'container_bg' => 'rgba(255, 255, 255, 1)',
                    'text' => '#202020',
                    'button_bg' => '#404040',
                    'button_hover' => '#202020',
                    'links' => '#505050',
                ],
                'typography' => [
                    'font_family' => '"Helvetica Neue", Helvetica, Arial, sans-serif',
                    'font_size' => '13px',
                ],
                'effects' => [
                    'border_radius' => '0px',
                    'box_shadow' => 'none',
                ],
            ],
            'colorful' => [
                'name' => __('Colorful', 'login-awp'),
                'description' => __('Vibrant and colorful style', 'login-awp'),
                'preview' => $this->dirUrl . 'img/previews/colorful.jpg',
                'colors' => [
                    'bg' => '#f5f5f5',
                    'container_bg' => 'rgba(255, 255, 255, 0.85)',
                    'text' => '#333333',
                    'button_bg' => '#e74c3c',
                    'button_hover' => '#c0392b',
                    'links' => '#3498db',
                ],
                'typography' => [
                    'font_family' => 'Roboto, "Helvetica Neue", sans-serif',
                    'font_size' => '14px',
                ],
                'effects' => [
                    'border_radius' => '20px',
                    'box_shadow' => '0 8px 20px rgba(0, 0, 0, 0.2)',
                ],
            ],
            'dark' => [
                'name' => __('Dark Mode', 'login-awp'),
                'description' => __('Dark theme for low-light environments', 'login-awp'),
                'preview' => $this->dirUrl . 'img/previews/dark.jpg',
                'colors' => [
                    'bg' => '#121212',
                    'container_bg' => 'rgba(30, 30, 30, 0.9)',
                    'text' => '#e0e0e0',
                    'button_bg' => '#bb86fc',
                    'button_hover' => '#9966cb',
                    'links' => '#03dac6',
                ],
                'typography' => [
                    'font_family' => 'Roboto, "Helvetica Neue", sans-serif',
                    'font_size' => '14px',
                ],
                'effects' => [
                    'border_radius' => '6px',
                    'box_shadow' => '0 4px 15px rgba(0, 0, 0, 0.5)',
                ],
            ],
        ];
    }

    /**
     * Get all available themes
     *
     * @return array List of available themes
     */
    public function getThemes(): array
    {
        // Si los temas aún no están inicializados, inicializarlos aquí
        if (empty($this->predefinedThemes)) {
            $this->predefinedThemes = $this->registerPredefinedThemes();
        }
        
        return $this->predefinedThemes;
    }

    /**
     * Get currently selected theme
     *
     * @return string Theme key
     */
    public function getSelectedTheme(): string
    {
        // Si los temas aún no están inicializados, inicializarlos aquí
        if (empty($this->predefinedThemes)) {
            $this->predefinedThemes = $this->registerPredefinedThemes();
        }
        
        $selectedTheme = get_option(self::$themeOptionName);
        return $selectedTheme && isset($this->predefinedThemes[$selectedTheme]) 
            ? $selectedTheme 
            : 'default';
    }

    /**
     * Get theme configuration
     *
     * @param string $theme_key Theme identifier
     * @return array|null Theme configuration or null if not found
     */
    public function getThemeConfig(string $theme_key): ?array
    {
        // Si los temas aún no están inicializarlos, inicializarlos aquí
        if (empty($this->predefinedThemes)) {
            $this->predefinedThemes = $this->registerPredefinedThemes();
        }
        
        return isset($this->predefinedThemes[$theme_key]) 
            ? $this->predefinedThemes[$theme_key] 
            : null;
    }

    /**
     * Enqueue admin assets for theme management
     */
    public function enqueueAdminAssets(): void
    {
        $screen = get_current_screen();
        
        // Only load on our plugin page
        if ($screen && $screen->id === 'appearance_page_login-awp') {
            wp_enqueue_style(
                'login-awp-theme-admin',
                $this->dirUrl . 'css/theme-admin.css',
                array(),
                '3.0.0'
            );
            
            wp_enqueue_script(
                'login-awp-theme-preview',
                $this->dirUrl . 'js/themePreview.js',
                array('jquery'),
                '3.0.0',
                true
            );
            
            // Get current logo and background images using the correct option names
            $logo_img = get_option('login_awp_logo_url', '');
            $background_img = get_option('login_awp_background_url', '');
            
            // Default background if not set
            if (empty($background_img)) {
                $background_img = $this->dirUrl . 'img/slider.jpg';
            }
            
            // Default logo uses site icon if available
            if (empty($logo_img)) {
                $logo_img = get_site_icon_url();
            }
            
            // Verificar si existen estilos personalizados
            $custom_styles = get_option(self::$customStylesOptionName, '');
            $has_custom_styles = !empty($custom_styles);
            
            wp_localize_script(
                'login-awp-theme-preview',
                'loginAwpThemes',
                array(
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('login_awp_theme_preview'),
                    'preview_nonce' => wp_create_nonce('login_awp_preview'),
                    'themes' => $this->predefinedThemes,
                    'selected' => $this->getSelectedTheme(),
                    'hasCustomStyles' => $has_custom_styles,
                    'currentImages' => array(
                        'logo' => esc_url($logo_img),
                        'background' => esc_url($background_img)
                    )
                )
            );
        }
    }

    /**
     * AJAX handler for theme preview
     */
    public function ajaxPreviewTheme(): void
    {
        // Check permissions and nonce
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have sufficient permissions', 'login-awp')));
            return;
        }
        
        if (!check_ajax_referer('login_awp_theme_preview', 'nonce', false)) {
            wp_send_json_error(array('message' => __('Invalid security token', 'login-awp')));
            return;
        }
        
        // Si los temas aún no están inicializados, inicializarlos aquí
        if (empty($this->predefinedThemes)) {
            $this->predefinedThemes = $this->registerPredefinedThemes();
        }
        
        $theme_key = isset($_POST['theme']) ? sanitize_text_field($_POST['theme']) : '';
        $theme_config = $this->getThemeConfig($theme_key);
        
        if ($theme_config) {
            wp_send_json_success(array('config' => $theme_config));
        } else {
            wp_send_json_error(array('message' => __('Theme not found', 'login-awp')));
        }
        
        wp_die();
    }

    /**
     * AJAX handler for saving theme
     */
    public function ajaxSaveTheme(): void
    {
        // Check permissions and nonce
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have sufficient permissions', 'login-awp')));
            return;
        }
        
        if (!check_ajax_referer('login_awp_theme_preview', 'nonce', false)) {
            wp_send_json_error(array('message' => __('Invalid security token', 'login-awp')));
            return;
        }
        
        // Si los temas aún no están inicializados, inicializarlos aquí
        if (empty($this->predefinedThemes)) {
            $this->predefinedThemes = $this->registerPredefinedThemes();
        }
        
        $theme_key = isset($_POST['theme']) ? sanitize_text_field($_POST['theme']) : '';
        
        if (isset($this->predefinedThemes[$theme_key])) {
            update_option(self::$themeOptionName, $theme_key);
            wp_send_json_success(array('message' => __('Theme saved successfully', 'login-awp')));
        } else {
            wp_send_json_error(array('message' => __('Invalid theme selected', 'login-awp')));
        }
        
        wp_die();
    }

    /**
     * Generate CSS for the selected theme
     *
     * @return string Generated CSS
     */
    public function generateThemeCSS(): string
    {
        $theme_key = $this->getSelectedTheme();
        
        // Si los temas aún no están inicializados, inicializarlos aquí
        if (empty($this->predefinedThemes)) {
            $this->predefinedThemes = $this->registerPredefinedThemes();
        }
        
        $theme = $this->getThemeConfig($theme_key);
        
        if (!$theme) {
            return '';
        }
        
        $custom_styles = get_option(self::$customStylesOptionName, '');
        
        // If we have custom styles, use those instead
        if (!empty($custom_styles)) {
            return $custom_styles;
        }
        
        // Otherwise generate CSS from theme config
        ob_start();
        ?>
body.login {
    background-color: <?php echo esc_attr($theme['colors']['bg']); ?>;
    font-family: <?php echo esc_attr($theme['typography']['font_family']); ?>;
    font-size: <?php echo esc_attr($theme['typography']['font_size']); ?>;
}

body.login div#login {
    background-color: <?php echo esc_attr($theme['colors']['container_bg']); ?>;
    border-radius: <?php echo esc_attr($theme['effects']['border_radius']); ?>;
    box-shadow: <?php echo esc_attr($theme['effects']['box_shadow']); ?>;
}

body.login div#login form#loginform p,
body.login div#login form#loginform p label {
    color: <?php echo esc_attr($theme['colors']['text']); ?>;
}

body.login div#login form#loginform p.submit input#wp-submit {
    background-color: <?php echo esc_attr($theme['colors']['button_bg']); ?>;
}

body.login div#login form#loginform p.submit input#wp-submit:hover {
    background-color: <?php echo esc_attr($theme['colors']['button_hover']); ?>;
}

body.login div#login p#nav a, 
body.login div#login p#backtoblog a {
    color: <?php echo esc_attr($theme['colors']['links']); ?>;
}
        <?php
        return ob_get_clean();
    }
}
