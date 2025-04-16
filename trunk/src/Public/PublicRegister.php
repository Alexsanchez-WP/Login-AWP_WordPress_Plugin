<?php

declare(strict_types=1);

/**
 * Register Login Area
 *
 * @author AWP-Software
 * @since 2.0.0
 * @version 3.0.0
 */

namespace Login\Awp\Public;

use Login\Awp\Admin\AdminRegister;
use Login\Awp\Admin\ThemeManager;

class PublicRegister
{
    public string $dirUrl;
    private ThemeManager $themeManager;
    
    public function __construct($dir_url)
    {
        $this->dirUrl = $dir_url . 'assets/';
        $this->themeManager = new ThemeManager($dir_url);
    }
    
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
        
        // Add the custom theme styles
        add_action(
            hook_name: 'login_head',
            callback: array($this, 'loginAwpThemeStyles')
        );
    }

    public function loginAwpStyles(): void
    {
        wp_enqueue_style(
            handle: 'vegasCSS',
            src: $this->dirUrl . 'css/vegas.min.css',
            deps: array(),
            ver: false
        );
        wp_enqueue_style(
            handle: 'loginCSS',
            src: $this->dirUrl . 'css/loginStyles.css',
            deps: array(),
            ver: false
        );
    }

    public function loginAwpScripts(): void
    {
        wp_enqueue_script(handle: 'jquery');
        wp_enqueue_script(
            handle: 'vegasJS',
            src: $this->dirUrl . 'js/vegas.min.js',
            deps: array('jquery'),
            ver: '2.5.4',
            args: true
        );
        wp_enqueue_script(
            handle: 'loginJS',
            src: $this->dirUrl . 'js/loginJS.js',
            deps: array('jquery'),
            ver: '1.0.0',
            args: true
        );
    }

    public function loginAwpLocalize(): void
    {
        $upload_img_logo = get_option(option: AdminRegister::$imgLogoName);
        $upload_img_logo = isset($upload_img_logo) && !empty($upload_img_logo) ?
            $upload_img_logo :
            get_site_icon_url();

        $upload_img_back = get_option(option: AdminRegister::$imgBackName);
        $upload_img_back = isset($upload_img_back) && !empty($upload_img_back) ?
            $upload_img_back :
            $this->dirUrl . 'img/slider.jpg';

        wp_localize_script(
            handle: 'loginJS',
            object_name: 'login_imagenes',
            l10n: array(
                'logo' => esc_url(url: $upload_img_logo),
                'sliders' => esc_url(url: $upload_img_back),
                'overlay' => esc_url(url: $this->dirUrl . 'img/overlay.png')
            )
        );
    }
    
    /**
     * Output theme styles in the login page head
     */
    public function loginAwpThemeStyles(): void
    {
        $theme_css = $this->themeManager->generateThemeCSS();
        if (!empty($theme_css)) {
            echo '<style id="login-awp-theme-styles">' . $theme_css . '</style>';
        }
    }
}
