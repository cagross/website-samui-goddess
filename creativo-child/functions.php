<?php

	add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
	function my_theme_enqueue_styles() {
	    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	}

	// This locks down my entire site to non-registered users.
	// add_action( 'template_redirect', 'redirect_func' );
	function redirect_func() {
		if( ! is_user_logged_in() && !( $GLOBALS['pagenow'] === 'wp-login.php') ) { if ( ! is_public_page_post() ) { auth_redirect(); } }
	}
	// This opens up a page/post of my choice to the public.  Mark page with a custom field of "show" and a value of 1.
	function is_public_page_post() {
		if ( ! ( is_single() || is_page () ) ) : return false; endif;// If you want to open up the blog page, comment out this line.
		$id = get_the_ID();
		$hide = get_post_meta ($id, 'show', true);
		if ( $hide == 1 ): return true; endif;
		return false;
	}

	// This function displays the name of the template used at the bottom of the page.
	function show_template() {
	    if( is_super_admin() ){
	        global $template;
	        print_r($template);
	    }
	}
	// add_action('wp_footer', 'show_template');

	/*Enqueue Google font (for About page).*/
	function custom_add_google_fonts() {
		if (is_page('About')) {
			wp_enqueue_style( 'custom-google-fonts', 'https://fonts.googleapis.com/css?family=Berkshire+Swash', false );		
		}
	}
	// add_action( 'wp_enqueue_scripts', 'custom_add_google_fonts' );

	/*Ensure product gallery thumbnails display at 60 x 90 px.*/
	add_filter( 'woocommerce_get_image_size_gallery_thumbnail', 'g_thumb_size');
	function g_thumb_size( $size ) {
		return array(
			'width' => 60,
			'height' => 90,
			'crop' => 0
		);
	}

/*Ensure free shipping is removed as an option when entering a coupon code.*/
add_filter( 'woocommerce_shipping_packages', function( $packages ) {
	$applied_coupons = WC()->session->get( 'applied_coupons', array() );
	if ( ! empty( $applied_coupons ) ) {
		$free_shipping_id = 'free_shipping:1';
		unset($packages[0]['rates'][ $free_shipping_id ]);
	}
	return $packages;
} );

/*Automatically update plugins and themes.*/
add_filter( 'auto_update_plugin', '__return_true' );
add_filter( 'auto_update_theme', '__return_true' );