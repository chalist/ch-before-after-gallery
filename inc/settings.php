<?php
/**
 * CH Before After Settings
 *
 * @package CH_Before_After
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue admin settings styles
 */
function ch_before_after_admin_settings_scripts($hook)
{
    // Only load on our settings page
    if ('ch_gallery_page_ch-before-after-settings' !== $hook) {
        return;
    }

    wp_enqueue_style(
        'ch-before-after-admin-settings',
        CH_BEFORE_AFTER_PLUGIN_URL . 'assets/admin/css/settings.css',
        array(),
        CH_BEFORE_AFTER_VERSION
    );
}
add_action('admin_enqueue_scripts', 'ch_before_after_admin_settings_scripts');

/**
 * Register the settings page
 */
function ch_before_after_add_settings_page()
{
    add_submenu_page(
        'edit.php?post_type=ch_gallery',
        __('Slider Settings', 'ch-before-after'),
        __('Settings', 'ch-before-after'),
        'manage_options',
        'ch-before-after-settings',
        'ch_before_after_render_settings_page'
    );
}
add_action('admin_menu', 'ch_before_after_add_settings_page');

/**
 * Register settings
 */
function ch_before_after_register_settings()
{
    register_setting(
        'ch_before_after_settings',
        'ch_before_after_options',
        'ch_before_after_validate_options'
    );

    // General settings section
    add_settings_section(
        'ch-before-after-settings-general',
        __('General Settings', 'ch-before-after'),
        'ch_before_after_settings_general_section_callback',
        'ch-before-after-settings'
    );

    // Add settings fields
    add_settings_field(
        'max_width',
        __('Maximum Width', 'ch-before-after'),
        'ch_before_after_max_width_field_callback',
        'ch-before-after-settings',
        'ch-before-after-settings-general'
    );

    add_settings_field(
        'width',
        __('Width', 'ch-before-after'),
        'ch_before_after_width_field_callback',
        'ch-before-after-settings',
        'ch-before-after-settings-general'
    );

    add_settings_field(
        'show_gallery_title',
        __('Show Gallery Title', 'ch-before-after'),
        'ch_before_after_show_gallery_title_field_callback',
        'ch-before-after-settings',
        'ch-before-after-settings-general'
    );

    add_settings_field(
        'show_gallery_description',
        __('Show Gallery Description', 'ch-before-after'),
        'ch_before_after_show_gallery_description_field_callback',
        'ch-before-after-settings',
        'ch-before-after-settings-general'
    );

    add_settings_field(
        'show_slide_icon',
        __('Show Slide Icon', 'ch-before-after'),
        'ch_before_after_show_slide_icon_field_callback',
        'ch-before-after-settings',
        'ch-before-after-settings-general'
    );

    add_settings_field(
        'show_slide_title',
        __('Show Slide Title', 'ch-before-after'),
        'ch_before_after_show_slide_title_field_callback',
        'ch-before-after-settings',
        'ch-before-after-settings-general'
    );

    add_settings_field(
        'show_slide_description',
        __('Show Slide Description', 'ch-before-after'),
        'ch_before_after_show_slide_description_field_callback',
        'ch-before-after-settings',
        'ch-before-after-settings-general'
    );

    add_settings_field(
        'before_text',
        __('Before Text Label', 'ch-before-after'),
        'ch_before_after_before_text_field_callback',
        'ch-before-after-settings',
        'ch-before-after-settings-general'
    );

    add_settings_field(
        'after_text',
        __('After Text Label', 'ch-before-after'),
        'ch_before_after_after_text_field_callback',
        'ch-before-after-settings',
        'ch-before-after-settings-general'
    );

    // Add Custom CSS Classes section
    add_settings_section(
        'ch-before-after-settings-css',
        __('Custom CSS Classes', 'ch-before-after'),
        'ch_before_after_settings_css_section_callback',
        'ch-before-after-settings'
    );

    // Add custom CSS class fields
    add_settings_field(
        'container_class',
        __('Slider Container Class', 'ch-before-after'),
        'ch_before_after_container_class_field_callback',
        'ch-before-after-settings',
        'ch-before-after-settings-css'
    );

    add_settings_field(
        'slide_class',
        __('Slide Class', 'ch-before-after'),
        'ch_before_after_slide_class_callback',
        'ch-before-after-settings',
        'ch-before-after-settings-css'
    );

    add_settings_field(
        'images_container_class',
        __('Images Container Class', 'ch-before-after'),
        'ch_before_after_images_container_class_callback',
        'ch-before-after-settings',
        'ch-before-after-settings-css'
    );

    add_settings_field(
        'image_class',
        __('Image Class', 'ch-before-after'),
        'ch_before_after_image_class_callback',
        'ch-before-after-settings',
        'ch-before-after-settings-css'
    );

    add_settings_field(
        'content_class',
        __('Slide Content Class', 'ch-before-after'),
        'ch_before_after_content_class_callback',
        'ch-before-after-settings',
        'ch-before-after-settings-css'
    );

    add_settings_field(
        'header_class',
        __('Slide Header Class', 'ch-before-after'),
        'ch_before_after_header_class_callback',
        'ch-before-after-settings',
        'ch-before-after-settings-css'
    );

    add_settings_field(
        'icon_class',
        __('Icon Class', 'ch-before-after'),
        'ch_before_after_icon_class_callback',
        'ch-before-after-settings',
        'ch-before-after-settings-css'
    );

    add_settings_field(
        'title_class',
        __('Title Class', 'ch-before-after'),
        'ch_before_after_title_class_callback',
        'ch-before-after-settings',
        'ch-before-after-settings-css'
    );

    add_settings_field(
        'description_class',
        __('Description Class', 'ch-before-after'),
        'ch_before_after_description_class_callback',
        'ch-before-after-settings',
        'ch-before-after-settings-css'
    );

    /* Labels section */
    add_settings_section(
        'ch_before_after_labels_section',
        __('Slider Labels', 'ch-before-after'),
        'ch_before_after_labels_section_callback',
        'ch-before-after'
    );

    // Before Text
    add_settings_field(
        'ch_before_after_before_text',
        __('Before Text', 'ch-before-after'),
        'ch_before_after_before_text_callback',
        'ch-before-after',
        'ch_before_after_labels_section'
    );

    // After Text
    add_settings_field(
        'ch_before_after_after_text',
        __('After Text', 'ch-before-after'),
        'ch_before_after_after_text_callback',
        'ch-before-after',
        'ch_before_after_labels_section'
    );
}
add_action('admin_init', 'ch_before_after_register_settings');

