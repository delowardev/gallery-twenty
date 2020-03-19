<?php

if(!defined('ABSPATH')){
    exit;
}

class Assets{
    public function __construct()
    {
        add_action( 'wp_enqueue_scripts', array($this, 'wp_enqueue_scripts'));
        add_action( 'wp_print_footer_scripts', array($this, 'wp_print_footer_scripts'));
    }

    /**
     * Fire on wp_enqueue_scripts Hook
     */
    public function wp_enqueue_scripts()
    {
        $this->register_styles();
        $this->register_scripts();
    }/**
 * Register & Enqueue Styles
 */

    public function register_styles()
    {
        wp_enqueue_style( 'gallery_twenty-style', get_stylesheet_uri(), array(), GT_VERSION );
        wp_enqueue_style( 'gallery_twenty-extend', get_template_directory_uri() . '/assets/css/extend.css', array(), GT_VERSION );
        wp_style_add_data( 'gallery_twenty-style', 'rtl', 'replace' );

        // Add output of Customizer settings as inline style.
        wp_add_inline_style( 'gallery_twenty-style', gallery_twenty_get_customizer_css( 'front-end' ) );

        // Add print CSS.
        wp_enqueue_style( 'gallery_twenty-print-style', get_template_directory_uri() . '/print.css', null, GT_VERSION, 'print' );

    }

    /**
     * Register & Enqueue Scripts
     */
    public function register_scripts()
    {
        if ( ( ! is_admin() ) && is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
        wp_enqueue_script( 'gallery_twenty-js', get_template_directory_uri() . '/assets/js/index.js', array(), GT_VERSION, false );
        wp_script_add_data( 'gallery_twenty-js', 'async', true );
    }

    /**
     * Print Footer Scripts
     */
    public function wp_print_footer_scripts()
    {
        $this->skip_link_focus_fix();
    }

    /**
     * Fix skip link focus in IE11.
     *
     * This does not enqueue the script because it is tiny and because it is only for IE11,
     * thus it does not warrant having an entire dedicated blocking script being loaded.
     *
     * @link https://git.io/vWdr2
     */
    public function skip_link_focus_fix() {
        // The following is minified via `terser --compress --mangle -- assets/js/skip-link-focus-fix.js`.
        ?>
        <script>
            /(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
        </script>
        <?php
    }



}
