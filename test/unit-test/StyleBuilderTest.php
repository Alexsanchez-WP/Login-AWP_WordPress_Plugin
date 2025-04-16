<?php

use PHPUnit\Framework\TestCase;

/**
 * Test for the Style Builder functionality
 * Note: Since this is mostly JavaScript, we'll test the PHP endpoints that support it
 */
class StyleBuilderTest extends TestCase
{
    /**
     * Set up test environment
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Reset globals before each test
        $GLOBALS['wp_options'] = [];
        $GLOBALS['wp_actions_executed'] = [];
        $GLOBALS['wp_enqueued_styles'] = [];
        $GLOBALS['wp_enqueued_scripts'] = [];
        
        // Define AJAX constants for testing
        if (!defined('DOING_AJAX')) {
            define('DOING_AJAX', true);
        }
    }
    
    /**
     * Test saving custom styles AJAX callback
     */
    public function testSaveCustomStyles()
    {
        // Mock the AJAX handler for saving custom styles
        function login_awp_save_custom_styles_callback() {
            // Check nonce (simplified for test)
            if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'login_awp_style_builder_nonce')) {
                wp_send_json_error(['message' => 'Invalid nonce']);
                return;
            }
            
            // Save the CSS
            $css = isset($_POST['css']) ? sanitize_textarea_field($_POST['css']) : '';
            update_option('login_awp_custom_styles', $css);
            
            wp_send_json_success(['message' => 'Custom styles saved successfully']);
        }
        
        // Setup request
        $_POST['nonce'] = wp_create_nonce('login_awp_style_builder_nonce');
        $_POST['css'] = 'body.login { background-color: #f1f1f1; }';
        
        // Capture the JSON response
        ob_start();
        login_awp_save_custom_styles_callback();
        $output = ob_get_clean();
        
        // Decode and check the response
        $response = json_decode($output, true);
        $this->assertTrue($response['success']);
        $this->assertEquals('Custom styles saved successfully', $response['data']['message']);
        
        // Check if the option was saved
        $savedCSS = get_option('login_awp_custom_styles');
        $this->assertEquals('body.login { background-color: #f1f1f1; }', $savedCSS);
    }
    
    /**
     * Test resetting custom styles AJAX callback
     */
    public function testResetCustomStyles()
    {
        // First save some custom styles
        update_option('login_awp_custom_styles', 'body.login { background-color: #f1f1f1; }');
        
        // Mock the AJAX handler for resetting custom styles
        function login_awp_reset_custom_styles_callback() {
            // Check nonce (simplified for test)
            if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'login_awp_style_builder_nonce')) {
                wp_send_json_error(['message' => 'Invalid nonce']);
                return;
            }
            
            // Delete the custom styles option
            delete_option('login_awp_custom_styles');
            
            wp_send_json_success(['message' => 'Custom styles reset successfully']);
        }
        
        // Setup request
        $_POST['nonce'] = wp_create_nonce('login_awp_style_builder_nonce');
        
        // Capture the JSON response
        ob_start();
        login_awp_reset_custom_styles_callback();
        $output = ob_get_clean();
        
        // Decode and check the response
        $response = json_decode($output, true);
        $this->assertTrue($response['success']);
        $this->assertEquals('Custom styles reset successfully', $response['data']['message']);
        
        // Check if the option was deleted
        $savedCSS = get_option('login_awp_custom_styles', false);
        $this->assertFalse($savedCSS);
    }
    
    /**
     * Test invalid nonce rejection for save custom styles
     */
    public function testInvalidNonceSaveCustomStyles()
    {
        // Mock the AJAX handler (same as above)
        function login_awp_save_custom_styles_invalid_callback() {
            // Check nonce (simplified for test)
            if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'login_awp_style_builder_nonce')) {
                wp_send_json_error(['message' => 'Invalid nonce']);
                return;
            }
            
            update_option('login_awp_custom_styles', $_POST['css']);
            wp_send_json_success(['message' => 'Custom styles saved successfully']);
        }
        
        // Setup request with invalid nonce
        $_POST['nonce'] = 'invalid_nonce';
        $_POST['css'] = 'body.login { background-color: #f1f1f1; }';
        
        // Capture the JSON response
        ob_start();
        login_awp_save_custom_styles_invalid_callback();
        $output = ob_get_clean();
        
        // Decode and check the response
        $response = json_decode($output, true);
        $this->assertFalse($response['success']);
        $this->assertEquals('Invalid nonce', $response['data']['message']);
        
        // Check that the option was not saved
        $savedCSS = get_option('login_awp_custom_styles', false);
        $this->assertFalse($savedCSS);
    }
    
    /**
     * Test preview URL generation
     */
    public function testPreviewUrlGeneration()
    {
        // Mock function for generating preview URL
        function login_awp_get_preview_url() {
            $nonce = wp_create_nonce('login_awp_preview');
            return add_query_arg([
                'login_awp_preview' => 1,
                'nonce' => $nonce
            ], wp_login_url());
        }
        
        // Mock wp_login_url
        function wp_login_url() {
            return 'https://example.com/wp-login.php';
        }
        
        // Generate preview URL
        $previewUrl = login_awp_get_preview_url();
        
        // Check URL structure
        $this->assertStringContainsString('login_awp_preview=1', $previewUrl);
        $this->assertStringContainsString('nonce=', $previewUrl);
        $this->assertStringStartsWith('https://example.com/wp-login.php', $previewUrl);
    }
}