/**
 * General section callback
 */
function ch_before_after_settings_general_section_callback()
{
    echo '<p>' . __('Configure the general settings for the CH Before After.', 'ch-before-after') . '</p>';
}

/**
 * Get default options
 *
 * @return array
 */
function ch_before_after_get_default_options()
{
    $defaults = array(
        'max_width' => 1100,
        'width' => 100,
        'show_gallery_title' => true,
        'show_gallery_description' => true,
        'show_slide_icon' => true,
        'show_slide_title' => true,
        'show_slide_description' => true,
        'before_text' => __('Before', 'ch-before-after'),
        'after_text' => __('After', 'ch-before-after'),
        'container_class' => '',
        'gallery_container_class' => '',
        'gallery_title_class' => '',
        'gallery_description_class' => '',
        'tabs_container_class' => '',
        'tab_element_class' => '',
        'tab_active_class' => '',
        'slider_container_class' => '',
        'slide_title_class' => '',
        'slide_description_class' => '',
        'caption_class' => '',
        'title_class' => '',
        'content_class' => '',
    );
    return apply_filters('ch_before_after_default_options', $defaults);
}

/**
 * Get plugin options
 *
 * @return array Plugin options
 */
function ch_before_after_get_options()
{
    $defaults = ch_before_after_get_default_options();
    $options = get_option('ch_before_after_options', $defaults);
    return wp_parse_args($options, $defaults);
}

/**
 * Max width field callback
 */
function ch_before_after_max_width_field_callback()
{
    $options = ch_before_after_get_options();
    ?>
        <input type="text" id="ch_before_after_max_width" name="ch_before_after_options[max_width]"
            value="<?php echo esc_attr($options['max_width']); ?>" class="regular-text" />
        <p class="description">
            <?php _e('Enter maximum width (e.g., 800px, 100%). Must be a valid CSS width value.', 'ch-before-after'); ?>
        </p>
        <?php
}

