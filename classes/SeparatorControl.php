<?php
/**
 * Customizer Separator Control settings for this theme.
 *
 * @package GalleryTwenty
 * @subpackage Gallery_Twenty
 * @since 1.0.0
 */

if ( class_exists( 'WP_Customize_Control' ) ) {

	if ( ! class_exists( 'Gallery_Twenty_Separator_Control' ) ) {
		/**
		 * Separator Control.
		 */
		class Gallery_Twenty_Separator_Control extends WP_Customize_Control {

			/**
             * @since 0.0.1
			 * Render the hr.
			 */
			public function render_content() {
				echo '<hr/>';
			}

		}
	}
}
