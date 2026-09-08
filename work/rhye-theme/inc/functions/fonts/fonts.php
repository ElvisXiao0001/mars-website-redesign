<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Initialize custom fonts
 */
add_action( 'after_setup_theme', 'arts_init_custom_fonts' );
if ( ! function_exists( 'arts_init_custom_fonts' ) ) {
	function arts_init_custom_fonts() {
		\Arts_Add_Custom_Fonts::instance();
	}
}

if ( ! function_exists( 'arts_add_fonts_custom_choice' ) ) {
	/**
	 * Add custom fonts choice to Kirki panels.
	 *
	 * @return array
	 */
	function arts_add_fonts_custom_choice() {
		return array(
			'fonts' => apply_filters( 'arts/kirki_font_choices', array() ),
		);
	}
}