/**
 * Width field callback
 */
function ch_before_after_width_field_callback()
{
    $options = ch_before_after_get_options();
    ?>
        <input type="text" id="ch_before_after_width" name="ch_before_after_options[width]"
            value="<?php echo esc_attr($options['width']); ?>" class="regular-text" />
        <p class="description">
            <?php _e('Enter width (e.g., 800px, 100%). Must be a valid CSS width value.', 'ch-before-after'); ?>
        </p>
        <?php
}

/**
 * Show gallery title field callback
 */
function ch_before_after_show_gallery_title_field_callback()
{
    $options = ch_before_after_get_options();
    ?>
        <input type="checkbox" id="ch_before_after_show_gallery_title" name="ch_before_after_options[show_gallery_title]"
            value="1" <?php checked(1, $options['show_gallery_title'], true); ?> />
        <label for="ch_before_after_show_gallery_title"><?php _e('Show gallery title in slider', 'ch-before-after'); ?></label>
        <?php
}

/**
 * Show gallery description field callback
 */
function ch_before_after_show_gallery_description_field_callback()
{
    $options = ch_before_after_get_options();
    ?>
        <input type="checkbox" id="ch_before_after_show_gallery_description"
            name="ch_before_after_options[show_gallery_description]" value="1" <?php checked(1, $options['show_gallery_description'], true); ?> />
        <label
            for="ch_before_after_show_gallery_description"><?php _e('Show gallery description in slider', 'ch-before-after'); ?></label>
        <?php
}

/**
 * Show slide icon field callback
 */
function ch_before_after_show_slide_icon_field_callback()
{
    $options = ch_before_after_get_options();
    ?>
        <input type="checkbox" id="ch_before_after_show_slide_icon" name="ch_before_after_options[show_slide_icon]" value="1"
            <?php checked(1, $options['show_slide_icon'], true); ?> />
        <label for="ch_before_after_show_slide_icon"><?php _e('Show slide icon in slider', 'ch-before-after'); ?></label>
        <?php
}

/**
 * Show slide title field callback
 */
function ch_before_after_show_slide_title_field_callback()
{
    $options = ch_before_after_get_options();
    ?>
        <input type="checkbox" id="ch_before_after_show_slide_title" name="ch_before_after_options[show_slide_title]" value="1"
            <?php checked(1, $options['show_slide_title'], true); ?> />
        <label for="ch_before_after_show_slide_title"><?php _e('Show slide title in slider', 'ch-before-after'); ?></label>
        <?php
}

/**
 * Show slide description field callback
 */
function ch_before_after_show_slide_description_field_callback()
{
    $options = ch_before_after_get_options();
    ?>
        <input type="checkbox" id="ch_before_after_show_slide_description" name="ch_before_after_options[show_slide_description]" value="1"
            <?php checked(1, $options['show_slide_description'], true); ?> />
        <label for="ch_before_after_show_slide_description"><?php _e('Show slide description in slider', 'ch-before-after'); ?></label>
        <?php
}

/**
 * Validate options
 *
 * @param array $input The input options
 * @return array The validated options
 */
function ch_before_after_validate_options($input)
{
    $validated = array();
    $defaults = ch_before_after_get_default_options();

    // Validate max_width
    if (isset($input['max_width'])) {
        $validated['max_width'] = ch_before_after_validate_dimension($input['max_width'], $defaults['max_width']);
    } else {
        $validated['max_width'] = $defaults['max_width'];
    }

    // Validate width
    if (isset($input['width'])) {
        $validated['width'] = ch_before_after_validate_dimension($input['width'], $defaults['width']);
    } else {
        $validated['width'] = $defaults['width'];
    }

    // Validate checkboxes
    $validated['show_gallery_title'] = isset($input['show_gallery_title']) ? 1 : 0;
    $validated['show_gallery_description'] = isset($input['show_gallery_description']) ? 1 : 0;
    $validated['show_slide_icon'] = isset($input['show_slide_icon']) ? 1 : 0;
    $validated['show_slide_title'] = isset($input['show_slide_title']) ? 1 : 0;
    $validated['show_slide_description'] = isset($input['show_slide_description']) ? 1 : 0;

    // Validate text fields
    $validated['before_text'] = isset($input['before_text']) ? sanitize_text_field($input['before_text']) : $defaults['before_text'];
    $validated['after_text'] = isset($input['after_text']) ? sanitize_text_field($input['after_text']) : $defaults['after_text'];

    // Validate CSS classes
    $css_fields = array(
        'container_class',
        'slide_class',
        'images_container_class',
        'image_class',
        'content_class',
        'header_class',
        'icon_class',
        'title_class',
        'description_class'
    );

    foreach ($css_fields as $field) {
        if (isset($input[$field])) {
            $validated[$field] = sanitize_text_field($input[$field]);
        } else {
            $validated[$field] = '';
        }
    }

    return $validated;
}

