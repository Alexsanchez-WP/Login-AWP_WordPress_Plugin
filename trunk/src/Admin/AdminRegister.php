<?php

declare(strict_types=1);

/**
 * Register Admin Area
 *
 * @author AWP-Software
 * @since 2.0.0
 * @version 3.0.0
 */

namespace Login\Awp\Admin;

class AdminRegister
{
    public string $dirUrl;

    public static string $imgLogoName = 'login_awp_logo_url';
    public static string $imgBackName = 'login_awp_background_url';
    private string $adminTemplate = 'templates/menu_admin.php';
    private string $messageTemplate = 'templates/status_message.php';
    private ThemeManager $themeManager;
    private StyleBuilder $styleBuilder;

    public function __construct($dir_url)
    {
        $this->dirUrl = $dir_url . 'assets/';
        $this->themeManager = new ThemeManager($dir_url);
        $this->styleBuilder = new StyleBuilder($dir_url);
    }

    public function load(): void
    {
        add_action(
            'admin_menu',
            array($this, 'registerSubMenu')
        );
        add_action(
            'admin_enqueue_scripts',
            array($this, 'adminScripts')
        );
        add_action(
            'admin_enqueue_scripts',
            array($this, 'adminStyles')
        );
        add_action(
            'admin_post_login_awp_form_action',
            array($this, 'loginAwpAdminform')
        );

        add_action(
            'admin_notices',
            array($this, 'statusMessage')
        );
        
        // Load theme manager and style builder components
        $this->themeManager->load();
        $this->styleBuilder->load();
    }

    public function registerSubMenu(): void
    {
        add_submenu_page(
            parent_slug: 'themes.php',
            page_title: __('Login AWP Plugin', 'login-awp'),
            menu_title: __('Login', 'login-awp'),
            capability: 'manage_options',
            menu_slug: 'login-awp',
            callback: array($this, 'loginAwpSubMenuTemplate')
        );
    }

    public function loginAwpSubMenuTemplate(): void
    {
        if (\file_exists(plugin_dir_path(__FILE__) . $this->adminTemplate)) {
            wp_create_nonce('login_awp_form_nonce');
            
            // Current active tab
            $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'general';
            
            include_once plugin_dir_path(__FILE__) . $this->adminTemplate;
        }
    }

    public function adminStyles(): void
    {
        wp_enqueue_style(
            handle: 'loginAdminCSS',
            src: $this->dirUrl . 'css/loginAdminStyles.css',
            deps: array(),
            ver: '3.0.0'
        );
    }
    
    public function adminScripts(): void
    {
        wp_enqueue_media();
        wp_enqueue_script('jquery');
        wp_enqueue_script(
            handle: 'loginAdminScript',
            src: $this->dirUrl . 'js/loginAdmin.js',
            deps: array('jquery'),
            ver: '3.0.0',
            args: true
        );
        wp_localize_script(
            handle: 'loginAdminScript',
            object_name: 'login_text',
            l10n: array(
                'text' => __('Select the image', 'login-awp'),
                'delete_button' => __('Delete image', 'login-awp')
            )
        );
    }


    public function loginAwpAdminform(): void
    {

        if (
            !isset($_POST['login_awp_form_nonce_field']) ||
            !wp_verify_nonce(
                nonce: $_POST['login_awp_form_nonce_field'],
                action: 'login_awp_form_nonce'
            )
        ) {
            wp_die(__('Verification failed', 'login-awp'));
        }

        $message = "";

        if (isset($_POST['submit_button_login_awp'])) {

            $upload_img_logo = $_POST["upload-img-logo"];
            $upload_img_back = $_POST["upload-img-back"];

            if (
                isset($upload_img_logo) &&
                filter_var($upload_img_logo, \FILTER_VALIDATE_URL)
            ) {

                $message .= $this->updateOption(
                    upload_data: $upload_img_logo,
                    message: 'logo_status',
                    db_file: self::$imgLogoName
                );
            }

            if (
                isset($upload_img_back) &&
                filter_var($upload_img_back, \FILTER_VALIDATE_URL)
            ) {
                $message .= $this->updateOption(
                    upload_data: $upload_img_back,
                    message: 'background_status',
                    db_file: self::$imgBackName
                );
            }
        }

        if (
            isset($_POST["delete-upload-img-logo-button"]) &&
            !is_null($_POST["delete-upload-img-logo-button"])
        ) {
            $message .= $this->updateOption(
                upload_data: "",
                message: 'logo_status',
                db_file: self::$imgLogoName
            );

        }

        if (
            isset($_POST["delete-upload-img-back-button"]) &&
            !is_null($_POST["delete-upload-img-back-button"])
        ) {
            $message .= $this->updateOption(
                upload_data: "",
                message: 'background_status',
                db_file: self::$imgBackName
            );
        }

        $url = parse_url($_POST["_wp_http_referer"])["path"] . "?page=login-awp";
        wp_redirect(sanitize_url($url . $message));
        exit;
    }

    /**
     * Summary of updateOption
     *
     * @param string $upload_data
     * @param string $message
     * @param string $db_file
     * @return string
     */
    private function updateOption($upload_data, $message, $db_file): string
    {
        $status = "&{$message}=error";
        $data = sanitize_text_field($upload_data);
        if (update_option($db_file, $data)) {

            $status = "&{$message}=success";
        }
        return $status;
    }

    public function statusMessage(): void
    {
        if (isset($_GET['logo_status'])) {
            $this->messageTemplate(
                sanitize_text_field($_GET['logo_status']),
                __('logo', 'login-awp')
            );
        }

        if (isset($_GET['background_status'])) {
            $this->messageTemplate(
                sanitize_text_field($_GET['background_status']),
                __('background', 'login-awp')
            );
        }
    }

    private function messageTemplate($status, $message): void
    {
        switch ($status) {
            case 'success':
                $class = 'notice notice-success is-dismissible';
                $text = sprintf(
                    __(
                        "The login area %s has been successfully changed.",
                        'login-awp'
                    ),
                    $message
                );
                break;
            case 'error':
                $class = 'notice notice-error is-dismissible';
                $text = sprintf(
                    __(
                        "The login area %s has not been changed.",
                        'login-awp'
                    ),
                    $message
                );
                break;
            default:
                $class = 'notice notice-info is-dismissible';
                $text = __("No actions were taken", 'login-awp');
                break;
        }

        if (\file_exists(plugin_dir_path(__FILE__) . $this->messageTemplate)) {
            include_once plugin_dir_path(__FILE__) . $this->messageTemplate;
        }
    }
}
