<?php

if(!defined('ABSPATH')){
    exit;
}

class Utils{

    static $instance = false;


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
     * Gallery Item Markup
     * @param $column
     * @param $disable_heading
     * @param $meta_position
     * @param $meta
     * @return false|string
     */
    public static function gallery_item_markup($column = null, $disable_heading = false, $meta_position = 'disable', $meta = array('author', 'date')){
        if($column === null){
            $column = get_theme_mod('gallery_column', '4');
        }
        if('post' !== get_post_type()) return '';
//        ob_start();
        ?>
            <div class="col-sm-6 col-md-<?php echo esc_attr($column) ?>">
                <article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
                    <figure class="gallery-featured-media">
                        <a title="<?php the_title(); ?>" class="gallery-featured-media-inner" href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail();?>
                        </a>
                    </figure>
                    <?php if($disable_heading !== 'true') { ?>
                        <div class="gallery-post-content <?php echo esc_attr('meta-position-' . $meta_position) ?>">
                            <h2 class="gallery-post-title"><a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <?php if($meta_position !== 'disable' && count($meta)) {
                                ?>
                                    <ul class="gallery-post-meta">
                                        <?php if(in_array('author', $meta)) { ?>
                                            <li class="post-author meta-wrapper">
                                            <span class="meta-icon">
                                                <span class="screen-reader-text">
                                                    <?php esc_html_e( 'Post author', 'gallery-twenty' ); ?>
                                                </span>
                                                <?php gallery_twenty_the_theme_svg( 'user' ); ?>
                                            </span>
                                                <span class="meta-text">
                                                <?php
                                                printf(
                                                /* translators: %s: Author name */
                                                    esc_html__( 'By %s', 'gallery-twenty' ),
                                                    '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author_meta( 'display_name' ) ) . '</a>'
                                                );
                                                ?>
                                            </span>
                                            </li>
                                        <?php } ?>
                                        <?php if(in_array('date', $meta)) { ?>
                                            <li class="post-date meta-wrapper">
                                            <span class="meta-icon">
                                                <span class="screen-reader-text"><?php esc_html_e( 'Post date', 'gallery-twenty' ); ?></span>
                                                <?php gallery_twenty_the_theme_svg( 'calendar' ); ?>
                                            </span>
                                                <span class="meta-text">
                                                <a href="<?php the_permalink(); ?>"><?php the_time( get_option( 'date_format' ) ); ?></a>
                                            </span>
                                            </li>
                                        <?php } ?>
                                        <?php if(in_array('category', $meta)) { ?>
                                            <li class="post-categories meta-wrapper">
                                            <span class="meta-icon">
                                                <span class="screen-reader-text"><?php esc_html_e( 'Categories', 'gallery-twenty' ); ?></span>
                                                <?php gallery_twenty_the_theme_svg( 'folder' ); ?>
                                            </span>
                                                <span class="meta-text">
                                                <?php esc_html_x( 'In', 'A string that is output before one or more categories', 'gallery-twenty' ); ?> <?php the_category( ', ' ); ?>
                                            </span>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                <?php
                            } ?>
                        </div>
                    <?php } ?>
                </article>
            </div>
        <?php
//        return ob_get_clean();
    }

    /**
     * Default Gallery Markups
     * @param string $column
     * @param string $gutter
     * @param string|array $classes
     * @param boolean|null $disable_heading
     * @param string|null $meta_position
     * @param string|null $heading_size
     * @param array $meta
     * @param string $heading_ellipsis
     * @return false|string
     */
    public static function gallery_markup(
        $column = null,
        $classes = array('section-inner'),
        $gutter = null,
        $disable_heading = null,
        $meta_position = null,
        $meta = array(),
        $heading_size = null,
        $heading_ellipsis = null
    ){
        /*-- get column count from theme option --*/
        if($column === null){
            $column = get_theme_mod('gallery_column', '4');
        }

        /*-- combine custom classes --*/
        if(is_array($classes)){
            $classes = implode(' ', $classes);
        }

        /*-- get style class from theme option --*/
        if(strpos($classes, 'gallery-style-') === false){
            $classes .= ' ' . get_theme_mod('gallery_style', 'gallery-style-1');
        }

        /*-- get gutter class from theme option --*/
        if($gutter === null){
            $gutter = get_theme_mod('gallery_column_gutter', 'true');
            $gutter = $gutter === 'false' ? 'no-gutters' : '';
        }

        /*-- get heading enable/disable from theme option --*/
        if($disable_heading === null){
            $disable_heading = get_theme_mod('gallery_disable_heading', 'false');
        }

        /*-- get meta position from theme option --*/
        if($meta_position === null){
            $meta_position = get_theme_mod('gallery_meta_position', 'disable');
        }

        /*-- get enabled meta from theme option --*/
        if(!count($meta)){
            if(get_theme_mod('gallery_column_meta_date', true)){
                $meta[] = 'date';
            }
            if(get_theme_mod('gallery_column_meta_author', true)){
                $meta[] = 'author';
            }
            if(get_theme_mod('gallery_column_meta_category', false)){
                $meta[] = 'category';
            }
        }

        /*-- get heading font size from theme option --*/
        if($heading_size === null){
            $heading_size = get_theme_mod('gallery_heading_size', 24);
        }

        if(empty($heading_size)){
            $heading_size = 24;
        }

        /** -- make heading text overflow ellipsis -- */

        if($heading_ellipsis === null){
            $heading_ellipsis = get_theme_mod('gallery_heading_ellipsis', 'true');
        }

        $heading_ellipsis = $heading_ellipsis !== 'false' ? 'nowrap' : 'normal';


//        ob_start();
        ?>
            <div class="gallery-container <?php echo esc_attr($classes) ?>" style="--gallery-heading-size: <?php echo esc_attr($heading_size . 'px'); ?>; --gallery-heading-whitespace: <?php echo esc_attr($heading_ellipsis); ?>">
                <div class="row gallery-row <?php echo esc_attr($gutter); ?>">
                    <?php
                        while(have_posts()){
                            the_post();
                            self::gallery_item_markup(esc_html($column), esc_html($disable_heading), esc_html($meta_position), $meta);
                        }
                    ?>
                </div>
            </div>
        <?php
//        return ob_get_clean();
    }

}
