<?php
/**
 * The template for displaying the 404 template in the Gallery Twenty theme.
 *
 * @package GalleryTwenty
 * @subpackage Gallery_Twenty
 * @since 1.0.0
 */

get_header();
?>

<main id="site-content" role="main">

	<div class="section-inner thin error404-content">

		<h1 class="entry-title"><?php _e( 'Page Not Found', 'gallery-twenty' ); ?></h1>

		<div class="intro-text"><p><?php _e( 'The page you were looking for could not be found. It might have been removed, renamed, or did not exist in the first place.', 'gallery-twenty' ); ?></p></div>

		<?php
		get_search_form(
			array(
				'label' => __( '404 not found', 'gallery-twenty' ),
			)
		);
		?>

	</div><!-- .section-inner -->

</main><!-- #site-content -->

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php
get_footer();
