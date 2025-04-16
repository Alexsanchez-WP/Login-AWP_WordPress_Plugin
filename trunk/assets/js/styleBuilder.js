/**
 * Style Builder Script
 *
 * Handles style builder functionality in the admin area
 */

(function($) {
    'use strict';

    // Store the current CSS rules
    let cssRules = {};
    // Flag to track if the iframe is loaded
    let previewFrameLoaded = false;

    // Initialize when document is ready
    $(document).ready(function() {
        initStyleBuilder();
    });

    /**
     * Initialize the style builder functionality
     */
    function initStyleBuilder() {
        // Color picker initialization
        $('.login-awp-color-picker').wpColorPicker({
            change: function(event, ui) {
                const property = $(this).data('property');
                const target = $(this).data('target');
                const value = ui.color.toString();
                updateCSSRule(target, property, value);
            }
        });

        // Font family change
        $('#login-awp-font-family').on('change', function() {
            const property = $(this).data('property');
            const target = $(this).data('target');
            const value = $(this).val();
            updateCSSRule(target, property, value);
        });

        // Slider controls
        $('.login-awp-slider-control input[type="range"]').on('input', function() {
            const property = $(this).data('property');
            const target = $(this).data('target');
            const value = $(this).val() + $(this).data('unit');
            updateCSSRule(target, property, value);
            $(this).next('.login-awp-slider-value').text(value);
        });

        // Box shadow change
        $('#login-awp-box-shadow').on('change', function() {
            const property = $(this).data('property');
            const target = $(this).data('target');
            const value = $(this).val();
            updateCSSRule(target, property, value);
        });

        // Button style change
        $('#login-awp-button-style').on('change', function() {
            const value = $(this).val();
            updateButtonStyle(value);
        });

        // Save styles button click
        $('#save-styles').on('click', function(e) {
            e.preventDefault();
            saveStyles();
        });

        // Reset styles button click
        $('#reset-styles').on('click', function(e) {
            e.preventDefault();
            if (confirm(loginAwpStyleBuilder.i18n.confirm_reset)) {
                resetStyles();
            }
        });

        // Initialize the preview iframe
        initPreviewFrame();
    }

    /**
     * Initialize the preview iframe with the current theme
     */
    function initPreviewFrame() {
        // Get the preview frame element
        const previewFrame = document.getElementById('style-preview-frame');
        
        if (!previewFrame) return;
        
        // Set the iframe source to the preview URL
        $(previewFrame).attr('src', loginAwpStyleBuilder.previewUrl);
        
        // Listen for the iframe to load
        $(previewFrame).on('load', function() {
            previewFrameLoaded = true;
            
            // When iframe is loaded, load the custom styles or current theme
            loadCustomStyles();
            
            // Hide the loading overlay once the iframe is loaded
            $('.login-awp-loading-overlay').fadeOut(300);
        });
    }

    /**
     * Update a CSS rule
     * 
     * @param {string} target The CSS selector
     * @param {string} property The CSS property
     * @param {string} value The CSS value
     */
    function updateCSSRule(target, property, value) {
        if (!cssRules[target]) {
            cssRules[target] = {};
        }
        cssRules[target][property] = value;
        
        // Si se está modificando el color de fondo del body, actualizar también el pseudo-elemento
        if (target === 'body.login' && property === 'background-color') {
            if (!cssRules['body.login::before']) {
                cssRules['body.login::before'] = {};
            }
            cssRules['body.login::before']['background-color'] = value;
        }
        
        updatePreview();
    }

    /**
     * Update button style based on selected template
     * 
     * @param {string} style The button style name
     */
    function updateButtonStyle(style) {
        const buttonSelector = 'body.login div#login form#loginform p.submit input#wp-submit';
        
        // Reset button styles
        delete cssRules[buttonSelector];
        
        // Apply new styles based on template
        switch (style) {
            case 'rounded':
                updateCSSRule(buttonSelector, 'border-radius', '4px');
                updateCSSRule(buttonSelector, 'text-transform', 'none');
                updateCSSRule(buttonSelector, 'box-shadow', 'none');
                break;
            case 'pill':
                updateCSSRule(buttonSelector, 'border-radius', '50px');
                updateCSSRule(buttonSelector, 'text-transform', 'uppercase');
                updateCSSRule(buttonSelector, 'box-shadow', 'none');
                updateCSSRule(buttonSelector, 'padding', '0 24px');
                break;
            case 'shadow':
                updateCSSRule(buttonSelector, 'border-radius', '3px');
                updateCSSRule(buttonSelector, 'box-shadow', '0 4px 6px rgba(0, 0, 0, 0.15)');
                updateCSSRule(buttonSelector, 'text-transform', 'none');
                break;
            default: // default
                updateCSSRule(buttonSelector, 'border-radius', '3px');
                updateCSSRule(buttonSelector, 'text-transform', 'none');
                updateCSSRule(buttonSelector, 'box-shadow', 'none');
                break;
        }
        
        updatePreview();
    }

    /**
     * Update the live preview
     */
    function updatePreview() {
        if (!previewFrameLoaded) return;
        
        try {
            let css = '';
            for (const target in cssRules) {
                css += target + ' {';
                for (const property in cssRules[target]) {
                    css += property + ': ' + cssRules[target][property] + ';';
                }
                css += '} ';
            }
            
            const frameDocument = document.getElementById('style-preview-frame').contentDocument || 
                                  document.getElementById('style-preview-frame').contentWindow.document;
            
            // Find or create the style element
            let styleEl = frameDocument.getElementById('login-awp-style-preview');
            if (!styleEl) {
                styleEl = frameDocument.createElement('style');
                styleEl.id = 'login-awp-style-preview';
                frameDocument.head.appendChild(styleEl);
            }
            
            // Update the style content
            styleEl.textContent = css;
            
            // Update the CSS textarea
            $('#login-awp-custom-css').val(css);
        } catch (e) {
            console.error('Error updating preview:', e);
        }
    }

    /**
     * Load initial custom styles
     */
    function loadCustomStyles() {
        if (!previewFrameLoaded) return;
        
        const customStyles = loginAwpStyleBuilder.customStyles;
        
        if (customStyles) {
            // Parse the custom CSS and populate the cssRules object
            try {
                // Create a simpler representation of the custom styles
                $('#login-awp-custom-css').val(customStyles);
                
                // Create a temporary style element and add it to the iframe
                const frameDocument = document.getElementById('style-preview-frame').contentDocument || 
                                      document.getElementById('style-preview-frame').contentWindow.document;
                
                let styleEl = frameDocument.getElementById('login-awp-style-preview');
                if (!styleEl) {
                    styleEl = frameDocument.createElement('style');
                    styleEl.id = 'login-awp-style-preview';
                    frameDocument.head.appendChild(styleEl);
                }
                
                styleEl.textContent = customStyles;
                
                // Try to parse the CSS and fill the cssRules object
                parseCustomCSS(customStyles);
                
                // Update the UI controls to match the custom styles
                updateControlsFromCSS();
                
            } catch (e) {
                console.error('Error loading custom styles:', e);
            }
        } else {
            // If no custom styles, set some default values to show something in the preview
            setDefaultStyles();
        }
    }
    
    /**
     * Parse custom CSS text into our cssRules object
     * 
     * @param {string} css The CSS text to parse
     */
    function parseCustomCSS(css) {
        // Basic CSS parser - note this is simplified and not a full CSS parser
        const ruleRegex = /([^{]+){([^}]*)}/g;
        const propertyRegex = /\s*([^:]+):\s*([^;]+);/g;
        
        let match;
        while ((match = ruleRegex.exec(css)) !== null) {
            const selector = match[1].trim();
            const ruleBody = match[2];
            
            if (!cssRules[selector]) {
                cssRules[selector] = {};
            }
            
            let propMatch;
            while ((propMatch = propertyRegex.exec(ruleBody)) !== null) {
                const property = propMatch[1].trim();
                const value = propMatch[2].trim();
                cssRules[selector][property] = value;
            }
        }
    }
    
    /**
     * Update UI controls based on the cssRules
     */
    function updateControlsFromCSS() {
        // Update color pickers
        updateColorPicker('#login-awp-bg-color', 'body.login', 'background-color');
        updateColorPicker('#login-awp-container-bg', 'body.login div#login', 'background-color');
        updateColorPicker('#login-awp-text-color', 'body.login div#login form#loginform p, body.login div#login form#loginform p label', 'color');
        updateColorPicker('#login-awp-button-bg', 'body.login div#login form#loginform p.submit input#wp-submit', 'background-color');
        updateColorPicker('#login-awp-button-hover', 'body.login div#login form#loginform p.submit input#wp-submit:hover', 'background-color');
        updateColorPicker('#login-awp-link-color', 'body.login div#login p#nav a, body.login div#login p#backtoblog a', 'color');
        
        // Update select controls
        updateSelectValue('#login-awp-font-family', 'body.login', 'font-family');
        updateSelectValue('#login-awp-box-shadow', 'body.login div#login', 'box-shadow');
        
        // Update range sliders
        updateRangeSlider('#login-awp-font-size', 'body.login', 'font-size', 'px');
        updateRangeSlider('#login-awp-line-height', 'body.login', 'line-height', '');
        updateRangeSlider('#login-awp-border-radius', 'body.login div#login', 'border-radius', 'px');
        updateRangeSlider('#login-awp-padding', 'body.login div#login', 'padding', 'px');
        updateRangeSlider('#login-awp-form-width', 'body.login div#login', 'width', '%');
    }
    
    /**
     * Update a color picker control value
     */
    function updateColorPicker(selector, cssSelector, property) {
        if (cssRules[cssSelector] && cssRules[cssSelector][property]) {
            const $picker = $(selector);
            if ($picker.length) {
                $picker.val(cssRules[cssSelector][property]).wpColorPicker('color', cssRules[cssSelector][property]);
            }
        }
    }
    
    /**
     * Update a select control value
     */
    function updateSelectValue(selector, cssSelector, property) {
        if (cssRules[cssSelector] && cssRules[cssSelector][property]) {
            const $select = $(selector);
            if ($select.length) {
                $select.val(cssRules[cssSelector][property]);
            }
        }
    }
    
    /**
     * Update a range slider control value
     */
    function updateRangeSlider(selector, cssSelector, property, unit) {
        if (cssRules[cssSelector] && cssRules[cssSelector][property]) {
            const $slider = $(selector);
            if ($slider.length) {
                let value = cssRules[cssSelector][property];
                // Remove unit if present
                if (unit) {
                    value = value.replace(unit, '');
                }
                $slider.val(value);
                $slider.next('.login-awp-slider-value').text(value + unit);
            }
        }
    }
    
    /**
     * Set default styles for the preview
     */
    function setDefaultStyles() {
        // Get current logo and background images from localized data
        const currentLogo = loginAwpStyleBuilder.currentImages?.logo || '';
        const currentBackground = loginAwpStyleBuilder.currentImages?.background || '';
        
        // Set default background color (antes de la imagen de fondo)
        updateCSSRule('body.login', 'background-color', '#f0f0f1');
        
        // Crear un pseudo-elemento que funcione como overlay del color de fondo
        updateCSSRule('body.login::before', 'content', '""');
        updateCSSRule('body.login::before', 'position', 'absolute');
        updateCSSRule('body.login::before', 'top', '0');
        updateCSSRule('body.login::before', 'left', '0');
        updateCSSRule('body.login::before', 'width', '100%');
        updateCSSRule('body.login::before', 'height', '100%');
        updateCSSRule('body.login::before', 'background-color', 'inherit');
        updateCSSRule('body.login::before', 'opacity', '0.7');
        updateCSSRule('body.login::before', 'z-index', '-1');
        
        // Add background image
        updateCSSRule('body.login', 'background-image', 'url(' + currentBackground + ')');
        updateCSSRule('body.login', 'background-size', 'cover');
        updateCSSRule('body.login', 'background-position', 'center');
        updateCSSRule('body.login', 'position', 'relative');
        
        // Add logo
        updateCSSRule('body.login div#login h1 a', 'background-image', 'url(' + currentLogo + ')');
        updateCSSRule('body.login div#login h1 a', 'background-size', '100%');
        
        updateCSSRule('body.login', 'font-family', '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif');
        updateCSSRule('body.login', 'font-size', '14px');
        
        // Set default container styles
        updateCSSRule('body.login div#login', 'background-color', 'rgba(255, 255, 255, 0.8)');
        updateCSSRule('body.login div#login', 'border-radius', '10px');
        updateCSSRule('body.login div#login', 'box-shadow', '0 2px 5px rgba(0, 0, 0, 0.1)');
        updateCSSRule('body.login div#login', 'position', 'relative');
        updateCSSRule('body.login div#login', 'z-index', '2');
        
        // Set default text colors
        updateCSSRule('body.login div#login form#loginform p, body.login div#login form#loginform p label', 'color', '#000000');
        
        // Set default button colors
        updateCSSRule('body.login div#login form#loginform p.submit input#wp-submit', 'background-color', '#4182E0');
        updateCSSRule('body.login div#login form#loginform p.submit input#wp-submit:hover', 'background-color', '#014F7F');
        
        // Set default link colors
        updateCSSRule('body.login div#login p#nav a, body.login div#login p#backtoblog a', 'color', '#808080');
        
        // Update the UI controls to match
        updateControlsFromCSS();
    }

    /**
     * Save custom styles
     */
    function saveStyles() {
        const css = $('#login-awp-custom-css').val();
        $.ajax({
            url: loginAwpStyleBuilder.ajaxurl,
            type: 'POST',
            data: {
                action: 'login_awp_save_custom_styles',
                nonce: loginAwpStyleBuilder.nonce,
                css: css
            },
            success: function(response) {
                if (response.success) {
                    showNotice('success', response.data.message || loginAwpStyleBuilder.i18n.save_success);
                } else {
                    showNotice('error', response.data.message || loginAwpStyleBuilder.i18n.save_error);
                }
            },
            error: function() {
                showNotice('error', loginAwpStyleBuilder.i18n.save_error);
            }
        });
    }

    /**
     * Reset custom styles
     */
    function resetStyles() {
        // Mostrar indicador visual de carga
        const $saveButton = $('#save-styles');
        const originalText = $saveButton.text();
        $saveButton.prop('disabled', true).text('Resetting...');
        
        $.ajax({
            url: loginAwpStyleBuilder.ajaxurl,
            type: 'POST',
            data: {
                action: 'login_awp_reset_custom_styles',
                nonce: loginAwpStyleBuilder.nonce
            },
            success: function(response) {
                if (response.success) {
                    showNotice('success', response.data.message || loginAwpStyleBuilder.i18n.reset_success);
                    // Reset UI to defaults
                    cssRules = {};
                    $('#login-awp-custom-css').val('');
                    
                    // Establecer estilos por defecto en lugar de recargar la página
                    setDefaultStyles();
                    
                    // Restaurar el botón
                    $saveButton.prop('disabled', false).text(originalText);
                } else {
                    showNotice('error', response.data.message || loginAwpStyleBuilder.i18n.reset_error);
                    $saveButton.prop('disabled', false).text(originalText);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error resetting styles:', error);
                showNotice('error', loginAwpStyleBuilder.i18n.reset_error);
                $saveButton.prop('disabled', false).text(originalText);
            }
        });
    }
    
    /**
     * Display an admin notice
     * 
     * @param {string} type Notice type (success, error, warning, info)
     * @param {string} message Message to display
     */
    function showNotice(type, message) {
        const $notice = $('<div class="notice notice-' + type + ' is-dismissible"><p>' + message + '</p></div>')
            .hide()
            .insertAfter('.wp-header-end');
        
        $notice.slideDown(300);
        
        // Auto dismiss after 5 seconds
        setTimeout(function() {
            $notice.slideUp(300, function() {
                $notice.remove();
            });
        }, 5000);
    }

})(jQuery);