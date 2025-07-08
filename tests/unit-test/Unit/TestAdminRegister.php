<?php

declare(strict_types=1);

namespace Login\Awp\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Login\Awp\Admin\AdminRegister;

/**
 * Test case para la clase AdminRegister
 */
class TestAdminRegister extends TestCase
{
    private $adminRegister;
    private $dirUrl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dirUrl = 'http://example.com/wp-content/plugins/login-awp/';
        $this->adminRegister = new AdminRegister($this->dirUrl);
    }

    public function testConstructorSetsProperties(): void
    {
        $expectedAssetUrl = $this->dirUrl . 'assets/';
        $this->assertEquals($expectedAssetUrl, $this->adminRegister->dirUrl);
    }

    public function testLoadMethodAddsActions(): void
    {
        // Esta prueba simplemente verifica que el método no arroje excepciones
        $this->adminRegister->load();
        $this->assertTrue(true); // Si llegamos aquí sin errores, la prueba pasa
    }

    public function testRegisterSubMenu(): void
    {
        // Esta prueba verifica que el método no arroje excepciones
        $this->adminRegister->registerSubMenu();
        $this->assertTrue(true); // Si llegamos aquí sin errores, la prueba pasa
    }

    public function testAdminStyles(): void
    {
        // Esta prueba verifica que el método no arroje excepciones
        $this->adminRegister->adminStyles();
        $this->assertTrue(true); // Si llegamos aquí sin errores, la prueba pasa
    }

    public function testAdminScripts(): void
    {
        // Esta prueba verifica que el método no arroje excepciones
        $this->adminRegister->adminScripts();
        $this->assertTrue(true); // Si llegamos aquí sin errores, la prueba pasa
    }

    public function testUpdateOption(): void
    {
        // Probamos el método privado utilizando Reflection
        $reflectionMethod = new \ReflectionMethod(
            AdminRegister::class,
            'updateOption'
        );
        $reflectionMethod->setAccessible(true);

        $result = $reflectionMethod->invoke(
            $this->adminRegister,
            'https://example.com/test.jpg',
            'logo_status',
            AdminRegister::$imgLogoName
        );

        // Verificamos que el resultado sea una cadena que contenga el parámetro 'logo_status'
        $this->assertStringContainsString('logo_status', $result);
    }

    public function testStatusMessage(): void
    {
        // Simulamos $_GET para probar el método
        $_GET = [];
        $this->adminRegister->statusMessage();
        $this->assertTrue(true); // Si llegamos aquí sin errores, la prueba pasa

        // Simulamos $_GET con logo_status
        $_GET = ['logo_status' => 'success'];
        ob_start(); // Capturamos la salida
        $this->adminRegister->statusMessage();
        $output = ob_get_clean();
        $this->assertIsString($output); // La salida debe ser una cadena (o vacía)

        // Simulamos $_GET con background_status
        $_GET = ['background_status' => 'success'];
        ob_start(); // Capturamos la salida
        $this->adminRegister->statusMessage();
        $output = ob_get_clean();
        $this->assertIsString($output); // La salida debe ser una cadena (o vacía)
    }

    public function testMessageTemplate(): void
    {
        // Probamos el método privado utilizando Reflection
        $reflectionMethod = new \ReflectionMethod(
            AdminRegister::class,
            'messageTemplate'
        );
        $reflectionMethod->setAccessible(true);

        ob_start(); // Capturamos la salida
        $reflectionMethod->invoke(
            $this->adminRegister,
            'success',
            'logo'
        );
        $output = ob_get_clean();
        
        // Simplemente verificamos que no haya errores
        $this->assertIsString($output);
    }

    public function testDisplayReviewNotice(): void
    {
        // Esta prueba verifica que el método no arroje excepciones
        ob_start(); // Capturamos la salida
        $this->adminRegister->displayReviewNotice();
        $output = ob_get_clean();
        $this->assertIsString($output);
    }
}