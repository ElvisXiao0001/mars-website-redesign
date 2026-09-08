<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'elementor/theme/register_locations', 'arts_register_elementor_locations' );
if ( ! function_exists( 'arts_register_elementor_locations' ) ) {
	/**
	 * Register custom locations for Elementor Theme Builder.
	 *
	 * @param \ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager Elementor theme manager instance.
	 *
	 * @return void
	 */
	function arts_register_elementor_locations( $elementor_theme_manager ) {
		$elementor_theme_manager->register_location( 'header' );
		$elementor_theme_manager->register_location( 'footer' );
		$elementor_theme_manager->register_location( 'popup' );
		$elementor_theme_manager->register_location( 'single-post' );
		$elementor_theme_manager->register_location( 'single-page' );
	}
}

add_action( 'elementor_pro/init', 'arts_enqueue_elementor_pro_widgets_assets' );
if ( ! function_exists( 'arts_enqueue_elementor_pro_widgets_assets' ) ) {
	/**
	 * Add AJAX transitions compatibility for Elementor Pro.
	 *
	 * Enforces exclusive Elementor Pro assets to load on all the pages.
	 *
	 * @return void
	 */
	function arts_enqueue_elementor_pro_widgets_assets() {
		$ajax_enabled              = get_theme_mod( 'ajax_enabled', false );
		$ajax_load_missing_scripts = get_theme_mod( 'ajax_load_missing_scripts', true );
		$ajax_load_missing_styles  = get_theme_mod( 'ajax_load_missing_styles', true );

		if ( $ajax_enabled ) {

			// JS assets
			if ( ! $ajax_load_missing_scripts ) {
				add_action(
					'elementor/frontend/before_enqueue_scripts',
					function() {
						wp_enqueue_script( 'elementor-gallery' ); // Elementor Gallery
						wp_enqueue_script( 'lottie' ); // Elementor Lottie animations
					}
				);
			}

			// CSS assets
			if ( ! $ajax_load_missing_styles ) {
				add_action(
					'elementor/frontend/before_enqueue_styles',
					function() {
						wp_enqueue_style( 'elementor-gallery' ); // Elementor Gallery
					}
				);
			}
		}
	}
}
