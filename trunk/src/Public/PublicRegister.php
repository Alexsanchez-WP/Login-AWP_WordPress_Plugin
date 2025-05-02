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
    public $dirUrl;
    private $themeManager;
    
    public function __construct($dir_url)
    {
        $this->dirUrl = $dir_url . 'assets/';
        $this->themeManager = new ThemeManager($dir_url);
    }
    
    public function load()
    {
        add_action(
            'login_enqueue_scripts',
            array($this, 'loginAwpStyles'),
            1
        );
        add_action(
            'login_enqueue_scripts',
            array($this, 'loginAwpScripts'),
            1
        );
        add_action(
            'login_enqueue_scripts',
            array($this, 'loginAwpLocalize'),
            1
        );
        
        // Add the custom theme styles
        add_action(
            'login_head',
            array($this, 'loginAwpThemeStyles')
        );
    }

    public function loginAwpStyles()
    {
        wp_enqueue_style(
            'vegasCSS',
            $this->dirUrl . 'css/vegas.min.css',
            array(),
            false
        );
        wp_enqueue_style(
            'loginCSS',
            $this->dirUrl . 'css/loginStyles.css',
            array(),
            false
        );
    }

    public function loginAwpScripts()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_script(
            'vegasJS',
            $this->dirUrl . 'js/vegas.min.js',
            array('jquery'),
            '2.5.4',
            true
        );
        wp_enqueue_script(
            'loginJS',
            $this->dirUrl . 'js/loginJS.js',
            array('jquery'),
            '1.0.0',
            true
        );
    }

    public function loginAwpLocalize()
    {
        $upload_img_logo = get_option(AdminRegister::$imgLogoName);
        $upload_img_logo = isset($upload_img_logo) && !empty($upload_img_logo) ?
            $upload_img_logo :
            get_site_icon_url();

        $upload_img_back = get_option(AdminRegister::$imgBackName);
        $upload_img_back = isset($upload_img_back) && !empty($upload_img_back) ?
            $upload_img_back :
            $this->dirUrl . 'img/slider.jpg';

        wp_localize_script(
            'loginJS',
            'login_imagenes',
            array(
                'logo' => esc_url($upload_img_logo),
                'sliders' => esc_url($upload_img_back),
                'overlay' => esc_url($this->dirUrl . 'img/overlay.png')
            )
        );
    }
    
    /**
     * Output theme styles in the login page head
     */
    public function loginAwpThemeStyles()
    {
        $theme_css = $this->themeManager->generateThemeCSS();
        if (!empty($theme_css)) {
            echo '<style id="login-awp-theme-styles">' . $theme_css . '</style>';
        }
    }
}
