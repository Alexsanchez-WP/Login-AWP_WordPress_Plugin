<?php

/**
 * Plugin Name: Login A-WP
 * Plugin URI: 
 * Description: Este plugin modifica el área de login para el administrador de WordPress
 * Version: 1.0.1
 * Author: Alexsanchez-WP
 * Author URI: https://github.com/Alexsanchez-WP
 * Text Domain: login_awp
 * License: GPLv2
 * Released under the GNU General Public License (GPL)
 * https://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 */


if (!defined('ABSPATH')) {
    exit;
}


if (!function_exists('awp_admin_styles')) {

    function awp_admin_styles()
    {
        wp_enqueue_style('vegasCSS', plugins_url('/assets/css/vegas.min.css', __FILE__), array(), false);
        wp_enqueue_style('loginCSS', plugins_url('/assets/css/loginStyles.css', __FILE__), array(), false);

        wp_enqueue_script('jquery');
        wp_enqueue_script('vegasJS', plugins_url('/assets/js/vegas.min.js', __FILE__), array('jquery'), '2.5.1', true);
        wp_enqueue_script('loginJS', plugins_url('/assets/js/loginJs.js', __FILE__), array('jquery'), '1.0.0', true);
    }

    add_action('login_enqueue_scripts', 'awp_admin_styles', 10);
}


function admin_styles()
{

    wp_enqueue_media();
    wp_enqueue_script('jquery');
    wp_enqueue_script('loginAdminScript', plugins_url('/assets/js/loginAdmin.js ', __FILE__), array('jquery'), '1.0.0', true);
    wp_localize_script(
        'loginAdminScript',
        'login_text',
        array(
            'text' => __('Seleccione imagen', 'login_awp')
        )
    );
}

add_action('admin_enqueue_scripts', 'admin_styles');

require_once plugin_dir_path(__FILE__) . 'inc/register.php';
register_activation_hook(__FILE__, 'register_plugin_login_awp');

require_once plugin_dir_path(__FILE__) . 'inc/media.php';

function register_sub_menu()
{
    add_submenu_page('themes.php', __('Datos para el Login', 'login_awp'), __('Login', 'login_awp'), 'manage_options', 'login-awp', 'call_sub_menu');
}
add_action('admin_menu', 'register_sub_menu');

function call_sub_menu()
{
?>

<div id="form-login">
    <div class="wrap">
        <h1><?php esc_html_e(get_admin_page_title()); ?></h1>

        <form metod="POST">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><?php _e('Logo', 'login_awp') ?></th>
                        <td>
                            <div>
                                <input class="regular-text code" type="url" id="upload-img" name="image">
                                <button class="upload-img button">
                                    <?php _e('Upload logo', 'login_awp') ?>
                                </button>
                            </div>
                            <p class="description">
                                <?php _e('Logo que se muestra en el formulario de login y/o registro', 'login_awp'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Imagen de fondo', 'login_awp') ?></th>
                        <td>
                            <div>
                                <input class="regular-text code" type="url" id="upload-img-01" name="image">
                                <button class="upload-img-01 button">
                                    <?php _e('Upload image', 'login_awp') ?>
                                </button>
                            </div>
                            <p class="description">
                                <?php _e('Imagen para el slider de fondo en el área de login', 'login_awp'); ?>
                                <code>1</code>
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p class="submit">
                <button class="button button-primary" title="<?php _e('Save more images', 'login_awp') ?>">
                    <?php _e('Save changes', 'login_awp') ?>
                </button>
                <button class="button" id="more-images" title="<?php _e('Load more images', 'login_awp') ?>">
                    <?php _e('Load more images +', 'login_awp') ?>
                </button>
            </p>

        </form>
    </div>
</div>
<?php
}