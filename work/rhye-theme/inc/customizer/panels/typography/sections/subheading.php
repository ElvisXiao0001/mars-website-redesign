<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;
$choices  = arts_add_fonts_custom_choice();

/**
 * Subheading
 */
new \Kirki\Field\Generic(
	array(
		'label'    => esc_html__( 'Subheading', 'rhye' ),
		'settings' => 'subheading_generic_heading' . $priority,
		'section'  => 'subheading',
		'priority' => $priority++,
		'choices'  => array(
			'element' => 'span',
		),
	)
);

new \Kirki\Field\Typography(
	array(
		'settings'  => 'subheading_font',
		'section'   => 'subheading',
		'default'   => array(
			'font-family'    => 'Raleway',
			'variant'        => '700',
			'line-height'    => 1.3,
			'letter-spacing' => 2,
			'text-transform' => 'uppercase',
		),
		'priority'  => $priority++,
		'choices'   => $choices,
		'transport' => 'auto',
		'output'    => array(
			array(
				'element' => '.subheading',
			),
		),
	)
);

new \Kirki\Field\Slider(
	array(
		'settings'    => 'subheading_max_font_size',
		'description' => esc_html__( 'Desktop font size (px)', 'rhye' ),
		'section'     => 'subheading',
		'default'     => 13,
		'priority'    => $priority++,
		'choices'     => array(
			'min'  => 8,
			'max'  => 300,
			'step' => 1,
		),
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element'  => ':root',
				'property' => '--subheading-max-font-size',
			),
		),
	)
);

new \Kirki\Field\Slider(
	array(
		'settings'    => 'subheading_min_font_size',
		'description' => esc_html__( 'Mobile font size (px)', 'rhye' ),
		'section'     => 'subheading',
		'default'     => 10,
		'priority'    => $priority++,
		'choices'     => array(
			'min'  => 8,
			'max'  => 300,
			'step' => 1,
		),
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element'  => ':root',
				'property' => '--subheading-min-font-size',
			),
		),
	)
);

new \Kirki\Field\Color(
	array(
		'section'     => 'subheading',
		'description' => esc_html__( 'Dark Color Preset', 'rhye' ),
		'default'     => '#777777',
		'settings'    => 'subheading_color_dark',
		'priority'    => $priority ++,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element'  => ':root',
				'property' => '--subheading-color-dark',
			),
		),
	)
);

new \Kirki\Field\Color(
	array(
		'section'     => 'subheading',
		'description' => esc_html__( 'Light Color Preset', 'rhye' ),
		'default'     => '#ffffff',
		'settings'    => 'subheading_color_light',
		'priority'    => $priority ++,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element'  => ':root',
				'property' => '--subheading-color-light',
			),
		),
	)
);

new \Kirki\Field\Generic(
	array(
		'settings' => 'par_generic_divider' . $priority,
		'section'  => 'subheading',
		'priority' => $priority++,
		'choices'  => array(
			'element' => 'hr',
		),
	)
);
