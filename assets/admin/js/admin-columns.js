/**
 * Admin Columns Scripts for CH Before After
 */
jQuery(document).ready(function ($) {
    // Handle shortcode copy button click
    $('.ch-copy-shortcode').on('click', function (e) {
        e.preventDefault();

        // Get the shortcode text
        var shortcodeText = $(this).data('clipboard-text');

        // Create a temporary input element
        var tempInput = $('<input>');
        $('body').append(tempInput);
        tempInput.val(shortcodeText).select();

        try {
            // Copy the text to clipboard
            document.execCommand('copy');

            // Show success message
            var successMessage = $('<div class="ch-before-after-copied">' +
                '<span class="dashicons dashicons-yes"></span> ' +
                'Shortcode copied to clipboard!</div>');
            $('body').append(successMessage);

            // Remove the message after animation completes
            setTimeout(function () {
                successMessage.remove();
            }, 2000);
        } catch (err) {
            console.error('Unable to copy shortcode:', err);
        }

        // Remove the temporary input
        tempInput.remove();
    });

    // Alternative method using Clipboard API if available
    if (navigator.clipboard && window.isSecureContext) {
        $('.ch-copy-shortcode').on('click', function (e) {
            e.preventDefault();

            var shortcodeText = $(this).data('clipboard-text');

            navigator.clipboard.writeText(shortcodeText)
                .then(function () {
                    // Show success message
                    var successMessage = $('<div class="ch-before-after-copied">' +
                        '<span class="dashicons dashicons-yes"></span> ' +
                        'Shortcode copied to clipboard!</div>');
                    $('body').append(successMessage);

                    // Remove the message after animation completes
                    setTimeout(function () {
                        successMessage.remove();
                    }, 2000);
                })
                .catch(function (err) {
                    console.error('Unable to copy shortcode:', err);
                });
        });
    }
}); 