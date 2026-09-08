<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'after_setup_theme', 'arts_init_navigation' );
if ( ! function_exists( 'arts_init_navigation' ) ) {
	/**
	 * Register the theme navigation menus.
	 *
	 * @return void
	 */
	function arts_init_navigation() {
		register_nav_menus(
			array(
				'main_menu' => esc_html__( 'Main Menu', 'rhye' ),
			)
		);
	}
}
