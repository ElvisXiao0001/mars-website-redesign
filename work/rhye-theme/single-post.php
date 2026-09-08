<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();

// Elementor "single-post" location
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single-post' ) ) {
	get_template_part( 'template-parts/masthead/masthead', 'post' );
	get_template_part( 'template-parts/blog/blog', 'post' );
}

get_footer();
