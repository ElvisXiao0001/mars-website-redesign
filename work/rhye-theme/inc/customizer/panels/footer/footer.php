<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;

/**
 * Options
 */
new \Kirki\Section(
	'footer_options',
	array(
		'title'    => esc_html__( 'Options', 'rhye' ),
		'priority' => $priority ++,
		'panel'    => 'footer',
	)
);
get_template_part( '/inc/customizer/panels/footer/sections/options' );

/**
 * Section Upper Layout
 */
new \Kirki\Section(
	'section_footer_upper',
	array(
		'title'    => esc_attr__( 'Layout Upper Section', 'rhye' ),
		'priority' => $priority ++,
		'panel'    => 'footer',
	)
);
get_template_part( '/inc/customizer/panels/footer/sections/section-upper' );

/**
 * Section Lower Layout
 */
new \Kirki\Section(
	'section_footer_lower',
	array(
		'title'    => esc_attr__( 'Layout Lower Section', 'rhye' ),
		'priority' => $priority ++,
		'panel'    => 'footer',
	)
);
get_template_part( '/inc/customizer/panels/footer/sections/section-lower' );
