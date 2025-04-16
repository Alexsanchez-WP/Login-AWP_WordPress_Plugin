<?php
/**
 * Style builder template
 *
 * @package Login\Awp\Admin
 * @requires Login\Awp\Admin\StyleBuilder
 * @author AWP-Software
 * @since 3.0.0
 * @version 3.0.0
 */

if (!defined('ABSPATH')) {
    die('You are not allowed to call this page directly.');
}

$custom_styles = $this->getCustomStyles();
?>

<div class="login-awp-style-builder">
    <div class="login-awp-style-editor">
        <div class="login-awp-style-controls">
            <div class="login-awp-control-panel">
                <h3><?php _e('Color Settings', 'login-awp'); ?></h3>
                
                <div class="login-awp-control-group">
                    <label for="login-awp-bg-color"><?php _e('Background Color', 'login-awp'); ?></label>
                    <input type="text" id="login-awp-bg-color" class="login-awp-color-picker" data-property="background-color" data-target="body.login" value="#f0f0f1">
                </div>
                
                <div class="login-awp-control-group">
                    <label for="login-awp-container-bg"><?php _e('Form Background', 'login-awp'); ?></label>
                    <input type="text" id="login-awp-container-bg" class="login-awp-color-picker" data-property="background-color" data-target="body.login div#login" value="rgba(255, 255, 255, 0.8)">
                </div>
                
                <div class="login-awp-control-group">
                    <label for="login-awp-text-color"><?php _e('Text Color', 'login-awp'); ?></label>
                    <input type="text" id="login-awp-text-color" class="login-awp-color-picker" data-property="color" data-target="body.login div#login form#loginform p, body.login div#login form#loginform p label" value="#000000">
                </div>
                
                <div class="login-awp-control-group">
                    <label for="login-awp-button-bg"><?php _e('Button Color', 'login-awp'); ?></label>
                    <input type="text" id="login-awp-button-bg" class="login-awp-color-picker" data-property="background-color" data-target="body.login div#login form#loginform p.submit input#wp-submit" value="#4182E0">
                </div>
                
                <div class="login-awp-control-group">
                    <label for="login-awp-button-hover"><?php _e('Button Hover Color', 'login-awp'); ?></label>
                    <input type="text" id="login-awp-button-hover" class="login-awp-color-picker" data-property="background-color" data-target="body.login div#login form#loginform p.submit input#wp-submit:hover" value="#014F7F">
                </div>
                
                <div class="login-awp-control-group">
                    <label for="login-awp-link-color"><?php _e('Link Color', 'login-awp'); ?></label>
                    <input type="text" id="login-awp-link-color" class="login-awp-color-picker" data-property="color" data-target="body.login div#login p#nav a, body.login div#login p#backtoblog a" value="#808080">
                </div>
            </div>
            
            <div class="login-awp-control-panel">
                <h3><?php _e('Typography', 'login-awp'); ?></h3>
                
                <div class="login-awp-control-group">
                    <label for="login-awp-font-family"><?php _e('Font Family', 'login-awp'); ?></label>
                    <select id="login-awp-font-family" class="login-awp-font-selector" data-property="font-family" data-target="body.login">
                        <option value=""><?php _e('Select a font', 'login-awp'); ?></option>
                        <optgroup label="<?php _e('System Fonts', 'login-awp'); ?>">
                            <option value="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif"><?php _e('System Default', 'login-awp'); ?></option>
                            <option value="Arial, sans-serif">Arial</option>
                            <option value="'Helvetica Neue', Helvetica, Arial, sans-serif">Helvetica</option>
                            <option value="Georgia, serif">Georgia</option>
                            <option value="'Times New Roman', Times, serif">Times New Roman</option>
                        </optgroup>
                        <optgroup label="<?php _e('Google Fonts', 'login-awp'); ?>">
                            <option value="'Open Sans', sans-serif">Open Sans</option>
                            <option value="'Roboto', sans-serif">Roboto</option>
                            <option value="'Lato', sans-serif">Lato</option>
                            <option value="'Montserrat', sans-serif">Montserrat</option>
                            <option value="'Poppins', sans-serif">Poppins</option>
                        </optgroup>
                    </select>
                </div>
                
                <div class="login-awp-control-group">
                    <label for="login-awp-font-size"><?php _e('Font Size', 'login-awp'); ?></label>
                    <div class="login-awp-slider-control">
                        <input type="range" id="login-awp-font-size" min="10" max="24" step="1" value="14" data-property="font-size" data-unit="px" data-target="body.login">
                        <span class="login-awp-slider-value">14px</span>
                    </div>
                </div>
                
                <div class="login-awp-control-group">
                    <label for="login-awp-line-height"><?php _e('Line Height', 'login-awp'); ?></label>
                    <div class="login-awp-slider-control">
                        <input type="range" id="login-awp-line-height" min="1" max="2" step="0.1" value="1.5" data-property="line-height" data-target="body.login">
                        <span class="login-awp-slider-value">1.5</span>
                    </div>
                </div>
            </div>
            
            <div class="login-awp-control-panel">
                <h3><?php _e('Layout & Effects', 'login-awp'); ?></h3>
                
                <div class="login-awp-control-group">
                    <label for="login-awp-border-radius"><?php _e('Border Radius', 'login-awp'); ?></label>
                    <div class="login-awp-slider-control">
                        <input type="range" id="login-awp-border-radius" min="0" max="50" step="1" value="10" data-property="border-radius" data-unit="px" data-target="body.login div#login">
                        <span class="login-awp-slider-value">10px</span>
                    </div>
                </div>
                
                <div class="login-awp-control-group">
                    <label for="login-awp-box-shadow"><?php _e('Box Shadow', 'login-awp'); ?></label>
                    <select id="login-awp-box-shadow" data-property="box-shadow" data-target="body.login div#login">
                        <option value="none"><?php _e('None', 'login-awp'); ?></option>
                        <option value="0 2px 5px rgba(0, 0, 0, 0.1)" selected><?php _e('Light', 'login-awp'); ?></option>
                        <option value="0 4px 10px rgba(0, 0, 0, 0.15)"><?php _e('Medium', 'login-awp'); ?></option>
                        <option value="0 8px 20px rgba(0, 0, 0, 0.2)"><?php _e('Heavy', 'login-awp'); ?></option>
                        <option value="0 12px 28px rgba(0, 0, 0, 0.25), 0 4px 10px rgba(0, 0, 0, 0.22)"><?php _e('Layered', 'login-awp'); ?></option>
                    </select>
                </div>
                
                <div class="login-awp-control-group">
                    <label for="login-awp-padding"><?php _e('Form Padding', 'login-awp'); ?></label>
                    <div class="login-awp-slider-control">
                        <input type="range" id="login-awp-padding" min="10" max="80" step="5" value="30" data-property="padding" data-unit="px" data-target="body.login div#login">
                        <span class="login-awp-slider-value">30px</span>
                    </div>
                </div>
                
                <div class="login-awp-control-group">
                    <label for="login-awp-form-width"><?php _e('Form Width', 'login-awp'); ?></label>
                    <div class="login-awp-slider-control">
                        <input type="range" id="login-awp-form-width" min="40" max="100" step="5" value="70" data-property="width" data-unit="%" data-target="body.login div#login">
                        <span class="login-awp-slider-value">70%</span>
                    </div>
                </div>
                
                <div class="login-awp-control-group">
                    <label for="login-awp-button-style"><?php _e('Button Style', 'login-awp'); ?></label>
                    <select id="login-awp-button-style" class="login-awp-button-style-selector">
                        <option value="default" selected><?php _e('Default', 'login-awp'); ?></option>
                        <option value="rounded"><?php _e('Rounded', 'login-awp'); ?></option>
                        <option value="pill"><?php _e('Pill', 'login-awp'); ?></option>
                        <option value="shadow"><?php _e('Shadow', 'login-awp'); ?></option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="login-awp-style-preview">
            <h3><?php _e('Live Preview', 'login-awp'); ?></h3>
            <div class="login-awp-preview-container">
                <iframe id="style-preview-frame" src="" title="<?php _e('Login Style Preview', 'login-awp'); ?>"></iframe>
                <div class="login-awp-loading-overlay">
                    <span class="spinner is-active"></span>
                    <p><?php _e('Loading preview...', 'login-awp'); ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="login-awp-style-actions">
        <button id="reset-styles" class="button"><?php _e('Reset Changes', 'login-awp'); ?></button>
        <button id="save-styles" class="button button-primary"><?php _e('Save Styles', 'login-awp'); ?></button>
    </div>
    
    <div class="login-awp-style-code">
        <h3><?php _e('Generated CSS', 'login-awp'); ?></h3>
        <textarea id="login-awp-custom-css" rows="10" readonly><?php echo esc_textarea($custom_styles); ?></textarea>
    </div>
</div>
