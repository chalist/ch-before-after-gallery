/**
 * CH Before After Scripts
 * Supports both individual sliders and galleries with tabs
 */
(function ($) {
    'use strict';

    $(document).ready(function () {
        // Initialize twentytwenty effect on all image containers
        $(".ch-before-after-images-container").each(function () {
            // Only apply twentytwenty if container has two images
            if ($(this).find('img').length === 2) {
                var container = $(this);
                var gallery = container.closest('.ch-before-after-container, .ch-gallery');

                // Get custom labels from data attributes if they exist
                var beforeText = gallery.data('before-text') || 'Before';
                var afterText = gallery.data('after-text') || 'After';

                container.twentytwenty({
                    default_offset_pct: 0.5,
                    orientation: 'horizontal',
                    before_label: beforeText,
                    after_label: afterText
                });
            }
        });

        // Tab functionality for tabbed galleries
        $('.ch-before-after-tab').on('click', function () {
            var $this = $(this);
            var tabId = $this.data('tab');
            var $gallery = $this.closest('.ch-before-after-tabbed-gallery');

            // Remove active class from all tabs and tab contents
            $gallery.find('.ch-before-after-tab').removeClass('active');
            $gallery.find('.ch-before-after-tab-content').removeClass('active');

            // Add active class to clicked tab and its content
            $this.addClass('active');
            $('#' + tabId).addClass('active');

            // Re-initialize twentytwenty for the newly shown tab
            // This is necessary because hidden slides might not initialize properly
            $('#' + tabId).find('.ch-before-after-images-container').each(function () {
                if ($(this).find('img').length === 2) {
                    // If twentytwenty was already initialized, destroy it first
                    if ($(this).hasClass('twentytwenty-container')) {
                        $(this).twentytwenty('destroy');
                    }
                    $(this).twentytwenty();
                }
            });
        });

        // Prevent images from being clickable
        $('.ch-before-after-image img, .ch-before-after-slide-icon img, .ch-before-after-tab-icon img').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        });

        $('.ch-before-after-slides').each(function () {
            const container = $(this);
            const slide = container.find('.ch-before-after-slide');
            const beforeImage = slide.find('.ch-before-after-before-image');
            const afterImage = slide.find('.ch-before-after-after-image');
            const sliderHandle = slide.find('.ch-before-after-slider-handle');

            // Set initial position
            afterImage.css({ width: '50%' });
            sliderHandle.css({ left: '50%' });

            // Handle mouse move
            slide.on('mousemove', function (e) {
                if (!slide.hasClass('slide-active')) {
                    return;
                }

                const slideWidth = slide.width();
                const offsetX = e.pageX - slide.offset().left;
                const percentage = Math.max(0, Math.min(100, offsetX / slideWidth * 100));

                afterImage.css({ width: percentage + '%' });
                sliderHandle.css({ left: percentage + '%' });
            });

            // Handle mouse events
            slide.on('mousedown', function () {
                slide.addClass('slide-active');
            });

            $(document).on('mouseup', function () {
                slide.removeClass('slide-active');
            });

            // Handle touch events
            slide.on('touchstart', function () {
                slide.addClass('slide-active');
            });

            slide.on('touchmove', function (e) {
                if (!slide.hasClass('slide-active')) {
                    return;
                }

                const touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
                const slideWidth = slide.width();
                const offsetX = touch.pageX - slide.offset().left;
                const percentage = Math.max(0, Math.min(100, offsetX / slideWidth * 100));

                afterImage.css({ width: percentage + '%' });
                sliderHandle.css({ left: percentage + '%' });

                e.preventDefault();
            });

            $(document).on('touchend', function () {
                slide.removeClass('slide-active');
            });
        });
    });

})(jQuery); 