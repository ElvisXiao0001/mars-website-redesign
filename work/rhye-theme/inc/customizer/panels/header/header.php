<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;

/**
 * Options
 */
new \Kirki\Section(
	'header_options',
	array(
		'title'    => esc_html__( 'Options', 'rhye' ),
		'panel'    => 'header',
		'priority' => $priority ++,
	)
);
get_template_part( '/inc/customizer/panels/header/sections/options' );

/**
 * Menu
 */
new \Kirki\Section(
	'menu',
	array(
		'title'    => esc_html__( 'Menu', 'rhye' ),
		'panel'    => 'header',
		'priority' => $priority ++,
	)
);
get_template_part( '/inc/customizer/panels/header/sections/menu' );
