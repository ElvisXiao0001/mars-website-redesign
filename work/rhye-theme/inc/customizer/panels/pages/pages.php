<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;

/**
 * 404
 */
new \Kirki\Section(
	'404',
	array(
		'title'    => esc_html__( '404', 'rhye' ),
		'panel'    => 'pages',
		'priority' => $priority ++,
	)
);
get_template_part( '/inc/customizer/panels/pages/sections/404' );

/**
 * Portfolio
 */
new \Kirki\Section(
	'portfolio',
	array(
		'title'    => esc_html__( 'Portfolio', 'rhye' ),
		'panel'    => 'pages',
		'priority' => $priority ++,
	)
);
get_template_part( '/inc/customizer/panels/pages/sections/portfolio' );

/**
 * Static Pages
 */
new \Kirki\Section(
	'static_pages',
	array(
		'title'    => esc_html__( 'Static Pages', 'rhye' ),
		'panel'    => 'pages',
		'priority' => $priority ++,
	)
);
get_template_part( '/inc/customizer/panels/pages/sections/static' );
