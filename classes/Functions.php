<?php

if(!defined('ABSPATH')){
    exit;
}

class Functions{
    /**
     * @since 0.0.1
     * Functions constructor.
     */
    public function __construct()
    {
        add_action('init', array($this, 'init'));
        add_action( 'after_setup_theme', array($this, 'after_setup_theme'));
        add_action( 'widgets_init', array($this, 'widgets_init'));
        add_action( 'get_custom_logo', array($this, 'get_custom_logo'));
        add_action( 'wp_enqueue_scripts', array($this, 'wp_enqueue_scripts'));
        add_action( 'enqueue_block_editor_assets', array($this, 'gallery_twenty_block_editor_styles'), 1, 1 );
        add_filter( 'gallery_twenty_add_classic_editor_non_latin_styles', array($this, 'tiny_mce_before_init') );
        add_filter( 'gallery_twenty_add_classic_editor_customizer_styles', array($this, 'tiny_mce_before_init') );
        add_action( 'customize_preview_init', array($this, 'gallery_twenty_customize_preview_init') );
        add_action( 'customize_controls_enqueue_scripts', array($this, 'gallery_twenty_customize_controls_enqueue_scripts') );
        add_filter( 'the_content_more_link', array($this, 'gallery_twenty_read_more_tag') );
    }

    /**
     * @since 0.0.1
     * Fire on init Hook
     */
    public function init()
    {
        $this->nav_menus();
        $this->gallery_twenty_classic_editor_styles();
    }

    /**
     * @since 0.0.1
     * Enqueue classic editor styles.
     */
    public function gallery_twenty_classic_editor_styles() {

        $classic_editor_styles = array(
            '/assets/css/editor-style-classic.css',
        );

        add_editor_style( $classic_editor_styles );

    }


    /**
     * Output Customizer settings in the classic editor.
     * Adds styles to the head of the TinyMCE iframe. Kudos to @Otto42 for the original solution.
     *
     * @param array $mce_init TinyMCE styles.
     * @since 0.0.1
     * @return array $mce_init TinyMCE styles.
     */
    function gallery_twenty_add_classic_editor_customizer_styles( $mce_init ) {

        $styles = gallery_twenty_get_customizer_css( 'classic-editor' );

        if ( ! isset( $mce_init['content_style'] ) ) {
            $mce_init['content_style'] = $styles . ' ';
        } else {
            $mce_init['content_style'] .= ' ' . $styles . ' ';
        }

        return $mce_init;

    }

    /**
     * Output non-latin font styles in the classic editor.
     * Adds styles to the head of the TinyMCE iframe. Kudos to @Otto42 for the original solution.
     *
     * @param array $mce_init TinyMCE styles.
     * @since 0.0.1
     * @return array $mce_init TinyMCE styles.
     */

    function gallery_twenty_add_classic_editor_non_latin_styles( $mce_init ) {

        $styles = self::get_non_latin_css( 'classic-editor' );

        // Return if there are no styles to add.
        if ( ! $styles ) {
            return $mce_init;
        }

        if ( ! isset( $mce_init['content_style'] ) ) {
            $mce_init['content_style'] = $styles . ' ';
        } else {
            $mce_init['content_style'] .= ' ' . $styles . ' ';
        }

        return $mce_init;

    }

    /*
     * @since 0.0.1
     * Enquery Scripts
     */
    public function wp_enqueue_scripts(){
        $this->non_latin_languages();
    }

    /**
     * @since 0.0.1
     * Enqueue supplemental block editor styles.
     */
    function gallery_twenty_block_editor_styles() {

        $css_dependencies = array();

        // Enqueue the editor styles.
        wp_enqueue_style( 'gallery_twenty-block-editor-styles', get_theme_file_uri( '/assets/css/editor-style-block.css' ), $css_dependencies, wp_get_theme()->get( 'Version' ), 'all' );
        wp_style_add_data( 'gallery_twenty-block-editor-styles', 'rtl', 'replace' );

        // Add inline style from the Customizer.
        wp_add_inline_style( 'gallery_twenty-block-editor-styles', gallery_twenty_get_customizer_css( 'block-editor' ) );

        // Add inline style for non-latin fonts.
        wp_add_inline_style( 'gallery_twenty-block-editor-styles', self::get_non_latin_css( 'block-editor' ) );

        // Enqueue the editor script.
        wp_enqueue_script( 'gallery_twenty-block-editor-script', get_theme_file_uri( '/assets/js/editor-script-block.js' ), array( 'wp-blocks', 'wp-dom' ), wp_get_theme()->get( 'Version' ), true );
    }


