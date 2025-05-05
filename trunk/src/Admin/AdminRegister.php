<?php

declare(strict_types=1);

/**
 * Register Admin Area
 *
 * @author AWP-Software
 * @since 2.0.0
 * @version 3.0.0
 */

namespace Login\Awp\Admin;

class AdminRegister
{
    public $dirUrl;

    public static $imgLogoName = 'login_awp_logo_url';
    public static $imgBackName = 'login_awp_background_url';
    public static $activateDateOption = 'login_awp_activation_date';
    public static $reviewNoticeDismissedOption = 'login_awp_review_notice_dismissed';
    private $adminTemplate = 'templates/menu_admin.php';
    private $messageTemplate = 'templates/status_message.php';
    private $themeManager;
    private $styleBuilder;

    public function __construct($dir_url)
    {
        $this->dirUrl = $dir_url . 'assets/';
        $this->themeManager = new ThemeManager($dir_url);
        $this->styleBuilder = new StyleBuilder($dir_url);
    }

    public function load()
    {
        add_action(
            'admin_menu',
            array($this, 'registerSubMenu')
        );
        add_action(
            'admin_enqueue_scripts',
            array($this, 'adminScripts')
        );
        add_action(
            'admin_enqueue_scripts',
            array($this, 'adminStyles')
        );
        add_action(
            'admin_post_login_awp_form_action',
            array($this, 'loginAwpAdminform')
        );

        add_action(
            'admin_notices',
            array($this, 'statusMessage')
        );

        // Add the review notice action
        add_action(
            'admin_notices',
            array($this, 'displayReviewNotice')
        );

        // Add the dismiss review notice action hook
        add_action(
            'admin_post_login_awp_dismiss_review_notice',
            array($this, 'dismissReviewNoticeHandler')
        );

        // Load theme manager and style builder components
        $this->themeManager->load();
        $this->styleBuilder->load();
    }

    public function registerSubMenu()
    {
        add_submenu_page(
            'themes.php',
            __('Login AWP Plugin', 'login-awp'),
            __('Login', 'login-awp'),
            'manage_options',
            'login-awp',
            array($this, 'loginAwpSubMenuTemplate')
        );
    }

    public function loginAwpSubMenuTemplate()
    {
        if (\file_exists(plugin_dir_path(__FILE__) . $this->adminTemplate)) {
            wp_create_nonce('login_awp_form_nonce');

            // Current active tab
            $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'general';

            include_once plugin_dir_path(__FILE__) . $this->adminTemplate;
        }
    }

    public function adminStyles()
    {
        wp_enqueue_style(
            'loginAdminCSS',
            $this->dirUrl . 'css/loginAdminStyles.css',
            array(),
            '3.0.0'
        );
    }

    public function adminScripts()
    {
        wp_enqueue_media();
        wp_enqueue_script('jquery');
        wp_enqueue_script(
            'loginAdminScript',
            $this->dirUrl . 'js/loginAdmin.js',
            array('jquery'),
            '3.0.0',
            true
        );
        wp_localize_script(
            'loginAdminScript',
            'login_text',
            array(
                'text' => __('Select the image', 'login-awp'),
                'delete_button' => __('Delete image', 'login-awp')
            )
        );
    }


    public function loginAwpAdminform()
    {

        if (
            !isset($_POST['login_awp_form_nonce_field']) ||
            !wp_verify_nonce(
                $_POST['login_awp_form_nonce_field'],
                'login_awp_form_nonce'
            )
        ) {
            wp_die(__('Verification failed', 'login-awp'));
        }

        $message = "";

        if (isset($_POST['submit_button_login_awp'])) {

            $upload_img_logo = $_POST["upload-img-logo"];
            $upload_img_back = $_POST["upload-img-back"];

            if (
                isset($upload_img_logo) &&
                filter_var($upload_img_logo, \FILTER_VALIDATE_URL)
            ) {

                $message .= $this->updateOption(
                    $upload_img_logo,
                    'logo_status',
                    self::$imgLogoName
                );
            }

            if (
                isset($upload_img_back) &&
                filter_var($upload_img_back, \FILTER_VALIDATE_URL)
            ) {
                $message .= $this->updateOption(
                    $upload_img_back,
                    'background_status',
                    self::$imgBackName
                );
            }
        }

        if (
            isset($_POST["delete-upload-img-logo-button"]) &&
            !is_null($_POST["delete-upload-img-logo-button"])
        ) {
            $message .= $this->updateOption(
                "",
                'logo_status',
                self::$imgLogoName
            );
        }

        if (
            isset($_POST["delete-upload-img-back-button"]) &&
            !is_null($_POST["delete-upload-img-back-button"])
        ) {
            $message .= $this->updateOption(
                "",
                'background_status',
                self::$imgBackName
            );
        }

        $url = parse_url($_POST["_wp_http_referer"])["path"] . "?page=login-awp";
        wp_redirect(sanitize_url($url . $message));
        exit;
    }

