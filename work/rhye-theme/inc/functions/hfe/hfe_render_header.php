<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

if ( ! function_exists( 'arts_hfe_render_header' ) ) {
	/**
	 * Renders the header using the HFE (Header Footer Elementor) plugin.
	 * Wraps the header in a div with attributes if provided.
	 *
	 * @return void
	 */
	function arts_hfe_render_header() {
		if ( ! function_exists( 'hfe_render_header' ) ) {
			return;
		}

		$header_hfe_wrapper_attributes = arts_hfe_get_header_attributes();

		if ( $header_hfe_wrapper_attributes ) {
			?><div id="page-header" <?php Utilities::print_attributes( $header_hfe_wrapper_attributes ); ?>>
			<?php
		}

		hfe_render_header();

		if ( $header_hfe_wrapper_attributes ) {
			?>
			</div>
			<?php
		}
	}
}

// Handle Elementor Canvas tempalte
if ( function_exists( 'get_hfe_header_id' ) ) {
	$hfe_override_canvas_template = get_post_meta( get_hfe_header_id(), 'display-on-canvas-template', true );

	// Current page has Elementor Canvas template and "Enable Layout for Elementor Canvas Template" feature is enabled
	if ( $hfe_override_canvas_template && function_exists( 'hfe_render_header' ) && arts_hfe_header_enabled() ) {
		// Disable header render from the plugin side
		add_filter( 'hfe_header_enabled', '__return_false' );

		// Header will be rendered by theme
		add_filter( 'arts/page_hfe_header/enabled', '__return_true' );
	}
}
