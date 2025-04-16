<?php

/**
 * Status message template
 *
 * @package Login\Awp\Admin
 * @requires Login\Awp\Admin\AdminRegister
 * @autor AWP-Software
 * @since 2.1.0
 * @version 2.1.0
 * @param string $class
 * @param string $text
 */

if (!defined('ABSPATH')) {
    die('You are not allowed to call this page directly.');
}

?>

<div class="<?php echo esc_attr($class); ?>">
    <p><?php echo esc_html($text); ?></p>
</div>