/**
 * Validate dimension value (px or %)
 *
 * @param string $value The value to validate
 * @param string $default The default value
 * @return string The validated value
 */
function ch_before_after_validate_dimension($value, $default)
{
    $value = trim($value);

    // Check if the value is a valid CSS dimension (px or %)
    if (preg_match('/^(\d+)(px|%)$/', $value)) {
        return $value;
    }

    // If it's just a number, assume pixels
    if (is_numeric($value)) {
        return $value . 'px';
    }

    // Return default if validation fails
    return $default;
}

/**
 * Render the settings page
 */
function ch_before_after_render_settings_page()
{
    $current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';
    ?>
        <div class="wrap ch-before-after-settings">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <h2 class="ch-before-after-nav-tab-wrapper nav-tab-wrapper">
                <a href="?post_type=ch_gallery&page=ch-before-after-settings"
                    class="nav-tab <?php echo $current_tab === 'general' || !$current_tab ? 'nav-tab-active' : ''; ?>">
                    <?php _e('General', 'ch-before-after'); ?>
                </a>
                <a href="?post_type=ch_gallery&page=ch-before-after-settings&tab=css"
                    class="nav-tab <?php echo $current_tab === 'css' ? 'nav-tab-active' : ''; ?>">
                    <?php _e('CSS Classes', 'ch-before-after'); ?>
                </a>
            </h2>
            <form action="options.php" method="post">
                <?php
                settings_fields('ch_before_after_settings');
                $options = ch_before_after_get_options();

                // Display either General or CSS section based on active tab
                if ($current_tab === 'css') {
                    // CSS Classes tab
                    ?>
                        <div id="ch_before_after_css_section">
                            <h3><?php _e('Custom CSS Classes', 'ch-before-after'); ?></h3>
                            <p><?php _e('Add custom CSS classes to the slider elements. Multiple classes should be separated by spaces.', 'ch-before-after'); ?></p>

                            <div class="css-classes-container">
                                <!-- Container and Slide Classes -->
                                <div class="css-class-column">
                                    <table class="form-table" role="presentation">
                                        <tbody>
                                            <tr>
                                                <th scope="row"><label for="ch_before_after_container_class"><?php _e('Slider Container Class', 'ch-before-after'); ?></label></th>
                                                <td>
                                                    <input type="text" id="ch_before_after_container_class" name="ch_before_after_options[container_class]" 
                                                        value="<?php echo esc_attr($options['container_class']); ?>" class="regular-text" />
                                                    <p class="description"><?php _e('CSS classes for the slider container', 'ch-before-after'); ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><label for="ch_before_after_slide_class"><?php _e('Slide Class', 'ch-before-after'); ?></label></th>
                                                <td>
                                                    <input type="text" id="ch_before_after_slide_class" name="ch_before_after_options[slide_class]" 
                                                        value="<?php echo esc_attr($options['slide_class']); ?>" class="regular-text" />
                                                    <p class="description"><?php _e('CSS classes for individual slides', 'ch-before-after'); ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><label for="ch_before_after_images_container_class"><?php _e('Images Container Class', 'ch-before-after'); ?></label></th>
                                                <td>
                                                    <input type="text" id="ch_before_after_images_container_class" name="ch_before_after_options[images_container_class]" 
                                                        value="<?php echo esc_attr($options['images_container_class']); ?>" class="regular-text" />
                                                    <p class="description"><?php _e('CSS classes for the images container', 'ch-before-after'); ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><label for="ch_before_after_image_class"><?php _e('Image Class', 'ch-before-after'); ?></label></th>
                                                <td>
                                                    <input type="text" id="ch_before_after_image_class" name="ch_before_after_options[image_class]" 
                                                        value="<?php echo esc_attr($options['image_class']); ?>" class="regular-text" />
                                                    <p class="description"><?php _e('CSS classes for slide images', 'ch-before-after'); ?></p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                        
                                <!-- Content, Title, and Description Classes -->
                                <div class="css-class-column">
                                    <table class="form-table" role="presentation">
                                        <tbody>
                                            <tr>
                                                <th scope="row"><label for="ch_before_after_content_class"><?php _e('Slide Content Class', 'ch-before-after'); ?></label></th>
                                                <td>
                                                    <input type="text" id="ch_before_after_content_class" name="ch_before_after_options[content_class]" 
                                                        value="<?php echo esc_attr($options['content_class']); ?>" class="regular-text" />
                                                    <p class="description"><?php _e('CSS classes for slide content container', 'ch-before-after'); ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><label for="ch_before_after_header_class"><?php _e('Slide Header Class', 'ch-before-after'); ?></label></th>
                                                <td>
                                                    <input type="text" id="ch_before_after_header_class" name="ch_before_after_options[header_class]" 
                                                        value="<?php echo esc_attr($options['header_class']); ?>" class="regular-text" />
                                                    <p class="description"><?php _e('CSS classes for slide header container', 'ch-before-after'); ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><label for="ch_before_after_icon_class"><?php _e('Icon Class', 'ch-before-after'); ?></label></th>
                                                <td>
                                                    <input type="text" id="ch_before_after_icon_class" name="ch_before_after_options[icon_class]" 
                                                        value="<?php echo esc_attr($options['icon_class']); ?>" class="regular-text" />
                                                    <p class="description"><?php _e('CSS classes for slide icons', 'ch-before-after'); ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><label for="ch_before_after_title_class"><?php _e('Title Class', 'ch-before-after'); ?></label></th>
                                                <td>
                                                    <input type="text" id="ch_before_after_title_class" name="ch_before_after_options[title_class]" 
                                                        value="<?php echo esc_attr($options['title_class']); ?>" class="regular-text" />
                                                    <p class="description"><?php _e('CSS classes for slide titles', 'ch-before-after'); ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><label for="ch_before_after_description_class"><?php _e('Description Class', 'ch-before-after'); ?></label></th>
                                                <td>
                                                    <input type="text" id="ch_before_after_description_class" name="ch_before_after_options[description_class]" 
                                                        value="<?php echo esc_attr($options['description_class']); ?>" class="regular-text" />
                                                    <p class="description"><?php _e('CSS classes for slide descriptions', 'ch-before-after'); ?></p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php
                } else {
                    // General Settings tab
                    ?>
                        <div id="ch_before_after_general_section">
                            <h3><?php _e('General Settings', 'ch-before-after'); ?></h3>
                            <p><?php _e('Configure the general settings for the CH Before After.', 'ch-before-after'); ?></p>
                    
                            <table class="form-table" role="presentation">
                                <tbody>
                                    <tr>
                                        <th scope="row"><label for="ch_before_after_max_width"><?php _e('Maximum Width', 'ch-before-after'); ?></label></th>
                                        <td>
                                            <input type="text" id="ch_before_after_max_width" name="ch_before_after_options[max_width]" 
                                                value="<?php echo esc_attr($options['max_width']); ?>" class="regular-text" />
                                            <p class="description"><?php _e('Maximum width of the slider (e.g., 800px, 100%, etc.)', 'ch-before-after'); ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="ch_before_after_width"><?php _e('Width', 'ch-before-after'); ?></label></th>
                                        <td>
                                            <input type="text" id="ch_before_after_width" name="ch_before_after_options[width]" 
                                                value="<?php echo esc_attr($options['width']); ?>" class="regular-text" />
                                            <p class="description"><?php _e('Width of the slider (e.g., 100%, 500px, etc.)', 'ch-before-after'); ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><?php _e('Show Gallery Title', 'ch-before-after'); ?></th>
                                        <td>
                                            <label for="ch_before_after_show_gallery_title">
                                                <input type="checkbox" id="ch_before_after_show_gallery_title" name="ch_before_after_options[show_gallery_title]" 
                                                    value="1" <?php checked(1, $options['show_gallery_title']); ?> />
                                                <?php _e('Display the gallery title', 'ch-before-after'); ?>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><?php _e('Show Gallery Description', 'ch-before-after'); ?></th>
                                        <td>
                                            <label for="ch_before_after_show_gallery_description">
                                                <input type="checkbox" id="ch_before_after_show_gallery_description" name="ch_before_after_options[show_gallery_description]" 
                                                    value="1" <?php checked(1, $options['show_gallery_description']); ?> />
                                                <?php _e('Display the gallery description', 'ch-before-after'); ?>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><?php _e('Show Slide Icon', 'ch-before-after'); ?></th>
                                        <td>
                                            <label for="ch_before_after_show_slide_icon">
                                                <input type="checkbox" id="ch_before_after_show_slide_icon" name="ch_before_after_options[show_slide_icon]" 
                                                    value="1" <?php checked(1, $options['show_slide_icon']); ?> />
                                                <?php _e('Display the slide icon', 'ch-before-after'); ?>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><?php _e('Show Slide Title', 'ch-before-after'); ?></th>
                                        <td>
                                            <label for="ch_before_after_show_slide_title">
                                                <input type="checkbox" id="ch_before_after_show_slide_title" name="ch_before_after_options[show_slide_title]" 
                                                    value="1" <?php checked(1, $options['show_slide_title']); ?> />
                                                <?php _e('Show slide title in slider', 'ch-before-after'); ?>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><?php _e('Show Slide Description', 'ch-before-after'); ?></th>
                                        <td>
                                            <label for="ch_before_after_show_slide_description">
                                                <input type="checkbox" id="ch_before_after_show_slide_description" name="ch_before_after_options[show_slide_description]" 
                                                    value="1" <?php checked(1, $options['show_slide_description']); ?> />
                                                <?php _e('Show slide description in slider', 'ch-before-after'); ?>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><?php _e('Before Text Label', 'ch-before-after'); ?></th>
                                        <td>
                                            <input type="text" id="ch_before_after_before_text" name="ch_before_after_options[before_text]" 
                                                value="<?php echo esc_attr($options['before_text']); ?>" class="regular-text" />
                                            <p class="description"><?php _e('Text label for the "Before" section', 'ch-before-after'); ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><?php _e('After Text Label', 'ch-before-after'); ?></th>
                                        <td>
                                            <input type="text" id="ch_before_after_after_text" name="ch_before_after_options[after_text]" 
                                                value="<?php echo esc_attr($options['after_text']); ?>" class="regular-text" />
                                            <p class="description"><?php _e('Text label for the "After" section', 'ch-before-after'); ?></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <?php
                }

                submit_button();
                ?>
            </form>
        </div>
        <?php
}

