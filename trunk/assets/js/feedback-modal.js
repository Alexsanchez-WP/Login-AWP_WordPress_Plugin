/**
 * Feedback Modal JavaScript for Login AWP
 *
 * Handles the deactivation feedback modal functionality
 */
(function($) {
    'use strict';

    // Initialize once DOM is fully loaded
    $(function() {
        const pluginSlug = loginAwpFeedback.plugin_slug;
        const deactivateLink = $('tr[data-slug="' + pluginSlug.split('/')[0] + '"] .deactivate a');
        
        // If deactivation link exists, intercept it
        if (deactivateLink.length) {
            const originalLink = deactivateLink.attr('href');

            // Replace the default deactivation action with our modal trigger
            deactivateLink.on('click', function(e) {
                e.preventDefault();
                showModal(originalLink);
            });
        }

        /**
         * Show the feedback modal
         */
        function showModal(deactivationUrl) {
            $('#login-awp-feedback-modal').show();
            setupModalEvents(deactivationUrl);
        }

        /**
         * Set up event listeners for the modal
         */
        function setupModalEvents(deactivationUrl) {
            // Process form submission
            $('#login-awp-feedback-form').on('submit', function(e) {
                e.preventDefault();
                submitFeedback(deactivationUrl);
            });

            // Skip feedback and deactivate directly
            $('#login-awp-skip-feedback').on('click', function() {
                window.location.href = deactivationUrl;
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
        function submitFeedback(deactivationUrl) {
            const form = $('#login-awp-feedback-form');
            const reason = form.find('input[name="reason"]:checked').val();
            
            // If no reason selected, just deactivate
            if (!reason) {
                window.location.href = deactivationUrl;
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
                reason: reason
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
                // After submitting feedback, deactivate the plugin
                window.location.href = deactivationUrl;
            }).fail(function() {
                // If submission fails, still deactivate
                window.location.href = deactivationUrl;
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