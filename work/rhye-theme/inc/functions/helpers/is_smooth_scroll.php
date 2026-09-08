<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'arts_is_smooth_scroll' ) ) {
	/**
	 * Check if smooth scroll is enabled
	 *
	 * @return false|'lenis'|'smooth-scrollbar'
	 */
	function arts_is_smooth_scroll() {
		if ( isset( $_GET['smooth_scrolling'] ) ) {
			if ( $_GET['smooth_scrolling'] === 'yes' ) {
				return 'smooth-scrollbar';
			} elseif ( $_GET['smooth_scrolling'] === 'no' ) {
				return false;
			} elseif ( $_GET['smooth_scrolling'] === 'lenis' ) {
				return 'lenis';
			}
		}

		$smooth_scroll_enabled = get_theme_mod( 'smooth_scroll_enabled', false );

		if ( $smooth_scroll_enabled ) {
			return get_theme_mod( 'smooth_scroll_library', 'smooth-scrollbar' );
		}

		return false;
	}
}