    /**
     * @since 0.0.1
     * Enqueue non-latin language styles
     */

    function non_latin_languages() {
        $custom_css = self::get_non_latin_css( 'front-end' );

        if ( $custom_css ) {
            wp_add_inline_style( 'gallery_twenty-style', $custom_css );
        }
    }


    /**
     * @since 0.0.1
     * Register navigation menus uses wp_nav_menu in five places.
     */
    function nav_menus() {

        $locations = array(
            'primary'  => esc_html__( 'Desktop Horizontal Menu', 'gallery-twenty' ),
            'expanded' => esc_html__( 'Desktop Expanded Menu', 'gallery-twenty' ),
            'mobile'   => esc_html__( 'Mobile Menu', 'gallery-twenty' ),
            'footer'   => esc_html__( 'Footer Menu', 'gallery-twenty' ),
            'social'   => esc_html__( 'Social Menu', 'gallery-twenty' ),
        );

        register_nav_menus( $locations );
    }


    /**
     * @since 0.0.1
     * Fire on after_setup_theme Hook
     */
    function after_setup_theme()
    {
        $this->gallery_twenty_theme_support();
        $this->gallery_twenty_block_editor_settings();
    }


    /**
     * @since 0.0.1
     * Block Editor Settings.
     * Add custom colors and font sizes to the block editor.
     */
    function gallery_twenty_block_editor_settings() {

        // Block Editor Palette.
        $editor_color_palette = array(
            array(
                'name'  => esc_html__( 'Accent Color', 'gallery-twenty' ),
                'slug'  => 'accent',
                'color' => self::gallery_twenty_get_color_for_area( 'content', 'accent' ),
            ),
            array(
                'name'  => esc_html__( 'Primary', 'gallery-twenty' ),
                'slug'  => 'primary',
                'color' => self::gallery_twenty_get_color_for_area( 'content', 'text' ),
            ),
            array(
                'name'  => esc_html__( 'Secondary', 'gallery-twenty' ),
                'slug'  => 'secondary',
                'color' => self::gallery_twenty_get_color_for_area( 'content', 'secondary' ),
            ),
            array(
                'name'  => esc_html__( 'Subtle Background', 'gallery-twenty' ),
                'slug'  => 'subtle-background',
                'color' => self::gallery_twenty_get_color_for_area( 'content', 'borders' ),
            ),
        );

        // Add the background option.
        $background_color = get_theme_mod( 'background_color' );
        if ( ! $background_color ) {
            $background_color_arr = get_theme_support( 'custom-background' );
            $background_color     = $background_color_arr[0]['default-color'];
        }
        $editor_color_palette[] = array(
            'name'  => esc_html__( 'Background Color', 'gallery-twenty' ),
            'slug'  => 'background',
            'color' => '#' . $background_color,
        );

        // If we have accent colors, add them to the block editor palette.
        if ( $editor_color_palette ) {
            add_theme_support( 'editor-color-palette', $editor_color_palette );
        }

        // Block Editor Font Sizes.
        add_theme_support(
            'editor-font-sizes',
            array(
                array(
                    'name'      => _x( 'Small', 'Name of the small font size in the block editor', 'gallery-twenty' ),
                    'shortName' => _x( 'S', 'Short name of the small font size in the block editor.', 'gallery-twenty' ),
                    'size'      => 18,
                    'slug'      => 'small',
                ),
                array(
                    'name'      => _x( 'Regular', 'Name of the regular font size in the block editor', 'gallery-twenty' ),
                    'shortName' => _x( 'M', 'Short name of the regular font size in the block editor.', 'gallery-twenty' ),
                    'size'      => 21,
                    'slug'      => 'normal',
                ),
                array(
                    'name'      => _x( 'Large', 'Name of the large font size in the block editor', 'gallery-twenty' ),
                    'shortName' => _x( 'L', 'Short name of the large font size in the block editor.', 'gallery-twenty' ),
                    'size'      => 26.25,
                    'slug'      => 'large',
                ),
                array(
                    'name'      => _x( 'Larger', 'Name of the larger font size in the block editor', 'gallery-twenty' ),
                    'shortName' => _x( 'XL', 'Short name of the larger font size in the block editor.', 'gallery-twenty' ),
                    'size'      => 32,
                    'slug'      => 'larger',
                ),
            )
        );

        // If we have a dark background color then add support for dark editor style.
        // We can determine if the background color is dark by checking if the text-color is white.
        if ( '#ffffff' === strtolower( self::gallery_twenty_get_color_for_area( 'content', 'text' ) ) ) {
            add_theme_support( 'dark-editor-style' );
        }

    }


