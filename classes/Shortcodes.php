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
         * @var $class
         * @var $pagination
         */
        extract(shortcode_atts(array(
            'column'        => get_theme_mod('gallery_column', '4'),
            'class'         => 'section-inner',
            'pagination'    => 'true'
        ), $atts));


        $args = array(
            'post_type' => 'post',
            'paged'     => is_front_page() ? get_query_var('page') : get_query_var('paged')
        );
        query_posts($args);
        ob_start();
        if(have_posts()){
            echo gutils()::gallery_markup($column, $class);
        }
        if('true' === $pagination){
            get_template_part('template-parts/pagination');
        }
        wp_reset_query();
        return ob_get_clean();
    }


}
