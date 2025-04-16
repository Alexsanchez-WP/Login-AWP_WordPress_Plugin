<?php

use Login\Awp\Admin\AdminRegister;
use PHPUnit\Framework\TestCase;

class AdminRegisterTest extends TestCase
{
    /**
     * @var AdminRegister
     */
    private $adminRegister;
    
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
        $GLOBALS['wp_site_options'] = [];
        $GLOBALS['wp_actions_executed'] = [];
        $GLOBALS['wp_enqueued_styles'] = [];
        $GLOBALS['wp_enqueued_scripts'] = [];
        
        $this->dirUrl = 'https://example.com/wp-content/plugins/login-awp/';
        $this->adminRegister = new AdminRegister($this->dirUrl);
    }
    
    /**
     * Test constructor sets properties correctly
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(AdminRegister::class, $this->adminRegister);
        
        // Use reflection to check private/protected properties
        $reflection = new ReflectionClass($this->adminRegister);
        $dirUrlProperty = $reflection->getProperty('dirUrl');
        $dirUrlProperty->setAccessible(true);
        
        $this->assertEquals($this->dirUrl . 'assets/', $dirUrlProperty->getValue($this->adminRegister));
    }
    
    /**
     * Test load method registers all the required hooks
     */
    public function testLoad()
    {
        $this->adminRegister->load();
        
        // Verify actions were added by triggering them and checking if they execute
        do_action('admin_enqueue_scripts');
        $this->assertNotFalse(did_action('admin_enqueue_scripts'));
        
        do_action('admin_menu');
        $this->assertNotFalse(did_action('admin_menu'));
        
        // Check if admin styles were enqueued
        $this->assertArrayHasKey('login-awp-admin-style', $GLOBALS['wp_enqueued_styles']);
    }
    
    /**
     * Test enqueue admin styles method
     */
    public function testEnqueueAdminStyles()
    {
        $this->adminRegister->enqueueAdminStyles();
        
        $this->assertArrayHasKey('login-awp-admin-style', $GLOBALS['wp_enqueued_styles']);
        $this->assertEquals(
            $this->dirUrl . 'assets/css/login-admin.css', 
            $GLOBALS['wp_enqueued_styles']['login-awp-admin-style']['src']
        );
    }
    
    /**
     * Test enqueue admin scripts method
     */
    public function testEnqueueAdminScripts()
    {
        $this->adminRegister->enqueueAdminScripts();
        
        $this->assertArrayHasKey('login-awp-admin-script', $GLOBALS['wp_enqueued_scripts']);
        $this->assertEquals(
            $this->dirUrl . 'assets/js/loginAdmin.js', 
            $GLOBALS['wp_enqueued_scripts']['login-awp-admin-script']['src']
        );
        
        // Check script localization
        $this->assertArrayHasKey('login-awp-admin-script', $GLOBALS['wp_localized_scripts']);
    }
    
    /**
     * Test add menu page method
     */
    public function testAddMenuPage()
    {
        // Mock the add_menu_page function for this test
        global $wpMenuPages;
        $wpMenuPages = [];
        
        function add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position) {
            global $wpMenuPages;
            $wpMenuPages[] = [
                'page_title' => $page_title,
                'menu_title' => $menu_title,
                'capability' => $capability,
                'menu_slug' => $menu_slug,
                'function' => $function,
                'icon_url' => $icon_url,
                'position' => $position
            ];
            return 'test-hook';
        }
        
        $this->adminRegister->addMenuPage();
        
        $this->assertCount(1, $wpMenuPages);
        $this->assertEquals('Login AWP', $wpMenuPages[0]['page_title']);
        $this->assertEquals('login-awp', $wpMenuPages[0]['menu_slug']);
    }
    
    /**
     * Test save data method
     */
    public function testSaveData()
    {
        $_POST['login-nonce'] = wp_create_nonce('login-form-nonce');
        $_POST['upload-img-logo'] = 'https://example.com/logo.png';
        $_POST['upload-img-back'] = 'https://example.com/background.jpg';
        
        // Call the method with direct true to bypass nonce verification for testing
        $result = $this->adminRegister->saveData(true);
        
        $this->assertTrue($result);
        $this->assertEquals('https://example.com/logo.png', get_option(AdminRegister::$imgLogoName));
        $this->assertEquals('https://example.com/background.jpg', get_option(AdminRegister::$imgBackName));
    }
    
    /**
     * Test delete data method
     */
    public function testDeleteData()
    {
        // Set initial values
        update_option(AdminRegister::$imgLogoName, 'https://example.com/logo.png');
        update_option(AdminRegister::$imgBackName, 'https://example.com/background.jpg');
        
        $_POST['login-delete-nonce'] = wp_create_nonce('login-delete-nonce');
        
        // Call the method with direct true to bypass nonce verification for testing
        $result = $this->adminRegister->deleteData(true);
        
        $this->assertTrue($result);
        $this->assertFalse(get_option(AdminRegister::$imgLogoName, false));
        $this->assertFalse(get_option(AdminRegister::$imgBackName, false));
    }
}
