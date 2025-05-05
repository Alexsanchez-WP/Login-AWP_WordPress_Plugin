/**
 * Feedback Modal JavaScript for Login AWP
 *
 * Handles the deactivation feedback modal functionality
 */
(function($) {
    'use strict';

    // Initialize once DOM is fully loaded
    $(function() {
        // Make sure we have the proper AJAX URL from the localized script
        const ajaxUrl = loginAwpFeedback.ajax_url || window.ajaxurl || '/wp-admin/admin-ajax.php';
        
        const pluginSlug = loginAwpFeedback.plugin_slug;
        const baseSlug = pluginSlug.split('/')[0];
        
        // Set up for deactivation link
        setupDeactivationFeedback();
        
        /**
         * Set up feedback for deactivation
         */
        function setupDeactivationFeedback() {
            const pluginRow = $('tr[data-slug="' + baseSlug + '"]');
            const deactivateLink = pluginRow.find('.deactivate a');
            
            if (deactivateLink.length) {
                const originalDeactivateLink = deactivateLink.attr('href');
                
                // Replace the default deactivation action with our modal trigger
                deactivateLink.on('click', function(e) {
                    e.preventDefault();
                    showModal(originalDeactivateLink, 'deactivate');
                });
            }
        }

        /**
         * Show the feedback modal
         * @param {string} actionUrl - The URL to redirect to after submission
         * @param {string} actionType - 'deactivate'
         */
        function showModal(actionUrl, actionType) {
            $('#login-awp-feedback-modal').show();
            
            // Update modal title and text
            $('#login-awp-feedback-title').text(loginAwpFeedback.translations.heading || 'Quick Feedback');
            $('#login-awp-feedback-intro').text(loginAwpFeedback.translations.intro || 'If you have a moment, please let us know why you are deactivating Login AWP:');
            $('#login-awp-skip-feedback').text(loginAwpFeedback.translations.skip || 'Skip & Deactivate');
            $('#login-awp-submit-feedback').text(loginAwpFeedback.translations.submit || 'Submit & Deactivate');
            
            // Store the action URL for form submission
            $('#login-awp-feedback-form').data('actionUrl', actionUrl);
            
            setupModalEvents();
        }

        /**
         * Set up event listeners for the modal
         */
        function setupModalEvents() {
            // Remove existing event handlers to prevent duplicates
            $('#login-awp-feedback-form').off('submit');
            $('#login-awp-skip-feedback').off('click');
            $('#login-awp-cancel-feedback').off('click');
            $('input[name="reason"]').off('change');
            
            const form = $('#login-awp-feedback-form');
            const actionUrl = form.data('actionUrl');
            
            // Process form submission
            form.on('submit', function(e) {
                e.preventDefault();
                submitFeedback();
            });

            // Skip feedback and proceed directly
            $('#login-awp-skip-feedback').on('click', function() {
                window.location.href = actionUrl;
            });

            // Close modal when clicking cancel
            $('#login-awp-cancel-feedback').on('click', function() {
                closeModal();
            });

            // Show/hide additional fields based on selected reason
            $('input[name="reason"]').on('change', function() {
                // Hide all detail fields first
                $('.login-awp-feedback-info').addClass('hidden');
                
                // Show the specific detail field if needed
                const reason = $(this).val();
                switch(reason) {
                    case 'found_better_plugin':
                        $('input[name="better_plugin"]').removeClass('hidden');
                        break;
                    case 'not_working':
                        $('textarea[name="not_working_details"]').removeClass('hidden');
                        break;
                    case 'other':
                        $('textarea[name="other_details"]').removeClass('hidden');
                        break;
                }
            });

            // Close on clicking outside the modal
            $(window).on('click', function(e) {
                if ($(e.target).is('#login-awp-feedback-modal')) {
                    closeModal();
                }
            });

            // Close on ESC key
            $(document).on('keydown', function(e) {
                if (e.keyCode === 27) { // ESC key
                    closeModal();
                }
            });
        }

        /**
         * Submit the feedback form via AJAX
         */
        function submitFeedback() {
            const form = $('#login-awp-feedback-form');
            const actionUrl = form.data('actionUrl');
            let reason = form.find('input[name="reason"]:checked').val();
            const includeEmail = form.find('input[name="include_email"]').is(':checked');
            
            // If no reason selected but email is included, set default reason to "no context"
            if (!reason && includeEmail) {
                reason = 'no context';
            } else if (!reason) {
                // If no reason selected and no email, just proceed with action
                window.location.href = actionUrl;
                return;
            }

            // Collect additional details if provided
            let details = '';
            if (reason === 'found_better_plugin') {
                details = form.find('input[name="better_plugin"]').val();
            } else if (reason === 'not_working') {
                details = form.find('textarea[name="not_working_details"]').val();
            } else if (reason === 'other') {
                details = form.find('textarea[name="other_details"]').val();
            }

            // Prepare the data to send
            const data = {
                action: 'login_awp_submit_feedback',
                nonce: loginAwpFeedback.nonce,
                reason: reason,
                action_type: 'deactivate'
            };

            // Add additional data if provided
            if (details) {
                if (reason === 'found_better_plugin') {
                    data.better_plugin = details;
                } else if (reason === 'not_working') {
                    data.not_working_details = details;
                } else if (reason === 'other') {
                    data.other_details = details;
                }
            }

            // Include email if checkbox is checked
            if (includeEmail) {
                data.include_email = '1';
            }

            // Show submitting state
            const submitButton = $('#login-awp-submit-feedback');
            const originalText = submitButton.text();
            submitButton.text(loginAwpFeedback.translations.submitting || 'Submitting...');
            submitButton.prop('disabled', true);

            // Use a try-catch block to handle AJAX errors
            try {
                // Send the data to the correct AJAX URL
                $.post(ajaxUrl, data)
                    .done(function(response) {
                        // After submitting feedback, proceed with action
                        window.location.href = actionUrl;
                    })
                    .fail(function(xhr, textStatus, errorThrown) {
                        // Log error details for debugging but still proceed with action
                        console.error('Feedback submission failed:', textStatus, errorThrown);
                        // Still proceed with the requested action even if feedback fails
                        window.location.href = actionUrl;
                    });
            } catch(e) {
                // If any error occurs, still proceed with the action
                console.error('Error during feedback submission:', e);
                window.location.href = actionUrl;
            }
        }

        /**
         * Close and reset the feedback modal
         */
        function closeModal() {
            $('#login-awp-feedback-modal').hide();
            $('#login-awp-feedback-form')[0].reset();
            $('.login-awp-feedback-info').addClass('hidden');
        }
    });
})(jQuery);