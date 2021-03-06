<?php
/**
 * Customizer settings for this theme.
 *
 * @package GalleryTwenty
 * @subpackage Gallery_Twenty
 * @since 1.0.0
 */

if ( ! class_exists( 'GalleryTwenty_Customize' ) ) {
	/**
	 * CUSTOMIZER SETTINGS
	 */
	class GalleryTwenty_Customize {

		/**
         * @since 0.0.1
		 * Register customizer options.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public static function register( $wp_customize ) {

			/**
			 * Site Title & Description.
			 * */
			$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
			$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

			$wp_customize->selective_refresh->add_partial(
				'blogname',
				array(
					'selector'        => '.site-title a',
					'render_callback' => 'gallery_twenty_customize_partial_blogname',
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'blogdescription',
				array(
					'selector'        => '.site-description',
					'render_callback' => 'gallery_twenty_customize_partial_blogdescription',
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'custom_logo',
				array(
					'selector'        => '.header-titles [class*=site-]:not(.site-description)',
					'render_callback' => 'gallery_twenty_customize_partial_site_logo',
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'retina_logo',
				array(
					'selector'        => '.header-titles [class*=site-]:not(.site-description)',
					'render_callback' => 'gallery_twenty_customize_partial_site_logo',
				)
			);

			/**
			 * Site Identity
			 */

			/* 2X Header Logo ---------------- */
			$wp_customize->add_setting(
				'retina_logo',
				array(
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				'retina_logo',
				array(
					'type'        => 'checkbox',
					'section'     => 'title_tagline',
					'priority'    => 10,
					'label'       => esc_html__( 'Retina logo', 'gallery-twenty' ),
					'description' => esc_html__( 'Scales the logo to half its uploaded size, making it sharp on high-res screens.', 'gallery-twenty' ),
				)
			);

			// Header & Footer Background Color.
			$wp_customize->add_setting(
				'header_footer_background_color',
				array(
					'default'           => '#ffffff',
					'sanitize_callback' => 'sanitize_hex_color',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'header_footer_background_color',
					array(
						'label'   => esc_html__( 'Header &amp; Footer Background Color', 'gallery-twenty' ),
						'section' => 'colors',
					)
				)
			);

			// Enable picking an accent color.
			$wp_customize->add_setting(
				'accent_hue_active',
				array(
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => array( __CLASS__, 'sanitize_select' ),
					'transport'         => 'postMessage',
					'default'           => 'default',
				)
			);

			$wp_customize->add_control(
				'accent_hue_active',
				array(
					'type'    => 'radio',
					'section' => 'colors',
					'label'   => esc_html__( 'Primary Color', 'gallery-twenty' ),
					'choices' => array(
						'default' => esc_html__( 'Default', 'gallery-twenty' ),
						'custom'  => esc_html__( 'Custom', 'gallery-twenty' ),
					),
				)
			);

			/**
			 * Implementation for the accent color.
			 * This is different to all other color options because of the accessibility enhancements.
			 * The control is a hue-only colorpicker, and there is a separate setting that holds values
			 * for other colors calculated based on the selected hue and various background-colors on the page.
			 *
			 * @since 0.0.1
			 */

			// Add the setting for the hue colorpicker.
			$wp_customize->add_setting(
				'accent_hue',
				array(
					'default'           => 344,
					'type'              => 'theme_mod',
					'sanitize_callback' => 'absint',
					'transport'         => 'postMessage',
				)
			);

			// Add setting to hold colors derived from the accent hue.
			$wp_customize->add_setting(
				'accent_accessible_colors',
				array(
					'default'           => array(
						'content'       => array(
							'text'      => '#000000',
							'accent'    => '#cd2653',
							'secondary' => '#6d6d6d',
							'borders'   => '#dcd7ca',
						),
						'header-footer' => array(
							'text'      => '#000000',
							'accent'    => '#cd2653',
							'secondary' => '#6d6d6d',
							'borders'   => '#dcd7ca',
						),
					),
					'type'              => 'theme_mod',
					'transport'         => 'postMessage',
					'sanitize_callback' => array( __CLASS__, 'sanitize_accent_accessible_colors' ),
				)
			);

			// Add the hue-only colorpicker for the accent color.
			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'accent_hue',
					array(
						'section'         => 'colors',
						'settings'        => 'accent_hue',
						'description'     => esc_html__( 'Apply a custom color for links, buttons, featured images.', 'gallery-twenty' ),
						'mode'            => 'hue',
						'active_callback' => function() use ( $wp_customize ) {
							return ( 'custom' === $wp_customize->get_setting( 'accent_hue_active' )->value() );
						},
					)
				)
			);

			// Update background color with postMessage, so inline CSS output is updated as well.
			$wp_customize->get_setting( 'background_color' )->transport = 'postMessage';

			/**
			 * Theme Options
			 */

			$wp_customize->add_section(
				'options',
				array(
					'title'      => esc_html__( 'Theme Options', 'gallery-twenty' ),
					'priority'   => 40,
					'capability' => 'edit_theme_options',
				)
			);

			/* Enable Header Search ----------------------------------------------- */

			$wp_customize->add_setting(
				'enable_header_search',
				array(
					'capability'        => 'edit_theme_options',
					'default'           => true,
					'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
				)
			);

			$wp_customize->add_control(
				'enable_header_search',
				array(
					'type'     => 'checkbox',
					'section'  => 'options',
					'priority' => 10,
					'label'    => esc_html__( 'Show search in header', 'gallery-twenty' ),
				)
			);

			/* Show author bio ---------------------------------------------------- */

			$wp_customize->add_setting(
				'show_author_bio',
				array(
					'capability'        => 'edit_theme_options',
					'default'           => true,
					'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
				)
			);

			$wp_customize->add_control(
				'show_author_bio',
				array(
					'type'     => 'checkbox',
					'section'  => 'options',
					'priority' => 10,
					'label'    => esc_html__( 'Show author bio', 'gallery-twenty' ),
				)
			);

			/* Display full content or excerpts on the blog and archives --------- */

			$wp_customize->add_setting(
				'blog_content',
				array(
					'capability'        => 'edit_theme_options',
					'default'           => 'full',
					'sanitize_callback' => array( __CLASS__, 'sanitize_select' ),
				)
			);

			$wp_customize->add_control(
				'blog_content',
				array(
					'type'     => 'radio',
					'section'  => 'options',
					'priority' => 10,
					'label'    => esc_html__( 'On archive pages, posts show:', 'gallery-twenty' ),
					'choices'  => array(
						'full'    => esc_html__( 'Full text', 'gallery-twenty' ),
						'summary' => esc_html__( 'Summary', 'gallery-twenty' ),
					),
				)
			);

            /**
             * Gallery Settings
             */

            $wp_customize->add_section(
                'gallery',
                array(
                    'title'         => esc_html__('Gallery Options', 'gallery-twenty'),
                    'capability'    => 'edit_theme_options',
                    'description'   => esc_html__('Settings to customize gallery', 'gallery-twenty'),
                    'priority'      => 41
                )
            );

            /* ---- column count ---- */
            $wp_customize->add_setting(
                'gallery_column',
                array(
                    'capability'        => 'edit_theme_options',
                    'default'           => get_theme_mod('gallery_column', '4'),
                    'sanitize_callback' => array( __CLASS__, 'sanitize_select' ),
                )
            );

            $wp_customize->add_control(
                'gallery_column',
                array(
                    'type'        => 'select',
                    'section'     => 'gallery',
                    'label'       => esc_html__( 'Column Count', 'gallery-twenty' ),
                    'description' => esc_html__( 'Select how many column you want to show in a row', 'gallery-twenty' ),
                    'choices'     => array(
                        '12' => '1 Columns',
                        '6' => '2 Columns',
                        '4' => '3 Columns',
                        '3' => '4 Columns',
                        '2' => '6 Columns',
                    )
                )
            );

            /* ---- column gap/gutter ---- */
            $wp_customize->add_setting(
                'gallery_column_gutter',
                array(
                    'capability'        => 'edit_theme_options',
                    'default'           => get_theme_mod('gallery_column_gutter', 'true'),
                    'sanitize_callback' => array( __CLASS__, 'sanitize_select' ),
                )
            );

            $wp_customize->add_control(
                'gallery_column_gutter',
                array(
                    'type'        => 'radio',
                    'section'     => 'gallery',
                    'label'       => esc_html__( 'Column Gap/Spacing', 'gallery-twenty' ),
                    'description'       => esc_html__( 'Enable/disable gap between columns', 'gallery-twenty' ),
                    'choices'     => array(
                        'true' => 'Yes',
                        'false' => 'No',
                    )
                )
            );

            /* ---- Gallery Style ---- */
            $wp_customize->add_setting(
                'gallery_style',
                array(
                    'capability'        => 'edit_theme_options',
                    'default'           => get_theme_mod('gallery_style', 'gallery-style-3'),
                    'sanitize_callback' => array( __CLASS__, 'sanitize_select' ),
                )
            );

            $wp_customize->add_control(
                'gallery_style',
                array(
                    'type'        => 'select',
                    'section'     => 'gallery',
                    'label'       => esc_html__( 'Gallery Style', 'gallery-twenty' ),
                    'choices'     => array(
                        'gallery-style-1' => 'Content Inside',
                        'gallery-style-2' => 'Content Inside Hidden',
                        'gallery-style-3' => 'Content Outside',
                        'gallery-style-4' => 'Content Outside Boxed',
                    )
                )
            );

            /* ---- column gap/gutter ---- */
            $wp_customize->add_setting(
                'gallery_disable_heading',
                array(
                    'capability'        => 'edit_theme_options',
                    'default'           => get_theme_mod('gallery_disable_heading', 'false'),
                    'sanitize_callback' => array( __CLASS__, 'sanitize_select' ),
                )
            );

            $wp_customize->add_control(
                'gallery_disable_heading',
                array(
                    'type'        => 'radio',
                    'section'     => 'gallery',
                    'label'       => esc_html__( 'Disable Content', 'gallery-twenty' ),
                    'description' => esc_html__( 'Settings to hide Gallery "Heading" & "Meta"', 'gallery-twenty' ),
                    'choices'     => array(
                        'true' => 'Yes',
                        'false' => 'No',
                    )
                )
            );

            /* ---- Heading Font Size ---- */
            $wp_customize->add_setting(
                'gallery_heading_size',
                array(
                    'capability'        => 'edit_theme_options',
                    'default'           => get_theme_mod('gallery_heading_size', '24'),
                    'sanitize_callback' => 'sanitize_text_field',
                )
            );

            $wp_customize->add_control(
                'gallery_heading_size',
                array(
                    'type'        => 'number',
                    'section'     => 'gallery',
                    'label'       => esc_html__( 'Heading font size', 'gallery-twenty' ),
                    'description' => esc_html__( 'Option to change heading font size, default value: 24', 'gallery-twenty' ),
                    'placeholder' => esc_html__( '24', 'gallery-twenty' ),
                )
            );

            /* ---- column gap/gutter ---- */
            $wp_customize->add_setting(
                'gallery_heading_ellipsis',
                array(
                    'capability'        => 'edit_theme_options',
                    'default'           => get_theme_mod('gallery_heading_ellipsis', 'true'),
                    'sanitize_callback' => array( __CLASS__, 'sanitize_select' ),
                )
            );

            $wp_customize->add_control(
                'gallery_heading_ellipsis',
                array(
                    'type'        => 'radio',
                    'section'     => 'gallery',
                    'label'       => esc_html__( 'Heading Text Overflow', 'gallery-twenty' ),
                    'description' => esc_html__( 'Settings to toggle ellipsis on heading', 'gallery-twenty' ),
                    'choices'     => array(
                        'true' => 'Ellipsis',
                        'false' => 'Normal',
                    )
                )
            );

            /* -- separator -- */
            $wp_customize->add_setting(
                'gallery_separator_1',
                array(
                    'sanitize_callback' => 'wp_filter_nohtml_kses',
                )
            );

            $wp_customize->add_control(
                new Gallery_Twenty_Separator_Control(
                    $wp_customize,
                    'gallery_separator_1',
                    array(
                        'section' => 'gallery',
                    )
                )
            );

            /* ---- column meta position ---- */
            $wp_customize->add_setting(
                'gallery_meta_position',
                array(
                    'capability'        => 'edit_theme_options',
                    'default'           => get_theme_mod('gallery_meta_position', 'disable'),
                    'sanitize_callback' => array( __CLASS__, 'sanitize_select' ),
                )
            );

            $wp_customize->add_control(
                'gallery_meta_position',
                array(
                    'type'        => 'radio',
                    'section'     => 'gallery',
                    'label'       => esc_html__( 'Meta Position', 'gallery-twenty' ),
                    'description'  => esc_html__( "Select meta position, You must select Date or Author name meta below to show meta", 'gallery-twenty' ),
                    'choices'     => array(
                        'disable' => 'Disable',
                        'before' => 'Before heading',
                        'after' => 'After heading',
                    )
                )
            );


            /* ---- Meta ---- */

            $wp_customize->add_setting(
                'gallery_column_meta_author',
                array(
                    'capability'        => 'edit_theme_options',
                    'default'           => get_theme_mod('gallery_column_meta_author', true),
                    'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
                )
            );

            $wp_customize->add_control(
                'gallery_column_meta_author',
                array(
                    'type'        => 'checkbox',
                    'section'     => 'gallery',
                    'label'       => esc_html__( 'Author Name', 'gallery-twenty' ),
                    'description' => esc_html__( 'Show Author name in gallery meta', 'gallery-twenty' ),
                )
            );

            $wp_customize->add_setting(
                'gallery_column_meta_date',
                array(
                    'capability'        => 'edit_theme_options',
                    'default'           => get_theme_mod('gallery_column_meta_date', true),
                    'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
                )
            );

            $wp_customize->add_control(
                'gallery_column_meta_date',
                array(
                    'type'        => 'checkbox',
                    'section'     => 'gallery',
                    'label'       => esc_html__( 'Date', 'gallery-twenty' ),
                    'description' => esc_html__( 'Show Date in gallery meta', 'gallery-twenty' ),
                )
            );


            $wp_customize->add_setting(
                'gallery_column_meta_category',
                array(
                    'capability'        => 'edit_theme_options',
                    'default'           => get_theme_mod('gallery_column_meta_category', false),
                    'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
                )
            );

            $wp_customize->add_control(
                'gallery_column_meta_category',
                array(
                    'type'        => 'checkbox',
                    'section'     => 'gallery',
                    'label'       => esc_html__( 'Category', 'gallery-twenty' ),
                    'description' => esc_html__( 'Show category in gallery meta', 'gallery-twenty' ),
                )
            );


			/**
			 * Template: Cover Template.
			 */
			$wp_customize->add_section(
				'cover_template_options',
				array(
					'title'       => esc_html__( 'Cover Template', 'gallery-twenty' ),
					'capability'  => 'edit_theme_options',
					'description' => esc_html__( 'Settings for the "Cover Template" page template. Add a featured image to use as background.', 'gallery-twenty' ),
					'priority'    => 42,
				)
			);

			/* Overlay Fixed Background ------ */

			$wp_customize->add_setting(
				'cover_template_fixed_background',
				array(
					'capability'        => 'edit_theme_options',
					'default'           => true,
					'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				'cover_template_fixed_background',
				array(
					'type'        => 'checkbox',
					'section'     => 'cover_template_options',
					'label'       => esc_html__( 'Fixed Background Image', 'gallery-twenty' ),
					'description' => esc_html__( 'Creates a parallax effect when the visitor scrolls.', 'gallery-twenty' ),
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'cover_template_fixed_background',
				array(
					'selector' => '.cover-header',
					'type'     => 'cover_fixed',
				)
			);

			/* Separator --------------------- */

			$wp_customize->add_setting(
				'cover_template_separator_1',
				array(
					'sanitize_callback' => 'wp_filter_nohtml_kses',
				)
			);

			$wp_customize->add_control(
				new Gallery_Twenty_Separator_Control(
					$wp_customize,
					'cover_template_separator_1',
					array(
						'section' => 'cover_template_options',
					)
				)
			);

			/* Overlay Background Color ------ */

			$wp_customize->add_setting(
				'cover_template_overlay_background_color',
				array(
					'default'           => Functions::gallery_twenty_get_color_for_area( 'content', 'accent' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'cover_template_overlay_background_color',
					array(
						'label'       => esc_html__( 'Overlay Background Color', 'gallery-twenty' ),
						'description' => esc_html__( 'The color used for the overlay. Defaults to the accent color.', 'gallery-twenty' ),
						'section'     => 'cover_template_options',
					)
				)
			);

			/* Overlay Text Color ------------ */

			$wp_customize->add_setting(
				'cover_template_overlay_text_color',
				array(
					'default'           => '#ffffff',
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'cover_template_overlay_text_color',
					array(
						'label'       => esc_html__( 'Overlay Text Color', 'gallery-twenty' ),
						'description' => esc_html__( 'The color used for the text in the overlay.', 'gallery-twenty' ),
						'section'     => 'cover_template_options',
					)
				)
			);

			/* Overlay Color Opacity --------- */

			$wp_customize->add_setting(
				'cover_template_overlay_opacity',
				array(
					'default'           => 80,
					'sanitize_callback' => 'absint',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				'cover_template_overlay_opacity',
				array(
					'label'       => esc_html__( 'Overlay Opacity', 'gallery-twenty' ),
					'description' => esc_html__( 'Make sure that the contrast is high enough so that the text is readable.', 'gallery-twenty' ),
					'section'     => 'cover_template_options',
					'type'        => 'range',
					'input_attrs' => gallery_twenty_customize_opacity_range(),
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'cover_template_overlay_opacity',
				array(
					'selector' => '.cover-color-overlay',
					'type'     => 'cover_opacity',
				)
			);
		}

		/**
		 * Sanitization callback for the "accent_accessible_colors" setting.
		 *
		 * @static
		 * @access public
		 * @since 0.0.1
		 * @param array $value The value we want to sanitize.
		 * @return array       Returns sanitized value. Each item in the array gets sanitized separately.
		 */
		public static function sanitize_accent_accessible_colors( $value ) {

			// Make sure the value is an array. Do not typecast, use empty array as fallback.
			$value = is_array( $value ) ? $value : array();

			// Loop values.
			foreach ( $value as $area => $values ) {
				foreach ( $values as $context => $color_val ) {
					$value[ $area ][ $context ] = sanitize_hex_color( $color_val );
				}
			}

			return $value;
		}

		/**
         * @since 0.0.1
		 * Sanitize select.
		 *
		 * @param string $input The input from the setting.
		 * @param object $setting The selected setting.
		 *
		 * @return string $input|$setting->default The input from the setting or the default setting.
		 */
		public static function sanitize_select( $input, $setting ) {
			$input   = sanitize_key( $input );
			$choices = $setting->manager->get_control( $setting->id )->choices;
			return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
		}

		/**
		 * Sanitize boolean for checkbox.
		 * @since 0.0.1
		 * @param bool $checked Whether or not a box is checked.
		 *
		 * @return bool
		 */
		public static function sanitize_checkbox( $checked ) {
			return ( ( isset( $checked ) && true === $checked ) ? true : false );
		}

	}

	// Setup the Theme Customizer settings and controls.
	add_action( 'customize_register', array( 'GalleryTwenty_Customize', 'register' ) );

}

/**
 * PARTIAL REFRESH FUNCTIONS
 * */
if ( ! function_exists( 'gallery_twenty_customize_partial_blogname' ) ) {
	/**
	 * Render the site title for the selective refresh partial.
	 */
	function gallery_twenty_customize_partial_blogname() {
		bloginfo( 'name' );
	}
}

if ( ! function_exists( 'gallery_twenty_customize_partial_blogdescription' ) ) {
	/**
	 * Render the site description for the selective refresh partial.
	 */
	function gallery_twenty_customize_partial_blogdescription() {
		bloginfo( 'description' );
	}
}

if ( ! function_exists( 'gallery_twenty_customize_partial_site_logo' ) ) {
	/**
	 * Render the site logo for the selective refresh partial.
	 *
	 * Doing it this way so we don't have issues with `render_callback`'s arguments.
	 */
	function gallery_twenty_customize_partial_site_logo() {
		gallery_twenty_site_logo();
	}
}


/**
 * Input attributes for cover overlay opacity option.
 * @since 0.0.1
 * @return array Array containing attribute names and their values.
 */
function gallery_twenty_customize_opacity_range() {
	/**
	 * Filter the input attributes for opacity
	 *
	 * @param array $attrs {
	 *     The attributes
	 *
	 *     @type int $min Minimum value
	 *     @type int $max Maximum value
	 *     @type int $step Interval between numbers
	 * }
	 */
	return apply_filters(
		'gallery_twenty_customize_opacity_range',
		array(
			'min'  => 0,
			'max'  => 90,
			'step' => 5,
		)
	);
}
