<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;

/**
 * Animation
 */
new \Kirki\Section(
	'animations',
	array(
		'title'    => esc_html__( 'Animations', 'rhye' ),
		'panel'    => 'general-style',
		'priority' => $priority ++,
	)
);
get_template_part( '/inc/customizer/panels/general-style/sections/animations' );

/**
 * Colors
 */
new \Kirki\Section(
	'colors',
	array(
		'title'    => esc_html__( 'Colors', 'rhye' ),
		'panel'    => 'general-style',
		'priority' => $priority ++,
	)
);
get_template_part( '/inc/customizer/panels/general-style/sections/colors' );

/**
 * Curtains
 */
new \Kirki\Section(
	'curtains',
	array(
		'title'    => esc_html__( 'Curtains', 'rhye' ),
		'panel'    => 'general-style',
		'priority' => $priority ++,
	)
);
get_template_part( '/inc/customizer/panels/general-style/sections/curtains' );

/**
 * Gutters
 */
new \Kirki\Section(
	'gutters',
	array(
		'title'    => esc_html__( 'Gutters', 'rhye' ),
		'panel'    => 'general-style',
		'priority' => $priority ++,
	)
);
get_template_part( '/inc/customizer/panels/general-style/sections/gutters' );

/**
 * Layout
 */
new \Kirki\Section(
	'layout',
	array(
		'title'    => esc_html__( 'Layout', 'rhye' ),
		'panel'    => 'general-style',
		'priority' => $priority ++,
	)
);
get_template_part( '/inc/customizer/panels/general-style/sections/layout' );

/**
 * Paddings & Margins
 */
new \Kirki\Section(
	'paddings_margins',
	array(
		'title'    => esc_html__( 'Paddings & Margins', 'rhye' ),
		'panel'    => 'general-style',
		'priority' => $priority ++,
	)
);
get_template_part( '/inc/customizer/panels/general-style/sections/paddings-margins' );
