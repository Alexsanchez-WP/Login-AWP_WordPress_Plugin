<?php

use Login\Awp\Admin\ThemeManager;
use PHPUnit\Framework\TestCase;

class ThemeManagerTest extends TestCase
{
    /**
     * @var ThemeManager
     */
    private $themeManager;
    
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
        
        $this->dirUrl = 'https://example.com/wp-content/plugins/login-awp/';
        $this->themeManager = new ThemeManager($this->dirUrl);
    }
    
    /**
     * Test constructor sets properties correctly
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(ThemeManager::class, $this->themeManager);
        
        // Use reflection to check private/protected properties
        $reflection = new ReflectionClass($this->themeManager);
        $dirUrlProperty = $reflection->getProperty('dirUrl');
        $dirUrlProperty->setAccessible(true);
        
        $this->assertEquals($this->dirUrl, $dirUrlProperty->getValue($this->themeManager));
    }
    
    /**
     * Test get themes method returns array of themes
     */
    public function testGetThemes()
    {
        $themes = $this->themeManager->getThemes();
        
        $this->assertIsArray($themes);
        $this->assertNotEmpty($themes);
        
        // Check structure of a theme
        $firstTheme = reset($themes);
        $this->assertArrayHasKey('name', $firstTheme);
        $this->assertArrayHasKey('description', $firstTheme);
        $this->assertArrayHasKey('preview', $firstTheme);
        $this->assertArrayHasKey('styles', $firstTheme);
    }
    
    /**
     * Test get theme by key method
     */
    public function testGetThemeByKey()
    {
        // Get all themes
        $themes = $this->themeManager->getThemes();
        
        // Get first theme key
        $firstThemeKey = array_key_first($themes);
        
        // Test getting a specific theme
        $theme = $this->themeManager->getThemeByKey($firstThemeKey);
        
        $this->assertIsArray($theme);
        $this->assertEquals($themes[$firstThemeKey], $theme);
        
        // Test getting a non-existent theme
        $nonExistentTheme = $this->themeManager->getThemeByKey('non-existent-theme');
        $this->assertNull($nonExistentTheme);
    }
    
    /**
     * Test apply theme method
     */
    public function testApplyTheme()
    {
        // Get all themes
        $themes = $this->themeManager->getThemes();
        
        // Get first theme key
        $firstThemeKey = array_key_first($themes);
        
        // Apply the theme
        $result = $this->themeManager->applyTheme($firstThemeKey);
        
        $this->assertTrue($result);
        
        // Check if the theme was saved to options
        $savedTheme = get_option('login_awp_current_theme');
        $this->assertEquals($firstThemeKey, $savedTheme);
        
        // Check if custom styles were saved
        $customStyles = get_option('login_awp_custom_styles');
        $this->assertNotEmpty($customStyles);
        
        // Test applying a non-existent theme
        $result = $this->themeManager->applyTheme('non-existent-theme');
        $this->assertFalse($result);
    }
    
    /**
     * Test get current theme method
     */
    public function testGetCurrentTheme()
    {
        // Initially, there should be no current theme
        $currentTheme = $this->themeManager->getCurrentTheme();
        $this->assertNull($currentTheme);
        
        // Set a current theme
        $themes = $this->themeManager->getThemes();
        $firstThemeKey = array_key_first($themes);
        update_option('login_awp_current_theme', $firstThemeKey);
        
        // Now get the current theme
        $currentTheme = $this->themeManager->getCurrentTheme();
        $this->assertEquals($themes[$firstThemeKey], $currentTheme);
    }
    
    /**
     * Test generate CSS from theme method
     */
    public function testGenerateCssFromTheme()
    {
        // Get a theme
        $themes = $this->themeManager->getThemes();
        $firstThemeKey = array_key_first($themes);
        $theme = $themes[$firstThemeKey];
        
        // Use reflection to access private method
        $reflection = new ReflectionClass($this->themeManager);
        $method = $reflection->getMethod('generateCssFromTheme');
        $method->setAccessible(true);
        
        // Generate CSS
        $css = $method->invoke($this->themeManager, $theme);
        
        $this->assertIsString($css);
        $this->assertNotEmpty($css);
        
        // Check if CSS contains some expected properties
        $this->assertStringContainsString('background-color', $css);
        $this->assertStringContainsString('color', $css);
    }
}
