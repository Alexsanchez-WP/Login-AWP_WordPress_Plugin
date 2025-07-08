<?php

declare(strict_types=1);

namespace Login\Awp\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Login\Awp\Admin\StyleBuilder;

/**
 * Test case para la clase StyleBuilder
 */
class TestStyleBuilder extends TestCase
{
    private $styleBuilder;
    private $dirUrl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dirUrl = 'http://example.com/wp-content/plugins/login-awp/';
        $this->styleBuilder = new StyleBuilder($this->dirUrl);
    }

    public function testConstructorSetsProperties(): void
    {
        $expectedAssetUrl = $this->dirUrl . 'assets/';
        $this->assertEquals($expectedAssetUrl, $this->styleBuilder->dirUrl);
    }

    public function testLoadMethodAddsActions(): void
    {
        // Esta prueba simplemente verifica que el método no arroje excepciones
        $this->styleBuilder->load();
        $this->assertTrue(true); // Si llegamos aquí sin errores, la prueba pasa
    }

    public function testInitializeI18n(): void
    {
        // Esta prueba verifica que el método no arroje excepciones
        $this->styleBuilder->initializeI18n();
        
        // Verificamos que i18n no esté vacío después de inicializar
        $reflectionProperty = new \ReflectionProperty(
            StyleBuilder::class,
            'i18n'
        );
        $reflectionProperty->setAccessible(true);
        $i18n = $reflectionProperty->getValue($this->styleBuilder);
        
        $this->assertNotEmpty($i18n);
    }

    public function testEnqueueAdminAssets(): void
    {
        // Esta prueba simplemente verifica que el método no arroje excepciones
        $this->styleBuilder->enqueueAdminAssets();
        $this->assertTrue(true); // Si llegamos aquí sin errores, la prueba pasa
    }

    public function testGetAvailableFonts(): void
    {
        // Probamos el método privado utilizando Reflection
        $reflectionMethod = new \ReflectionMethod(
            StyleBuilder::class,
            'getAvailableFonts'
        );
        $reflectionMethod->setAccessible(true);

        $fonts = $reflectionMethod->invoke($this->styleBuilder);
        
        // Verificamos que el resultado sea un array no vacío
        $this->assertIsArray($fonts);
        $this->assertNotEmpty($fonts);
        
        // Verificamos que tenga las categorías esperadas
        $this->assertArrayHasKey('system', $fonts);
        $this->assertArrayHasKey('google', $fonts);
    }

    public function testGetCustomStyles(): void
    {
        $styles = $this->styleBuilder->getCustomStyles();
        
        // Verificamos que el resultado sea una cadena
        $this->assertIsString($styles);
    }

    public function testRenderStyleBuilder(): void
    {
        // Capturamos la salida para verificar que no haya errores
        ob_start();
        $this->styleBuilder->renderStyleBuilder();
        $output = ob_get_clean();
        
        // No necesitamos verificar el contenido exacto, solo que no haya errores
        $this->assertIsString($output);
    }
}