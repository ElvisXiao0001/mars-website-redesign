<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

if ( ! function_exists( 'arts_hfe_render_footer' ) ) {
	/**
	 * Renders the footer using the HFE (Header Footer Elementor) plugin.
	 * Wraps the footer in a div with attributes if provided.
	 *
	 * @return void
	 */
	function arts_hfe_render_footer() {
		if ( ! function_exists( 'hfe_render_footer' ) ) {
			return;
		}

		$footer_hfe_wrapper_attributes = arts_hfe_get_footer_attributes();

		if ( $footer_hfe_wrapper_attributes ) {
			?><div id="page-footer" <?php Utilities::print_attributes( $footer_hfe_wrapper_attributes ); ?>>
			<?php
		}

		hfe_render_footer();

		if ( $footer_hfe_wrapper_attributes ) {
			?>
			</div>
			<?php
		}
	}
}

// Handle Elementor Canvas tempalte
if ( function_exists( 'get_hfe_footer_id' ) ) {
	$hfe_override_canvas_template = get_post_meta( get_hfe_footer_id(), 'display-on-canvas-template', true );

	// Current page has Elementor Canvas template and "Enable Layout for Elementor Canvas Template" feature is enabled
	if ( $hfe_override_canvas_template && function_exists( 'hfe_render_footer' ) && arts_hfe_footer_enabled() ) {
		// Disable footer render from the plugin side
		// We will render the footer inside the <main> container on our own
		add_filter( 'hfe_footer_enabled', '__return_false' );

		// Footer will be rendered by theme
		add_filter( 'arts/page_hfe_footer/enabled', '__return_true' );
	}
}
