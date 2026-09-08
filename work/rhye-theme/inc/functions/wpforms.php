<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_filter( 'wpforms_global_assets', 'arts_wp_forms_force_load_global_assets' );
if ( ! function_exists( 'arts_wp_forms_force_load_global_assets' ) ) {
	/**
	 * Forces the loading of global assets for WPForms when AJAX transitions are enabled.
	 *
	 * @param bool $value The current value indicating whether to load global assets.
	 * @return bool True if AJAX is enabled, otherwise the original value.
	 */
	function arts_wp_forms_force_load_global_assets( $value ) {
		return get_theme_mod( 'ajax_enabled', false ) ? true : $value;
	}
}
