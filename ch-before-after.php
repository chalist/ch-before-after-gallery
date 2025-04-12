<?php
/**
 * Plugin Name: CH Before & After
 * Plugin URI: https://github.com/chalist/plugins/ch-before-after
 * Description: A custom slider plugin that supports icons, titles, and slide images.
 * Version: 1.0.0
 * Author: Chalist
 * Author URI: https://github.com/chalist/
 * Text Domain: ch-before-after
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('CH_BEFORE_AFTER_VERSION', '1.0.0');
define('CH_BEFORE_AFTER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CH_BEFORE_AFTER_PLUGIN_URL', plugin_dir_url(__FILE__));


// Include files
require_once CH_BEFORE_AFTER_PLUGIN_DIR . 'inc/post-types.php';
require_once CH_BEFORE_AFTER_PLUGIN_DIR . 'inc/meta-boxes.php';
require_once CH_BEFORE_AFTER_PLUGIN_DIR . 'inc/shortcode.php';
require_once CH_BEFORE_AFTER_PLUGIN_DIR . 'inc/settings.php';

// Activation hook
register_activation_hook(__FILE__, 'ch_before_after_activate');
function ch_before_after_activate()
{
    // Flush rewrite rules
    flush_rewrite_rules();
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'ch_before_after_deactivate');
function ch_before_after_deactivate()
{
    // Flush rewrite rules
    flush_rewrite_rules();
}

// Enqueue scripts and styles
function ch_before_after_enqueue_scripts()
{
    // Need to always load these for WordPress admin previews to work
    wp_enqueue_style('ch-before-after-css', CH_BEFORE_AFTER_PLUGIN_URL . 'assets/css/ch-before-after.css', array(), CH_BEFORE_AFTER_VERSION);
    wp_enqueue_style('ch-before-after-twentytwenty-css', CH_BEFORE_AFTER_PLUGIN_URL . 'assets/css/twentytwenty.css', array(), CH_BEFORE_AFTER_VERSION);

    // Make sure jQuery is loaded
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-event-move', CH_BEFORE_AFTER_PLUGIN_URL . 'assets/js/jquery.event.move.js', array('jquery'), CH_BEFORE_AFTER_VERSION, true);
    wp_enqueue_script('jquery-twentytwenty-js', CH_BEFORE_AFTER_PLUGIN_URL . 'assets/js/jquery.twentytwenty.js', array('jquery', 'jquery-event-move'), CH_BEFORE_AFTER_VERSION, true);
    wp_enqueue_script('ch-before-after-js', CH_BEFORE_AFTER_PLUGIN_URL . 'assets/js/ch-before-after.js', array('jquery', 'jquery-twentytwenty-js'), CH_BEFORE_AFTER_VERSION, true);
}
add_action('wp_enqueue_scripts', 'ch_before_after_enqueue_scripts');

// Enqueue admin scripts and styles
function ch_before_after_admin_enqueue_scripts($hook)
{
    // Only load on post.php, post-new.php for our custom post types
    if (!in_array($hook, array('post.php', 'post-new.php'))) {
        return;
    }

    $allowed_post_types = array('ch_slide', 'ch_gallery');
    $current_post_type = '';

    // For post.php, check the post type
    if ($hook === 'post.php' && isset($_GET['post'])) {
        $post_id = $_GET['post'];
        $current_post_type = get_post_type($post_id);

        if (!in_array($current_post_type, $allowed_post_types)) {
            return;
        }
    }

    // For post-new.php, check if the correct post type is being created
    if ($hook === 'post-new.php') {
        if (isset($_GET['post_type']) && in_array($_GET['post_type'], $allowed_post_types)) {
            $current_post_type = $_GET['post_type'];
        } else if (!isset($_GET['post_type'])) {
            // If no post_type is specified in the URL, it's creating a regular post
            return;
        } else {
            return;
        }
    }

    // Enqueue WordPress media scripts and styles for both post types
    wp_enqueue_media();

    // Enqueue our custom styles and scripts with proper dependencies
    wp_enqueue_style('ch-before-after-admin-style', CH_BEFORE_AFTER_PLUGIN_URL . 'assets/admin/css/admin.css', array(), CH_BEFORE_AFTER_VERSION);

    // For ch_gallery, enqueue jQuery UI Sortable
    if ($current_post_type === 'ch_gallery') {
        wp_enqueue_script('jquery-ui-sortable');
    }

    // For ch_slide, load media and image management scripts and 3-column layout CSS
    if ($current_post_type === 'ch_slide') {
        // Load 3-column layout CSS
        wp_enqueue_style('ch-before-after-slide-editor', CH_BEFORE_AFTER_PLUGIN_URL . 'assets/admin/css/slide-editor.css', array(), CH_BEFORE_AFTER_VERSION);

        // Attempt to load the main admin script first
        wp_enqueue_script('ch-before-after-admin-script', CH_BEFORE_AFTER_PLUGIN_URL . 'assets/admin/js/admin.js', array('jquery', 'media-upload', 'media-views'), CH_BEFORE_AFTER_VERSION, true);

        // Also load the fallback script for environments with CSP restrictions
        wp_enqueue_script('ch-before-after-admin-fallback', CH_BEFORE_AFTER_PLUGIN_URL . 'assets/admin/js/admin-fallback.js', array('jquery'), CH_BEFORE_AFTER_VERSION, true);

        // Add inline script to detect CSP errors
        wp_add_inline_script('ch-before-after-admin-fallback', '
            // Check for CSP errors
            window.addEventListener("error", function(e) {
                if (e.message && e.message.indexOf("Content Security Policy") !== -1) {
                    console.log("CSP error detected, enabling fallback uploader");
                    jQuery(document).trigger("error");
                }
            });
        ');

        // Add inline script to create the 3-column layout
        wp_add_inline_script('ch-before-after-admin-script', '
            jQuery(document).ready(function($) {
                // Create the custom 3-column layout
                var $editorContainer = $("<div>").addClass("ch-before-after-editor-container");
                
                // Create columns
                var $iconColumn = $("<div>").addClass("ch-before-after-editor-column ch-before-after-icon-column");
                var $imagesColumn = $("<div>").addClass("ch-before-after-editor-column ch-before-after-images-column");
                var $contentColumn = $("<div>").addClass("ch-before-after-editor-column ch-before-after-content-column");
                
                // Move the meta boxes to the appropriate columns
                var $iconBox = $("#ch_before_after_icon .inside").contents();
                var $image1Box = $("#ch_before_after_image .inside").contents();
                var $image2Box = $("#ch_before_after_image_2 .inside").contents();
                var $editor = $("#postdivrich").contents();
                
                // Create sections for each element
                var $iconSection = $("<div>").addClass("ch-before-after-section")
                    .append("<h3>' . __('Slide Icon', 'ch-before-after') . '</h3>")
                    .append($iconBox);
                
                var $image1Section = $("<div>").addClass("ch-before-after-section")
                    .append("<h3>' . __('Slide Image 1', 'ch-before-after') . '</h3>")
                    .append($image1Box);
                
                var $image2Section = $("<div>").addClass("ch-before-after-section")
                    .append("<h3>' . __('Slide Image 2', 'ch-before-after') . '</h3>")
                    .append($image2Box);
                
                var $editorSection = $("<div>").addClass("ch-before-after-section")
                    .append("<h3>' . __('Slide Content', 'ch-before-after') . '</h3>")
                    .append($editor);
                
                // Add sections to columns
                $iconColumn.append($iconSection);
                $imagesColumn.append($image1Section).append($image2Section);
                $contentColumn.append($editorSection);
                
                // Add columns to container
                $editorContainer.append($iconColumn).append($imagesColumn).append($contentColumn);
                
                // Insert the container after the title
                $("#titlediv").after($editorContainer);
                
                // Make sure media buttons work
                if (typeof wp !== "undefined" && wp.media) {
                    $(".ch-before-after-upload-icon").on("click", function(e) {
                        e.preventDefault();
                        var button = $(this);
                        var id = "ch_before_after_icon_id";
                        
                        // Create a new media frame
                        var frame = wp.media({
                            title: "Select or Upload Icon Image",
                            button: {
                                text: "Use this image for icon"
                            },
                            multiple: false
                        });
                        
                        // When an image is selected in the media frame...
                        frame.on("select", function() {
                            // Get media attachment details from the frame state
                            var attachment = frame.state().get("selection").first().toJSON();
                            
                            // Update the icon
                            $("#" + id).val(attachment.id);
                            $(".ch-before-after-icon-column .ch-before-after-image-preview").html("<img src=\\"" + attachment.url + "\\" style=\\"max-width:100px;height:auto;\\">");
                            
                            // Add remove button if it doesn\'t exist
                            if ($(".ch-before-after-remove-icon").length === 0) {
                                button.after("<button type=\\"button\\" class=\\"button ch-before-after-remove-icon\\">' . __('Remove Icon', 'ch-before-after') . '</button>");
                            }
                        });
                        
                        // Open the media frame
                        frame.open();
                        return false;
                    });
                    
                    $(".ch-before-after-upload-image").on("click", function(e) {
                        e.preventDefault();
                        var button = $(this);
                        var id = "ch_before_after_image_id";
                        
                        // Create a new media frame
                        var frame = wp.media({
                            title: "Select or Upload Image 1",
                            button: {
                                text: "Use this image for slide 1"
                            },
                            multiple: false
                        });
                        
                        // When an image is selected in the media frame...
                        frame.on("select", function() {
                            // Get media attachment details from the frame state
                            var attachment = frame.state().get("selection").first().toJSON();
                            
                            // Update image 1
                            $("#" + id).val(attachment.id);
                            $(".ch-before-after-images-column .ch-before-after-section:first-child .ch-before-after-image-preview").html("<img src=\\"" + attachment.url + "\\" style=\\"max-width:100%;height:auto;\\">");
                            
                            // Add remove button if it doesn\'t exist
                            if (button.next(".ch-before-after-remove-image").length === 0) {
                                button.after("<button type=\\"button\\" class=\\"button ch-before-after-remove-image\\">' . __('Remove Image', 'ch-before-after') . '</button>");
                            }
                        });
                        
                        // Open the media frame
                        frame.open();
                        return false;
                    });
                    
                    $(".ch-before-after-upload-image-2").on("click", function(e) {
                        e.preventDefault();
                        var button = $(this);
                        var id = "ch_before_after_image_2_id";
                        
                        // Create a new media frame
                        var frame = wp.media({
                            title: "Select or Upload Image 2",
                            button: {
                                text: "Use this image for slide 2"
                            },
                            multiple: false
                        });
                        
                        // When an image is selected in the media frame...
                        frame.on("select", function() {
                            // Get media attachment details from the frame state
                            var attachment = frame.state().get("selection").first().toJSON();
                            
                            // Update image 2
                            $("#" + id).val(attachment.id);
                            $(".ch-before-after-images-column .ch-before-after-section:nth-child(2) .ch-before-after-image-preview").html("<img src=\\"" + attachment.url + "\\" style=\\"max-width:100%;height:auto;\\">");
                            
                            // Add remove button if it doesn\'t exist
                            if (button.next(".ch-before-after-remove-image-2").length === 0) {
                                button.after("<button type=\\"button\\" class=\\"button ch-before-after-remove-image-2\\">' . __('Remove Image 2', 'ch-before-after') . '</button>");
                            }
                        });
                        
                        // Open the media frame
                        frame.open();
                        return false;
                    });
                    
                    // Remove buttons for images
                    $(document).on("click", ".ch-before-after-remove-icon", function() {
                        $("#ch_before_after_icon_id").val("");
                        $(".ch-before-after-icon-column .ch-before-after-image-preview").empty();
                        $(this).remove();
                        return false;
                    });
                    
                    $(document).on("click", ".ch-before-after-remove-image", function() {
                        $("#ch_before_after_image_id").val("");
                        $(".ch-before-after-images-column .ch-before-after-section:first-child .ch-before-after-image-preview").empty();
                        $(this).remove();
                        return false;
                    });
                    
                    $(document).on("click", ".ch-before-after-remove-image-2", function() {
                        $("#ch_before_after_image_2_id").val("");
                        $(".ch-before-after-images-column .ch-before-after-section:nth-child(2) .ch-before-after-image-preview").empty();
                        $(this).remove();
                        return false;
                    });
                }
            });
        ');
    }
}
add_action('admin_enqueue_scripts', 'ch_before_after_admin_enqueue_scripts');

// Function to modify the Content Security Policy for the admin area
function ch_before_after_modify_csp_headers()
{
    // Only modify CSP in the admin area
    if (is_admin()) {
        // Remove existing CSP headers that might be set by the server
        header_remove('Content-Security-Policy');

        // Add a new CSP header that allows 'unsafe-eval' for admin
        header("Content-Security-Policy: default-src 'self' http: https: data: blob: 'unsafe-inline' 'unsafe-eval';");
    }
}
add_action('init', 'ch_before_after_modify_csp_headers', 1);

// Function to display an admin notice about the folder rename
function ch_before_after_admin_notice()
{
    // Check if the plugin directory is still named 'ch-before-after'
    if (strpos(plugin_dir_path(__FILE__), '/ch-before-after/') !== false) {
        // We're good, do nothing
    } else {
        add_action('admin_notices', function () {
            ?>
            <div class="notice notice-warning is-dismissible">
                <p><?php _e('It is recommended to rename the plugin folder to "ch-before-after" for consistency. Deactivate the plugin, rename the folder, and then reactivate.', 'ch-before-after'); ?>
                </p>
            </div>
            <?php
        });
    }
}
add_action('admin_notices', 'ch_before_after_admin_notice');

// Alternative approach using the wp_headers filter
function ch_before_after_filter_wp_headers($headers)
{
    if (is_admin()) {
        // Modify the CSP header if it exists, or add a new one
        $headers['Content-Security-Policy'] = "default-src 'self' http: https: data: blob: 'unsafe-inline' 'unsafe-eval';";
    }
    return $headers;
}
add_filter('wp_headers', 'ch_before_after_filter_wp_headers');

// Conditional script loading for when shortcode is used
function ch_before_after_footer_scripts()
{
    global $ch_before_after_shortcode_used;

    // Only execute initialize code if the shortcode was used on the page
    if (isset($ch_before_after_shortcode_used) && $ch_before_after_shortcode_used === true) {
        // Debug - show what meta data is available
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                console.log('CH Before After initialized');

                // Log all image containers
                console.log('Image containers found: ' + $('.ch-before-after-images-container').length);

                // Force initialize all sliders
                $('.ch-before-after-images-container').each(function () {
                    // Check if it has two images
                    var $container = $(this);
                    var images = $container.find('img');
                    console.log('Images in container: ' + images.length);

                    if (images.length === 2) {
                        console.log('Initializing twentytwenty on container');

                        // Make sure the images are loaded before initialization
                        var imagesLoaded = 0;
                        images.on('load', function () {
                            imagesLoaded++;
                            if (imagesLoaded === 2) {
                                // Add explicit class names expected by twentytwenty
                                $(images[0]).addClass('twentytwenty-before');
                                $(images[1]).addClass('twentytwenty-after');

                                // Initialize twentytwenty
                                $container.twentytwenty({
                                    default_offset_pct: 0.5,
                                    orientation: 'horizontal',
                                    before_label: '',
                                    after_label: '',
                                    no_overlay: true
                                });
                            }
                        });

                        // If images are already loaded, trigger load event
                        images.each(function () {
                            if (this.complete) {
                                $(this).trigger('load');
                            }
                        });
                    }
                });

                // Tab functionality for tabbed galleries
                $('.ch-tab').on('click', function () {
                    var $this = $(this);
                    var tabId = $this.data('tab');
                    console.log('Tab clicked: ' + tabId);

                    var $gallery = $this.closest('.ch-gallery');

                    // Remove active class from all tabs and contents
                    $gallery.find('.ch-tab').removeClass('active');
                    $gallery.find('.ch-tab-content').removeClass('active');

                    // Add active class to the clicked tab and its content
                    $this.addClass('active');
                    $('#' + tabId).addClass('active');

                    // Re-initialize twentytwenty for the newly visible content
                    setTimeout(function () {
                        var $slideContainer = $('#' + tabId).find('.ch-before-after-images-container');
                        console.log('Reinitializing container in tab: ' + $slideContainer.length);

                        $slideContainer.each(function () {
                            var images = $(this).find('img');
                            console.log('Images in tab container: ' + images.length);

                            if (images.length === 2) {
                                // If already initialized, destroy it first
                                if ($(this).hasClass('twentytwenty-container')) {
                                    $(this).twentytwenty('destroy');
                                }

                                // Add explicit class names expected by twentytwenty
                                $(images[0]).addClass('twentytwenty-before');
                                $(images[1]).addClass('twentytwenty-after');

                                // Initialize twentytwenty
                                $(this).twentytwenty({
                                    default_offset_pct: 0.5,
                                    orientation: 'horizontal',
                                    before_label: '',
                                    after_label: '',
                                    no_overlay: true
                                });
                            }
                        });
                    }, 100);
                });
            });
        </script>
        <?php
    }
}
add_action('wp_footer', 'ch_before_after_footer_scripts');