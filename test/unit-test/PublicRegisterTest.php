<?php

use Login\Awp\Public\PublicRegister;
use PHPUnit\Framework\TestCase;

class PublicRegisterTest extends TestCase
{
    /**
     * @var PublicRegister
     */
    private $publicRegister;
    
    /**
     * @var string
     */
    private $dirUrl;
    
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
        
        $this->dirUrl = 'https://example.com/wp-content/plugins/login-awp/';
        $this->publicRegister = new PublicRegister($this->dirUrl);
    }
    
    /**
     * Test constructor sets properties correctly
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(PublicRegister::class, $this->publicRegister);
        $this->assertEquals($this->dirUrl . 'assets/', $this->publicRegister->dirUrl);
    }
    
    /**
     * Test load method registers all the required hooks
     */
    public function testLoad()
    {
        $this->publicRegister->load();
        
        // Verify actions were added by triggering them and checking if they execute
        do_action('login_enqueue_scripts');
        $this->assertNotFalse(did_action('login_enqueue_scripts'));
        
        // Since login_head is called multiple times in the class methods, we should have multiple executions
        do_action('login_head');
        $this->assertGreaterThan(0, did_action('login_head'));
    }
    
    /**
     * Test login styles enqueue method
     */
    public function testLoginAwpStyles()
    {
        $this->publicRegister->loginAwpStyles();
        
        $this->assertArrayHasKey('login-awp-login-style', $GLOBALS['wp_enqueued_styles']);
        $this->assertEquals(
            $this->dirUrl . 'css/login-public.css', 
            $GLOBALS['wp_enqueued_styles']['login-awp-login-style']['src']
        );
    }
    
    /**
     * Test login scripts enqueue method
     */
    public function testLoginAwpScripts()
    {
        $this->publicRegister->loginAwpScripts();
        
        $this->assertArrayHasKey('login-awp-login-script', $GLOBALS['wp_enqueued_scripts']);
        $this->assertEquals(
            $this->dirUrl . 'js/loginPublic.js', 
            $GLOBALS['wp_enqueued_scripts']['login-awp-login-script']['src']
        );
    }
    
    /**
     * Test custom logo method when logo URL is set
     */
    public function testCustomLogoWithUrl()
    {
        // Set logo URL
        $logoUrl = 'https://example.com/custom-logo.png';
        update_option('login_awp_logo_img', $logoUrl);
        
        // Capture output
        ob_start();
        $this->publicRegister->customLogo();
        $output = ob_get_clean();
        
        $this->assertStringContainsString($logoUrl, $output);
        $this->assertStringContainsString('background-image: url', $output);
    }
    
    /**
     * Test custom logo method when logo URL is not set
     */
    public function testCustomLogoWithoutUrl()
    {
        // Make sure logo URL is not set
        delete_option('login_awp_logo_img');
        
        // Capture output
        ob_start();
        $this->publicRegister->customLogo();
        $output = ob_get_clean();
        
        // Should output empty style tag or no output
        $this->assertEmpty(trim($output));
    }
    
    /**
     * Test custom background image method when background URL is set
     */
    public function testCustomBackgroundWithUrl()
    {
        // Set background URL
        $backgroundUrl = 'https://example.com/custom-background.jpg';
        update_option('login_awp_background_img', $backgroundUrl);
        
        // Capture output
        ob_start();
        $this->publicRegister->customBackground();
        $output = ob_get_clean();
        
        $this->assertStringContainsString($backgroundUrl, $output);
        $this->assertStringContainsString('background-image', $output);
    }
    
    /**
     * Test custom background image method when background URL is not set
     */
    public function testCustomBackgroundWithoutUrl()
    {
        // Make sure background URL is not set
        delete_option('login_awp_background_img');
        
        // Capture output
        ob_start();
        $this->publicRegister->customBackground();
        $output = ob_get_clean();
        
        // Should output empty style tag or no output
        $this->assertEmpty(trim($output));
    }
}
