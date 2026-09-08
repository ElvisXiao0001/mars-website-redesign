<?php
/**
 * Mars Light child-theme enhancements.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_enqueue_scripts', 'mars_light_assets', 100 );
function mars_light_assets() {
	$version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style(
		'mars-light',
		get_stylesheet_uri(),
		array( 'rhye-main-style', 'rhye-theme-style' ),
		$version
	);

	// Core Rhye scripts remain loaded because they operate the mobile menu.
	// The filters below turn off the optional animation features at their source.
	wp_dequeue_style( 'preloader' );
	wp_dequeue_style( 'cursor' );
}

add_filter( 'body_class', 'mars_light_body_class' );
function mars_light_body_class( $classes ) {
	$classes[] = 'mars-light';
	return $classes;
}

// Prevent the old preloader markup from delaying first paint.
add_filter( 'theme_mod_preloader_enabled', '__return_false' );
add_filter( 'theme_mod_cursor_enabled', '__return_false' );
add_filter( 'theme_mod_ajax_enabled', '__return_false' );
add_filter( 'theme_mod_smooth_scroll', '__return_false' );
