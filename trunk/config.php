<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! defined( 'AWP_LOGIN_FEEDBACK_EMAIL' ) ) {
    define('AWP_LOGIN_FEEDBACK_EMAIL', 'support@awp-software.com');
}

if ( ! defined( 'AWP_LOGIN_FEEDBACK_WEBHOOK' ) ) {
    define('AWP_LOGIN_FEEDBACK_WEBHOOK', 'https://telemetry.awp-software.com/feedback');
}
