<?php
/**
 * The default template for displaying content
 *
 * Used for both singular and index.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Gallery_Twenty
 * @since 1.0.0
 */

?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

        <?php


        if(!is_single()){
            // code here

            ?>
                <figure class="gallery-featured-media">
                    <a class="featured-media-inner" href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail();?>
                    </a><!-- .featured-media-inner -->
                </figure><!-- .featured-media -->
                <h2 class="gallery-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <?php

        } else {
            get_template_part( 'template-parts/entry-header' );
            get_template_part( 'template-parts/featured-image' );
            ?>

            <div class="post-inner <?php echo is_page_template( 'templates/template-full-width.php' ) ? '' : 'thin'; ?> ">
                <div class="entry-content">
                    <?php
                    if ( is_search() || ! is_singular() && 'summary' === get_theme_mod( 'blog_content', 'full' ) ) {
                        the_excerpt();
                    } else {
                        the_content( __( 'Continue reading', 'gallery-twenty' ) );
                    }
                    ?>
                </div><!-- .entry-content -->
            </div><!-- .post-inner -->

            <div class="section-inner">
                <?php
                wp_link_pages(
                    array(
                        'before'      => '<nav class="post-nav-links bg-light-background" aria-label="' . esc_attr__( 'Page', 'gallery-twenty' ) . '"><span class="label">' . __( 'Pages:', 'gallery-twenty' ) . '</span>',
                        'after'       => '</nav>',
                        'link_before' => '<span class="page-number">',
                        'link_after'  => '</span>',
                    )
                );
                edit_post_link();
                // Single bottom post meta.
                gallery_twenty_the_post_meta( get_the_ID(), 'single-bottom' );
                get_template_part( 'template-parts/entry-author-bio' );

                ?>

            </div><!-- .section-inner -->

            <?php

            get_template_part( 'template-parts/navigation' );

            /**
             *  Output comments wrapper if it's a post, or if comments are open,
             * or if there's a comment number – and check for password.
             * */
            if ( ( is_single() || is_page() ) && ( comments_open() || get_comments_number() ) && ! post_password_required() ) {
                ?>

                <div class="comments-wrapper section-inner">

                    <?php comments_template(); ?>

                </div><!-- .comments-wrapper -->

                <?php
            }
        }

        ?>


    </article><!-- .post -->
