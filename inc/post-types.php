<?php
/**
 * Register custom post types for slider and gallery
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register the 'ch_gallery' custom post type
 */
function ch_before_after_register_post_types()
{
    // Register Gallery Custom Post Type
    $gallery_labels = array(
        'name' => _x('Galleries', 'post type general name', 'ch-before-after'),
        'singular_name' => _x('Gallery', 'post type singular name', 'ch-before-after'),
        'menu_name' => _x('CH Galleries', 'admin menu', 'ch-before-after'),
        'name_admin_bar' => _x('Gallery', 'add new on admin bar', 'ch-before-after'),
        'add_new' => _x('Add New', 'gallery', 'ch-before-after'),
        'add_new_item' => __('Add New Gallery', 'ch-before-after'),
        'new_item' => __('New Gallery', 'ch-before-after'),
        'edit_item' => __('Edit Gallery', 'ch-before-after'),
        'view_item' => __('View Gallery', 'ch-before-after'),
        'all_items' => __('All Galleries', 'ch-before-after'),
        'search_items' => __('Search Galleries', 'ch-before-after'),
        'parent_item_colon' => __('Parent Galleries:', 'ch-before-after'),
        'not_found' => __('No galleries found.', 'ch-before-after'),
        'not_found_in_trash' => __('No galleries found in Trash.', 'ch-before-after')
    );

    $gallery_args = array(
        'labels' => $gallery_labels,
        'description' => __('Galleries for the slider.', 'ch-before-after'),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'slider-gallery'),
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-images-alt',
        'supports' => array('title', 'editor', 'thumbnail'),
    );

    register_post_type('ch_gallery', $gallery_args);

    // Register Slide Custom Post Type
    $slide_labels = array(
        'name' => _x('Slides', 'post type general name', 'ch-before-after'),
        'singular_name' => _x('Slide', 'post type singular name', 'ch-before-after'),
        'menu_name' => _x('Slides', 'admin menu', 'ch-before-after'),
        'name_admin_bar' => _x('Slide', 'add new on admin bar', 'ch-before-after'),
        'add_new' => _x('Add New', 'slide', 'ch-before-after'),
        'add_new_item' => __('Add New Slide', 'ch-before-after'),
        'new_item' => __('New Slide', 'ch-before-after'),
        'edit_item' => __('Edit Slide', 'ch-before-after'),
        'view_item' => __('View Slide', 'ch-before-after'),
        'all_items' => __('All Slides', 'ch-before-after'),
        'search_items' => __('Search Slides', 'ch-before-after'),
        'parent_item_colon' => __('Parent Slides:', 'ch-before-after'),
        'not_found' => __('No slides found.', 'ch-before-after'),
        'not_found_in_trash' => __('No slides found in Trash.', 'ch-before-after')
    );

    $slide_args = array(
        'labels' => $slide_labels,
        'description' => __('Slides for the slider.', 'ch-before-after'),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => 'edit.php?post_type=ch_gallery',  // Make Slides a submenu of Galleries
        'query_var' => true,
        'rewrite' => array('slug' => 'slide'),
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'supports' => array('title', 'editor', 'thumbnail', 'page-attributes'),
    );

    register_post_type('ch_slide', $slide_args);
}
add_action('init', 'ch_before_after_register_post_types');

/**
 * Add custom columns to the slider admin list
 */
function ch_before_after_add_admin_columns($columns)
{
    $new_columns = array();

    // Insert columns before the 'date' column
    foreach ($columns as $key => $value) {
        if ($key === 'date') {
            $new_columns['shortcode'] = __('Shortcode', 'ch-before-after');
        }
        $new_columns[$key] = $value;
    }

    return $new_columns;
}
add_filter('manage_ch_slide_posts_columns', 'ch_before_after_add_admin_columns');

/**
 * Add custom columns to the gallery admin list
 */
function ch_gallery_add_admin_columns($columns)
{
    $new_columns = array();

    // Insert columns before the 'date' column
    foreach ($columns as $key => $value) {
        if ($key === 'date') {
            $new_columns['shortcode'] = __('Shortcode', 'ch-before-after');
            $new_columns['slide_count'] = __('Slides', 'ch-before-after');
        }
        $new_columns[$key] = $value;
    }

    return $new_columns;
}
add_filter('manage_ch_gallery_posts_columns', 'ch_gallery_add_admin_columns');

/**
 * Display content for custom columns in the slider admin list
 */
function ch_before_after_display_admin_columns($column, $post_id)
{
    switch ($column) {
        case 'shortcode':
            $shortcode = '[ch_before_after id="' . $post_id . '"]';
            echo '<div class="ch-before-after-shortcode-wrap">';
            echo '<input type="text" class="ch-before-after-shortcode" value="' . esc_attr($shortcode) . '" readonly onClick="this.select();" />';
            echo '<button type="button" class="button ch-copy-shortcode" data-clipboard-text="' . esc_attr($shortcode) . '">';
            echo '<span class="dashicons dashicons-clipboard"></span> ' . __('Copy', 'ch-before-after');
            echo '</button>';
            echo '</div>';
            break;
    }
}
add_action('manage_ch_slide_posts_custom_column', 'ch_before_after_display_admin_columns', 10, 2);

/**
 * Display content for custom columns in the gallery admin list
 */
function ch_gallery_display_admin_columns($column, $post_id)
{
    switch ($column) {
        case 'shortcode':
            $shortcode = '[ch_gallery id="' . $post_id . '"]';
            echo '<div class="ch-before-after-shortcode-wrap">';
            echo '<input type="text" class="ch-before-after-shortcode" value="' . esc_attr($shortcode) . '" readonly onClick="this.select();" />';
            echo '<button type="button" class="button ch-copy-shortcode" data-clipboard-text="' . esc_attr($shortcode) . '">';
            echo '<span class="dashicons dashicons-clipboard"></span> ' . __('Copy', 'ch-before-after');
            echo '</button>';
            echo '</div>';
            break;
        case 'slide_count':
            $slides = get_post_meta($post_id, '_ch_gallery_slides', true);
            $count = is_array($slides) ? count($slides) : 0;
            echo $count;
            break;
    }
}
add_action('manage_ch_gallery_posts_custom_column', 'ch_gallery_display_admin_columns', 10, 2);

/**
 * Enqueue admin scripts and styles for the shortcode copy functionality
 */
function ch_before_after_admin_columns_scripts($hook)
{
    if ($hook !== 'edit.php') {
        return;
    }

    $screen = get_current_screen();
    if (!$screen || !in_array($screen->post_type, array('ch_slide', 'ch_gallery'))) {
        return;
    }

    // Enqueue styles for the admin columns
    wp_enqueue_style('ch-before-after-admin-columns', CH_BEFORE_AFTER_PLUGIN_URL . 'assets/admin/css/columns.css', array(), CH_BEFORE_AFTER_VERSION);

    // Enqueue script for copying shortcode
    wp_enqueue_script('ch-before-after-admin-columns', CH_BEFORE_AFTER_PLUGIN_URL . 'assets/admin/js/admin-columns.js', array('jquery'), CH_BEFORE_AFTER_VERSION, true);
}
add_action('admin_enqueue_scripts', 'ch_before_after_admin_columns_scripts');