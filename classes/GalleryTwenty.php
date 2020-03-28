<?php

if(!defined('ABSPATH')){
    exit;
}

final class GalleryTwenty{
    /**
     * @since 0.0.1
     * Theme Version
     * @var string
     */
    static $instance = false;
    public $assets;
    public $functions;
    public $shortcodes;
    public $utils;

    /**
     * @since 0.0.1
     * GalleryTwenty constructor.
     */
    public function __construct()
    {
        $this->define_constants();
        $this->includes();
        $this->load_classess();
        $this->add_filter();

    }

    public function add_filter()
    {
        add_filter('pre_get_posts', array($this, 'pre_get_posts'));
    }

    public function pre_get_posts($query)
    {
        if ($query->is_search) {
            $query->set('post_type', 'post');
        }
        return $query;
    }

    /**
     * @since 0.0.1
     * Load External Classes
     */
    public function load_classess()
    {
        $this->assets = new Assets();
        $this->functions = new Functions();
        $this->shortcodes = new Shortcodes();
        $this->utils = new Utils();
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
     * @since 0.0.1
     * Define Global Constants
     */
    public function define_constants()
    {
        define('GT_VERSION', wp_get_theme()->get( 'Version' ));
        define('GT_FILE', __FILE__);
        define('GT_DIR', get_template_directory());
        define('GT_URL', get_template_directory_uri());
        define('GT_ASSETS', GT_URL. '/assets');
    }

    /**
     * @since 0.0.1
     * Include Required Files
     */
    public function includes()
    {
        require GT_DIR . '/classes/Assets.php';
        require GT_DIR . '/classes/Functions.php';
        require GT_DIR . '/inc/template-tags.php';
        require GT_DIR . '/classes/SVGIcons.php';
        require GT_DIR . '/inc/svg-icons.php';
        require GT_DIR . '/classes/Customize.php';
        require GT_DIR . '/classes/SeparatorControl.php';
        require GT_DIR . '/classes/WalkerComment.php';
        require GT_DIR . '/classes/WalkerPage.php';
        require GT_DIR . '/inc/custom-css.php';
        require GT_DIR . '/classes/Shortcodes.php';
        require GT_DIR . '/classes/Utils.php';
    }

}

/**
 * Initilize Main Class
 * @return mixed
 */
// phpcs:ignore WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedFunctionFound
function gallery_twenty(){
    return GalleryTwenty::init();
}

/**
 * Utility Class
 * @return mixed
 */
// phpcs:ignore WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedFunctionFound
function gutils(){
    return Utils::init();
}

gallery_twenty();
