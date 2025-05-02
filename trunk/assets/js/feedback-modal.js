/**
 * Feedback Modal JavaScript for Login AWP
 *
 * Handles the deactivation and deletion feedback modal functionality
 */
(function($) {
    'use strict';

    // Initialize once DOM is fully loaded
    $(function() {
        const pluginSlug = loginAwpFeedback.plugin_slug;
        const baseSlug = pluginSlug.split('/')[0];
        
        // Set up for deactivation link
        setupDeactivationFeedback();
        
        // Set up delegation for delete link which might be added dynamically
        setupDeletionFeedback();
        
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
         * Set up feedback for deletion using event delegation
         * This is necessary because delete links are often added dynamically after plugin deactivation
         */
        function setupDeletionFeedback() {
            // Use event delegation to capture any delete links that may be added dynamically
            $(document).on('click', 'tr[data-slug="' + baseSlug + '"] .delete a, a[href*="action=delete-selected"][href*="' + baseSlug + '"]', function(e) {
                e.preventDefault();
                const deleteUrl = $(this).attr('href');
                showModal(deleteUrl, 'delete');
            });
            
            // Also capture deletion via the bulk actions dropdown
            $('#bulk-action-form').on('submit', function(e) {
                const action = $('#bulk-action-selector-top, #bulk-action-selector-bottom').val();
                
                if (action === 'delete') {
                    // Check if our plugin is selected
                    const isPluginSelected = $('input[name="checked[]"][value="' + pluginSlug + '"]:checked').length > 0;
                    
                    if (isPluginSelected) {
                        e.preventDefault();
                        showModal($(this).attr('action') + '?' + $(this).serialize(), 'delete');
                    }
                }
            });
        }

        /**
         * Show the feedback modal
         * @param {string} actionUrl - The URL to redirect to after submission
         * @param {string} actionType - Either 'deactivate' or 'delete'
         */
        function showModal(actionUrl, actionType) {
            $('#login-awp-feedback-modal').show();
            
            // Update modal title based on action type
            if (actionType === 'delete') {
                $('#login-awp-feedback-title').text(loginAwpFeedback.translations.deletion_heading || 'Quick Feedback Before Deleting');
                $('#login-awp-feedback-intro').text(loginAwpFeedback.translations.deletion_intro || 'If you have a moment, please let us know why you are deleting Login AWP:');
                $('#login-awp-skip-feedback').text(loginAwpFeedback.translations.skip_delete || 'Skip & Delete');
                $('#login-awp-submit-feedback').text(loginAwpFeedback.translations.submit_delete || 'Submit & Delete');
            } else {
                $('#login-awp-feedback-title').text(loginAwpFeedback.translations.heading || 'Quick Feedback');
                $('#login-awp-feedback-intro').text(loginAwpFeedback.translations.intro || 'If you have a moment, please let us know why you are deactivating Login AWP:');
                $('#login-awp-skip-feedback').text(loginAwpFeedback.translations.skip || 'Skip & Deactivate');
                $('#login-awp-submit-feedback').text(loginAwpFeedback.translations.submit || 'Submit & Deactivate');
            }
            
            // Store the action type and URL for form submission
            $('#login-awp-feedback-form').data('actionType', actionType);
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
            const reason = form.find('input[name="reason"]:checked').val();
            const actionType = form.data('actionType') || 'deactivate';
            
            // If no reason selected, just proceed with action
            if (!reason) {
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
                action_type: actionType
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
            if (form.find('input[name="include_email"]').is(':checked')) {
                data.include_email = '1';
            }

            // Show submitting state
            const submitButton = $('#login-awp-submit-feedback');
            const originalText = submitButton.text();
            submitButton.text(loginAwpFeedback.translations.submitting || 'Submitting...');
            submitButton.prop('disabled', true);

            // Send the data
            $.post(loginAwpFeedback.ajax_url, data, function() {
                // After submitting feedback, proceed with action
                window.location.href = actionUrl;
            }).fail(function() {
                // If submission fails, still proceed
                window.location.href = actionUrl;
            });
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