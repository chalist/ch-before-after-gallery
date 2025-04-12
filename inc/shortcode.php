<?php
/**
 * Shortcodes for displaying the slider and galleries
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register the shortcode [ch_before_after]
 */
function ch_before_after_shortcode($atts)
{
    // Set a flag that the shortcode is being used
    global $ch_before_after_shortcode_used;
    $ch_before_after_shortcode_used = true;

    // Get plugin options
    $options = ch_before_after_get_options();

    $atts = shortcode_atts(
        array(
            'id' => '',     // Optional parameter to display a specific slide
            'limit' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'max_width' => $options['max_width'],
            'width' => $options['width'],
            'show_title' => $options['show_slide_title'] ? 'true' : 'false',
            'show_icon' => $options['show_slide_icon'] ? 'true' : 'false',
            'show_description' => $options['show_slide_description'] ? 'true' : 'false',
            // Custom CSS classes from settings
            'container_class' => $options['container_class'],
            'slide_class' => $options['slide_class'],
            'images_container_class' => $options['images_container_class'],
            'image_class' => $options['image_class'],
            'content_class' => $options['content_class'],
            'header_class' => $options['header_class'],
            'icon_class' => $options['icon_class'],
            'title_class' => $options['title_class'],
            'description_class' => $options['description_class']
        ),
        $atts,
        'ch_before_after'
    );

    // Convert string values to boolean
    $show_title = filter_var($atts['show_title'], FILTER_VALIDATE_BOOLEAN);
    $show_icon = filter_var($atts['show_icon'], FILTER_VALIDATE_BOOLEAN);
    $show_description = filter_var($atts['show_description'], FILTER_VALIDATE_BOOLEAN);

    // Query slides
    $args = array(
        'post_type' => 'ch_slide',
        'posts_per_page' => intval($atts['limit']),
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
        'post_status' => 'publish',
    );

    // If ID is specified, get that specific slide
    if (!empty($atts['id'])) {
        $args['include'] = absint($atts['id']);
        $args['posts_per_page'] = 1;
    }

    $slides = get_posts($args);

    if (empty($slides)) {
        return '<p>' . __('No slides found.', 'ch-before-after') . '</p>';
    }

    // Start output buffering
    ob_start();

    // Set inline style for width and max-width
    $container_style = '';
    if (!empty($atts['max_width'])) {
        $container_style .= 'max-width: ' . esc_attr($atts['max_width']) . '; ';
    }
    if (!empty($atts['width'])) {
        $container_style .= 'width: ' . esc_attr($atts['width']) . '; ';
    }

    // Get custom CSS classes from settings
    $container_class = !empty($atts['container_class']) ? ' ' . esc_attr($atts['container_class']) : '';
    $slide_class = !empty($atts['slide_class']) ? ' ' . esc_attr($atts['slide_class']) : '';
    $images_container_class = !empty($atts['images_container_class']) ? ' ' . esc_attr($atts['images_container_class']) : '';
    $image_class = !empty($atts['image_class']) ? ' ' . esc_attr($atts['image_class']) : '';
    $content_class = !empty($atts['content_class']) ? ' ' . esc_attr($atts['content_class']) : '';
    $header_class = !empty($atts['header_class']) ? ' ' . esc_attr($atts['header_class']) : '';
    $icon_class = !empty($atts['icon_class']) ? ' ' . esc_attr($atts['icon_class']) : '';
    $title_class = !empty($atts['title_class']) ? ' ' . esc_attr($atts['title_class']) : '';
    $description_class = !empty($atts['description_class']) ? ' ' . esc_attr($atts['description_class']) : '';

    // Simple container for the slides
    echo '<div class="ch-before-after-slides' . $container_class . '" style="' . $container_style . '">';

    foreach ($slides as $slide) {
        // Get slide data
        $icon_id = get_post_meta($slide->ID, '_ch_before_after_icon_id', true);
        $icon_url = '';

        // Handle icon URL from different sources
        if (strpos($icon_id, 'url:') === 0) {
            $icon_url = esc_url(substr($icon_id, 4));
        } else if ($icon_id) {
            $icon_url = wp_get_attachment_url($icon_id);
        }

        if (empty($icon_url)) {
            $direct_icon_url = get_post_meta($slide->ID, '_ch_before_after_icon_url', true);
            if (!empty($direct_icon_url)) {
                $icon_url = esc_url($direct_icon_url);
            }
        }

        // Get image 1 data
        $image_id = get_post_meta($slide->ID, '_ch_before_after_image_id', true);
        $image_url = '';

        if (strpos($image_id, 'url:') === 0) {
            $image_url = esc_url(substr($image_id, 4));
        } else if ($image_id) {
            $image_url = wp_get_attachment_url($image_id);
        }

        if (empty($image_url)) {
            $direct_image_url = get_post_meta($slide->ID, '_ch_before_after_image_url', true);
            if (!empty($direct_image_url)) {
                $image_url = esc_url($direct_image_url);
            }
        }

        // Get image 2 data
        $image_2_id = get_post_meta($slide->ID, '_ch_before_after_image_2_id', true);
        $image_2_url = '';

        if (strpos($image_2_id, 'url:') === 0) {
            $image_2_url = esc_url(substr($image_2_id, 4));
        } else if ($image_2_id) {
            $image_2_url = wp_get_attachment_url($image_2_id);
        }

        if (empty($image_2_url)) {
            $direct_image_2_url = get_post_meta($slide->ID, '_ch_before_after_image_2_url', true);
            if (!empty($direct_image_2_url)) {
                $image_2_url = esc_url($direct_image_2_url);
            }
        }

        $title = get_the_title($slide->ID);
        $content = apply_filters('the_content', $slide->post_content);
        ?>
        <div class="ch-before-after-slide<?php echo $slide_class; ?>">
            <?php if ($image_url && $image_2_url): ?>
                <div class="ch-before-after-images-container<?php echo $images_container_class; ?>">
                    <?php if ($image_url): ?>
                        <?php if (!empty($atts['before_text'])): ?>
                            <span class="ch-before-text"><?php echo esc_html($atts['before_text']); ?></span>
                        <?php endif; ?>
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" class="ch-before-image">
                    <?php endif; ?>
                    <?php if ($image_2_url): ?>
                        <?php if (!empty($atts['after_text'])): ?>
                            <span class="ch-after-text"><?php echo esc_html($atts['after_text']); ?></span>
                        <?php endif; ?>
                        <img src="<?php echo esc_url($image_2_url); ?>" alt="<?php echo esc_attr($title); ?>" class="ch-after-image">
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="ch-before-after-slide-content<?php echo $content_class; ?>">
                <?php if ($show_icon && $icon_url && $show_title && $title): ?>
                    <div class="ch-before-after-slide-header<?php echo $header_class; ?>">
                        <?php if ($show_icon && $icon_url): ?>
                            <div class="ch-before-after-slide-icon<?php echo $icon_class; ?>">
                                <img src="<?php echo esc_url($icon_url); ?>" alt="">
                            </div>
                        <?php endif; ?>
                        <?php if ($show_title): ?>
                            <h3 class="ch-before-after-slide-title<?php echo $title_class; ?>"><?php echo esc_html($title); ?></h3>
                        <?php endif; ?>
                    </div>
                <?php elseif ($show_title && $title): ?>
                    <h3 class="ch-before-after-slide-title<?php echo $title_class; ?>"><?php echo esc_html($title); ?></h3>
                <?php endif; ?>

                <?php if ($show_description && $content): ?>
                    <div class="ch-before-after-slide-description<?php echo $description_class; ?>">
                        <?php echo $content; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    echo '</div>'; // End .ch-before-after-slides

    return ob_get_clean();
}
add_shortcode('ch_before_after', 'ch_before_after_shortcode');

/**
 * Renders a single slide
 */
function ch_render_slide($slide_id)
{
    $slide = get_post($slide_id);

    // Check if slide exists and is the correct post type
    if (!$slide || 'ch_slide' !== get_post_type($slide)) {
        return '<p>' . __('Error: Slide not found.', 'ch-before-after') . '</p>';
    }

    // Get plugin options
    $options = ch_before_after_get_options();
    $show_title = $options['show_slide_title'];
    $show_icon = $options['show_slide_icon'];

    // Get custom CSS classes from settings
    $slide_class = !empty($options['slide_class']) ? ' ' . esc_attr($options['slide_class']) : '';
    $images_container_class = !empty($options['images_container_class']) ? ' ' . esc_attr($options['images_container_class']) : '';
    $image_class = !empty($options['image_class']) ? ' ' . esc_attr($options['image_class']) : '';
    $content_class = !empty($options['content_class']) ? ' ' . esc_attr($options['content_class']) : '';
    $header_class = !empty($options['header_class']) ? ' ' . esc_attr($options['header_class']) : '';
    $icon_class = !empty($options['icon_class']) ? ' ' . esc_attr($options['icon_class']) : '';
    $title_class = !empty($options['title_class']) ? ' ' . esc_attr($options['title_class']) : '';
    $description_class = !empty($options['description_class']) ? ' ' . esc_attr($options['description_class']) : '';

    // Get slide data
    $icon_id = get_post_meta($slide->ID, '_ch_before_after_icon_id', true);
    $icon_url = '';

    // Handle icon URL from different sources
    if (strpos($icon_id, 'url:') === 0) {
        $icon_url = esc_url(substr($icon_id, 4));
    } else if ($icon_id) {
        $icon_url = wp_get_attachment_url($icon_id);
    }

    if (empty($icon_url)) {
        $direct_icon_url = get_post_meta($slide->ID, '_ch_before_after_icon_url', true);
        if (!empty($direct_icon_url)) {
            $icon_url = esc_url($direct_icon_url);
        }
    }

    // Get image 1 data
    $image_id = get_post_meta($slide->ID, '_ch_before_after_image_id', true);
    $image_url = '';

    if (strpos($image_id, 'url:') === 0) {
        $image_url = esc_url(substr($image_id, 4));
    } else if ($image_id) {
        $image_url = wp_get_attachment_url($image_id);
    }

    if (empty($image_url)) {
        $direct_image_url = get_post_meta($slide->ID, '_ch_before_after_image_url', true);
        if (!empty($direct_image_url)) {
            $image_url = esc_url($direct_image_url);
        }
    }

    // Get image 2 data
    $image_2_id = get_post_meta($slide->ID, '_ch_before_after_image_2_id', true);
    $image_2_url = '';

    if (strpos($image_2_id, 'url:') === 0) {
        $image_2_url = esc_url(substr($image_2_id, 4));
    } else if ($image_2_id) {
        $image_2_url = wp_get_attachment_url($image_2_id);
    }

    if (empty($image_2_url)) {
        $direct_image_2_url = get_post_meta($slide->ID, '_ch_before_after_image_2_url', true);
        if (!empty($direct_image_2_url)) {
            $image_2_url = esc_url($direct_image_2_url);
        }
    }

    $title = get_the_title($slide->ID);
    $content = apply_filters('the_content', $slide->post_content);

    // Debug info
    $debug = '<!-- Slide ID: ' . $slide_id . ', Image 1: ' . $image_url . ', Image 2: ' . $image_2_url . ' -->';

    // Start building the HTML for the slide
    ob_start();
    echo $debug;
    ?>
    <div class="ch-before-after-slide<?php echo $slide_class; ?>">
        <?php if ($image_url && $image_2_url): ?>
            <div class="ch-before-after-images-container<?php echo $images_container_class; ?>">
                <?php if (!empty($options['before_text'])): ?>
                    <span class="ch-before-text"><?php echo esc_html($options['before_text']); ?></span>
                <?php endif; ?>
                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" class="twentytwenty-before">
                <?php if (!empty($options['after_text'])): ?>
                    <span class="ch-after-text"><?php echo esc_html($options['after_text']); ?></span>
                <?php endif; ?>
                <img src="<?php echo esc_url($image_2_url); ?>" alt="<?php echo esc_attr($title); ?>"
                    class="twentytwenty-after">
            </div>
        <?php else: ?>
            <!-- Missing images: Image 1: <?php echo $image_url ? 'Yes' : 'No'; ?>, Image 2: <?php echo $image_2_url ? 'Yes' : 'No'; ?> -->
        <?php endif; ?>

        <div class="ch-before-after-slide-content<?php echo $content_class; ?>">
            <?php if ($show_icon && $icon_url && $show_title && $title): ?>
                <div class="ch-before-after-slide-header<?php echo $header_class; ?>">
                    <?php if ($show_title): ?>
                        <h3 class="ch-before-after-slide-title<?php echo $title_class; ?>"><?php echo esc_html($title); ?></h3>
                    <?php endif; ?>
                </div>
            <?php elseif ($show_title && $title): ?>
                <h3 class="ch-before-after-slide-title<?php echo $title_class; ?>"><?php echo esc_html($title); ?></h3>
            <?php endif; ?>

            <?php if ($content): ?>
                <div class="ch-before-after-slide-description<?php echo $description_class; ?>">
                    <?php echo $content; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php

    return ob_get_clean();
}

/**
 * Register the shortcode [ch_gallery]
 */
function ch_gallery_shortcode($atts)
{
    // Set a flag that the shortcode is being used
    global $ch_before_after_shortcode_used;
    $ch_before_after_shortcode_used = true;

    // Get plugin options
    $options = ch_before_after_get_options();

    $atts = shortcode_atts(
        array(
            'id' => '',     // Required parameter to display a specific gallery
            'max_width' => $options['max_width'],
            'width' => $options['width'],
            'show_gallery_title' => $options['show_gallery_title'] ? '1' : '0',
            'show_gallery_description' => $options['show_gallery_description'] ? '1' : '0',
            'show_slide_title' => $options['show_slide_title'] ? '1' : '0',
            'show_slide_icon' => $options['show_slide_icon'] ? '1' : '0',
            'show_slide_description' => $options['show_slide_description'] ? '1' : '0',
            'before_text' => $options['before_text'],
            'after_text' => $options['after_text'],
            // Custom CSS classes from settings
            'container_class' => $options['container_class'],
            'slide_class' => $options['slide_class'],
            'images_container_class' => $options['images_container_class'],
            'image_class' => $options['image_class'],
            'content_class' => $options['content_class'],
            'header_class' => $options['header_class'],
            'icon_class' => $options['icon_class'],
            'title_class' => $options['title_class'],
            'description_class' => $options['description_class']
        ),
        $atts,
        'ch_gallery'
    );

    // Convert string values to boolean
    $show_slide_title = filter_var($atts['show_slide_title'], FILTER_VALIDATE_BOOLEAN);
    $show_slide_icon = filter_var($atts['show_slide_icon'], FILTER_VALIDATE_BOOLEAN);
    $show_slide_description = filter_var($atts['show_slide_description'], FILTER_VALIDATE_BOOLEAN);
    $show_gallery_title = filter_var($atts['show_gallery_title'], FILTER_VALIDATE_BOOLEAN);
    $show_gallery_description = filter_var($atts['show_gallery_description'], FILTER_VALIDATE_BOOLEAN);

    // If no ID is specified, return error message
    if (empty($atts['id'])) {
        return '<p>' . __('Error: Gallery ID is required.', 'ch-before-after') . '</p>';
    }

    $gallery_id = absint($atts['id']);
    $gallery = get_post($gallery_id);

    // Check if gallery exists and is published
    if (!$gallery || 'ch_gallery' !== get_post_type($gallery) || 'publish' !== get_post_status($gallery)) {
        return '<p>' . __('Error: Gallery not found.', 'ch-before-after') . '</p>';
    }

    // Get the slides in this gallery
    $slide_ids = get_post_meta($gallery_id, '_ch_gallery_slides', true);

    if (empty($slide_ids) || !is_array($slide_ids)) {
        return '<p>' . __('No slides found in this gallery.', 'ch-before-after') . '</p>';
    }

    // Start output buffering
    ob_start();

    // Get gallery title and description
    $gallery_title = get_the_title($gallery_id);
    $gallery_content = apply_filters('the_content', $gallery->post_content);

    // Set inline style for width and max-width
    $container_style = '';
    if (!empty($atts['max_width'])) {
        $container_style .= 'max-width: ' . esc_attr($atts['max_width']) . '; ';
    }
    if (!empty($atts['width'])) {
        $container_style .= 'width: ' . esc_attr($atts['width']) . '; ';
    }

    // Get custom CSS classes from settings
    $container_class = !empty($atts['container_class']) ? ' ' . esc_attr($atts['container_class']) : '';
    $title_class = !empty($atts['title_class']) ? ' ' . esc_attr($atts['title_class']) : '';
    $description_class = !empty($atts['description_class']) ? ' ' . esc_attr($atts['description_class']) : '';
    $icon_class = !empty($atts['icon_class']) ? ' ' . esc_attr($atts['icon_class']) : '';

    // Generate a unique ID for this tabbed gallery
    $tabbed_gallery_id = 'ch-tabbed-gallery-' . $gallery_id;

    // Get custom container class if specified
    $container_class = !empty($atts['container_class']) ? ' ' . esc_attr($atts['container_class']) : '';
    $max_width = esc_attr($atts['max_width']);
    $width = esc_attr($atts['width']);
    $width_style = 'max-width: ' . $max_width;

    // Add before and after text data attributes for the twentytwenty plugin
    $before_text = esc_attr($atts['before_text']);
    $after_text = esc_attr($atts['after_text']);
    $data_attributes = ' data-before-text="' . $before_text . '" data-after-text="' . $after_text . '"';

    ?>
    <div class="ch-gallery ch-tabbed-gallery<?php echo $container_class; ?>"
        id="<?php echo esc_attr($tabbed_gallery_id); ?>" style="<?php echo $container_style; ?>" <?php echo $data_attributes; ?>>
        <?php if ($show_gallery_title && !empty($gallery_title)): ?>
            <h2 class="ch-gallery-title<?php echo $title_class; ?>"><?php echo esc_html($gallery_title); ?></h2>
        <?php endif; ?>

        <?php if ($show_gallery_description && !empty($gallery_content)): ?>
            <div class="ch-gallery-description<?php echo $description_class; ?>">
                <?php echo $gallery_content; ?>
            </div>
        <?php endif; ?>

        <!-- Tabs navigation -->
        <div class="ch-tabs-nav">
            <ul class="ch-tabs-list">
                <?php
                $first_tab = true;
                foreach ($slide_ids as $index => $slide_id):
                    $slide = get_post($slide_id);
                    if (!$slide)
                        continue;

                    $tab_id = 'tab-' . $tabbed_gallery_id . '-' . $slide_id;
                    $title = get_the_title($slide_id);

                    // Get icon
                    $icon_id = get_post_meta($slide_id, '_ch_before_after_icon_id', true);
                    $icon_url = '';

                    if (strpos($icon_id, 'url:') === 0) {
                        $icon_url = esc_url(substr($icon_id, 4));
                    } else if ($icon_id) {
                        $icon_url = wp_get_attachment_url($icon_id);
                    }

                    if (empty($icon_url)) {
                        $direct_icon_url = get_post_meta($slide_id, '_ch_before_after_icon_url', true);
                        if (!empty($direct_icon_url)) {
                            $icon_url = esc_url($direct_icon_url);
                        }
                    }

                    $active_class = $first_tab ? 'active' : '';
                    ?>
                    <li class="ch-tab <?php echo $active_class; ?>" data-tab="<?php echo esc_attr($tab_id); ?>">
                        <?php if (filter_var($atts['show_slide_icon'], FILTER_VALIDATE_BOOLEAN) && !empty($icon_url)): ?>
                            <div class="ch-tab-icon<?php echo $icon_class; ?>">
                                <img src="<?php echo esc_url($icon_url); ?>" alt="">
                            </div>
                        <?php endif; ?>
                        <?php if (filter_var($atts['show_slide_title'], FILTER_VALIDATE_BOOLEAN)): ?>
                            <div class="ch-tab-title<?php echo $title_class; ?>"><?php echo esc_html($title); ?></div>
                        <?php endif; ?>
                    </li>
                    <?php
                    $first_tab = false;
                endforeach;
                ?>
            </ul>
        </div>

        <!-- Tab content -->
        <div class="ch-tab-content-container">
            <?php
            $first_tab = true;
            foreach ($slide_ids as $slide_id):
                $tab_id = 'tab-' . $tabbed_gallery_id . '-' . $slide_id;

                // Create custom attributes array to pass to ch_render_slide
                $slide_args = array(
                    'container_class' => $atts['container_class'],
                    'image_class' => $atts['image_class'],
                    'show_caption' => false,
                    'caption_class' => '',
                    'show_title' => false, // Title already shown in tab
                    'title_class' => '',
                    'show_content' => $atts['content_class'],
                    'content_class' => $atts['content_class'],
                    'overlay_style' => ''
                );

                $slide_html = ch_render_slide($slide_id);
                if (empty($slide_html))
                    continue;

                $active_class = $first_tab ? 'active' : '';
                ?>
                <div id="<?php echo esc_attr($tab_id); ?>" class="ch-tab-content <?php echo $active_class; ?>">
                    <?php echo $slide_html; ?>
                </div>
                <?php
                $first_tab = false;
            endforeach;
            ?>
        </div>
    </div>
    <?php

    return ob_get_clean();
}
add_shortcode('ch_gallery', 'ch_gallery_shortcode');

/**
 * Register the shortcode [ch_slider]
 */
function ch_slider_shortcode($atts)
{
    // Set a flag that the shortcode is being used
    global $ch_before_after_shortcode_used;
    $ch_before_after_shortcode_used = true;

    // Get plugin options
    $options = ch_before_after_get_options();

    $atts = shortcode_atts(
        array(
            'id' => '',     // Optional parameter to display a specific slide
            'limit' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'max_width' => $options['max_width'],
            'width' => $options['width'],
            'show_title' => $options['show_slide_title'] ? 'true' : 'false',
            'show_icon' => $options['show_slide_icon'] ? 'true' : 'false',
            'show_description' => $options['show_slide_description'] ? 'true' : 'false',
            'before_text' => $options['before_text'],
            'after_text' => $options['after_text'],
            // Custom CSS classes from settings
            'container_class' => $options['container_class'],
            'slide_class' => $options['slide_class'],
            'images_container_class' => $options['images_container_class'],
            'image_class' => $options['image_class'],
            'content_class' => $options['content_class'],
            'header_class' => $options['header_class'],
            'icon_class' => $options['icon_class'],
            'title_class' => $options['title_class'],
            'description_class' => $options['description_class']
        ),
        $atts,
        'ch_slider'
    );

    // Convert string values to boolean
    $show_title = filter_var($atts['show_title'], FILTER_VALIDATE_BOOLEAN);
    $show_icon = filter_var($atts['show_icon'], FILTER_VALIDATE_BOOLEAN);
    $show_description = filter_var($atts['show_description'], FILTER_VALIDATE_BOOLEAN);

    // Get slides
    $slides = get_posts(array(
        'post_type' => 'ch_slide',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => $atts['orderby'],
        'order' => $atts['order']
    ));

    if (empty($slides)) {
        return '<p>' . __('No slides found.', 'ch-before-after') . '</p>';
    }

    // Start output buffering
    ob_start();

    // Set inline style for width and max-width
    $container_style = '';
    if (!empty($atts['max_width'])) {
        $container_style .= 'max-width: ' . esc_attr($atts['max_width']) . '; ';
    }
    if (!empty($atts['width'])) {
        $container_style .= 'width: ' . esc_attr($atts['width']) . '; ';
    }

    // Get custom CSS classes from settings
    $container_class = !empty($atts['container_class']) ? ' ' . esc_attr($atts['container_class']) : '';
    $slide_class = !empty($atts['slide_class']) ? ' ' . esc_attr($atts['slide_class']) : '';
    $images_container_class = !empty($atts['images_container_class']) ? ' ' . esc_attr($atts['images_container_class']) : '';
    $image_class = !empty($atts['image_class']) ? ' ' . esc_attr($atts['image_class']) : '';
    $content_class = !empty($atts['content_class']) ? ' ' . esc_attr($atts['content_class']) : '';
    $header_class = !empty($atts['header_class']) ? ' ' . esc_attr($atts['header_class']) : '';
    $icon_class = !empty($atts['icon_class']) ? ' ' . esc_attr($atts['icon_class']) : '';
    $title_class = !empty($atts['title_class']) ? ' ' . esc_attr($atts['title_class']) : '';
    $description_class = !empty($atts['description_class']) ? ' ' . esc_attr($atts['description_class']) : '';

    // Simple container for the slides
    echo '<div class="ch-before-after-slides' . $container_class . '" style="' . $container_style . '">';

    foreach ($slides as $slide) {
        // Get slide data
        $icon_id = get_post_meta($slide->ID, '_ch_before_after_icon_id', true);
        $icon_url = '';

        // Handle icon URL from different sources
        if (strpos($icon_id, 'url:') === 0) {
            $icon_url = esc_url(substr($icon_id, 4));
        } else if ($icon_id) {
            $icon_url = wp_get_attachment_url($icon_id);
        }

        if (empty($icon_url)) {
            $direct_icon_url = get_post_meta($slide->ID, '_ch_before_after_icon_url', true);
            if (!empty($direct_icon_url)) {
                $icon_url = esc_url($direct_icon_url);
            }
        }

        // Get image 1 data
        $image_id = get_post_meta($slide->ID, '_ch_before_after_image_id', true);
        $image_url = '';

        if (strpos($image_id, 'url:') === 0) {
            $image_url = esc_url(substr($image_id, 4));
        } else if ($image_id) {
            $image_url = wp_get_attachment_url($image_id);
        }

        if (empty($image_url)) {
            $direct_image_url = get_post_meta($slide->ID, '_ch_before_after_image_url', true);
            if (!empty($direct_image_url)) {
                $image_url = esc_url($direct_image_url);
            }
        }

        // Get image 2 data
        $image_2_id = get_post_meta($slide->ID, '_ch_before_after_image_2_id', true);
        $image_2_url = '';

        if (strpos($image_2_id, 'url:') === 0) {
            $image_2_url = esc_url(substr($image_2_id, 4));
        } else if ($image_2_id) {
            $image_2_url = wp_get_attachment_url($image_2_id);
        }

        if (empty($image_2_url)) {
            $direct_image_2_url = get_post_meta($slide->ID, '_ch_before_after_image_2_url', true);
            if (!empty($direct_image_2_url)) {
                $image_2_url = esc_url($direct_image_2_url);
            }
        }

        $title = get_the_title($slide->ID);
        $content = apply_filters('the_content', $slide->post_content);
        ?>
        <div class="ch-before-after-slide<?php echo $slide_class; ?>">
            <?php if ($image_url && $image_2_url): ?>
                <div class="ch-before-after-images-container<?php echo $images_container_class; ?>">
                    <?php if ($image_url): ?>
                        <?php if (!empty($atts['before_text'])): ?>
                            <span class="ch-before-text"><?php echo esc_html($atts['before_text']); ?></span>
                        <?php endif; ?>
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" class="ch-before-image">
                    <?php endif; ?>
                    <?php if ($image_2_url): ?>
                        <?php if (!empty($atts['after_text'])): ?>
                            <span class="ch-after-text"><?php echo esc_html($atts['after_text']); ?></span>
                        <?php endif; ?>
                        <img src="<?php echo esc_url($image_2_url); ?>" alt="<?php echo esc_attr($title); ?>" class="ch-after-image">
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="ch-before-after-slide-content<?php echo $content_class; ?>">
                <?php if ($show_icon && $icon_url && $show_title && $title): ?>
                    <div class="ch-before-after-slide-header<?php echo $header_class; ?>">
                        <?php if ($show_icon && $icon_url): ?>
                            <div class="ch-before-after-slide-icon<?php echo $icon_class; ?>">
                                <img src="<?php echo esc_url($icon_url); ?>" alt="">
                            </div>
                        <?php endif; ?>
                        <?php if ($show_title): ?>
                            <h3 class="ch-before-after-slide-title<?php echo $title_class; ?>"><?php echo esc_html($title); ?></h3>
                        <?php endif; ?>
                    </div>
                <?php elseif ($show_title && $title): ?>
                    <h3 class="ch-before-after-slide-title<?php echo $title_class; ?>"><?php echo esc_html($title); ?></h3>
                <?php endif; ?>

                <?php if ($show_description && $content): ?>
                    <div class="ch-before-after-slide-description<?php echo $description_class; ?>">
                        <?php echo $content; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    echo '</div>'; // End .ch-before-after-slides

    return ob_get_clean();
}
add_shortcode('ch_slider', 'ch_slider_shortcode');