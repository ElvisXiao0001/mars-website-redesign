<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;

/**
 * Gutters Horizontal
 */
new \Kirki\Field\Generic(
	array(
		'label'    => esc_html__( 'Horizontal Gutter Presets', 'rhye' ),
		'settings' => 'gutters_generic_heading' . $priority,
		'section'  => 'gutters',
		'priority' => $priority++,
		'choices'  => array(
			'element' => 'span',
		),
	)
);

new \Kirki\Field\Slider(
	array(
		'settings'    => 'gutter_horizontal_xlarge',
		'description' => esc_html__( 'Screens min. of 1401px width', 'rhye' ),
		'section'     => 'gutters',
		'default'     => 120,
		'priority'    => $priority++,
		'choices'     => array(
			'min'  => 0,
			'max'  => 200,
			'step' => 1,
		),
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element'  => ':root',
				'property' => '--gutter-horizontal',
				'units'    => 'px',
			),
			array(
				'element'  => ':root',
				'property' => '--gutter-horizontal',
				'units'    => 'px',
				'context'  => array( 'editor' ),
			),
		),
	)
);

new \Kirki\Field\Slider(
	array(
		'settings'    => 'gutter_horizontal_large',
		'description' => esc_html__( 'Screens max. of 1400px width', 'rhye' ),
		'section'     => 'gutters',
		'default'     => 80,
		'priority'    => $priority++,
		'choices'     => array(
			'min'  => 0,
			'max'  => 200,
			'step' => 1,
		),
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element'     => ':root',
				'property'    => '--gutter-horizontal',
				'units'       => 'px',
				'media_query' => '@media (max-width: 1400px)',
			),
			array(
				'element'     => ':root',
				'property'    => '--gutter-horizontal',
				'units'       => 'px',
				'media_query' => '@media (max-width: 1400px)',
				'context'     => array( 'editor' ),
			),
		),
	)
);

new \Kirki\Field\Slider(
	array(
		'settings'    => 'gutter_horizontal_medium',
		'description' => esc_html__( 'Screens max. of 1280px width', 'rhye' ),
		'section'     => 'gutters',
		'default'     => 60,
		'priority'    => $priority++,
		'choices'     => array(
			'min'  => 0,
			'max'  => 200,
			'step' => 1,
		),
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element'     => ':root',
				'property'    => '--gutter-horizontal',
				'units'       => 'px',
				'media_query' => '@media (max-width: 1280px)',
			),
			array(
				'element'     => ':root',
				'property'    => '--gutter-horizontal',
				'units'       => 'px',
				'media_query' => '@media (max-width: 1280px)',
				'context'     => array( 'editor' ),
			),
		),
	)
);

new \Kirki\Field\Slider(
	array(
		'settings'    => 'gutter_horizontal_small',
		'description' => esc_html__( 'Screens max. of 991px width', 'rhye' ),
		'section'     => 'gutters',
		'default'     => 20,
		'priority'    => $priority++,
		'choices'     => array(
			'min'  => 0,
			'max'  => 200,
			'step' => 1,
		),
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element'     => ':root',
				'property'    => '--gutter-horizontal',
				'units'       => 'px',
				'media_query' => '@media (max-width: 991px)',
			),
			array(
				'element'     => ':root',
				'property'    => '--gutter-horizontal',
				'units'       => 'px',
				'media_query' => '@media (max-width: 991px)',
				'context'     => array( 'editor' ),
			),
		),
	)
);

new \Kirki\Field\Generic(
	array(
		'settings' => 'gutters_generic_divider' . $priority,
		'section'  => 'gutters',
		'priority' => $priority++,
		'choices'  => array(
			'element' => 'hr',
		),
	)
);

/**
 * Gutters Vertical
 */
new \Kirki\Field\Generic(
	array(
		'label'    => esc_html__( 'Vertical Gutter Presets', 'rhye' ),
		'settings' => 'gutters_generic_heading' . $priority,
		'section'  => 'gutters',
		'priority' => $priority++,
		'choices'  => array(
			'element' => 'span',
		),
	)
);

new \Kirki\Field\Slider(
	array(
		'settings'    => 'gutter_vertical_xlarge',
		'description' => esc_html__( 'Screens min. of 1401px width', 'rhye' ),
		'section'     => 'gutters',
		'default'     => 80,
		'priority'    => $priority++,
		'choices'     => array(
			'min'  => 0,
			'max'  => 200,
			'step' => 1,
		),
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element'  => ':root',
				'property' => '--gutter-vertical',
				'units'    => 'px',
			),
			array(
				'element'  => ':root',
				'property' => '--gutter-vertical',
				'units'    => 'px',
				'context'  => array( 'editor' ),
			),
		),
	)
);

new \Kirki\Field\Slider(
	array(
		'settings'    => 'gutter_vertical_large',
		'description' => esc_html__( 'Screens max. of 1400px width', 'rhye' ),
		'section'     => 'gutters',
		'default'     => 60,
		'priority'    => $priority++,
		'choices'     => array(
			'min'  => 0,
			'max'  => 200,
			'step' => 1,
		),
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element'     => ':root',
				'property'    => '--gutter-vertical',
				'units'       => 'px',
				'media_query' => '@media (max-width: 1400px)',
			),
			array(
				'element'     => ':root',
				'property'    => '--gutter-vertical',
				'units'       => 'px',
				'media_query' => '@media (max-width: 1400px)',
				'context'     => array( 'editor' ),
			),
		),
	)
);

new \Kirki\Field\Slider(
	array(
		'settings'    => 'gutter_vertical_medium',
		'description' => esc_html__( 'Screens max. of 1280px width', 'rhye' ),
		'section'     => 'gutters',
		'default'     => 40,
		'priority'    => $priority++,
		'choices'     => array(
			'min'  => 0,
			'max'  => 200,
			'step' => 1,
		),
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element'     => ':root',
				'property'    => '--gutter-vertical',
				'units'       => 'px',
				'media_query' => '@media (max-width: 1280px)',
			),
			array(
				'element'     => ':root',
				'property'    => '--gutter-vertical',
				'units'       => 'px',
				'media_query' => '@media (max-width: 1280px)',
				'context'     => array( 'editor' ),
			),
		),
	)
);

new \Kirki\Field\Slider(
	array(
		'settings'    => 'gutter_vertical_small',
		'description' => esc_html__( 'Screens max. of 991px width', 'rhye' ),
		'section'     => 'gutters',
		'default'     => 20,
		'priority'    => $priority++,
		'choices'     => array(
			'min'  => 0,
			'max'  => 200,
			'step' => 1,
		),
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element'     => ':root',
				'property'    => '--gutter-vertical',
				'units'       => 'px',
				'media_query' => '@media (max-width: 991px)',
			),
			array(
				'element'     => ':root',
				'property'    => '--gutter-vertical',
				'units'       => 'px',
				'media_query' => '@media (max-width: 991px)',
				'context'     => array( 'editor' ),
			),
		),
	)
);
