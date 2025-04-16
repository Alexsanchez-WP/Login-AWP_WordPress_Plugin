/**
 * Theme Preview Script
 *
 * Handles theme preview functionality in the admin area
 */

(function($) {
    'use strict';

    // Store the current selected theme for preview
    let currentPreviewTheme = '';

    // Initialize when document is ready
    $(document).ready(function() {
        initThemeSelector();
    });

    /**
     * Initialize the theme selector functionality
     */
    function initThemeSelector() {
        // Preview theme button click
        $('.preview-theme').on('click', function(e) {
            e.preventDefault();
            const themeKey = $(this).data('theme');
            previewTheme(themeKey);
        });

        // Select/apply theme button click
        $('.select-theme').on('click', function(e) {
            e.preventDefault();
            const themeKey = $(this).data('theme');
            
            // Check if there are custom styles before applying theme
            if (hasCustomStyles()) {
                showResetConfirmModal(themeKey);
            } else {
                applyTheme(themeKey);
            }
        });

        // Close preview button click
        $('.close-preview').on('click', function(e) {
            e.preventDefault();
            closePreview();
        });

        // Apply theme from preview modal
        $('.apply-theme').on('click', function(e) {
            e.preventDefault();
            if (currentPreviewTheme) {
                if (hasCustomStyles()) {
                    showResetConfirmModal(currentPreviewTheme);
                    closePreview();
                } else {
                    applyTheme(currentPreviewTheme);
                    closePreview();
                }
            }
        });
        
        // Reset confirm modal events
        $('.cancel-reset').on('click', function(e) {
            e.preventDefault();
            closeResetConfirmModal();
        });
        
        $('.confirm-reset').on('click', function(e) {
            e.preventDefault();
            const themeKey = $(this).data('theme');
            resetCustomStylesAndApplyTheme(themeKey);
        });
    }
    
    /**
     * Check if there are custom styles saved
     * 
     * @return {boolean} True if custom styles exist
     */
    function hasCustomStyles() {
        // Primera verificación: si el tema actual es 'custom'
        if (loginAwpThemes.selected === 'custom') {
            return true;
        }
        
        // Segunda verificación: si hay estilos personalizados guardados en la base de datos
        if (loginAwpThemes.hasCustomStyles) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Show the reset confirmation modal
     * 
     * @param {string} themeKey The theme to apply after reset
     */
    function showResetConfirmModal(themeKey) {
        // Actualizar el data attribute para el tema seleccionado
        $('.confirm-reset').attr('data-theme', themeKey);
        
        // Forzar la visualización del modal con estilo inline
        $('#login-awp-reset-confirm-modal')
            .css('display', 'flex')
            .hide() // Ocultar para poder hacer fadeIn
            .fadeIn(300);
        
        // Agregar un evento de escape para cerrar el modal con la tecla Esc
        $(document).on('keydown.resetModal', function(e) {
            if (e.key === 'Escape') {
                closeResetConfirmModal();
            }
        });
    }
    
    /**
     * Close the reset confirmation modal
     */
    function closeResetConfirmModal() {
        $('#login-awp-reset-confirm-modal').fadeOut(200);
        
        // Eliminar el evento de teclado
        $(document).off('keydown.resetModal');
    }
    
    /**
     * Reset custom styles and then apply the selected theme
     * 
     * @param {string} themeKey The theme identifier to apply after reset
     */
    function resetCustomStylesAndApplyTheme(themeKey) {
        // Mostrar un indicador de carga
        const $confirmButton = $('.confirm-reset');
        const originalText = $confirmButton.text();
        $confirmButton.prop('disabled', true).text('Resetting...');
        
        // Usar el nonce del theme para resetear los estilos
        $.ajax({
            url: loginAwpThemes.ajaxurl,
            type: 'POST',
            data: {
                action: 'login_awp_reset_custom_styles',
                nonce: loginAwpThemes.nonce 
            },
            success: function(response) {
                if (response.success) {
                    // Actualizar las variables de estado para indicar que ya no hay estilos personalizados
                    loginAwpThemes.hasCustomStyles = false;
                    loginAwpThemes.selected = 'default'; // Resetear a default antes de aplicar el nuevo tema
                    
                    console.log('Reset successful, applying theme:', themeKey);
                    
                    // Hacer una pequeña pausa para asegurar que el servidor ha procesado el reinicio
                    setTimeout(function() {
                        // After successful reset, apply the theme
                        applyTheme(themeKey);
                        closeResetConfirmModal();
                        
                        // Recargar la página para asegurarnos de que todo se actualiza correctamente
                        // pero solo después de un breve retraso para que el usuario pueda ver la notificación
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    }, 300);
                } else {
                    showNotice('error', 'Error resetting custom styles');
                    closeResetConfirmModal();
                    
                    // Restaurar el botón
                    $confirmButton.prop('disabled', false).text(originalText);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error details:', error, xhr.responseText);
                showNotice('error', 'Network error while resetting custom styles');
                closeResetConfirmModal();
                
                // Restaurar el botón
                $confirmButton.prop('disabled', false).text(originalText);
            }
        });
    }

    /**
     * Preview a theme
     * 
     * @param {string} themeKey The theme identifier
     */
    function previewTheme(themeKey) {
        currentPreviewTheme = themeKey;
        
        // Show loading state
        $('#theme-preview-frame').attr('src', 'about:blank');
        $('.login-awp-theme-preview-modal').fadeIn(300);
        
        // Get theme data via AJAX
        $.ajax({
            url: loginAwpThemes.ajaxurl,
            type: 'POST',
            data: {
                action: 'login_awp_preview_theme',
                nonce: loginAwpThemes.nonce,
                theme: themeKey
            },
            success: function(response) {
                if (response.success && response.data.config) {
                    // Generate CSS from theme config
                    const css = generateThemeCSS(response.data.config);
                    
                    // Load the preview iframe with the custom CSS
                    // Fixed: Use the correct preview_nonce for this action
                    const previewUrl = loginAwpThemes.ajaxurl + '?action=login_awp_get_preview&_wpnonce=' + 
                                       loginAwpThemes.preview_nonce + '&styles=' + encodeURIComponent(css);
                    
                    $('#theme-preview-frame').attr('src', previewUrl);
                } else {
                    alert(response.data.message || 'Error loading theme preview');
                    closePreview();
                }
            },
            error: function() {
                alert('Network error while loading theme preview');
                closePreview();
            }
        });
    }

    /**
     * Apply a theme
     * 
     * @param {string} themeKey The theme identifier
     */
    function applyTheme(themeKey) {
        $.ajax({
            url: loginAwpThemes.ajaxurl,
            type: 'POST',
            data: {
                action: 'login_awp_save_theme',
                nonce: loginAwpThemes.nonce,
                theme: themeKey
            },
            success: function(response) {
                if (response.success) {
                    // Update UI to reflect the selected theme
                    $('.login-awp-theme-card').removeClass('selected');
                    $('.login-awp-theme-card[data-theme="' + themeKey + '"]').addClass('selected');
                    
                    // Update buttons
                    $('.theme-active').remove();
                    $('.select-theme').show();
                    
                    // Add "Active Theme" text to the selected theme and hide its Apply button
                    const $selectedCard = $('.login-awp-theme-card[data-theme="' + themeKey + '"]');
                    $selectedCard.find('.select-theme').hide();
                    $selectedCard.find('.login-awp-theme-actions').append('<span class="theme-active">' + 
                                                                         'Active Theme</span>');
                    
                    // Show success message
                    showNotice('success', response.data.message || 'Theme applied successfully');
                    
                    // Update the selected theme in the JS object for future reference
                    loginAwpThemes.selected = themeKey;
                } else {
                    showNotice('error', response.data.message || 'Error applying theme');
                }
            },
            error: function() {
                showNotice('error', 'Network error while applying theme');
            }
        });
    }

    /**
     * Close the preview modal
     */
    function closePreview() {
        $('.login-awp-theme-preview-modal').fadeOut(200);
        currentPreviewTheme = '';
    }

    /**
     * Generate CSS from theme configuration
     * 
     * @param {Object} config Theme configuration object
     * @return {string} Generated CSS
     */
    function generateThemeCSS(config) {
        // Get current logo and background images from localized data
        const currentLogo = loginAwpThemes.currentImages?.logo || '';
        const currentBackground = loginAwpThemes.currentImages?.background || '';
        
        let css = `
body.login {
    background-color: ${config.colors.bg};
    font-family: ${config.typography.font_family};
    font-size: ${config.typography.font_size};
    background-image: url(${currentBackground});
    background-size: cover;
    background-position: center;
}

body.login div#login {
    background-color: ${config.colors.container_bg};
    border-radius: ${config.effects.border_radius};
    box-shadow: ${config.effects.box_shadow};
}

body.login div#login h1 a {
    background-image: url(${currentLogo});
    background-size: 100%;
}

body.login div#login form#loginform p,
body.login div#login form#loginform p label {
    color: ${config.colors.text};
}

body.login div#login form#loginform p.submit input#wp-submit {
    background-color: ${config.colors.button_bg};
}

body.login div#login form#loginform p.submit input#wp-submit:hover {
    background-color: ${config.colors.button_hover};
}

body.login div#login p#nav a, 
body.login div#login p#backtoblog a {
    color: ${config.colors.links};
}`;

        return css;
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
