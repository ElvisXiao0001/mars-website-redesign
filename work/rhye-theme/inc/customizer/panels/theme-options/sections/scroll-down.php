<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;

$lg = intval( get_option( 'elementor_viewport_lg', 992 ) );

new \Kirki\Field\Text(
	array(
		'label'       => esc_html__( 'Label', 'rhye' ),
		'description' => esc_html__( 'Used for masthead scroll down button on: Pages, Portfolio Items, Services, Albums.', 'rhye' ),
		'settings'    => 'label_scroll_down_pages',
		'section'     => 'scroll_down',
		'priority'    => $priority++,
		'default'     => esc_html__( 'Scroll Down', 'rhye' ),
	)
);

new \Kirki\Field\Generic(
	array(
		'settings' => 'scroll_down_generic_divider' . $priority,
		'section'  => 'scroll_down',
		'priority' => $priority++,
		'choices'  => array(
			'element' => 'hr',
		),
	)
);

/**
 * Scroll down offset
 */
new \Kirki\Field\Generic(
	array(
		'label'    => esc_html__( 'Scroll Down Top Offset (px)', 'rhye' ),
		'settings' => 'scroll_down_generic_heading' . $priority,
		'section'  => 'scroll_down',
		'priority' => $priority++,
		'choices'  => array(
			'element' => 'span',
		),
	)
);

new \Kirki\Field\Slider(
	array(
		'description' => esc_html__( 'Desktop screens', 'rhye' ),
		'settings'    => 'scroll_down_offset_desktop',
		'section'     => 'scroll_down',
		'default'     => 0,
		'priority'    => $priority++,
		'choices'     => array(
			'min'  => -300,
			'max'  => 300,
			'step' => 1,
		),
	)
);

new \Kirki\Field\Slider(
	array(
		'description' => sprintf(
			'%1s %2s%3s %4s',
			esc_html__( 'Tablet and mobile screens', 'rhye' ),
			esc_attr( $lg - 1 ),
			esc_html__( 'px', 'rhye' ),
			esc_html__( 'and lower', 'rhye' )
		),
		'settings'    => 'scroll_down_offset_mobile',
		'section'     => 'scroll_down',
		'default'     => 0,
		'priority'    => $priority++,
		'choices'     => array(
			'min'  => -300,
			'max'  => 300,
			'step' => 1,
		),
	)
);
