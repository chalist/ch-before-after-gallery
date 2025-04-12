<?php
/**
 * Meta boxes for slider
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add meta boxes to the slide post type
 */
function ch_before_after_add_meta_boxes()
{
    add_meta_box(
        'ch_before_after_icon',
        __('Slide Icon', 'ch-before-after'),
        'ch_before_after_icon_meta_box_callback',
        'ch_slide',
        'normal',
        'high'
    );

    add_meta_box(
        'ch_before_after_image',
        __('Slide Image 1', 'ch-before-after'),
        'ch_before_after_image_meta_box_callback',
        'ch_slide',
        'normal',
        'high'
    );

    add_meta_box(
        'ch_before_after_image_2',
        __('Slide Image 2', 'ch-before-after'),
        'ch_before_after_image_2_meta_box_callback',
        'ch_slide',
        'normal',
        'high'
    );

    // Add meta box for selecting slides in a gallery
    add_meta_box(
        'ch_gallery_slides',
        __('Gallery Slides', 'ch-before-after'),
        'ch_gallery_slides_meta_box_callback',
        'ch_gallery',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'ch_before_after_add_meta_boxes');

/**
 * Render the icon meta box
 */
function ch_before_after_icon_meta_box_callback($post)
{
    // Add nonce for security
    wp_nonce_field('ch_before_after_icon_meta_box', 'ch_before_after_icon_meta_box_nonce');

    // Get the saved icon
    $icon_id = get_post_meta($post->ID, '_ch_before_after_icon_id', true);
    $icon_url = '';

    if ($icon_id) {
        $icon_url = wp_get_attachment_url($icon_id);
    }

    ?>
    <p><?php _e('Upload a small icon image to display with the slide title.', 'ch-before-after'); ?></p>
    <div class="ch-before-after-metabox">
        <div class="ch-before-after-image-preview">
            <?php if ($icon_url): ?>
                <img src="<?php echo esc_url($icon_url); ?>" alt="" style="max-width: 100px; height: auto;">
            <?php endif; ?>
        </div>
        <input type="hidden" id="ch_before_after_icon_id" name="ch_before_after_icon_id"
            value="<?php echo esc_attr($icon_id); ?>">
        <button type="button"
            class="button ch-before-after-upload-icon"><?php _e('Upload Icon', 'ch-before-after'); ?></button>
        <?php if ($icon_id): ?>
            <button type="button"
                class="button ch-before-after-remove-icon"><?php _e('Remove Icon', 'ch-before-after'); ?></button>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Render the slide image 1 meta box
 */
function ch_before_after_image_meta_box_callback($post)
{
    // Add nonce for security
    wp_nonce_field('ch_before_after_image_meta_box', 'ch_before_after_image_meta_box_nonce');

    // Get the saved slide image
    $image_id = get_post_meta($post->ID, '_ch_before_after_image_id', true);
    $image_url = '';

    if ($image_id) {
        $image_url = wp_get_attachment_url($image_id);
    }

    ?>
    <p><?php _e('Upload the first slide image.', 'ch-before-after'); ?></p>
    <div class="ch-before-after-metabox">
        <div class="ch-before-after-image-preview">
            <?php if ($image_url): ?>
                <img src="<?php echo esc_url($image_url); ?>" alt="" style="max-width: 300px; height: auto;">
            <?php endif; ?>
        </div>
        <input type="hidden" id="ch_before_after_image_id" name="ch_before_after_image_id"
            value="<?php echo esc_attr($image_id); ?>">
        <button type="button"
            class="button ch-before-after-upload-image"><?php _e('Upload Image', 'ch-before-after'); ?></button>
        <?php if ($image_id): ?>
            <button type="button"
                class="button ch-before-after-remove-image"><?php _e('Remove Image', 'ch-before-after'); ?></button>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Render the slide image 2 meta box
 */
function ch_before_after_image_2_meta_box_callback($post)
{
    // Add nonce for security
    wp_nonce_field('ch_before_after_image_2_meta_box', 'ch_before_after_image_2_meta_box_nonce');

    // Get the saved slide image
    $image_id = get_post_meta($post->ID, '_ch_before_after_image_2_id', true);
    $image_url = '';

    if ($image_id) {
        $image_url = wp_get_attachment_url($image_id);
    }

    ?>
    <p><?php _e('Upload the second slide image.', 'ch-before-after'); ?></p>
    <div class="ch-before-after-metabox">
        <div class="ch-before-after-image-preview">
            <?php if ($image_url): ?>
                <img src="<?php echo esc_url($image_url); ?>" alt="" style="max-width: 300px; height: auto;">
            <?php endif; ?>
        </div>
        <input type="hidden" id="ch_before_after_image_2_id" name="ch_before_after_image_2_id"
            value="<?php echo esc_attr($image_id); ?>">
        <button type="button"
            class="button ch-before-after-upload-image-2"><?php _e('Upload Image 2', 'ch-before-after'); ?></button>
        <?php if ($image_id): ?>
            <button type="button"
                class="button ch-before-after-remove-image-2"><?php _e('Remove Image 2', 'ch-before-after'); ?></button>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Render the gallery slides meta box
 */
function ch_gallery_slides_meta_box_callback($post)
{
    // Add nonce for security
    wp_nonce_field('ch_gallery_slides_meta_box', 'ch_gallery_slides_meta_box_nonce');

    // Get the saved slides
    $selected_slides = get_post_meta($post->ID, '_ch_gallery_slides', true);

    if (!is_array($selected_slides)) {
        $selected_slides = array();
    }

    // Query all slides
    $slides = get_posts(array(
        'post_type' => 'ch_slide',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    ));

    if (empty($slides)) {
        echo '<p>' . __('No slides found. Please create some slides first.', 'ch-before-after') . '</p>';
        echo '<a href="' . esc_url(admin_url('post-new.php?post_type=ch_slide')) . '" class="button">' . __('Create New Slide', 'ch-before-after') . '</a>';
        return;
    }
    ?>
    <p><?php _e('Select slides to include in this gallery. You can rearrange the slides by dragging them.', 'ch-before-after'); ?>
    </p>

    <div class="ch-gallery-slides-container">
        <div class="ch-gallery-available-slides">
            <h4><?php _e('Available Slides', 'ch-before-after'); ?></h4>
            <div class="ch-slides-search">
                <input type="text" placeholder="<?php esc_attr_e('Search slides...', 'ch-before-after'); ?>"
                    class="ch-slides-search-input">
            </div>
            <ul class="ch-available-slides-list">
                <?php foreach ($slides as $slide): ?>
                    <?php if (!in_array($slide->ID, $selected_slides)): ?>
                        <li class="ch-slide-item" data-id="<?php echo esc_attr($slide->ID); ?>">
                            <div class="ch-slide-info">
                                <span class="ch-slide-title"><?php echo esc_html($slide->post_title); ?></span>
                                <button type="button" class="button ch-add-slide"><?php _e('Add', 'ch-before-after'); ?></button>
                            </div>
                            <?php
                            // Display the slide image if available
                            $image_id = get_post_meta($slide->ID, '_ch_before_after_image_id', true);
                            if ($image_id) {
                                $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
                                if ($image_url) {
                                    echo '<img src="' . esc_url($image_url) . '" alt="" class="ch-slide-thumbnail">';
                                }
                            }
                            ?>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="ch-gallery-selected-slides">
            <h4><?php _e('Selected Slides (Drag to Reorder)', 'ch-before-after'); ?></h4>
            <ul class="ch-selected-slides-list" id="ch-selected-slides-list">
                <?php foreach ($selected_slides as $slide_id): ?>
                    <?php
                    $slide = get_post($slide_id);
                    if ($slide):
                        ?>
                        <li class="ch-slide-item" data-id="<?php echo esc_attr($slide->ID); ?>">
                            <div class="ch-slide-info">
                                <span class="ch-slide-title"><?php echo esc_html($slide->post_title); ?></span>
                                <button type="button"
                                    class="button ch-remove-slide"><?php _e('Remove', 'ch-before-after'); ?></button>
                            </div>
                            <?php
                            // Display the slide image if available
                            $image_id = get_post_meta($slide->ID, '_ch_before_after_image_id', true);
                            if ($image_id) {
                                $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
                                if ($image_url) {
                                    echo '<img src="' . esc_url($image_url) . '" alt="" class="ch-slide-thumbnail">';
                                }
                            }
                            ?>
                            <input type="hidden" name="ch_gallery_slides[]" value="<?php echo esc_attr($slide->ID); ?>">
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            // Make the selected slides sortable
            $("#ch-selected-slides-list").sortable({
                placeholder: "ch-sortable-placeholder",
                update: function (event, ui) {
                    // Optional: Add logic here to handle the sorting update
                }
            });

            // Handle adding a slide to the selected list
            $(".ch-gallery-available-slides").on("click", ".ch-add-slide", function () {
                var slideItem = $(this).closest(".ch-slide-item");
                var slideId = slideItem.data("id");
                var slideTitle = slideItem.find(".ch-slide-title").text();
                var slideImage = slideItem.find("img").clone();

                // Create the new selected slide item
                var newSelectedItem = $('<li class="ch-slide-item" data-id="' + slideId + '"></li>');
                var slideInfo = $('<div class="ch-slide-info"></div>');
                slideInfo.append('<span class="ch-slide-title">' + slideTitle + '</span>');
                slideInfo.append('<button type="button" class="button ch-remove-slide"><?php _e('Remove', 'ch-before-after'); ?></button>');
                newSelectedItem.append(slideInfo);

                // Add the image if it exists
                if (slideImage.length) {
                    newSelectedItem.append(slideImage);
                }

                // Add the hidden input for saving
                newSelectedItem.append('<input type="hidden" name="ch_gallery_slides[]" value="' + slideId + '">');

                // Add to the selected list and remove from available
                $("#ch-selected-slides-list").append(newSelectedItem);
                slideItem.remove();
            });

            // Handle removing a slide from the selected list
            $(".ch-gallery-selected-slides").on("click", ".ch-remove-slide", function () {
                var slideItem = $(this).closest(".ch-slide-item");
                var slideId = slideItem.data("id");
                var slideTitle = slideItem.find(".ch-slide-title").text();
                var slideImage = slideItem.find("img").clone();

                // Create the new available slide item
                var newAvailableItem = $('<li class="ch-slide-item" data-id="' + slideId + '"></li>');
                var slideInfo = $('<div class="ch-slide-info"></div>');
                slideInfo.append('<span class="ch-slide-title">' + slideTitle + '</span>');
                slideInfo.append('<button type="button" class="button ch-add-slide"><?php _e('Add', 'ch-before-after'); ?></button>');
                newAvailableItem.append(slideInfo);

                // Add the image if it exists
                if (slideImage.length) {
                    newAvailableItem.append(slideImage);
                }

                // Add to the available list and remove from selected
                $(".ch-available-slides-list").append(newAvailableItem);
                slideItem.remove();
            });

            // Search functionality
            $(".ch-slides-search-input").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $(".ch-available-slides-list .ch-slide-item").filter(function () {
                    var title = $(this).find(".ch-slide-title").text().toLowerCase();
                    $(this).toggle(title.indexOf(value) > -1);
                });
            });
        });
    </script>
    <style>
        .ch-gallery-slides-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .ch-gallery-available-slides,
        .ch-gallery-selected-slides {
            flex: 1;
            min-width: 300px;
            border: 1px solid #ddd;
            padding: 15px;
            background: #f9f9f9;
        }

        .ch-available-slides-list,
        .ch-selected-slides-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .ch-slide-item {
            margin-bottom: 10px;
            padding: 10px;
            background: #fff;
            border: 1px solid #e0e0e0;
            cursor: pointer;
        }

        .ch-selected-slides-list .ch-slide-item {
            cursor: move;
        }

        .ch-sortable-placeholder {
            border: 1px dashed #bbb;
            height: 100px;
            background: #f5f5f5;
        }

        .ch-slide-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .ch-slide-title {
            font-weight: bold;
        }

        .ch-slide-thumbnail {
            max-width: 100px;
            height: auto;
            display: block;
            margin-top: 5px;
        }

        .ch-slides-search {
            margin-bottom: 15px;
        }
    </style>
    <?php
}

