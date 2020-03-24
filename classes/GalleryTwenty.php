<?php

if(!defined('ABSPATH')){
    exit;
}

final class GalleryTwenty{
    /**
     * Theme Version
     * @var string
     */
    static $instance = false;
    public $assets;
    public $functions;

    public function __construct()
    {
        $this->define_constants();
        $this->includes();
        $this->load_classess();


    }

    /**
     * Load External Classes
     */
    public function load_classess()
    {
        $this->assets = new Assets();
        $this->functions = new Functions();
    }

    /**
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
     * Include Required Files
     */
    public function includes()
    {
        require GT_DIR . '/classes/Assets.php';
        require GT_DIR . '/classes/Functions.php';
        require GT_DIR . '/inc/template-tags.php';
        // Handle SVG icons.
        require GT_DIR . '/classes/SVGIcons.php';
        require GT_DIR . '/inc/svg-icons.php';
        // Handle Customizer settings.
        require GT_DIR . '/classes/Customize.php';
        // Require Separator Control class.
        require GT_DIR . '/classes/SeparatorControl.php';
        // Custom comment walker.
        require GT_DIR . '/classes/WalkerComment.php';
        // Custom page walker.
        require GT_DIR . '/classes/WalkerPage.php';
        // Custom CSS.
        require GT_DIR . '/inc/custom-css.php';
    }



}

function gallery_twenty(){
    return GalleryTwenty::init();
}

gallery_twenty();

