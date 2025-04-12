/**
 * CH Before After Admin Scripts
 */
jQuery(document).ready(function ($) {
    /**
     * Media uploader
     */
    function initMediaUploader() {
        var mediaUploader;

        // Prevent the default click action for buttons
        $('button.button').on('click', function (e) {
            e.preventDefault();
        });

        /**
         * Initialize and open the media uploader for selecting icons
         */
        function openIconSelector(button) {
            // If the uploader object has already been created, reopen the dialog
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            // Create the media uploader
            mediaUploader = wp.media({
                title: 'Select or Upload Icon',
                button: {
                    text: 'Use this icon'
                },
                multiple: false
            });

            // When an icon is selected, run a callback
            mediaUploader.on('select', function () {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                var container = button.closest('.ch-before-after-metabox');
                var preview = container.find('.ch-before-after-image-preview');
                var idField = container.find('#ch_before_after_icon_id');
                var imgUrl = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

                // Update the preview image
                preview.html('<img src="' + imgUrl + '" alt="Icon Preview">');

                // Update the hidden field value
                idField.val(attachment.id);

                // Add a remove button if it doesn't exist
                if (container.find('.ch-before-after-remove-icon').length === 0) {
                    $('<button type="button" class="button ch-before-after-remove-icon">Remove Icon</button>').insertAfter(button);
                }
            });

            // Open the uploader dialog
            mediaUploader.open();
        }

        /**
         * Initialize and open the media uploader for selecting slide images
         */
        function openImageSelector(button) {
            // If the uploader object has already been created, reopen the dialog
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            // Create the media uploader
            mediaUploader = wp.media({
                title: 'Select or Upload Image',
                button: {
                    text: 'Use this image'
                },
                multiple: false
            });

            // When an image is selected, run a callback
            mediaUploader.on('select', function () {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                var container = button.closest('.ch-before-after-metabox');
                var preview = container.find('.ch-before-after-image-preview');
                var idField = container.find('#ch_before_after_image_id');
                var imgUrl = attachment.sizes && attachment.sizes.large ? attachment.sizes.large.url : attachment.url;

                // Update the preview image
                preview.html('<img src="' + imgUrl + '" alt="Image Preview">');

                // Update the hidden field value
                idField.val(attachment.id);

                // Add a remove button if it doesn't exist
                if (container.find('.ch-before-after-remove-image').length === 0) {
                    $('<button type="button" class="button ch-before-after-remove-image">Remove Image</button>').insertAfter(button);
                }
            });

            // Open the uploader dialog
            mediaUploader.open();
        }

        /**
         * Initialize and open the media uploader for selecting second slide images
         */
        function openImage2Selector(button) {
            // If the uploader object has already been created, reopen the dialog
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            // Create the media uploader
            mediaUploader = wp.media({
                title: 'Select or Upload Second Image',
                button: {
                    text: 'Use this image'
                },
                multiple: false
            });

            // When an image is selected, run a callback
            mediaUploader.on('select', function () {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                var container = button.closest('.ch-before-after-metabox');
                var preview = container.find('.ch-before-after-image-preview');
                var idField = container.find('#ch_before_after_image_2_id');
                var imgUrl = attachment.sizes && attachment.sizes.large ? attachment.sizes.large.url : attachment.url;

                // Update the preview image
                preview.html('<img src="' + imgUrl + '" alt="Image 2 Preview">');

                // Update the hidden field value
                idField.val(attachment.id);

                // Add a remove button if it doesn't exist
                if (container.find('.ch-before-after-remove-image-2').length === 0) {
                    $('<button type="button" class="button ch-before-after-remove-image-2">Remove Image 2</button>').insertAfter(button);
                }
            });

            // Open the uploader dialog
            mediaUploader.open();
        }

        /**
         * Remove the selected icon and reset the fields
         */
        function removeIcon(button) {
            // Get the fields to reset
            var container = button.closest('.ch-before-after-metabox');
            var preview = container.find('.ch-before-after-image-preview');
            var idField = container.find('#ch_before_after_icon_id');

            // Clear the preview and field value
            preview.empty();
            idField.val('');

            // Remove the remove button
            button.remove();
        }

        /**
         * Remove the selected image and reset the fields
         */
        function removeImage(button) {
            // Get the fields to reset
            var container = button.closest('.ch-before-after-metabox');
            var preview = container.find('.ch-before-after-image-preview');
            var idField = container.find('#ch_before_after_image_id');

            // Clear the preview and field value
            preview.empty();
            idField.val('');

            // Remove the remove button
            button.remove();
        }

        /**
         * Remove the selected second image and reset the fields
         */
        function removeImage2(button) {
            // Get the fields to reset
            var container = button.closest('.ch-before-after-metabox');
            var preview = container.find('.ch-before-after-image-preview');
            var idField = container.find('#ch_before_after_image_2_id');

            // Clear the preview and field value
            preview.empty();
            idField.val('');

            // Remove the remove button
            button.remove();
        }

        // Event delegation for the upload icon button
        $(document).on('click', '.ch-before-after-upload-icon', function (e) {
            e.preventDefault();
            openIconSelector($(this));
        });

        // Event delegation for the upload image button
        $(document).on('click', '.ch-before-after-upload-image', function (e) {
            e.preventDefault();
            openImageSelector($(this));
        });

        // Event delegation for the upload second image button
        $(document).on('click', '.ch-before-after-upload-image-2', function (e) {
            e.preventDefault();
            openImage2Selector($(this));
        });

        // Event delegation for the remove icon button
        $(document).on('click', '.ch-before-after-remove-icon', function (e) {
            e.preventDefault();
            removeIcon($(this));
        });

        // Event delegation for the remove image button
        $(document).on('click', '.ch-before-after-remove-image', function (e) {
            e.preventDefault();
            removeImage($(this));
        });

        // Event delegation for the remove second image button
        $(document).on('click', '.ch-before-after-remove-image-2', function (e) {
            e.preventDefault();
            removeImage2($(this));
        });
    }

    // Initialize media uploader when DOM is ready
    initMediaUploader();
}); 