<?php
/**
 * Customizer Separator Control settings for this theme.
 *
 * @package WordPress
 * @subpackage Gallery_Twenty
 * @since 1.0.0
 */

if ( class_exists( 'WP_Customize_Control' ) ) {

	if ( ! class_exists( 'GalleryTwenty_Separator_Control' ) ) {
		/**
		 * Separator Control.
		 */
		class GalleryTwenty_Separator_Control extends WP_Customize_Control {

			/**
			 * Render the hr.
			 */
			public function render_content() {
				echo '<hr/>';
			}

		}
	}
}
