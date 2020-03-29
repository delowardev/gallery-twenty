<?php

if(!defined('ABSPATH')){
    exit;
}

class Shortcodes{

    static $instance = false;

    public function __construct()
    {
        $this->add_shortcode();
    }

    /**
     * @since 0.0.1
     * Initialize Object
     * @return mixed
     */
    public static function init()
    {
        if(!self::$instance){
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Add all shortcode
     */
    public function add_shortcode()
    {
        // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.plugin_territory_add_shortcode
        add_shortcode('gallery', array($this, 'gallery_shortcode'));
    }

    /**
     * Gallery shortcode Function
     * @param $atts
     * @param null $content
     * @return false|string
     */
    public function gallery_shortcode($atts, $content = null) {
        /**
         * @var $column
         * @var $classes
         * @var $gutter
         * @var $disable_heading
         * @var $meta_position
         * @var $meta
         * @var $heading_size
         * @var $heading_ellipsis
         * @var $pagination
         * @var $post_status
         * @var $posts_per_page
         */
        extract(shortcode_atts(array(
            'column'            => get_theme_mod('gallery_column', '4'),
            'classes'           => null,
            'gutter'            => null,
            'disable_heading'   => null,
            'meta_position'     => null,
            'meta'              => array(),
            'heading_size'      => null,
            'heading_ellipsis'  => null,
            'pagination'        => 'true',
            'post_status'       => 'publish',
            'posts_per_page'    => get_option('posts_per_page'),
        ), $atts));


        if(!empty($meta)){
            $meta = explode(',', $meta);
            $meta = array_map('trim', $meta);
        }


        $args = array(
            'post_type' => 'post',
            'paged'     => is_front_page() ? get_query_var('page') : get_query_var('paged'),
            'post_status' => $post_status,
            'posts_per_page' => $posts_per_page
        );

        query_posts($args);
        ob_start();
        if(have_posts()){
            gallery_twenty_utils()::gallery_markup($column, $classes, $gutter, $disable_heading, $meta_position, $meta, $heading_size, $heading_ellipsis);
        }
        if('true' === $pagination){
            get_template_part('template-parts/pagination');
        }
        wp_reset_query();
        return ob_get_clean();
    }


}
