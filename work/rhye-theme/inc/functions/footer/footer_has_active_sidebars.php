<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'arts_footer_has_active_sidebars' ) ) {
	/**
	 * Check if there is at least 1 active
	 * sidebar in footer
	 *
	 * @return bool
	 */
	function arts_footer_has_active_sidebars( $suffix = null ) {
		$footer_columns = get_theme_mod( 'footer_columns_' . $suffix, 1 );

		for ( $i = 1; $i <= $footer_columns; $i++ ) {
			if ( is_active_sidebar( 'footer-sidebar-' . $suffix . $i ) ) {
				return true;
			}
		}

		return false;
	}
}
