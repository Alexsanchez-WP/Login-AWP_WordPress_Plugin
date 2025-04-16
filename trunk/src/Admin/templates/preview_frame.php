<?php
/**
 * Preview frame template
 *
 * @package Login\Awp\Admin
 * @author AWP-Software
 * @since 3.0.0
 * @version 3.0.0
 */

if (!defined('ABSPATH')) {
    die('You are not allowed to call this page directly.');
}

// Get current logo and background images using the correct option names
$logo_img = get_option('login_awp_logo_url');
$background_img = get_option('login_awp_background_url');

// Default background if not set
if (empty($background_img)) {
    $background_img = plugins_url('assets/img/slider.jpg', dirname(__DIR__, 2));
}

// Default logo uses site icon if available
if (empty($logo_img)) {
    $logo_img = get_site_icon_url();
}

// Generate a mock login form similar to WordPress login
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php _e('Login Preview', 'login-awp'); ?></title>
    <?php
    // Include basic WP styling for login page
    wp_enqueue_style('login');
    wp_print_styles(array('login'));
    
    // Custom styles for preview
    if (isset($_GET['styles']) && $_GET['styles']) {
        echo '<style>' . wp_kses_post(stripslashes($_GET['styles'])) . '</style>';
    } else {
        // Try to load theme styles if available
        if (class_exists('\\Login\\Awp\\Admin\\ThemeManager')) {
            $theme_manager = new \Login\Awp\Admin\ThemeManager('');
            echo '<style>' . $theme_manager->generateThemeCSS() . '</style>';
        }
    }
    ?>
    <style>
        body.login {
            background-image: url(<?php echo esc_url($background_img); ?>);
            background-size: cover;
            background-position: center;
        }
        body.login div#login h1 a {
            background-image: url(<?php echo esc_url($logo_img); ?>);
            background-size: 100%;
        }
    </style>
</head>
<body class="login wp-core-ui">
    <div id="login">
        <h1><a href="<?php echo esc_url(home_url('/')); ?>"><?php echo get_bloginfo('name'); ?></a></h1>
        <form name="loginform" id="loginform" action="#" method="post">
            <p>
                <label for="user_login"><?php _e('Username or Email Address', 'login-awp'); ?></label>
                <input type="text" name="log" id="user_login" class="input" value="" size="20" autocapitalize="off" autocomplete="username">
            </p>
            
            <p>
                <label for="user_pass"><?php _e('Password', 'login-awp'); ?></label>
                <input type="password" name="pwd" id="user_pass" class="input" value="" size="20" autocomplete="current-password">
            </p>
            
            <p class="forgetmenot">
                <input name="rememberme" type="checkbox" id="rememberme" value="forever">
                <label for="rememberme"><?php _e('Remember Me', 'login-awp'); ?></label>
            </p>
            
            <p class="submit">
                <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php _e('Log In', 'login-awp'); ?>">
            </p>
        </form>
        
        <p id="nav">
            <a href="#"><?php _e('Lost your password?', 'login-awp'); ?></a>
        </p>
        
        <p id="backtoblog">
            <a href="#"><?php _e('← Go to Site', 'login-awp'); ?></a>
        </p>
    </div>
    <script>
        // Prevent any form submissions in preview
        document.getElementById('loginform').addEventListener('submit', function(e) {
            e.preventDefault();
            return false;
        });
    </script>
</body>
</html>