/**
 * Save the meta box data
 */
function ch_before_after_save_meta_boxes($post_id)
{
    // Check if we're autosaving
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions
    if (isset($_POST['post_type'])) {
        if ($_POST['post_type'] === 'ch_slide') {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        } elseif ($_POST['post_type'] === 'ch_gallery') {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        }
    }

    // Save icon meta box
    if (
        isset($_POST['ch_before_after_icon_meta_box_nonce']) &&
        wp_verify_nonce($_POST['ch_before_after_icon_meta_box_nonce'], 'ch_before_after_icon_meta_box')
    ) {
        if (isset($_POST['ch_before_after_icon_id'])) {
            update_post_meta($post_id, '_ch_before_after_icon_id', sanitize_text_field($_POST['ch_before_after_icon_id']));
        }
    }

    // Save direct URL for icon (fallback method)
    if (isset($_POST['ch_before_after_icon_url'])) {
        update_post_meta($post_id, '_ch_before_after_icon_url', esc_url_raw($_POST['ch_before_after_icon_url']));
    }

    // Save slide image 1 meta box
    if (
        isset($_POST['ch_before_after_image_meta_box_nonce']) &&
        wp_verify_nonce($_POST['ch_before_after_image_meta_box_nonce'], 'ch_before_after_image_meta_box')
    ) {
        if (isset($_POST['ch_before_after_image_id'])) {
            update_post_meta($post_id, '_ch_before_after_image_id', sanitize_text_field($_POST['ch_before_after_image_id']));
        }
    }

    // Save direct URL for image 1 (fallback method)
    if (isset($_POST['ch_before_after_image_url'])) {
        update_post_meta($post_id, '_ch_before_after_image_url', esc_url_raw($_POST['ch_before_after_image_url']));
    }

    // Save slide image 2 meta box
    if (
        isset($_POST['ch_before_after_image_2_meta_box_nonce']) &&
        wp_verify_nonce($_POST['ch_before_after_image_2_meta_box_nonce'], 'ch_before_after_image_2_meta_box')
    ) {
        if (isset($_POST['ch_before_after_image_2_id'])) {
            update_post_meta($post_id, '_ch_before_after_image_2_id', sanitize_text_field($_POST['ch_before_after_image_2_id']));
        }
    }

    // Save direct URL for image 2 (fallback method)
    if (isset($_POST['ch_before_after_image_2_url'])) {
        update_post_meta($post_id, '_ch_before_after_image_2_url', esc_url_raw($_POST['ch_before_after_image_2_url']));
    }

    // Save gallery slides meta box
    if (
        isset($_POST['ch_gallery_slides_meta_box_nonce']) &&
        wp_verify_nonce($_POST['ch_gallery_slides_meta_box_nonce'], 'ch_gallery_slides_meta_box')
    ) {
        if (isset($_POST['ch_gallery_slides'])) {
            $slides = array_map('absint', $_POST['ch_gallery_slides']);
            update_post_meta($post_id, '_ch_gallery_slides', $slides);
        } else {
            // If no slides are selected, save an empty array
            update_post_meta($post_id, '_ch_gallery_slides', array());
        }
    }
}
add_action('save_post', 'ch_before_after_save_meta_boxes');