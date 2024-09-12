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

    public function load(): void
    {
        add_action(
            hook_name: 'admin_menu',
            callback: array($this, 'registerSubMenu')
        );
        add_action(
            hook_name: 'admin_enqueue_scripts',
            callback: array($this, 'adminStyles')
        );
        add_action(
            hook_name: 'admin_post_login_awp_form_action',
            callback: array($this, 'loginAwsAdminform')
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
        $admin_template = plugin_dir_path(file: __FILE__) . 'templates/menu_admin.phtml';
        if (\file_exists($admin_template)) {
            wp_create_nonce(action: 'login_awp_form_nonce');
            require_once $admin_template;
        }
    }

    public function adminStyles(): void
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


    public function loginAwsAdminform(): void
    {

        var_dump($_POST);
        // TODO: validate form

        echo $_POST['login_awp_form_nonce_field'];
        echo wp_verify_nonce($_POST['login_awp_form_nonce_field'], 'login_awp_form_nonce');
        // Verificar que el nonce es válido
        // if (!isset($_POST['login_awp_form_field']) || !wp_verify_nonce($_POST['login_awp_form_field'], 'login_awp_form')) {
        //     wp_die(__('Nonce verification failed', 'text-domain'));
        // }

        // // Verificar que el formulario fue enviado
        // if (isset($_POST['submit_button'])) {
        //     // Sanitizar y procesar los datos del formulario
        //     $example_input = sanitize_text_field($_POST['example_input']);

        //     // Aquí puedes realizar las acciones necesarias con los datos del formulario
        //     // Por ejemplo, guardar los datos en la base de datos

        //     // Redirigir después de procesar el formulario
        //     wp_redirect(admin_url('admin.php?page=my_plugin_page&status=success'));
        //     exit;
        // }
    }
}
