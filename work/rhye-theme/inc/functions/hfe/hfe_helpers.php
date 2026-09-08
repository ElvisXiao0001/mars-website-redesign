<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'arts_hfe_header_enabled' ) ) {
	/**
	 * Checks if the HFE header is enabled.
	 *
	 * @return bool
	 */
	function arts_hfe_header_enabled() {
		$hfe_header_enabled = function_exists( 'hfe_header_enabled' ) && hfe_header_enabled();

		return apply_filters( 'arts/page_hfe_header/enabled', $hfe_header_enabled );
	}
}

if ( ! function_exists( 'arts_hfe_footer_enabled' ) ) {
	/**
	 * Checks if the HFE footer is enabled.
	 *
	 * @return bool
	 */
	function arts_hfe_footer_enabled() {
		$hfe_footer_enabled = function_exists( 'hfe_footer_enabled' ) && hfe_footer_enabled();

		return apply_filters( 'arts/page_hfe_footer/enabled', $hfe_footer_enabled );
	}
}
