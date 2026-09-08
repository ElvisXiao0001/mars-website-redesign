<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'arts_hfe_get_footer_attributes' ) ) {
	/**
	 * Get the footer attributes for the Elementor Header Footer Builder.
	 *
	 * @return array The footer attributes.
	 */
	function arts_hfe_get_footer_attributes() {
		$elementor_header_footer_builder_footer_wrapper_enabled = get_theme_mod( 'elementor_header_footer_builder_footer_wrapper_enabled', true );
		$attributes = array();

		if ( ! $elementor_header_footer_builder_footer_wrapper_enabled ) {
			return $attributes;
		}

		$attributes = array(
			'class' => array( 'hfe-rhye-footer-wrapper' ),
		);

		$attributes = apply_filters( 'arts/page_hfe_footer/attributes', $attributes );

		return $attributes;
	}
}
