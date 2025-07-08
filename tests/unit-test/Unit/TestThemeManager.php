<?php

declare(strict_types=1);

namespace Login\Awp\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Login\Awp\Admin\ThemeManager;

/**
 * Test case para la clase ThemeManager
 */
class TestThemeManager extends TestCase
{
    private $themeManager;
    private $dirUrl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dirUrl = 'http://example.com/wp-content/plugins/login-awp/';
        $this->themeManager = new ThemeManager($this->dirUrl);
    }

    public function testConstructorSetsProperties(): void
    {
        $expectedAssetUrl = $this->dirUrl . 'assets/';
        $this->assertEquals($expectedAssetUrl, $this->themeManager->dirUrl);
    }

    public function testLoadMethodAddsActions(): void
    {
        // Esta prueba simplemente verifica que el método no arroje excepciones
        $this->themeManager->load();
        $this->assertTrue(true); // Si llegamos aquí sin errores, la prueba pasa
    }

    public function testInitializePredefinedThemes(): void
    {
        $this->themeManager->initializePredefinedThemes();
        
        // Verificamos que predefinedThemes no esté vacío después de inicializar
        $reflectionProperty = new \ReflectionProperty(
            ThemeManager::class,
            'predefinedThemes'
        );
        $reflectionProperty->setAccessible(true);
        $themes = $reflectionProperty->getValue($this->themeManager);
        
        $this->assertNotEmpty($themes);
    }

    public function testRegisterPredefinedThemes(): void
    {
        // Probamos el método privado utilizando Reflection
        $reflectionMethod = new \ReflectionMethod(
            ThemeManager::class,
            'registerPredefinedThemes'
        );
        $reflectionMethod->setAccessible(true);

        $themes = $reflectionMethod->invoke($this->themeManager);
        
        // Verificamos que el resultado sea un array no vacío
        $this->assertIsArray($themes);
        $this->assertNotEmpty($themes);
        
        // Verificamos que tenga al menos el tema 'default'
        $this->assertArrayHasKey('default', $themes);
    }

    public function testGetThemes(): void
    {
        $themes = $this->themeManager->getThemes();
        
        // Verificamos que el resultado sea un array no vacío
        $this->assertIsArray($themes);
        $this->assertNotEmpty($themes);
    }

    public function testGetSelectedTheme(): void
    {
        $selectedTheme = $this->themeManager->getSelectedTheme();
        
        // Por defecto debería devolver 'default'
        $this->assertEquals('default', $selectedTheme);
    }

    public function testGetThemeConfig(): void
    {
        // Probamos con un tema que sabemos que existe
        $themeConfig = $this->themeManager->getThemeConfig('default');
        
        // Verificamos que el resultado sea un array no vacío
        $this->assertIsArray($themeConfig);
        $this->assertNotEmpty($themeConfig);
        
        // Verificamos que tenga las propiedades básicas
        $this->assertArrayHasKey('name', $themeConfig);
        $this->assertArrayHasKey('colors', $themeConfig);
        $this->assertArrayHasKey('typography', $themeConfig);
        $this->assertArrayHasKey('effects', $themeConfig);
        
        // Probamos con un tema que no existe
        $nonExistentTheme = $this->themeManager->getThemeConfig('non_existent_theme');
        $this->assertNull($nonExistentTheme);
    }

    public function testEnqueueAdminAssets(): void
    {
        // Esta prueba simplemente verifica que el método no arroje excepciones
        $this->themeManager->enqueueAdminAssets();
        $this->assertTrue(true); // Si llegamos aquí sin errores, la prueba pasa
    }

    public function testGenerateThemeCSS(): void
    {
        $css = $this->themeManager->generateThemeCSS();
        
        // Verificamos que el resultado sea una cadena
        $this->assertIsString($css);
    }
}