/**
 * CSS section callback
 */
function ch_before_after_settings_css_section_callback()
{
    echo '<p>' . __('Add custom CSS classes to the slider elements. Multiple classes should be separated by spaces.', 'ch-before-after') . '</p>';
}

/**
 * Container class field callback
 */
function ch_before_after_container_class_callback()
{
    $options = ch_before_after_get_options();
    $value = isset($options['container_class']) ? $options['container_class'] : '';
    ?>
        <input type="text" id="ch_before_after_container_class" name="ch_before_after_options[container_class]"
            value="<?php echo esc_attr($value); ?>" class="regular-text" />
        <p class="description"><?php _e('CSS classes for the slider container', 'ch-before-after'); ?></p>
        <?php
}

/**
 * Slide class field callback
 */
function ch_before_after_slide_class_callback()
{
    $options = ch_before_after_get_options();
    $value = isset($options['slide_class']) ? $options['slide_class'] : '';
    ?>
        <input type="text" id="ch_before_after_slide_class" name="ch_before_after_options[slide_class]"
            value="<?php echo esc_attr($value); ?>" class="regular-text" />
        <p class="description"><?php _e('CSS classes for individual slides', 'ch-before-after'); ?></p>
        <?php
}

/**
 * Images container class field callback
 */
function ch_before_after_images_container_class_callback()
{
    $options = ch_before_after_get_options();
    $value = isset($options['images_container_class']) ? $options['images_container_class'] : '';
    ?>
        <input type="text" id="ch_before_after_images_container_class" name="ch_before_after_options[images_container_class]"
            value="<?php echo esc_attr($value); ?>" class="regular-text" />
        <p class="description"><?php _e('CSS classes for the images container', 'ch-before-after'); ?></p>
        <?php
}

