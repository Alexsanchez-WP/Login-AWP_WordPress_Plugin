<?php

/**
 * Admin Menu Template
 *
 * @package Login\Awp\Admin
 * @requires Login\Awp\Admin\AdminRegister
 * @autor AWP-Software
 * @since 2.0.0
 * @version 2.0.0
 * @param string $upload_img_logo
 * @param string $upload_img_back
 */

if (!defined('ABSPATH')) {
    die('You are not allowed to call this page directly.');
}

$upload_img_logo = get_option(self::$imgLogoName);
$upload_img_back = get_option(self::$imgBackName);

?>

<div style="margin: 50px;">
    <div id="form-login">
        <div class="wrap">
            <h1><?php esc_html_e(get_admin_page_title()); ?></h1>
            <form method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <div class="flex-container">
                    <div class="flex-item">
                        <div class="row">
                            <h2><?php _e('Logo', 'login-awp') ?></h2>
                        </div>
                        <div>
                            <input class="regular-text mg-b20" type="url" id="upload-img-logo" name="upload-img-logo"
                                value="<?php echo esc_url($upload_img_logo); ?>">
                            <button class="upload-img-logo button mg-b20" type="button">
                                <?php _e('Upload logo', 'login-awp') ?>
                            </button>
                            <p class="description mg-b20">
                                <?php _e('Logo displayed on the login and/or registration form', 'login-awp'); ?>
                            </p>
                            <div id="upload-img-logo-container">
                                <?php if (isset($upload_img_logo) && !empty($upload_img_logo)) { ?>
                                    <button name="delete-upload-img-logo-button" class="delete-img-logo button mg-b20">
                                        <?php _e('Delete image', 'login-awp') ?>
                                    </button>
                                    <img src="<?php echo esc_url($upload_img_logo); ?>" class="login-awp-img" alt="">
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="flex-item">
                        <div class="row">
                            <h2>
                                <?php _e('Background image', 'login-awp') ?>
                            </h2>
                        </div>
                        <div>
                            <input class="regular-text mg-b20" type="url" id="upload-img-back" name="upload-img-back"
                                value="<?php echo esc_url($upload_img_back); ?>">
                            <button class="upload-img-back button mg-b20" type="button">
                                <?php _e('Upload image', 'login-awp') ?>
                            </button>
                        </div>
                        <p class="description mg-b20">
                            <?php _e('Image of background slider in login area', 'login-awp'); ?>
                        </p>
                        <div id="upload-img-back-container">
                            <?php if (isset($upload_img_back) && !empty($upload_img_back)) { ?>
                                <button name="delete-upload-img-back-button" class="delete-img-logo button mg-b20">
                                    <?php _e('Delete image', 'login-awp') ?>
                                </button>
                                <img src="<?php echo esc_url($upload_img_back); ?>" class="login-awp-img" alt="">
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="submit">
                    <?php wp_nonce_field(
                        action: 'login_awp_form_nonce',
                        name: 'login_awp_form_nonce_field'
                    ); ?>

                    <input type="hidden" name="action" value="login_awp_form_action">

                    <button name="submit_button_login_awp" type="submit" class="button button-primary"
                        title="<?php _e('Save images', 'login-awp') ?>">
                        <?php _e('Save images', 'login-awp') ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
