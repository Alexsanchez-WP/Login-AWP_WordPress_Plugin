
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
  wp_enqueue_script('loginAdminScript', plugins_url('/assets/js/loginAdmin.js', __FILE__), array('jquery'), '1.0.0', true);
  wp_localize_script(
    'loginAdminScript',
    'login_text',
    array(
      'text' => __('Seleccione imagen', 'register_directory')
    )
  );
}

add_action('admin_enqueue_scripts', 'admin_styles');


function register_sub_menu()
{
  add_submenu_page('themes.php', __('Datos para el Login', 'register_directory'), __('Login', 'register_directory'), 'manage_options', 'login-awp', 'call_sub_menu');
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
              <th scope="row"><?php _e('Logo', 'register_directory') ?></th>
              <td>
                <div>
                  <input class="regular-text code" type="url" id="upload-img" name="image">
                  <button class="upload-img button">
                    <?php _e('Upload logo', 'register_directory') ?>
                  </button>
                </div>
                <p class="description">
                  <?php _e('Logo que se muestra en el formulario de login y/o registro', 'register_directory'); ?>
                </p>
              </td>
            </tr>
            <tr>
              <th scope="row"><?php _e('Imagen de fondo', 'register_directory') ?></th>
              <td>
                <div>
                  <input class="regular-text code" type="url" id="upload-img-01" name="image">
                  <button class="upload-img-01 button">
                    <?php _e('Upload image', 'register_directory') ?>
                  </button>
                </div>
                <p class="description">
                  <?php _e('Imagen para el slider de fondo en el área de login', 'register_directory'); ?>
                  <code>1</code>
                </p>
              </td>
            </tr>
          </tbody>
        </table>
        <p class="submit">
          <button class="button button-primary" title="<?php _e('Save more images', 'register_directory') ?>">
            <?php _e('Save changes', 'register_directory') ?>
          </button>
          <button class="button" id="more-images" title="<?php _e('Load more images', 'register_directory') ?>">
            <?php _e('Load more images +', 'register_directory') ?>
          </button>
        </p>

      </form>
    </div>
  </div>
<?php
}
