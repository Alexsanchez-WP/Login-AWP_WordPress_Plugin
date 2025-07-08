<?php

declare(strict_types=1);

namespace Login\Awp\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Login\Awp\Register;

/**
 * Test case para la clase Register
 */
class TestRegister extends TestCase
{
    private $register;
    private $dirUrl;
    private $dirPath;
    private $domainPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dirUrl = 'http://example.com/wp-content/plugins/login-awp/';
        $this->dirPath = '/path/to/plugin/login-awp/';
        $this->domainPath = 'languages';
        $this->register = new Register(
            $this->dirUrl,
            $this->dirPath,
            $this->domainPath
        );
    }

    public function testConstructorSetsProperties(): void
    {
        $this->assertEquals($this->dirUrl, $this->register->dirUrl);
        $this->assertEquals($this->dirPath, $this->register->dirPath);
        $this->assertEquals($this->domainPath, $this->register->domainPath);
    }

    public function testLoadMethodAddsAction(): void
    {
        // Esta prueba simplemente verifica que el método no arroje excepciones
        $this->register->load();
        $this->assertTrue(true); // Si llegamos aquí sin errores, la prueba pasa
    }

    public function testInitPluginLoadsTextdomain(): void
    {
        // Esta prueba verifica que el método no arroje excepciones
        $this->register->initPlugin();
        $this->assertTrue(true); // Si llegamos aquí sin errores, la prueba pasa
    }

    public function testLoginAwpTextdomain(): void
    {
        // Esta prueba verifica que el método no arroje excepciones
        $this->register->loginAwpTextdomain();
        $this->assertTrue(true); // Si llegamos aquí sin errores, la prueba pasa
    }
}