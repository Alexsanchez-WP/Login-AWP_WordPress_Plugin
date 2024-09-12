<?php 

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



