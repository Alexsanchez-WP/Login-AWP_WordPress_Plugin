<?php

declare(strict_types=1);

namespace Login\Awp\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Login\Awp\Public\PublicRegister;

/**
 * Test case para la clase PublicRegister
 */
class TestPublicRegister extends TestCase
{
    private $publicRegister;
    private $dirUrl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dirUrl = 'http://example.com/wp-content/plugins/login-awp/';
        $this->publicRegister = new PublicRegister($this->dirUrl);
    }

    public function testConstructorSetsProperties(): void
    {
        $expectedAssetUrl = $this->dirUrl . 'assets/';
        $this->assertEquals($expectedAssetUrl, $this->publicRegister->dirUrl);
    }

    public function testLoadMethodAddsActions(): void
    {
        // Esta prueba simplemente verifica que el método no arroje excepciones
        $this->publicRegister->load();
        $this->assertTrue(true); // Si llegamos aquí sin errores, la prueba pasa
    }

    public function testLoginAwpStyles(): void
    {
        // Esta prueba verifica que el método no arroje excepciones
        $this->publicRegister->loginAwpStyles();
        $this->assertTrue(true); // Si llegamos aquí sin errores, la prueba pasa
    }

    public function testLoginAwpScripts(): void
    {
        // Esta prueba verifica que el método no arroje excepciones
        $this->publicRegister->loginAwpScripts();
        $this->assertTrue(true); // Si llegamos aquí sin errores, la prueba pasa
    }

    public function testLoginAwpLocalize(): void
    {
        // Esta prueba verifica que el método no arroje excepciones
        $this->publicRegister->loginAwpLocalize();
        $this->assertTrue(true); // Si llegamos aquí sin errores, la prueba pasa
    }

    public function testLoginAwpThemeStyles(): void
    {
        // Capturamos la salida para verificar que no haya errores
        ob_start();
        $this->publicRegister->loginAwpThemeStyles();
        $output = ob_get_clean();
        
        // No necesitamos verificar el contenido exacto, solo que no haya errores
        $this->assertIsString($output);
    }
}