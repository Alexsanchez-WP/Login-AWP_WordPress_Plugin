<?php

declare(strict_types=1);

/**
 * Register Admin Area
 *
 * @author AWP-Software
 * @since 2.0.0
 * @global LOGIN_AWP_DIR_URL
 * @global LOGIN_AWP_DOMAIN
 */

namespace Login\Awp\Admin;

class AdminRegister
{
    public string $domain = LOGIN_AWP_DOMAIN;
    public string $publicPath = LOGIN_AWP_DIR_URL;

    public static string $imgLogoName = 'login_awp_logo_url';
    public static string $imgBackName = 'login_awp_background_url';
    private string $adminTemplate = 'templates/menu_admin.phtml';
    private string $messageTemplate = 'templates/status_message.phtml';

    public function load(): void
    {
        add_action(
            hook_name: 'admin_menu',
            callback: array($this, 'registerSubMenu')
        );
        add_action(
            hook_name: 'admin_enqueue_scripts',
            callback: array($this, 'adminScripts')
        );
        add_action(
            hook_name: 'admin_enqueue_scripts',
            callback: array($this, 'adminStyles')
        );
        add_action(
            hook_name: 'admin_post_login_awp_form_action',
            callback: array($this, 'loginAwpAdminform')
        );

        add_action(
            hook_name: 'admin_notices',
            callback: array($this, 'statusMessage')
        );
    }

    public function registerSubMenu(): void
    {
        add_submenu_page(
            parent_slug: 'themes.php',
            page_title: __(
                text: 'Login AWP Plugin',
                domain: $this->domain
            ),
            menu_title: __(text: 'Login', domain: $this->domain),
            capability: 'manage_options',
            menu_slug: 'login-awp',
            callback: array($this, 'loginAwpSubMenuTemplate')
        );
    }

    public function loginAwpSubMenuTemplate(): void
    {
        if (\file_exists(filename: plugin_dir_path(file: __FILE__) . $this->adminTemplate)) {
            wp_create_nonce(action: 'login_awp_form_nonce');
            require_once plugin_dir_path(file: __FILE__) . $this->adminTemplate;

        }
    }

    public function adminStyles(): void
    {
        wp_enqueue_style(
            handle: 'loginAdminCSS',
            src: $this->publicPath . 'assets/css/loginAdminStyles.css',
            deps: array(),
            ver: false
        );
    }
    public function adminScripts(): void
    {
        wp_enqueue_media();
        wp_enqueue_script(handle: 'jquery');
        wp_enqueue_script(
            handle: 'loginAdminScript',
            src: $this->publicPath . 'assets/js/loginAdmin.js',
            deps: array('jquery'),
            ver: '1.0.0',
            args: true
        );
        wp_localize_script(
            handle: 'loginAdminScript',
            object_name: 'login_text',
            l10n: array(
                'text' => __(text: 'Select the image', domain: $this->domain),
                'delete_button' => __(text: 'Delete image', domain: $this->domain)
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
            wp_die(message: __(text: 'Verification failed', domain: $this->domain));
        }

        $message = "";

        if (isset($_POST['submit_button_login_awp'])) {

            $upload_img_logo = $_POST["upload-img-logo"];
            $upload_img_back = $_POST["upload-img-back"];

            if (
                isset($upload_img_logo) &&
                filter_var(value: $upload_img_logo, filter: \FILTER_VALIDATE_URL)
            ) {

                $message .= $this->updateOption(
                    upload_data: $upload_img_logo,
                    message: 'logo_status',
                    db_file: self::$imgLogoName
                );
            }

            if (
                isset($upload_img_back) &&
                filter_var(value: $upload_img_back, filter: \FILTER_VALIDATE_URL)
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
            !is_null(value: $_POST["delete-upload-img-logo-button"])
        ) {
            $message .= $this->updateOption(
                upload_data: "",
                message: 'logo_status',
                db_file: self::$imgLogoName
            );

        }

        if (
            isset($_POST["delete-upload-img-back-button"]) &&
            !is_null(value: $_POST["delete-upload-img-back-button"])
        ) {
            $message .= $this->updateOption(
                upload_data: "",
                message: 'background_status',
                db_file: self::$imgBackName
            );
        }

        $url = parse_url(url: $_POST["_wp_http_referer"])["path"] . "?page=login-awp";
        wp_redirect(location: sanitize_url(url: $url . $message));
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
        $data = sanitize_text_field(str: $upload_data);
        if (update_option(option: $db_file, value: $data)) {

            $status = "&{$message}=success";
        }
        return $status;
    }

    public function statusMessage(): void
    {
        if (isset($_GET['logo_status'])) {
            $this->messageTemplate(
                status: sanitize_text_field($_GET['logo_status']),
                message: 'logo'
            );
        }

        if (isset($_GET['background_status'])) {
            $this->messageTemplate(
                status: sanitize_text_field($_GET['background_status']),
                message: 'background'
            );
        }
    }

    private function messageTemplate($status, $message): void
    {
        switch ($status) {
            case 'success':
                $class = 'notice notice-success is-dismissible';
                $text = __(text: "The login area {$message} has been successfully changed.", domain: $this->domain);
                break;
            case 'error':
                $class = 'notice notice-error is-dismissible';
                $text = __(text: "The login area {$message} has not been changed.", domain: $this->domain);
                break;
            default:
                $class = 'notice notice-info is-dismissible';
                $text = __(text: "No actions were taken", domain: $this->domain);
                break;
        }

        if (\file_exists(filename: plugin_dir_path(file: __FILE__) . $this->messageTemplate)) {
            require_once plugin_dir_path(file: __FILE__) . $this->messageTemplate;
        }
    }
}
