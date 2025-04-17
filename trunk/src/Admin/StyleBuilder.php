<?php

declare(strict_types=1);

/**
 * Style Builder for Login AWP
 *
 * @package Login\Awp\Admin
 * @author AWP-Software
 * @since 3.0.0
 * @version 3.0.0
 */

namespace Login\Awp\Admin;

class StyleBuilder
{
    public string $dirUrl;
    private string $customStyleTemplate = 'templates/style_builder.php';
    private string $previewTemplate = 'templates/preview_frame.php';
    private array $i18n = [];

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
     * Initialize the style builder
     */
    public function load(): void
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueueAdminAssets'));
        add_action('wp_ajax_login_awp_save_custom_styles', array($this, 'ajaxSaveCustomStyles'));
        add_action('wp_ajax_login_awp_reset_custom_styles', array($this, 'ajaxResetCustomStyles'));
        add_action('wp_ajax_login_awp_get_preview', array($this, 'ajaxGetPreview'));
        
        // Inicializar traducciones después de que WordPress esté listo
        add_action('init', array($this, 'initializeI18n'));
    }
    
    /**
     * Inicializa las traducciones una vez que WordPress está listo
     */
    public function initializeI18n(): void
    {
        $this->i18n = [
            'save_success' => __('Custom styles saved successfully', 'login-awp'),
            'save_error' => __('Error saving custom styles', 'login-awp'),
            'reset_success' => __('Custom styles have been reset', 'login-awp'),
            'reset_error' => __('Error resetting custom styles', 'login-awp'),
            'confirm_reset' => __('Are you sure you want to reset all custom styles? This cannot be undone.', 'login-awp'),
            'preview_not_found' => __('Preview template not found', 'login-awp'),
            'insufficient_permissions' => __('You do not have sufficient permissions', 'login-awp'),
            'invalid_token' => __('Invalid security token', 'login-awp'),
        ];
    }

    /**
     * Enqueue admin assets for style builder
     */
    public function enqueueAdminAssets(): void
    {
        $screen = get_current_screen();
        
        // Only load on our plugin page
        if ($screen && $screen->id === 'appearance_page_login-awp') {
            // Color picker
            wp_enqueue_style('wp-color-picker');
            
            // jQuery UI for draggable/sortable elements
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-draggable');
            wp_enqueue_script('jquery-ui-droppable');
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('jquery-ui-slider');
            
            // Main style builder assets
            wp_enqueue_style(
                'login-awp-style-builder',
                $this->dirUrl . 'css/style-builder.css',
                array('wp-color-picker'),
                '3.0.0'
            );
            
            wp_enqueue_script(
                'login-awp-style-builder',
                $this->dirUrl . 'js/styleBuilder.js',
                array('jquery', 'jquery-ui-core', 'wp-color-picker', 'jquery-ui-slider'),
                '3.0.0',
                true
            );
            
            // Google Fonts API for font selector
            wp_enqueue_script(
                'login-awp-google-fonts',
                'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js',
                array(),
                '1.6.26',
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
            
            // Localize script with data
            wp_localize_script(
                'login-awp-style-builder',
                'loginAwpStyleBuilder',
                array(
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('login_awp_style_builder'),
                    'previewUrl' => add_query_arg(
                        array(
                            'action' => 'login_awp_get_preview',
                            '_wpnonce' => wp_create_nonce('login_awp_preview'),
                        ),
                        admin_url('admin-ajax.php')
                    ),
                    'customStyles' => $this->getCustomStyles(),
                    'defaultColors' => array(
                        'background' => '#f0f0f1',
                        'text' => '#000000',
                        'container' => 'rgba(255, 255, 255, 0.8)',
                        'button' => '#4182E0',
                        'button_hover' => '#014F7F',
                        'links' => '#808080',
                    ),
                    'fonts' => $this->getAvailableFonts(),
                    'currentImages' => array(
                        'logo' => esc_url($logo_img),
                        'background' => esc_url($background_img)
                    ),
                    'i18n' => $this->i18n,
                )
            );
        }
    }

    /**
     * Get available fonts
     *
     * @return array List of available fonts
     */
    private function getAvailableFonts(): array
    {
        return array(
            'system' => array(
                'label' => __('System Fonts', 'login-awp'),
                'fonts' => array(
                    '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif' => __('System Default', 'login-awp'),
                    'Arial, sans-serif' => 'Arial',
                    '"Helvetica Neue", Helvetica, Arial, sans-serif' => 'Helvetica',
                    'Georgia, serif' => 'Georgia',
                    '"Times New Roman", Times, serif' => 'Times New Roman',
                    'Tahoma, Geneva, sans-serif' => 'Tahoma',
                    'Verdana, Geneva, sans-serif' => 'Verdana',
                ),
            ),
            'google' => array(
                'label' => __('Google Fonts', 'login-awp'),
                'fonts' => array(
                    '"Open Sans", sans-serif' => 'Open Sans',
                    '"Roboto", sans-serif' => 'Roboto',
                    '"Lato", sans-serif' => 'Lato',
                    '"Montserrat", sans-serif' => 'Montserrat',
                    '"Poppins", sans-serif' => 'Poppins',
                    '"Raleway", sans-serif' => 'Raleway',
                    '"Source Sans Pro", sans-serif' => 'Source Sans Pro',
                ),
            ),
        );
    }

    /**
     * Get custom styles from database
     *
     * @return string Custom CSS styles
     */
    public function getCustomStyles(): string
    {
        return get_option(ThemeManager::$customStylesOptionName, '');
    }

    /**
     * AJAX handler for saving custom styles
     */
    public function ajaxSaveCustomStyles(): void
    {
        // Check permissions and nonce
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => $this->i18n['insufficient_permissions']));
            return;
        }
        
        if (!check_ajax_referer('login_awp_style_builder', 'nonce', false)) {
            wp_send_json_error(array('message' => $this->i18n['invalid_token']));
            return;
        }
        
        $css = isset($_POST['css']) ? wp_kses_post($_POST['css']) : '';
        
        // Save the custom CSS to the database
        $result = update_option(ThemeManager::$customStylesOptionName, $css);
        
        if ($result) {
            // Also set theme to 'custom'
            update_option(ThemeManager::$themeOptionName, 'custom');
            wp_send_json_success(array('message' => $this->i18n['save_success']));
        } else {
            wp_send_json_error(array('message' => $this->i18n['save_error']));
        }
        
        wp_die();
    }

    /**
     * AJAX handler for resetting custom styles
     */
    public function ajaxResetCustomStyles(): void
    {
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => $this->i18n['insufficient_permissions']));
            return;
        }
        
        // Check for either StyleBuilder or ThemeManager nonce
        $style_builder_nonce_valid = check_ajax_referer('login_awp_style_builder', 'nonce', false);
        $theme_preview_nonce_valid = check_ajax_referer('login_awp_theme_preview', 'nonce', false);
        
        if (!$style_builder_nonce_valid && !$theme_preview_nonce_valid) {
            wp_send_json_error(array('message' => $this->i18n['invalid_token']));
            return;
        }
        
        // Delete the custom CSS from the database
        $result = delete_option(ThemeManager::$customStylesOptionName);
        
        // Set theme back to default
        update_option(ThemeManager::$themeOptionName, 'default');
        
        wp_send_json_success(array('message' => $this->i18n['reset_success']));
        
        wp_die();
    }

    /**
     * AJAX handler for login preview
     */
    public function ajaxGetPreview(): void
    {
        // Check nonce
        if (!check_ajax_referer('login_awp_preview', '_wpnonce', false)) {
            wp_die($this->i18n['invalid_token']);
            return;
        }
        
        if (\file_exists(plugin_dir_path(__FILE__) . $this->previewTemplate)) {
            include_once plugin_dir_path(__FILE__) . $this->previewTemplate;
            exit;
        }
        
        wp_die($this->i18n['preview_not_found']);
    }

    /**
     * Render the style builder template
     */
    public function renderStyleBuilder(): void
    {
        if (\file_exists(plugin_dir_path(__FILE__) . $this->customStyleTemplate)) {
            include_once plugin_dir_path(__FILE__) . $this->customStyleTemplate;
        }
    }
}
