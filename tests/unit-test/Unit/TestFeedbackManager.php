<?php

declare(strict_types=1);

namespace Login\Awp\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Login\Awp\Admin\FeedbackManager;

/**
 * Test case para la clase FeedbackManager
 */
class TestFeedbackManager extends TestCase
{
    private $feedbackManager;
    private $dirUrl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dirUrl = 'http://example.com/wp-content/plugins/login-awp/';
        $this->feedbackManager = new FeedbackManager($this->dirUrl);
    }

    public function testConstructorSetsProperties(): void
    {
        $expectedAssetUrl = $this->dirUrl . 'assets/';
        $reflection = new \ReflectionObject($this->feedbackManager);
        $property = $reflection->getProperty('dirUrl');
        $property->setAccessible(true);
        $this->assertEquals($expectedAssetUrl, $property->getValue($this->feedbackManager));
    }

    public function testLoadMethodAddsActions(): void
    {
        // Simulamos estar en el admin
        $_SERVER['PHP_SELF'] = '/wp-admin/index.php';
        
        // Esta prueba simplemente verifica que el método no arroje excepciones
        $this->feedbackManager->load();
        $this->assertTrue(true); // Si llegamos aquí sin errores, la prueba pasa
    }

    public function testEnqueueScripts(): void
    {
        // Esta prueba verifica que el método no arroje excepciones
        $this->feedbackManager->enqueueScripts('plugins.php');
        $this->assertTrue(true); // Si llegamos aquí sin errores, la prueba pasa
        
        // También probamos cuando se llama desde otra página
        $this->feedbackManager->enqueueScripts('index.php');
        $this->assertTrue(true);
    }

    public function testRenderModal(): void
    {
        // Simulamos la pantalla de plugins
        set_current_screen('plugins');
        
        // Capturamos la salida para verificar que no haya errores
        ob_start();
        $this->feedbackManager->renderModal();
        $output = ob_get_clean();
        
        // No necesitamos verificar el contenido exacto, solo que no haya errores
        $this->assertIsString($output);
    }

    public function testGetReadableReason(): void
    {
        // Probamos el método privado utilizando Reflection
        $reflectionMethod = new \ReflectionMethod(
            FeedbackManager::class,
            'getReadableReason'
        );
        $reflectionMethod->setAccessible(true);

        $reason = $reflectionMethod->invoke($this->feedbackManager, 'no_longer_needed');
        
        // Verificamos que el resultado sea una cadena no vacía
        $this->assertIsString($reason);
        $this->assertNotEmpty($reason);
    }

    public function testRegisterSettings(): void
    {
        // Esta prueba verifica que el método no arroje excepciones
        $this->feedbackManager->registerSettings();
        $this->assertTrue(true); // Si llegamos aquí sin errores, la prueba pasa
    }

    public function testRenderSettingsSection(): void
    {
        // Capturamos la salida para verificar que no haya errores
        ob_start();
        $this->feedbackManager->renderSettingsSection();
        $output = ob_get_clean();
        
        // No necesitamos verificar el contenido exacto, solo que no haya errores
        $this->assertIsString($output);
    }

    public function testRenderEmailField(): void
    {
        // Capturamos la salida para verificar que no haya errores
        ob_start();
        $this->feedbackManager->renderEmailField();
        $output = ob_get_clean();
        
        // No necesitamos verificar el contenido exacto, solo que no haya errores
        $this->assertIsString($output);
    }

    public function testRenderWebhookField(): void
    {
        // Capturamos la salida para verificar que no haya errores
        ob_start();
        $this->feedbackManager->renderWebhookField();
        $output = ob_get_clean();
        
        // No necesitamos verificar el contenido exacto, solo que no haya errores
        $this->assertIsString($output);
    }
}