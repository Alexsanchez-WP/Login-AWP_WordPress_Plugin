<?php
/**
 * Theme selector template
 *
 * @package Login\Awp\Admin
 * @requires Login\Awp\Admin\ThemeManager
 * @author AWP-Software
 * @since 3.0.0
 * @version 3.0.0
 */

if (!defined('ABSPATH')) {
    die('You are not allowed to call this page directly.');
}

// Get all available themes
$themes = $theme_manager->getThemes();
$selected_theme = $theme_manager->getSelectedTheme();
?>

<div class="login-awp-theme-selector">
    <div class="login-awp-themes-grid">
        <?php foreach ($themes as $theme_key => $theme) : ?>
            <div class="login-awp-theme-card <?php echo $theme_key === $selected_theme ? 'selected' : ''; ?>" 
                 data-theme="<?php echo esc_attr($theme_key); ?>">
                <div class="login-awp-theme-preview">
                    <img src="<?php echo esc_url($theme['preview']); ?>" alt="<?php echo esc_attr($theme['name']); ?>">
                </div>
                <div class="login-awp-theme-info">
                    <h3><?php echo esc_html($theme['name']); ?></h3>
                    <p><?php echo esc_html($theme['description']); ?></p>
                </div>
                <div class="login-awp-theme-actions">
                    <button class="button preview-theme" data-theme="<?php echo esc_attr($theme_key); ?>">
                        <?php _e('Preview', 'login-awp'); ?>
                    </button>
                    <?php if ($theme_key !== $selected_theme) : ?>
                        <button class="button button-primary select-theme" data-theme="<?php echo esc_attr($theme_key); ?>">
                            <?php _e('Apply Theme', 'login-awp'); ?>
                        </button>
                    <?php else : ?>
                        <span class="theme-active"><?php _e('Active Theme', 'login-awp'); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="login-awp-theme-preview-modal" style="display: none;">
        <div class="login-awp-preview-container">
            <div class="login-awp-preview-header">
                <h2><?php _e('Theme Preview', 'login-awp'); ?></h2>
                <button class="close-preview">&times;</button>
            </div>
            <div class="login-awp-preview-content">
                <iframe id="theme-preview-frame" src="about:blank"></iframe>
            </div>
            <div class="login-awp-preview-footer">
                <button class="button close-preview"><?php _e('Close', 'login-awp'); ?></button>
                <button class="button button-primary apply-theme"><?php _e('Apply This Theme', 'login-awp'); ?></button>
            </div>
        </div>
    </div>
    
    <!-- Modal de confirmación para reset de Style Builder -->
    <div id="login-awp-reset-confirm-modal" class="login-awp-modal" style="display: none;">
        <div class="login-awp-modal-container">
            <div class="login-awp-modal-header">
                <h2><?php _e('Reset Style Builder Changes', 'login-awp'); ?></h2>
            </div>
            <div class="login-awp-modal-content">
                <p><?php _e('You have custom styles from the Style Builder. To apply this theme, you need to reset your Style Builder changes first.', 'login-awp'); ?></p>
                <p><?php _e('Do you want to reset your Style Builder changes and apply this theme?', 'login-awp'); ?></p>
            </div>
            <div class="login-awp-modal-footer">
                <button class="button cancel-reset"><?php _e('Cancel', 'login-awp'); ?></button>
                <button class="button button-primary confirm-reset" data-theme=""><?php _e('Reset and Apply Theme', 'login-awp'); ?></button>
            </div>
        </div>
    </div>
</div>