    /**
     * @since 0.0.1
     * Theme Support Functions
     */
    function gallery_twenty_theme_support() {

        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );

        // Custom background color.
        add_theme_support(
            'custom-background',
            array(
                'default-color' => 'ffffff',
            )
        );

        // Set content-width.

        global $content_width;
        if ( ! isset( $content_width ) ) {
            // phpcs:ignore WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedVariableFound
            $content_width = 580;
        }

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support( 'post-thumbnails' );

        // Set post thumbnail size.
        set_post_thumbnail_size( 1200, 9999 );

        // Add custom image size used in Cover Template.
        add_image_size( 'gallery_twenty-fullscreen', 1980, 9999 );

        // Custom logo.
        $logo_width  = 120;
        $logo_height = 90;

        // If the retina setting is active, double the recommended width and height.
        if ( get_theme_mod( 'retina_logo', false ) ) {
            $logo_width  = floor( $logo_width * 2 );
            $logo_height = floor( $logo_height * 2 );
        }

        add_theme_support(
            'custom-logo',
            array(
                'height'      => $logo_height,
                'width'       => $logo_width,
                'flex-height' => true,
                'flex-width'  => true,
            )
        );

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support( 'title-tag' );

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support(
            'html5',
            array(
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'script',
                'style',
            )
        );

        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on Gallery Twenty, use a find and replace
         * to change 'gallery-twenty' to the name of your theme in all the template files.
         */
        load_theme_textdomain( 'gallery-twenty' );

        // Add support for full and wide align images.
        add_theme_support( 'align-wide' );

        /*
         * Adds starter content to highlight the theme on fresh sites.
         * This is done conditionally to avoid loading the starter content on every
         * page load, as it is a one-off operation only needed once in the customizer.
         */
        if ( is_customize_preview() ) {
            // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
            require get_template_directory() . '/inc/starter-content.php';
            add_theme_support( 'starter-content', gallery_twenty_get_starter_content() );
        }

        // Add theme support for selective refresh for widgets.
        add_theme_support( 'customize-selective-refresh-widgets' );

