<?php

declare(strict_types=1);

/**
 * Bootstrap file for PHPUnit tests
 */

// Carga el autoloader de Composer
require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

// Define constantes que normalmente estarían disponibles en WordPress
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__DIR__) . '/');
}

// Define las constantes de opciones utilizadas en el plugin
define('AWP_LOGIN_LOGO_OPTION', 'awp_login_logo');
define('AWP_LOGIN_BACKGROUND_OPTION', 'awp_login_background');
define('AWP_LOGIN_ACTIVATION_DATE_OPTION', 'awp_login_activation_date');
define('AWP_LOGIN_REVIEW_DISMISSED_OPTION', 'awp_login_review_dismissed');
define('AWP_LOGIN_FEEDBACK_EMAIL_OPTION', 'awp_login_feedback_email');
define('AWP_LOGIN_FEEDBACK_WEBHOOK_OPTION', 'awp_login_feedback_webhook');
define('AWP_LOGIN_FEEDBACK_EMAIL', 'feedback@example.com');
define('AWP_LOGIN_FEEDBACK_WEBHOOK', 'https://example.com/webhook');

// Mock de funciones de WordPress
require_once __DIR__ . '/wp-mock-functions.php';