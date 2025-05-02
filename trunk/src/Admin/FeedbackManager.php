<?php

declare(strict_types=1);

/**
 * Handles feedback collection when plugin is deactivated or deleted
 *
 * @author AWP-Software
 * @since 3.1.0
 * @version 3.1.0
 */

namespace Login\Awp\Admin;

class FeedbackManager
{
    private $dirUrl;
    
    public function __construct($dir_url)
    {
        $this->dirUrl = $dir_url . 'assets/';
    }
    
    public function load()
    {
        // Only load on admin pages
        if (!is_admin()) {
            return;
        }
        
        // Add scripts and styles for the feedback modal
        add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
        
        // Add the modal HTML to the admin footer
        add_action('admin_footer', array($this, 'renderModal'));
        
        // Register AJAX handler for feedback submission
        add_action('wp_ajax_login_awp_submit_feedback', array($this, 'handleFeedbackSubmission'));
        
        // Add settings fields for feedback email and webhook
        add_action('admin_init', array($this, 'registerSettings'));
    }
    
    public function enqueueScripts($hook)
    {
        // Only load on plugins page
        if ('plugins.php' !== $hook) {
            return;
        }
        
        wp_enqueue_style(
            'login-awp-feedback-css',
            $this->dirUrl . 'css/feedback-modal.css',
            array(),
            '3.1.0'
        );
        
        wp_enqueue_script(
            'login-awp-feedback-js',
            $this->dirUrl . 'js/feedback-modal.js',
            array('jquery'),
            '3.1.0',
            true
        );
        
        wp_localize_script(
            'login-awp-feedback-js',
            'loginAwpFeedback',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('login_awp_feedback_nonce'),
                'plugin_slug' => 'login-awp/login_awp.php',
                'translations' => array(
                    'heading' => __('Quick Feedback', 'login-awp'),
                    'intro' => __('If you have a moment, please let us know why you are deactivating Login AWP:', 'login-awp'),
                    'skip' => __('Skip & Deactivate', 'login-awp'),
                    'submit' => __('Submit & Deactivate', 'login-awp'),
                    'cancel' => __('Cancel', 'login-awp'),
                    'deletion_heading' => __('Quick Feedback Before Deleting', 'login-awp'),
                    'deletion_intro' => __('If you have a moment, please let us know why you are deleting Login AWP:', 'login-awp'),
                    'skip_delete' => __('Skip & Delete', 'login-awp'),
                    'submit_delete' => __('Submit & Delete', 'login-awp'),
                    'submitting' => __('Submitting...', 'login-awp')
                )
            )
        );
    }
    
    public function renderModal()
    {
        // Only show on plugins page
        $screen = get_current_screen();
        if (!$screen || 'plugins' !== $screen->id) {
            return;
        }
        
        ?>
        <div id="login-awp-feedback-modal" style="display:none;">
            <div class="login-awp-feedback-modal-content">
                <h2 id="login-awp-feedback-title"><?php esc_html_e('Quick Feedback', 'login-awp'); ?></h2>
                <p id="login-awp-feedback-intro"><?php esc_html_e('If you have a moment, please let us know why you are deactivating Login AWP:', 'login-awp'); ?></p>
                
                <form id="login-awp-feedback-form">
                    <div class="login-awp-feedback-options">
                        <label>
                            <input type="radio" name="reason" value="no_longer_needed">
                            <?php esc_html_e('I no longer need the plugin', 'login-awp'); ?>
                        </label>
                        
                        <label>
                            <input type="radio" name="reason" value="found_better_plugin">
                            <?php esc_html_e('I found a better plugin', 'login-awp'); ?>
                            <input type="text" name="better_plugin" placeholder="<?php esc_attr_e('Which plugin?', 'login-awp'); ?>" class="login-awp-feedback-info hidden">
                        </label>
                        
                        <label>
                            <input type="radio" name="reason" value="not_working">
                            <?php esc_html_e('The plugin is not working', 'login-awp'); ?>
                            <textarea name="not_working_details" placeholder="<?php esc_attr_e('Please describe the issue', 'login-awp'); ?>" class="login-awp-feedback-info hidden"></textarea>
                        </label>
                        
                        <label>
                            <input type="radio" name="reason" value="temporary_deactivation">
                            <?php esc_html_e('It\'s a temporary deactivation', 'login-awp'); ?>
                        </label>
                        
                        <label>
                            <input type="radio" name="reason" value="other">
                            <?php esc_html_e('Other', 'login-awp'); ?>
                            <textarea name="other_details" placeholder="<?php esc_attr_e('Please specify', 'login-awp'); ?>" class="login-awp-feedback-info hidden"></textarea>
                        </label>
                    </div>
                    
                    <div class="login-awp-anonymous-feedback">
                        <label>
                            <input type="checkbox" name="include_email" value="1">
                            <?php esc_html_e('Include my email address for follow-up', 'login-awp'); ?>
                        </label>
                        <p class="description"><?php esc_html_e('We will only use your email to address your feedback', 'login-awp'); ?></p>
                    </div>
                    
                    <div class="login-awp-feedback-actions">
                        <button type="button" class="button" id="login-awp-skip-feedback"><?php esc_html_e('Skip & Deactivate', 'login-awp'); ?></button>
                        <button type="submit" class="button button-primary" id="login-awp-submit-feedback"><?php esc_html_e('Submit & Deactivate', 'login-awp'); ?></button>
                        <button type="button" class="button button-secondary" id="login-awp-cancel-feedback"><?php esc_html_e('Cancel', 'login-awp'); ?></button>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
    
    public function handleFeedbackSubmission()
    {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_key($_POST['nonce']), 'login_awp_feedback_nonce')) {
            wp_send_json_error('Invalid nonce');
            return;
        }
        
        $reason = isset($_POST['reason']) ? sanitize_text_field($_POST['reason']) : '';
        $action_type = isset($_POST['action_type']) ? sanitize_text_field($_POST['action_type']) : 'deactivate';
        $email = '';
        $details = '';
        
        // Get reason details if provided
        if ($reason === 'found_better_plugin' && isset($_POST['better_plugin'])) {
            $details = sanitize_text_field($_POST['better_plugin']);
        } elseif ($reason === 'not_working' && isset($_POST['not_working_details'])) {
            $details = sanitize_textarea_field($_POST['not_working_details']);
        } elseif ($reason === 'other' && isset($_POST['other_details'])) {
            $details = sanitize_textarea_field($_POST['other_details']);
        }
        
        // Get user email if they opted to include it
        if (isset($_POST['include_email']) && $_POST['include_email'] === '1') {
            $current_user = wp_get_current_user();
            $email = $current_user->user_email;
        }
        
        // Prepare feedback message
        $feedback = array(
            'reason' => $reason,
            'details' => $details,
            'email' => $email,
            'action_type' => $action_type,
            'site_url' => home_url(),
            'plugin_version' => '3.1.0',
            'wp_version' => get_bloginfo('version'),
            'php_version' => phpversion(),
            'timestamp' => current_time('mysql')
        );
        
        // Send email
        $this->sendFeedbackEmail($feedback);
        
        // Send to webhook if configured
        $this->sendFeedbackWebhook($feedback);
        
        wp_send_json_success('Feedback submitted successfully');
    }
    
    private function sendFeedbackEmail($feedback)
    {
        $to = get_option('login_awp_feedback_email', get_option('admin_email'));
        
        if (empty($to)) {
            return false;
        }
        
        $action_type = isset($feedback['action_type']) && $feedback['action_type'] === 'delete' ? 
            __('deletion', 'login-awp') : 
            __('deactivation', 'login-awp');
            
        $subject = sprintf(__('[Login AWP] %s Feedback from %s', 'login-awp'), 
            ucfirst($action_type),
            parse_url(home_url(), PHP_URL_HOST)
        );
        
        $message = sprintf(__('Action: %s', 'login-awp'), ucfirst($action_type)) . "\n";
        $message .= sprintf(__('Reason: %s', 'login-awp'), $this->getReadableReason($feedback['reason'])) . "\n\n";
        
        if (!empty($feedback['details'])) {
            $message .= sprintf(__('Details: %s', 'login-awp'), $feedback['details']) . "\n\n";
        }
        
        $message .= sprintf(__('Site URL: %s', 'login-awp'), $feedback['site_url']) . "\n";
        $message .= sprintf(__('Plugin version: %s', 'login-awp'), $feedback['plugin_version']) . "\n";
        $message .= sprintf(__('WordPress version: %s', 'login-awp'), $feedback['wp_version']) . "\n";
        $message .= sprintf(__('PHP version: %s', 'login-awp'), $feedback['php_version']) . "\n";
        
        if (!empty($feedback['email'])) {
            $message .= sprintf(__('User email: %s', 'login-awp'), $feedback['email']) . "\n";
            $headers = array('Reply-To: ' . $feedback['email']);
        } else {
            $message .= __('User email: Anonymous', 'login-awp') . "\n";
            $headers = array();
        }
        
        return wp_mail($to, $subject, $message, $headers);
    }
    
    private function sendFeedbackWebhook($feedback)
    {
        $webhook_url = get_option('login_awp_feedback_webhook', '');
        
        if (empty($webhook_url)) {
            return false;
        }
        
        // Format feedback for webhook
        $data = array(
            'feedback_type' => isset($feedback['action_type']) ? $feedback['action_type'] : 'deactivation',
            'feedback' => $feedback
        );
        
        // Send to webhook
        $response = wp_remote_post($webhook_url, array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => false,
            'headers' => array('Content-Type' => 'application/json'),
            'body' => wp_json_encode($data),
            'cookies' => array()
        ));
        
        return !is_wp_error($response);
    }
    
    private function getReadableReason($reason)
    {
        $reasons = array(
            'no_longer_needed' => __('I no longer need the plugin', 'login-awp'),
            'found_better_plugin' => __('I found a better plugin', 'login-awp'),
            'not_working' => __('The plugin is not working', 'login-awp'),
            'temporary_deactivation' => __('It\'s a temporary deactivation', 'login-awp'),
            'other' => __('Other', 'login-awp')
        );
        
        return isset($reasons[$reason]) ? $reasons[$reason] : $reason;
    }
    
    public function registerSettings()
    {
        // Register a new section in the Login AWP settings
        add_settings_section(
            'login_awp_feedback_settings',
            __('Feedback Settings', 'login-awp'),
            array($this, 'renderSettingsSection'),
            'login-awp-feedback'
        );
        
        // Register the email field
        add_settings_field(
            'login_awp_feedback_email',
            __('Feedback Email', 'login-awp'),
            array($this, 'renderEmailField'),
            'login-awp-feedback',
            'login_awp_feedback_settings'
        );
        
        // Register the webhook field
        add_settings_field(
            'login_awp_feedback_webhook',
            __('Feedback Webhook URL', 'login-awp'),
            array($this, 'renderWebhookField'),
            'login-awp-feedback',
            'login_awp_feedback_settings'
        );
        
        // Register the settings
        register_setting('login-awp-feedback', 'login_awp_feedback_email', 'sanitize_email');
        register_setting('login-awp-feedback', 'login_awp_feedback_webhook', 'esc_url_raw');
    }
    
    public function renderSettingsSection()
    {
        echo '<p>' . esc_html__('Configure where feedback should be sent when users deactivate or delete the plugin.', 'login-awp') . '</p>';
    }
    
    public function renderEmailField()
    {
        $email = get_option('login_awp_feedback_email', get_option('admin_email'));
        echo '<input type="email" name="login_awp_feedback_email" value="' . esc_attr($email) . '" class="regular-text">';
        echo '<p class="description">' . esc_html__('Email address where feedback will be sent.', 'login-awp') . '</p>';
    }
    
    public function renderWebhookField()
    {
        $webhook = get_option('login_awp_feedback_webhook', '');
        echo '<input type="url" name="login_awp_feedback_webhook" value="' . esc_attr($webhook) . '" class="regular-text">';
        echo '<p class="description">' . esc_html__('Optional webhook URL where feedback will be sent in JSON format.', 'login-awp') . '</p>';
    }
}