/**
 * Image class field callback
 */
function ch_before_after_image_class_callback()
{
    $options = ch_before_after_get_options();
    $value = isset($options['image_class']) ? $options['image_class'] : '';
    ?>
        <input type="text" id="ch_before_after_image_class" name="ch_before_after_options[image_class]"
            value="<?php echo esc_attr($value); ?>" class="regular-text" />
        <p class="description"><?php _e('CSS classes for slide images', 'ch-before-after'); ?></p>
        <?php
}

/**
 * Content class field callback
 */
function ch_before_after_content_class_callback()
{
    $options = ch_before_after_get_options();
    $value = isset($options['content_class']) ? $options['content_class'] : '';
    ?>
        <input type="text" id="ch_before_after_content_class" name="ch_before_after_options[content_class]"
            value="<?php echo esc_attr($value); ?>" class="regular-text" />
        <p class="description"><?php _e('CSS classes for slide content container', 'ch-before-after'); ?></p>
        <?php
}

/**
 * Header class field callback
 */
function ch_before_after_header_class_callback()
{
    $options = ch_before_after_get_options();
    $value = isset($options['header_class']) ? $options['header_class'] : '';
    ?>
        <input type="text" id="ch_before_after_header_class" name="ch_before_after_options[header_class]"
            value="<?php echo esc_attr($value); ?>" class="regular-text" />
        <p class="description"><?php _e('CSS classes for slide header container', 'ch-before-after'); ?></p>
        <?php
}