    /**
     * Summary of updateOption
     *
     * @param string $upload_data
     * @param string $message
     * @param string $db_file
     * @return string
     */
    private function updateOption($upload_data, $message, $db_file)
    {
        $status = "&{$message}=error";
        $data = sanitize_text_field($upload_data);
        if (update_option($db_file, $data)) {

            $status = "&{$message}=success";
        }
        return $status;
    }

    public function statusMessage()
    {
        if (isset($_GET['logo_status'])) {
            $this->messageTemplate(
                sanitize_text_field($_GET['logo_status']),
                __('logo', 'login-awp')
            );
        }

        if (isset($_GET['background_status'])) {
            $this->messageTemplate(
                sanitize_text_field($_GET['background_status']),
                __('background', 'login-awp')
            );
        }
    }

    private function messageTemplate($status, $message)
    {
        switch ($status) {
            case 'success':
                $class = 'notice notice-success is-dismissible';
                $text = sprintf(
                    __(
                        "The login area %s has been successfully changed.",
                        'login-awp'
                    ),
                    $message
                );
                break;
            case 'error':
                $class = 'notice notice-error is-dismissible';
                $text = sprintf(
                    __(
                        "The login area %s has not been changed.",
                        'login-awp'
                    ),
                    $message
                );
                break;
            default:
                $class = 'notice notice-info is-dismissible';
                $text = __("No actions were taken", 'login-awp');
                break;
        }

        if (\file_exists(plugin_dir_path(__FILE__) . $this->messageTemplate)) {
            include_once plugin_dir_path(__FILE__) . $this->messageTemplate;
        }
    }

    /**
     * Display the review request admin notice if applicable.
     * 
     * Shows a non-intrusive admin notice to administrators after 3 days of plugin usage,
     * asking for a review on wordpress.org.
     */
    public function displayReviewNotice()
    {
        // Only show to administrators
        if (!current_user_can('manage_options')) {
            return;
        }

        // Check if the notice has been dismissed
        $dismissed = get_option(self::$reviewNoticeDismissedOption, '0');
        if ('1' === $dismissed) {
            return;
        }

        // Check if 24 hours (86400 seconds) have passed since activation
        $activation_date = get_option(self::$activateDateOption);
        if (!$activation_date || (time() - $activation_date < 86400)) {
            return;
        }

        // Prepare dismiss URL
        $dismiss_url = add_query_arg(
            array(
                'action' => 'login_awp_dismiss_review_notice',
                '_wpnonce' => wp_create_nonce('login_awp_dismiss_review_nonce')
            ),
            admin_url('admin-post.php')
        );

        // Plugin review URL
        $review_url = 'https://wordpress.org/support/plugin/login-awp/reviews/?filter=5#new-post';

?>
        <div class="notice notice-info is-dismissible login-awp-review-notice">
            <p>
                <?php esc_html_e('Enjoying Login AWP? Please take a moment to rate us! ⭐⭐⭐⭐⭐', 'login-awp'); ?>
            </p>
            <p>
                <a href="<?php echo esc_url($review_url); ?>" class="button button-primary" target="_blank" rel="noopener noreferrer">
                    <?php esc_html_e('Leave a Review', 'login-awp'); ?>
                </a>
                <a href="<?php echo esc_url($dismiss_url); ?>" class="button button-secondary">
                    <?php esc_html_e('Dismiss Permanently', 'login-awp'); ?>
                </a>
            </p>
        </div>
        <style>
            .login-awp-review-notice p {
                margin-bottom: 10px;
            }

            .login-awp-review-notice .button {
                margin-right: 5px;
            }
        </style>
<?php
    }

    /**
     * Handle the dismissal of the review notice.
     * 
     * Sets an option to permanently hide the review notice when the user clicks the dismiss link.
     * Includes security checks with nonce verification and capability check.
     */
    public function dismissReviewNoticeHandler()
    {
        // Verify nonce
        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce(sanitize_key($_GET['_wpnonce']), 'login_awp_dismiss_review_nonce')) {
            wp_die(esc_html__('Security check failed', 'login-awp'));
        }

        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to dismiss this notice.', 'login-awp'));
        }

        // Update the option to permanently dismiss the notice
        update_option(self::$reviewNoticeDismissedOption, '1');

        // Redirect back to the previous page
        wp_safe_redirect(wp_get_referer() ? wp_get_referer() : admin_url());
        exit;
    }
}
