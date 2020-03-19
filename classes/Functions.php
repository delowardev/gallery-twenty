<?php

if(!defined('ABSPATH')){
    exit;
}

class Functions{
    public function __construct()
    {
        add_action('init', array($this, 'init'));
        add_action( 'after_setup_theme', array($this, 'after_setup_theme'));
        add_action('widgets_init', array($this, 'widgets_init'));
        add_action('get_custom_logo', array($this, 'get_custom_logo'));
    }
    /**
     * Fire on init Hook
     */
    public function init()
    {
        $this->nav_menus();
    }

    /**
     * Register navigation menus uses wp_nav_menu in five places.
     */
    function nav_menus() {

        $locations = array(
            'primary'  => __( 'Desktop Horizontal Menu', 'gallery-twenty' ),
            'expanded' => __( 'Desktop Expanded Menu', 'gallery-twenty' ),
            'mobile'   => __( 'Mobile Menu', 'gallery-twenty' ),
            'footer'   => __( 'Footer Menu', 'gallery-twenty' ),
            'social'   => __( 'Social Menu', 'gallery-twenty' ),
        );

        register_nav_menus( $locations );
    }


    /**
     * Fire on after_setup_theme Hook
     */
    public function after_setup_theme()
    {
        $this->gallery_twenty_theme_support();
    }


    /**
     * Theme Support Functions
     */
    public function gallery_twenty_theme_support() {

        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );

        // Custom background color.
        add_theme_support(
            'custom-background',
            array(
                'default-color' => 'f5efe0',
            )
        );

        // Set content-width.
        global $content_width;
        if ( ! isset( $content_width ) ) {
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
     *
     * @param string $tag    The script tag.
     * @param string $handle The script handle.
     * @return string Script HTML string.
     */
    public function filter_script_loader_tag( $tag, $handle ) {
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
     *
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
     * Fire on widgets_init hook
     */
    public function widgets_init()
    {
        $this->sidebar_registration();
    }


    /**
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
                    'name'        => __( 'Footer #1', 'gallery-twenty' ),
                    'id'          => 'sidebar-1',
                    'description' => __( 'Widgets in this area will be displayed in the first column in the footer.', 'gallery-twenty' ),
                )
            )
        );

        // Footer #2.
        register_sidebar(
            array_merge(
                $shared_args,
                array(
                    'name'        => __( 'Footer #2', 'gallery-twenty' ),
                    'id'          => 'sidebar-2',
                    'description' => __( 'Widgets in this area will be displayed in the second column in the footer.', 'gallery-twenty' ),
                )
            )
        );

    }


}
