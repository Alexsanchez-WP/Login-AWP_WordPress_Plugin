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

        wp_enqueue_style('admin-styles', plugins_url('/assets/css/admin-styles.css', __FILE__));

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
        $admin_template = plugin_dir_path(file: __FILE__) . 'templates/menu_admin.phtml';

        if (\file_exists(filename: $admin_template)) {
            wp_create_nonce(action: 'login_awp_form_nonce');
            require_once $admin_template;
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
                'text' => __(text: 'Seleccione imagen', domain: $this->domain)
            )
        );
    }


    public function loginAwpAdminform(): void
    {
        // TODO delete images
        if (
            !isset($_POST['login_awp_form_nonce_field']) ||
            !wp_verify_nonce(
                nonce: $_POST['login_awp_form_nonce_field'],
                action: 'login_awp_form_nonce'
            )
        ) {
            wp_die(message: __(text: 'Verification failed', domain: $this->domain));
        }

        if (isset($_POST['submit_button_login_awp'])) {

            $upload_img_logo = $_POST["upload-img-logo"];
            $upload_img_back = $_POST["upload-img-back"];
            $message = "";

            if (
                isset($upload_img_logo) &&
                filter_var(value: $upload_img_logo, filter: \FILTER_VALIDATE_URL)
            ) {

                $message .= $this->updateOption(
                    upload_img: $upload_img_logo,
                    message: 'logo_status',
                    db_file: self::$imgLogoName
                );
            }

            if (
                isset($upload_img_back) &&
                filter_var(value: $upload_img_back, filter: \FILTER_VALIDATE_URL)
            ) {
                $message .= $this->updateOption(
                    upload_img: $upload_img_back,
                    message: 'background_status',
                    db_file: self::$imgBackName
                );
            }

            wp_redirect(location: sanitize_url(url: $_POST["_wp_http_referer"] . $message));
            exit;
        }
    }

    /**
     * Summary of updateOption
     *
     * @param string $upload_img
     * @param string $message
     * @param string $db_file
     * @return string
     */
    private function updateOption(string $upload_img, string $message, string $db_file): string
    {
        $status = "&{$message}=error";
        $img_back = sanitize_text_field(str: $upload_img);
        if (update_option(option: $db_file, value: $img_back)) {
            $status = "&{$message}=success";
        }
        return $status;
    }
}