/**
 * Icon class field callback
 */
function ch_before_after_icon_class_callback()
{
    $options = ch_before_after_get_options();
    $value = isset($options['icon_class']) ? $options['icon_class'] : '';
    ?>
        <input type="text" id="ch_before_after_icon_class" name="ch_before_after_options[icon_class]"
            value="<?php echo esc_attr($value); ?>" class="regular-text" />
        <p class="description"><?php _e('CSS classes for slide icons', 'ch-before-after'); ?></p>
        <?php
}

/**
 * Title class field callback
 */
function ch_before_after_title_class_callback()
{
    $options = ch_before_after_get_options();
    $value = isset($options['title_class']) ? $options['title_class'] : '';
    ?>
        <input type="text" id="ch_before_after_title_class" name="ch_before_after_options[title_class]"
            value="<?php echo esc_attr($value); ?>" class="regular-text" />
        <p class="description"><?php _e('CSS classes for slide titles', 'ch-before-after'); ?></p>
        <?php
}

/**
 * Description class field callback
 */
function ch_before_after_description_class_callback()
{
    $options = ch_before_after_get_options();
    $value = isset($options['description_class']) ? $options['description_class'] : '';
    ?>
        <input type="text" id="ch_before_after_description_class" name="ch_before_after_options[description_class]"
            value="<?php echo esc_attr($value); ?>" class="regular-text" />
        <p class="description"><?php _e('CSS classes for slide descriptions', 'ch-before-after'); ?></p>
        <?php
}

/**
 * Before text field callback
 */
function ch_before_after_before_text_field_callback()
{
    $options = ch_before_after_get_options();
    ?>
        <input type="text" id="ch_before_after_before_text" name="ch_before_after_options[before_text]"
            value="<?php echo esc_attr($options['before_text']); ?>" class="regular-text" />
        <p class="description">
            <?php _e('Enter the text label for the "Before" section', 'ch-before-after'); ?>
        </p>
        <?php
}

/**
 * After text field callback
 */
function ch_before_after_after_text_field_callback()
{
    $options = ch_before_after_get_options();
    ?>
        <input type="text" id="ch_before_after_after_text" name="ch_before_after_options[after_text]"
            value="<?php echo esc_attr($options['after_text']); ?>" class="regular-text" />
        <p class="description">
            <?php _e('Enter the text label for the "After" section', 'ch-before-after'); ?>
        </p>
        <?php
}

/**
 * Labels section callback
 */
function ch_before_after_labels_section_callback()
{
    _e('Customize the text labels that appear on the before-after slider.', 'ch-before-after');
}