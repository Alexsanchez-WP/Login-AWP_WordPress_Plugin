<?php

declare(strict_types=1);

/**
 * Register Login Area
 *
 * @author AWP-Software
 * @since 2.0.0
 * @global LOGIN_AWP_DIR_URL
 */

namespace Login\Awp\Public;

use Login\Awp\Admin\AdminRegister;

class PublicRegister
{
    public string $publicPath = LOGIN_AWP_DIR_URL;
    public function load(): void
    {
        add_action(
            hook_name: 'login_enqueue_scripts',
            callback: array($this, 'loginAwpStyles'),
            priority: 1
        );
        add_action(
            hook_name: 'login_enqueue_scripts',
            callback: array($this, 'loginAwpScripts'),
            priority: 1
        );
        add_action(
            hook_name: 'login_enqueue_scripts',
            callback: array($this, 'loginAwpLocalize'),
            priority: 1
        );
    }

    public function loginAwpStyles(): void
    {
        wp_enqueue_style(
            handle: 'vegasCSS',
            src: $this->publicPath . 'assets/css/vegas.min.css',
            deps: array(),
            ver: false
        );
        wp_enqueue_style(
            handle: 'loginCSS',
            src: $this->publicPath . 'assets/css/loginStyles.css',
            deps: array(),
            ver: false
        );
    }

    public function loginAwpScripts(): void
    {
        wp_enqueue_script(handle: 'jquery');
        wp_enqueue_script(
            handle: 'vegasJS',
            src: $this->publicPath . 'assets/js/vegas.min.js',
            deps: array('jquery'),
            ver: '2.5.4',
            args: true
        );
        wp_enqueue_script(
            handle: 'loginJS',
            src: $this->publicPath . 'assets/js/loginJS.js',
            deps: array('jquery'),
            ver: '1.0.0',
            args: true
        );
    }

    public function loginAwpLocalize(): void
    {
        $upload_img_logo = get_option(AdminRegister::$imgLogoName, null) ??
            get_site_icon_url();
        $upload_img_back = get_option(AdminRegister::$imgBackName, null) ??
            $this->publicPath . 'assets/img/slider.jpg';

        wp_localize_script(
            handle: 'loginJS',
            object_name: 'login_imagenes',
            l10n: array(
                'logo' => esc_url(url: $upload_img_logo),
                'sliders' => esc_url(url: $upload_img_back),
                'overlay' => esc_url(url: $this->publicPath . 'assets/img/overlay.png')
            )
        );
    }
}
