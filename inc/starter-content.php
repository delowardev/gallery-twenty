<?php
/**
 * Gallery Twenty Starter Content
 *
 * @link https://make.wordpress.org/core/2016/11/30/starter-content-for-themes-in-4-7/
 *
 * @package GalleryTwenty
 * @subpackage Gallery_Twenty
 * @since 1.0.0
 */

/**
 * Function to return the array of starter content for the theme.
 *
 * Passes it through the `gallery_twenty_starter_content` filter before returning.
 *
 * @since  Gallery Twenty 1.0.0
 * @return array a filtered array of args for the starter_content.
 */
function gallery_twenty_get_starter_content() {
    $post_content = join('', array(
        '<!-- wp:paragraph -->',
        '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam laoreet, nunc et accumsan cursus, neque eros sodales lectus.</p>',
        '<!-- /wp:paragraph -->',

        '<!-- wp:paragraph -->',
        '<p><strong>Step 1</strong></p>',
        '<!-- /wp:paragraph -->',

        '<!-- wp:paragraph -->',
        '<p>volutpat nec libero.&nbsp;Fusce mattis non nisi quis tincidunt. Donec dictum velit sed feugiat laoreet. Sed justo magna, sollicitudin et dignissim ac, venenatis at tortor.</p>',
        '<!-- /wp:paragraph -->',

        '<!-- wp:quote -->',
        '<blockquote class="wp-block-quote">',
        '<p>Pellentesque eget metus et velit maximus placerat ut in quam</p><cite>Lorem ipsum</cite>',
        '</blockquote>',
        '<!-- /wp:quote -->',

        '<!-- wp:paragraph -->',
        '<p><a href="#">PlacesBeautiful Architecture That Will Blow Your Mind  Gillion, 2 years ago 0 </a> 3 min read  2559</p>',
        '<!-- /wp:paragraph -->',

        '<!-- wp:paragraph -->',
        '<p><strong>Step </strong>2</p>',
        '<!-- /wp:paragraph -->',

        '<!-- wp:paragraph -->',
        '<p>volutpat nec libero. Fusce mattis non nisi quis tincidunt. Donec dictum velit sed feugiat laoreet.</p>',
        '<!-- /wp:paragraph -->',

        '<!-- wp:paragraph -->',
        '<p><strong>Go to :</strong> <em><a href="#">Link Page</a></em></p>',
        '<!-- /wp:paragraph -->',

        '<!-- wp:paragraph -->',
        '<p><strong>Step 3: Nulla congue mi sed enim venenatis</strong></p>',
        '<!-- /wp:paragraph -->',

        '<!-- wp:paragraph -->',
        '<p>Nulla vel euismod eros. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse.</p>',
        '<!-- /wp:paragraph -->',

        '<!-- wp:list -->',
        '<ul>',
        '<li>Lorem ipsum dolor sit amet</li>',
        '<li>Consectetur adipiscing elit</li>',
        '<li>Suspendisse lacinia finibus ipsum</li>',
        '</ul>',
        '<!-- /wp:list -->'
    ));

	// Define and register starter content to showcase the theme on new sites.
	$starter_content = array(
		'widgets'     => array(
			// Place one core-defined widgets in the first footer widget area.
			'sidebar-1' => array(
				'text_about',
			),
			// Place one core-defined widgets in the second footer widget area.
			'sidebar-2' => array(
				'text_business_info',
			),
		),

		// Create the custom image attachments used as post thumbnails for pages.
		'attachments' => array(
            'post1' => array(
                'post_title' => esc_html_x( 'Post 1', 'Theme starter content', 'gallery-twenty' ),
                'file'       => 'assets/images/post1.jpg', // URL relative to the template directory.
            ),
            'post2' => array(
                'post_title' => esc_html_x( 'Post 2', 'Theme starter content', 'gallery-twenty' ),
                'file'       => 'assets/images/post2.jpg', // URL relative to the template directory.
            ),
            'post3' => array(
                'post_title' => esc_html_x( 'Post 3', 'Theme starter content', 'gallery-twenty' ),
                'file'       => 'assets/images/post3.jpg', // URL relative to the template directory.
            ),
            'post4' => array(
                'post_title' => esc_html_x( 'Post 4', 'Theme starter content', 'gallery-twenty' ),
                'file'       => 'assets/images/post4.jpg', // URL relative to the template directory.
            ),
		),

		// Specify the core-defined pages to create and add custom thumbnails to some of them.
		'posts'       => array(
            'post1' => array(
                'post_type'    => 'post',
                'post_title'   => esc_html__( 'Gallery & Project Showcase Theme', 'gallery-twenty' ),
                // Use the above featured image with the predefined about page.
                'thumbnail'    => '{{post1}}',
                'post_content' => $post_content
            ),
            'post2' => array(
                'post_type'    => 'post',
                'post_title'   => esc_html__( 'Gallery & Project Showcase Theme', 'gallery-twenty' ),
                // Use the above featured image with the predefined about page.
                'thumbnail'    => '{{post2}}',
                'post_content' => $post_content
            ),
            'post3' => array(
                'post_type'    => 'post',
                'post_title'   => esc_html__( 'Gallery & Project Showcase Theme', 'gallery-twenty' ),
                // Use the above featured image with the predefined about page.
                'thumbnail'    => '{{post3}}',
                'post_content' => $post_content
            ),
            'post4' => array(
                'post_type'    => 'post',
                'post_title'   => esc_html__( 'Gallery & Project Showcase Theme', 'gallery-twenty' ),
                // Use the above featured image with the predefined about page.
                'thumbnail'    => '{{post4}}',
                'post_content' => $post_content
            ),
			'front' => array(
				'post_type'    => 'page',
				'post_title'   => esc_html__( 'John\'s Photo Gallery & Showcase', 'gallery-twenty' ),
				// Use the above featured image with the predefined about page.
				'post_content' => join(
					'',
					array(
                        '<!-- wp:paragraph {"align":"wide"} -->',
                        '[gallery classes="alignwide" post_status="auto-draft" pagination="false" posts_per_page="4"]',
                        '<!-- /wp:paragraph -->',
						'<!-- wp:spacer {"height":40} -->',
                        '<div style="height:40px" aria-hidden="true" class="wp-block-spacer"></div>',
                        '<!-- /wp:spacer -->',
						'<!-- wp:group {"customBackgroundColor":"#ffffff","align":"wide"} -->',
						'<div class="wp-block-group alignwide has-background" style="background-color:#ffffff"><div class="wp-block-group__inner-container"><!-- wp:group -->',
						'<div class="wp-block-group"><div class="wp-block-group__inner-container"><!-- wp:heading {"align":"center"} -->',
						'<h2 class="has-text-align-center">' . esc_html__( 'Become a Member and Get Exclusive Offers!', 'gallery-twenty' ) . '</h2>',
						'<!-- /wp:heading -->',
						'<!-- wp:paragraph {"align":"center"} -->',
						'<p class="has-text-align-center">' . esc_html__( 'Members get access to exclusive exhibits and sales. Our memberships cost $99.99 and are billed annually.', 'gallery-twenty' ) . '</p>',
						'<!-- /wp:paragraph -->',
						'<!-- wp:button {"align":"center"} -->',
						'<div class="wp-block-button aligncenter"><a class="wp-block-button__link" href="https://make.wordpress.org/core/2019/09/27/block-editor-theme-related-updates-in-wordpress-5-3/">' . esc_html__( 'Join the Club', 'gallery-twenty' ) . '</a></div>',
						'<!-- /wp:button --></div></div>',
						'<!-- /wp:group --></div></div>',
						'<!-- /wp:group -->',
						'<!-- wp:gallery {"ids":[39,38],"align":"wide"} -->',
						'<figure class="wp-block-gallery alignwide columns-2 is-cropped"><ul class="blocks-gallery-grid"><li class="blocks-gallery-item"><figure><img src="' . get_theme_file_uri() . '/assets/images/post6.jpg" alt="" data-id="39" data-full-url="' . get_theme_file_uri() . '/assets/images/post6.jpg" data-link="assets/images/post6/" class="wp-image-39"/></figure></li><li class="blocks-gallery-item"><figure><img src="' . get_theme_file_uri() . '/assets/images/post5.jpg" alt="" data-id="38" data-full-url="' . get_theme_file_uri() . '/assets/images/post-5.jpg" data-link="' . get_theme_file_uri() . '/assets/images/post5/" class="wp-image-38"/></figure></li></ul></figure>',
						'<!-- /wp:gallery -->',
					)
				),
			),
			'about',
			'contact',
			'blog'
		),

		// Default to a static front page and assign the front and posts pages.
		'options'     => array(
			'show_on_front'  => 'page',
			'page_on_front'  => '{{front}}',
			'page_for_posts' => '{{blog}}',
			'posts_per_page' => 4,
		),

		// Set up nav menus for each of the two areas registered in the theme.
		'nav_menus'   => array(
			// Assign a menu to the "primary" location.
			'primary'  => array(
				'name'  => esc_html__( 'Primary', 'gallery-twenty' ),
				'items' => array(
					'link_home', // Note that the core "home" page is actually a link in case a static front page is not used.
					'page_about',
					'page_blog',
					'page_contact',
				),
			),
			// This replicates primary just to demonstrate the expanded menu.
			'expanded' => array(
				'name'  => esc_html__( 'Primary', 'gallery-twenty' ),
				'items' => array(
					'link_home', // Note that the core "home" page is actually a link in case a static front page is not used.
					'page_about',
					'page_blog',
					'page_contact',
				),
			),
			// Assign a menu to the "social" location.
			'social'   => array(
				'name'  => esc_html__( 'Social Links Menu', 'gallery-twenty' ),
				'items' => array(
					'link_yelp',
					'link_facebook',
					'link_twitter',
					'link_instagram',
					'link_email',
				),
			),
		),

        // Theme options
        'theme_mods' => array(
            'gallery_column' => '6',
            'gallery_style' => 'gallery-style-2',
            'gallery_heading_size' => '30',
            'gallery_meta_position' => 'after'
        ),
	);

	/**
	 * Filters Gallery Twenty array of starter content.
	 *
	 * @since Gallery Twenty 1.0.0
	 *
	 * @param array $starter_content Array of starter content.
	 */
	return apply_filters( 'gallery_twenty_starter_content', $starter_content );

}
