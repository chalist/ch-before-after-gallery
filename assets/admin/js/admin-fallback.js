/**
 * CH Before After Admin Fallback Scripts
 * Used when the standard media uploader doesn't work due to CSP restrictions
 */
jQuery(document).ready(function ($) {
    /**
     * Initialize fallback uploader
     */
    function initFallbackUploader() {
        // Hide default WP Media uploader related buttons first
        $('.ch-before-after-metabox button').hide();

        // Show a simple file input instead
        $('.ch-before-after-metabox').each(function () {
            var $metabox = $(this);
            var $uploadBtns = $metabox.find('button');
            var html = '<div class="fallback-uploader">';
            html += '<input type="file" class="fallback-file-input" accept="image/*">';
            html += '<p class="description">Upload an image file</p>';
            html += '</div>';

            $metabox.append(html);
        });

        // Handle file input change
        $('.ch-before-after-metabox').on('change', '.fallback-file-input', function () {
            var $input = $(this);
            var $container = $input.closest('.ch-before-after-metabox');
            var $button = $container.find('button').first();
            var isIcon = $button.hasClass('ch-before-after-upload-icon');
            var isImage1 = $button.hasClass('ch-before-after-upload-image');
            var isImage2 = $button.hasClass('ch-before-after-upload-image-2');
            var fieldName = '';

            // Determine the field name
            if (isIcon) {
                fieldName = 'ch_before_after_icon_url';
            } else if (isImage1) {
                fieldName = 'ch_before_after_image_url';
            } else if (isImage2) {
                fieldName = 'ch_before_after_image_2_url';
            } else {
                return; // Unknown button type
            }

            // Get the file
            var file = $input[0].files[0];
            if (!file) return;

            // Create a simple preview
            var reader = new FileReader();
            reader.onload = function (e) {
                var imgUrl = e.target.result;
                var $preview = $container.find('.ch-before-after-image-preview');
                $preview.html('<img src="' + imgUrl + '" alt="Preview">');

                // Set the URL in a hidden field for form submission
                var idFieldName = '';
                var removeButtonClass = '';

                if (isIcon) {
                    idFieldName = 'ch_before_after_icon_id';
                    removeButtonClass = 'ch-before-after-remove-icon';
                } else if (isImage1) {
                    idFieldName = 'ch_before_after_image_id';
                    removeButtonClass = 'ch-before-after-remove-image';
                } else if (isImage2) {
                    idFieldName = 'ch_before_after_image_2_id';
                    removeButtonClass = 'ch-before-after-remove-image-2';
                }

                // Create hidden fields if they don't exist
                if ($container.find('input[name="' + fieldName + '"]').length === 0) {
                    $container.append('<input type="hidden" name="' + fieldName + '">');
                }

                // Set the value
                $container.find('input[name="' + fieldName + '"]').val(imgUrl);
                $container.find('#' + idFieldName).val('0'); // Set to 0 to indicate direct URL

                // Add remove button
                if ($container.find('.' + removeButtonClass).length === 0) {
                    $container.append('<button type="button" class="button ' + removeButtonClass + '">Remove</button>');
                }
            };
            reader.readAsDataURL(file);
        });

        // Handle remove button click
        $(document).on('click', '.ch-before-after-remove-icon, .ch-before-after-remove-image, .ch-before-after-remove-image-2', function (e) {
            e.preventDefault();

            var $button = $(this);
            var $container = $button.closest('.ch-before-after-metabox');
            var $preview = $container.find('.ch-before-after-image-preview');
            var isIcon = $button.hasClass('ch-before-after-remove-icon');
            var isImage1 = $button.hasClass('ch-before-after-remove-image');
            var isImage2 = $button.hasClass('ch-before-after-remove-image-2');

            var idFieldName = '';
            var urlFieldName = '';

            if (isIcon) {
                idFieldName = 'ch_before_after_icon_id';
                urlFieldName = 'ch_before_after_icon_url';
            } else if (isImage1) {
                idFieldName = 'ch_before_after_image_id';
                urlFieldName = 'ch_before_after_image_url';
            } else if (isImage2) {
                idFieldName = 'ch_before_after_image_2_id';
                urlFieldName = 'ch_before_after_image_2_url';
            }

            // Clear preview
            $preview.empty();

            // Clear input values
            $container.find('#' + idFieldName).val('');
            $container.find('input[name="' + urlFieldName + '"]').val('');
            $container.find('.fallback-file-input').val('');

            // Remove the button
            $button.remove();
        });
    }

    // Initialize on error (triggered from admin.js when CSP blocks standard uploader)
    $(document).on('error', function () {
        initFallbackUploader();
    });
}); 