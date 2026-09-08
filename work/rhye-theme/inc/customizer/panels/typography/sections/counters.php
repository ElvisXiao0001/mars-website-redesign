<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;
$choices  = arts_add_fonts_custom_choice();

new \Kirki\Field\Typography(
	array(
		'label'     => esc_html__( 'Slider Counter: Current', 'rhye' ),
		'settings'  => 'slider_counter_current_font',
		'section'   => 'typography_counters',
		'default'   => array(
			'font-family' => 'Cinzel',
			'variant'     => 'regular',
		),
		'priority'  => $priority++,
		'choices'   => $choices,
		'transport' => 'auto',
		'output'    => array(
			array(
				'element' => '.slider__counter_current',
			),
		),
	)
);

new \Kirki\Field\Typography(
	array(
		'label'     => esc_html__( 'Slider Counter: Total', 'rhye' ),
		'settings'  => 'slider_counter_total_font',
		'section'   => 'typography_counters',
		'default'   => array(
			'font-family' => 'Cinzel',
			'variant'     => 'regular',
		),
		'priority'  => $priority++,
		'choices'   => $choices,
		'transport' => 'auto',
		'output'    => array(
			array(
				'element' => '.slider__counter_total',
			),
		),
	)
);

new \Kirki\Field\Typography(
	array(
		'label'     => esc_html__( 'Albums Images Counter', 'rhye' ),
		'settings'  => 'albums_images_counter_font',
		'section'   => 'typography_counters',
		'default'   => array(
			'font-family' => 'Cinzel',
			'variant'     => '700',
		),
		'priority'  => $priority++,
		'choices'   => $choices,
		'transport' => 'auto',
		'output'    => array(
			array(
				'element' => '.block-counter__counter',
			),
		),
	)
);
