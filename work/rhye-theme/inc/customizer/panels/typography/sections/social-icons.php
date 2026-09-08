<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;

new \Kirki\Field\Slider(
	array(
		'settings'    => 'social_icons_width',
		'description' => esc_html__( 'Icons Width (px)', 'rhye' ),
		'section'     => 'typography_social_icons',
		'default'     => 30,
		'priority'    => $priority++,
		'choices'     => array(
			'min'  => 8,
			'max'  => 100,
			'step' => 1,
		),
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element'  => '.social__item a',
				'property' => 'width',
				'suffix'   => 'px',
			),
		),
	)
);

new \Kirki\Field\Slider(
	array(
		'settings'    => 'social_icons_height',
		'description' => esc_html__( 'Icons Height (px)', 'rhye' ),
		'section'     => 'typography_social_icons',
		'default'     => 30,
		'priority'    => $priority++,
		'choices'     => array(
			'min'  => 8,
			'max'  => 100,
			'step' => 1,
		),
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element'  => '.social__item a',
				'property' => 'height',
				'suffix'   => 'px',
			),
		),
	)
);

new \Kirki\Field\Slider(
	array(
		'settings'    => 'social_icons_font_size',
		'description' => esc_html__( 'Icons Font Size (px)', 'rhye' ),
		'section'     => 'typography_social_icons',
		'default'     => 14,
		'priority'    => $priority++,
		'choices'     => array(
			'min'  => 8,
			'max'  => 100,
			'step' => 1,
		),
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element'  => '.social__item a',
				'property' => 'font-size',
				'suffix'   => 'px',
			),
		),
	)
);