        /*
         * Adds `async` and `defer` support for scripts registered or enqueued
         * by the theme.
         */
        add_filter( 'script_loader_tag', array( $this, 'filter_script_loader_tag' ), 10, 2 );

    }

    /**
     * Adds async/defer attributes to enqueued / registered scripts.
     *
     * If #12009 lands in WordPress, this function can no-op since it would be handled in core.
     *
     * @link https://core.trac.wordpress.org/ticket/12009
     * @since 0.0.1
     * @param string $tag    The script tag.
     * @param string $handle The script handle.
     * @return string Script HTML string.
     */
    function filter_script_loader_tag( $tag, $handle ) {
        foreach ( [ 'async', 'defer' ] as $attr ) {
            if ( ! wp_scripts()->get_data( $handle, $attr ) ) {
                continue;
            }
            // Prevent adding attribute when already added in #12009.
            if ( ! preg_match( ":\s$attr(=|>|\s):", $tag ) ) {
                $tag = preg_replace( ':(?=></script>):', " $attr", $tag, 1 );
            }
            // Only allow async or defer, not both.
            break;
        }
        return $tag;
    }

    /**
     * Get the information about the logo.
     * @since 0.0.1
     * @param string $html The HTML output from get_custom_logo (core function).
     *
     * @return string $html
     */
    function get_custom_logo( $html ) {

        $logo_id = get_theme_mod( 'custom_logo' );

        if ( ! $logo_id ) {
            return $html;
        }

        $logo = wp_get_attachment_image_src( $logo_id, 'full' );

        if ( $logo ) {
            // For clarity.
            $logo_width  = esc_attr( $logo[1] );
            $logo_height = esc_attr( $logo[2] );

            // If the retina logo setting is active, reduce the width/height by half.
            if ( get_theme_mod( 'retina_logo', false ) ) {
                $logo_width  = floor( $logo_width / 2 );
                $logo_height = floor( $logo_height / 2 );

                $search = array(
                    '/width=\"\d+\"/iU',
                    '/height=\"\d+\"/iU',
                );

                $replace = array(
                    "width=\"{$logo_width}\"",
                    "height=\"{$logo_height}\"",
                );

                // Add a style attribute with the height, or append the height to the style attribute if the style attribute already exists.
                if ( strpos( $html, ' style=' ) === false ) {
                    $search[]  = '/(src=)/';
                    $replace[] = "style=\"height: {$logo_height}px;\" src=";
                } else {
                    $search[]  = '/(style="[^"]*)/';
                    $replace[] = "$1 height: {$logo_height}px;";
                }

                $html = preg_replace( $search, $replace, $html );

            }
        }

        return $html;

    }

    /**
     * @since 0.0.1
     * Fire on widgets_init hook
     */
    function widgets_init()
    {
        $this->sidebar_registration();
    }


    /**
     * @since 0.0.1
     * Register widget areas.
     *
     * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
     */
    function sidebar_registration() {

        // Arguments used in all register_sidebar() calls.
        $shared_args = array(
            'before_title'  => '<h2 class="widget-title subheading heading-size-3">',
            'after_title'   => '</h2>',
            'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
            'after_widget'  => '</div></div>',
        );

        // Footer #1.
        register_sidebar(
            array_merge(
                $shared_args,
                array(
                    'name'        => esc_html__( 'Footer #1', 'gallery-twenty' ),
                    'id'          => 'sidebar-1',
                    'description' => esc_html__( 'Widgets in this area will be displayed in the first column in the footer.', 'gallery-twenty' ),
                )
            )
        );

        // Footer #2.
        register_sidebar(
            array_merge(
                $shared_args,
                array(
                    'name'        => esc_html__( 'Footer #2', 'gallery-twenty' ),
                    'id'          => 'sidebar-2',
                    'description' => esc_html__( 'Widgets in this area will be displayed in the second column in the footer.', 'gallery-twenty' ),
                )
            )
        );

    }

    /**
     * Get an array of elements.
     *
     * @since 0.0.1
     *
     * @return array
     */
    public static function gallery_twenty_get_elements_array() {

        // The array is formatted like this:
        // [key-in-saved-setting][sub-key-in-setting][css-property] = [elements].
        $elements = array(
            'content'       => array(
                'accent'     => array(
                    'color'            => array( '.color-accent', '.color-accent-hover:hover', '.color-accent-hover:focus', ':root .has-accent-color', '.has-drop-cap:not(:focus):first-letter', '.wp-block-button.is-style-outline', 'a' ),
                    'border-color'     => array( 'blockquote', '.border-color-accent', '.border-color-accent-hover:hover', '.border-color-accent-hover:focus' ),
                    'background-color' => array( 'button:not(.toggle)', '.button', '.faux-button', '.wp-block-button__link', '.wp-block-file .wp-block-file__button', 'input[type="button"]', 'input[type="reset"]', 'input[type="submit"]', '.bg-accent', '.bg-accent-hover:hover', '.bg-accent-hover:focus', ':root .has-accent-background-color', '.comment-reply-link' ),
                    'fill'             => array( '.fill-children-accent', '.fill-children-accent *' ),
                ),
                'background' => array(
                    'color'            => array( ':root .has-background-color', 'button', '.button', '.faux-button', '.wp-block-button__link', '.wp-block-file__button', 'input[type="button"]', 'input[type="reset"]', 'input[type="submit"]', '.wp-block-button', '.comment-reply-link', '.has-background.has-primary-background-color:not(.has-text-color)', '.has-background.has-primary-background-color *:not(.has-text-color)', '.has-background.has-accent-background-color:not(.has-text-color)', '.has-background.has-accent-background-color *:not(.has-text-color)' ),
                    'background-color' => array( ':root .has-background-background-color' ),
                ),
                'text'       => array(
                    'color'            => array( 'body', '.entry-title a', ':root .has-primary-color' ),
                    'background-color' => array( ':root .has-primary-background-color' ),
                ),
                'secondary'  => array(
                    'color'            => array( 'cite', 'figcaption', '.wp-caption-text', '.post-meta', '.entry-content .wp-block-archives li', '.entry-content .wp-block-categories li', '.entry-content .wp-block-latest-posts li', '.wp-block-latest-comments__comment-date', '.wp-block-latest-posts__post-date', '.wp-block-embed figcaption', '.wp-block-image figcaption', '.wp-block-pullquote cite', '.comment-metadata', '.comment-respond .comment-notes', '.comment-respond .logged-in-as', '.pagination .dots', '.entry-content hr:not(.has-background)', 'hr.styled-separator', ':root .has-secondary-color' ),
                    'background-color' => array( ':root .has-secondary-background-color' ),
                ),
                'borders'    => array(
                    'border-color'        => array( 'pre', 'fieldset', 'input', 'textarea', 'table', 'table *', 'hr' ),
                    'background-color'    => array( 'caption', 'code', 'code', 'kbd', 'samp', '.wp-block-table.is-style-stripes tbody tr:nth-child(odd)', ':root .has-subtle-background-background-color' ),
                    'border-bottom-color' => array( '.wp-block-table.is-style-stripes' ),
                    'border-top-color'    => array( '.wp-block-latest-posts.is-grid li' ),
                    'color'               => array( ':root .has-subtle-background-color' ),
                ),
            ),
            'header-footer' => array(
                'accent'     => array(
                    'color'            => array( 'body:not(.overlay-header) .primary-menu > li > a', 'body:not(.overlay-header) .primary-menu > li > .icon', '.modal-menu a', '.footer-menu a, .footer-widgets a', '#site-footer .wp-block-button.is-style-outline', '.wp-block-pullquote:before', '.singular:not(.overlay-header) .entry-header a', '.archive-header a', '.header-footer-group .color-accent', '.header-footer-group .color-accent-hover:hover' ),
                    'background-color' => array( '.social-icons a', '#site-footer button:not(.toggle)', '#site-footer .button', '#site-footer .faux-button', '#site-footer .wp-block-button__link', '#site-footer .wp-block-file__button', '#site-footer input[type="button"]', '#site-footer input[type="reset"]', '#site-footer input[type="submit"]' ),
                ),
                'background' => array(
                    'color'            => array( '.social-icons a', 'body:not(.overlay-header) .primary-menu ul', '.header-footer-group button', '.header-footer-group .button', '.header-footer-group .faux-button', '.header-footer-group .wp-block-button:not(.is-style-outline) .wp-block-button__link', '.header-footer-group .wp-block-file__button', '.header-footer-group input[type="button"]', '.header-footer-group input[type="reset"]', '.header-footer-group input[type="submit"]' ),
                    'background-color' => array( '#site-header', '.footer-nav-widgets-wrapper', '#site-footer', '.menu-modal', '.menu-modal-inner', '.search-modal-inner', '.archive-header', '.singular .entry-header', '.singular .featured-media:before', '.wp-block-pullquote:before' ),
                ),
                'text'       => array(
                    'color'               => array( '.header-footer-group', 'body:not(.overlay-header) #site-header .toggle', '.menu-modal .toggle' ),
                    'background-color'    => array( 'body:not(.overlay-header) .primary-menu ul' ),
                    'border-bottom-color' => array( 'body:not(.overlay-header) .primary-menu > li > ul:after' ),
                    'border-left-color'   => array( 'body:not(.overlay-header) .primary-menu ul ul:after' ),
                ),
                'secondary'  => array(
                    'color' => array( '.site-description', 'body:not(.overlay-header) .toggle-inner .toggle-text', '.widget .post-date', '.widget .rss-date', '.widget_archive li', '.widget_categories li', '.widget cite', '.widget_pages li', '.widget_meta li', '.widget_nav_menu li', '.powered-by-wordpress', '.to-the-top', '.singular .entry-header .post-meta', '.singular:not(.overlay-header) .entry-header .post-meta a' ),
                ),
                'borders'    => array(
                    'border-color'     => array( '.header-footer-group pre', '.header-footer-group fieldset', '.header-footer-group input', '.header-footer-group textarea', '.header-footer-group table', '.header-footer-group table *', '.footer-nav-widgets-wrapper', '#site-footer', '.menu-modal nav *', '.footer-widgets-outer-wrapper', '.footer-top' ),
                    'background-color' => array( '.header-footer-group table caption', 'body:not(.overlay-header) .header-inner .toggle-wrapper::before' ),
                ),
            ),
        );

        /**
         * Filters Gallery Twenty theme elements
         *
         * @since 1.0.0
         *
         * @param array Array of elements
         */
        return apply_filters( 'gallery_twenty_get_elements_array', $elements );
    }


    /**
     * Enqueue scripts for the customizer preview.
     *
     * @since 0.0.1
     *
     * @return void
     */
    function gallery_twenty_customize_preview_init() {
        $theme_version = wp_get_theme()->get( 'Version' );

        wp_enqueue_script( 'gallery_twenty-customize-preview', get_theme_file_uri( '/assets/js/customize-preview.js' ), array( 'customize-preview', 'customize-selective-refresh', 'jquery' ), $theme_version, true );
        wp_localize_script( 'gallery_twenty-customize-preview', 'galleryTwentyBgColors', $this->gallery_twenty_get_customizer_color_vars() );
        wp_localize_script( 'gallery_twenty-customize-preview', 'galleryTwentyPreviewEls', $this->gallery_twenty_get_elements_array() );

        wp_add_inline_script(
            'gallery_twenty-customize-preview',
            sprintf(
                'wp.customize.selectiveRefresh.partialConstructor[ %1$s ].prototype.attrs = %2$s;',
                wp_json_encode( 'cover_opacity' ),
                wp_json_encode( gallery_twenty_customize_opacity_range() )
            )
        );
    }


    /**
     * Returns an array of variables for the customizer preview.
     *
     * @since 0.0.1
     *
     * @return array
     */
    function gallery_twenty_get_customizer_color_vars() {
        $colors = array(
            'content'       => array(
                'setting' => 'background_color',
            ),
            'header-footer' => array(
                'setting' => 'header_footer_background_color',
            ),
        );
        return $colors;
    }



    /**
     * Get accessible color for an area.
     *
     * @since 0.0.1
     *
     * @param string $area The area we want to get the colors for.
     * @param string $context Can be 'text' or 'accent'.
     * @return string Returns a HEX color.
     */
    public static function gallery_twenty_get_color_for_area( $area = 'content', $context = 'text' ) {

        // Get the value from the theme-mod.
        $settings = get_theme_mod(
            'accent_accessible_colors',
            array(
                'content'       => array(
                    'text'      => '#000000',
                    'accent'    => '#cd2653',
                    'secondary' => '#6d6d6d',
                    'borders'   => '#dcd7ca',
                ),
                'header-footer' => array(
                    'text'      => '#000000',
                    'accent'    => '#cd2653',
                    'secondary' => '#6d6d6d',
                    'borders'   => '#dcd7ca',
                ),
            )
        );

        // If we have a value return it.
        if ( isset( $settings[ $area ] ) && isset( $settings[ $area ][ $context ] ) ) {
            return $settings[ $area ][ $context ];
        }

        // Return false if the option doesn't exist.
        return false;
    }

    /**
     * Enqueues scripts for customizer controls & settings.
     *
     * @since 0.0.1
     *
     * @return void
     */
    function gallery_twenty_customize_controls_enqueue_scripts() {
        $theme_version = wp_get_theme()->get( 'Version' );

        // Add main customizer js file.
        wp_enqueue_script( 'gallery_twenty-customize', get_template_directory_uri() . '/assets/js/customize.js', array( 'jquery' ), $theme_version, false );

        // Add script for color calculations.
        wp_enqueue_script( 'gallery_twenty-color-calculations', get_template_directory_uri() . '/assets/js/color-calculations.js', array( 'wp-color-picker' ), $theme_version, false );

        // Add script for controls.
        wp_enqueue_script( 'gallery_twenty-customize-controls', get_template_directory_uri() . '/assets/js/customize-controls.js', array( 'gallery_twenty-color-calculations', 'customize-controls', 'underscore', 'jquery' ), $theme_version, false );
        wp_localize_script( 'gallery_twenty-customize-controls', 'galleryTwentyBgColors', $this->gallery_twenty_get_customizer_color_vars() );
    }

    /**
     * Get custom CSS.
     *
     * Return CSS for non-latin language, if available, or null
     *
     * @param string $type Whether to return CSS for the "front-end", "block-editor" or "classic-editor".
     * @since 0.0.1
     * @return void
     */
    public static function get_non_latin_css( $type = 'front-end' ) {

        // Fetch users locale.
        $locale = get_bloginfo( 'language' );

        // Define fallback fonts for non-latin languages.
        $font_family = apply_filters(
            'gallery_twenty_get_localized_font_family_types',
            array(

                // Arabic.
                'ar'    => array( 'Tahoma', 'Arial', 'sans-serif' ),
                'ary'   => array( 'Tahoma', 'Arial', 'sans-serif' ),
                'azb'   => array( 'Tahoma', 'Arial', 'sans-serif' ),
                'ckb'   => array( 'Tahoma', 'Arial', 'sans-serif' ),
                'fa-IR' => array( 'Tahoma', 'Arial', 'sans-serif' ),
                'haz'   => array( 'Tahoma', 'Arial', 'sans-serif' ),
                'ps'    => array( 'Tahoma', 'Arial', 'sans-serif' ),

                // Chinese Simplified (China) - Noto Sans SC.
                'zh-CN' => array( '\'PingFang SC\'', '\'Helvetica Neue\'', '\'Microsoft YaHei New\'', '\'STHeiti Light\'', 'sans-serif' ),

                // Chinese Traditional (Taiwan) - Noto Sans TC.
                'zh-TW' => array( '\'PingFang TC\'', '\'Helvetica Neue\'', '\'Microsoft YaHei New\'', '\'STHeiti Light\'', 'sans-serif' ),

                // Chinese (Hong Kong) - Noto Sans HK.
                'zh-HK' => array( '\'PingFang HK\'', '\'Helvetica Neue\'', '\'Microsoft YaHei New\'', '\'STHeiti Light\'', 'sans-serif' ),

                // Cyrillic.
                'bel'   => array( '\'Helvetica Neue\'', 'Helvetica', '\'Segoe UI\'', 'Arial', 'sans-serif' ),
                'bg-BG' => array( '\'Helvetica Neue\'', 'Helvetica', '\'Segoe UI\'', 'Arial', 'sans-serif' ),
                'kk'    => array( '\'Helvetica Neue\'', 'Helvetica', '\'Segoe UI\'', 'Arial', 'sans-serif' ),
                'mk-MK' => array( '\'Helvetica Neue\'', 'Helvetica', '\'Segoe UI\'', 'Arial', 'sans-serif' ),
                'mn'    => array( '\'Helvetica Neue\'', 'Helvetica', '\'Segoe UI\'', 'Arial', 'sans-serif' ),
                'ru-RU' => array( '\'Helvetica Neue\'', 'Helvetica', '\'Segoe UI\'', 'Arial', 'sans-serif' ),
                'sah'   => array( '\'Helvetica Neue\'', 'Helvetica', '\'Segoe UI\'', 'Arial', 'sans-serif' ),
                'sr-RS' => array( '\'Helvetica Neue\'', 'Helvetica', '\'Segoe UI\'', 'Arial', 'sans-serif' ),
                'tt-RU' => array( '\'Helvetica Neue\'', 'Helvetica', '\'Segoe UI\'', 'Arial', 'sans-serif' ),
                'uk'    => array( '\'Helvetica Neue\'', 'Helvetica', '\'Segoe UI\'', 'Arial', 'sans-serif' ),

                // Devanagari.
                'bn-BD' => array( 'Arial', 'sans-serif' ),
                'hi-IN' => array( 'Arial', 'sans-serif' ),
                'mr'    => array( 'Arial', 'sans-serif' ),
                'ne-NP' => array( 'Arial', 'sans-serif' ),

                // Greek.
                'el'    => array( '\'Helvetica Neue\', Helvetica, Arial, sans-serif' ),

                // Gujarati.
                'gu'    => array( 'Arial', 'sans-serif' ),

                // Hebrew.
                'he-IL' => array( '\'Arial Hebrew\'', 'Arial', 'sans-serif' ),

                // Japanese.
                'ja'    => array( 'sans-serif' ),

                // Korean.
                'ko-KR' => array( '\'Apple SD Gothic Neo\'', '\'Malgun Gothic\'', '\'Nanum Gothic\'', 'Dotum', 'sans-serif' ),

                // Thai.
                'th'    => array( '\'Sukhumvit Set\'', '\'Helvetica Neue\'', 'Helvetica', 'Arial', 'sans-serif' ),

                // Vietnamese.
                'vi'    => array( '\'Libre Franklin\'', 'sans-serif' ),

            )
        );

        // Return if the selected language has no fallback fonts.
        if ( empty( $font_family[ $locale ] ) ) {
            return;
        }

        // Define elements to apply fallback fonts to.
        $elements = apply_filters(
            'gallery_twenty_get_localized_font_family_elements',
            array(
                'front-end'      => array( 'body', 'input', 'textarea', 'button', '.button', '.faux-button', '.wp-block-button__link', '.wp-block-file__button', '.has-drop-cap:not(:focus)::first-letter', '.has-drop-cap:not(:focus)::first-letter', '.entry-content .wp-block-archives', '.entry-content .wp-block-categories', '.entry-content .wp-block-cover-image', '.entry-content .wp-block-latest-comments', '.entry-content .wp-block-latest-posts', '.entry-content .wp-block-pullquote', '.entry-content .wp-block-quote.is-large', '.entry-content .wp-block-quote.is-style-large', '.entry-content .wp-block-archives *', '.entry-content .wp-block-categories *', '.entry-content .wp-block-latest-posts *', '.entry-content .wp-block-latest-comments *', '.entry-content p', '.entry-content ol', '.entry-content ul', '.entry-content dl', '.entry-content dt', '.entry-content cite', '.entry-content figcaption', '.entry-content .wp-caption-text', '.comment-content p', '.comment-content ol', '.comment-content ul', '.comment-content dl', '.comment-content dt', '.comment-content cite', '.comment-content figcaption', '.comment-content .wp-caption-text', '.widget_text p', '.widget_text ol', '.widget_text ul', '.widget_text dl', '.widget_text dt', '.widget-content .rssSummary', '.widget-content cite', '.widget-content figcaption', '.widget-content .wp-caption-text' ),
                'block-editor'   => array( '.editor-styles-wrapper > *', '.editor-styles-wrapper p', '.editor-styles-wrapper ol', '.editor-styles-wrapper ul', '.editor-styles-wrapper dl', '.editor-styles-wrapper dt', '.editor-post-title__block .editor-post-title__input', '.editor-styles-wrapper .wp-block h1', '.editor-styles-wrapper .wp-block h2', '.editor-styles-wrapper .wp-block h3', '.editor-styles-wrapper .wp-block h4', '.editor-styles-wrapper .wp-block h5', '.editor-styles-wrapper .wp-block h6', '.editor-styles-wrapper .has-drop-cap:not(:focus)::first-letter', '.editor-styles-wrapper cite', '.editor-styles-wrapper figcaption', '.editor-styles-wrapper .wp-caption-text' ),
                'classic-editor' => array( 'body#tinymce.wp-editor', 'body#tinymce.wp-editor p', 'body#tinymce.wp-editor ol', 'body#tinymce.wp-editor ul', 'body#tinymce.wp-editor dl', 'body#tinymce.wp-editor dt', 'body#tinymce.wp-editor figcaption', 'body#tinymce.wp-editor .wp-caption-text', 'body#tinymce.wp-editor .wp-caption-dd', 'body#tinymce.wp-editor cite', 'body#tinymce.wp-editor table' ),
            )
        );

        // Return if the specified type doesn't exist.
        if ( empty( $elements[ $type ] ) ) {
            return;
        }

        // Return the specified styles.
        return gallery_twenty_generate_css( implode( ',', $elements[ $type ] ), 'font-family', implode( ',', $font_family[ $locale ] ), null, null, false );

    }

    /**
     * Overwrite default more tag with styling and screen reader markup.
     *
     * @param string $html The default output HTML for the more tag.
     * @since 0.0.1
     * @return string $html
     */
    function gallery_twenty_read_more_tag( $html ) {
        return preg_replace( '/<a(.*)>(.*)<\/a>/iU', sprintf( '<div class="read-more-button-wrap"><a$1><span class="faux-button">$2</span> <span class="screen-reader-text">"%1$s"</span></a></div>', get_the_title( get_the_ID() ) ), $html );
    }

}
