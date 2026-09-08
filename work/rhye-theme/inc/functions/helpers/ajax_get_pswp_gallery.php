<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'wp_ajax_get_pswp_gallery', 'arts_ajax_get_pswp_gallery' );
add_action( 'wp_ajax_nopriv_get_pswp_gallery', 'arts_ajax_get_pswp_gallery' );
if ( ! function_exists( 'arts_ajax_get_pswp_gallery' ) ) {
	/**
	 * Handle AJAX request to get the PhotoSwipe gallery container.
	 *
	 * @return void
	 */
	function arts_ajax_get_pswp_gallery() {
		get_template_part( 'template-parts/photoswipe/pswp-container' );
		wp_die();
	}
}
