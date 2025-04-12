# CH Before & After Plugin

A custom WordPress Before and After slider plugin that supports before/after image comparison with title and description for the gallery and each slide.

![gallery](https://github.com/user-attachments/assets/364c444f-250a-4c43-90d7-f827c4692be7)


## Table of Contents

- [Features](#features)
- [Usage](#usage)
  - [Creating Slides](#creating-slides)
  - [Creating Galleries](#creating-galleries)
  - [Shortcodes](#shortcodes)
  - [Settings](#settings)
  - [Custom CSS Classes](#custom-css-classes)
  - [Shortcode Parameters](#shortcode-parameters)
  - [Example CSS Usage](#example-css-usage)
- [Third-Party Tools](#third-party-tools)
  - [TwentyTwenty](#twentytwenty)
  - [Usage with TwentyTwenty](#usage-with-twentytwenty)
- [Support](#support)
- [Contributing](#contributing)
  - [Creating Issues](#creating-issues)
  - [Pull Requests](#pull-requests)
  - [Development Guidelines](#development-guidelines)

## Features

- Create beautiful sliders with before/after image comparison
- Organize slides into galleries with tabs
- Display icons and titles for each slide
- Customize width and appearance
- Responsive design for all devices
- Apply custom CSS classes to any slider element

## Usage

### Creating Slides

1. Go to **Slides** > **Add New** in your WordPress admin
2. Add a title and description for your slide
3. Upload an icon (optional)
4. Upload before and after images
5. Publish your slide

### Creating Galleries

1. Go to **Galleries** > **Add New** in your WordPress admin
2. Add a title and description for your gallery
3. Add slides to your gallery using the Slides section
4. Publish your gallery

### Shortcodes

Use the following shortcodes to display your sliders and galleries:

- Display a single slide: `[ch_before_after id="123"]`
- Display all slides: `[ch_before_after]`
- Display a gallery: `[ch_gallery id="456"]`

### Settings

Configure your slider settings by going to **Galleries** > **Settings** in your WordPress admin.

Available settings:

1. **Maximum Width**: Set a maximum width for your sliders (e.g., 1200px, 100%)
2. **Width**: Set the width for your sliders (e.g., 800px, 80%)
3. **Show Gallery Title**: Toggle to show/hide gallery titles
4. **Show Gallery Description**: Toggle to show/hide gallery descriptions
5. **Show Slide Icon**: Toggle to show/hide slide icons
6. **Show Slide Title**: Toggle to show/hide slide titles
7. **Before Text Label**: Text label for the "Before" section
8. **After Text Label**: Text label for the "After" section



#### Custom CSS Classes

If you use some tools like `Tailwindcss` or `unocss`, you can use this section. Or you can write a custom style file for this plugin. You can add custom CSS classes to various slider elements in the Settings page:

1. **Slider Container Class**: CSS classes for the main slider container
2. **Slide Class**: CSS classes for individual slides
3. **Images Container Class**: CSS classes for the images container
4. **Image Class**: CSS classes for slide images
5. **Slide Content Class**: CSS classes for slide content container
6. **Slide Header Class**: CSS classes for slide header container
7. **Icon Class**: CSS classes for slide icons
8. **Title Class**: CSS classes for slide titles
9. **Description Class**: CSS classes for slide descriptions

These classes will be applied to both individual slides and gallery slides.

### Shortcode Parameters

You can override the global settings in individual shortcodes:

```
[ch_before_after 
  id="123" 
  max_width="1000px" 
  width="90%" 
  show_title="true" 
  show_icon="false"
  container_class="my-custom-slider"
  slide_class="my-custom-slide"
  title_class="fancy-title"
]

[ch_gallery 
  id="456" 
  max_width="1200px" 
  width="100%" 
  show_gallery_title="true" 
  show_gallery_description="true" 
  show_slide_title="true" 
  show_slide_icon="true"
  container_class="my-gallery-style"
  slide_class="gallery-slide"
  icon_class="custom-icon"
]
```

### Example CSS Usage

Add your custom CSS to your theme's stylesheet or the WordPress Customizer:

```css
/* Example custom styling for slider elements */
.my-custom-slider {
  box-shadow: 0 0 10px rgba(0,0,0,0.1);
  border-radius: 10px;
  overflow: hidden;
}

.my-custom-slide {
  padding: 20px;
}

.fancy-title {
  color: #007bff;
  font-size: 24px;
  text-transform: uppercase;
}
```
## Third-Party Tools

This plugin utilizes the following third-party libraries:

### TwentyTwenty

The before-and-after slider functionality is powered by [TwentyTwenty](https://github.com/zurb/twentytwenty/), a visual diff tool developed by ZURB. This jQuery plugin makes it easy to compare two images by revealing one image over another with a draggable slider.

#### Features:
- Responsive design that works on all screen sizes
- Smooth animation for image comparison
- Customizable slider appearance
- Touch-enabled for mobile devices

The TwentyTwenty library is included with the plugin, so you don't need to install it separately.

### Usage with TwentyTwenty

The plugin automatically initializes the TwentyTwenty slider for all before-and-after image pairs. You can customize the appearance of the slider handle and other elements through the plugin settings or with custom CSS.

## Support

For support or feature requests, please contact us at chalist1@gmail.com. While I may not be able to respond to all emails immediately, I will make every effort to address your questions or update requests as soon as possible. 

**If you have ideas for improving the plugin, please share them with me so I can consider adding them in future updates.**

## Contributing

We welcome contributions to improve the CH Before & After plugin! If you have suggestions, bug reports, or want to contribute code, there are several ways to get involved:

### Creating Issues

If you encounter a bug or have a feature request:

1. Visit our [GitHub repository](https://github.com/chalist/ch-before-after/issues)
2. Click on "New Issue"
3. Select the appropriate issue template
4. Provide as much detail as possible, including:
   - Steps to reproduce (for bugs)
   - Expected behavior
   - Screenshots if applicable
   - WordPress version and theme information

### Pull Requests

Want to contribute code? Great! Here's how:

1. Fork the repository
2. Create a new branch for your feature or bugfix
3. Make your changes, following WordPress coding standards
4. Test thoroughly with different WordPress versions
5. Submit a pull request with a clear description of the changes

All pull requests will be reviewed as soon as possible. We appreciate detailed descriptions that explain the purpose and implementation of your changes.

### Development Guidelines

When contributing code, please follow these guidelines:

- Adhere to [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)
- Maintain compatibility with PHP 7.0+ and WordPress 5.0+
- Write clear, documented code with appropriate comments
- Include inline documentation for new functions and classes
- Test your changes in various environments before submitting

We appreciate your interest in making CH Before & After better for everyone!


## Donations

If you find this plugin useful and would like to support its continued development, consider making a donation. Your contributions help maintain and improve the plugin for everyone.

### Cryptocurrency Donations

I accept donations in the following cryptocurrencies:

| Cryptocurrency | Address |
|----------------|---------|
| Bitcoin (BTC) | `bc1pg0rtkcvet57fumpe3gxkyq28a39k8t4urk2q084ly7pcl7ukakms23yumj` |
| Ethereum (ETH) | `0xe4d6569afb59C8944594E99544bb7E3ea5391945` |
| Ton | `UQB8QIXJ8UoDxusXzfI1YxHgokmmOwTSCiCX4Mz_-3CLwfeL` |
| Doge  | `0xe4d6569afb59C8944594E99544bb7E3ea5391945` |
| Solana (SOL) | `2SDSajo7RwWsHMg5r7XDhAyzcKphwv2YBEmYKsNekUej` |

Your support is greatly appreciated and helps us dedicate more time to developing and maintaining this plugin. Thank you!
