<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;
$choices  = arts_add_fonts_custom_choice();

/**
 * Paragraph
 */
new \Kirki\Field\Generic(
	array(
		'label'    => esc_html__( 'Body Text & Paragraph', 'rhye' ),
		'settings' => 'par_generic_heading' . $priority,
		'section'  => 'paragraph',
		'priority' => $priority++,
		'choices'  => array(
			'element' => 'span',
		),
	)
);

new \Kirki\Field\Typography(
	array(
		'settings'  => 'par_font',
		'section'   => 'paragraph',
		'default'   => array(
			'font-family'    => 'Raleway',
			'variant'        => 'regular',
			'line-height'    => 1.8,
			'letter-spacing' => 0,
			'text-transform' => 'none',
		),
		'priority'  => $priority++,
		'choices'   => $choices,
		'transport' => 'auto',
		'output'    => array(
			array(
				'element' => 'body, p, .paragraph, .small, .widget small',
			),
		),
	)
);

new \Kirki\Field\Slider(
	array(
		'settings'    => 'par_max_font_size',
		'description' => esc_html__( 'Desktop font size (px)', 'rhye' ),
		'section'     => 'paragraph',
		'default'     => 16,
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
				'property' => '--paragraph-max-font-size',
			),
			array(
				'element'  => ':root',
				'property' => '--paragraph-max-font-size',
				'context'  => array( 'editor' ),
			),
		),
	)
);

new \Kirki\Field\Slider(
	array(
		'settings'    => 'par_min_font_size',
		'description' => esc_html__( 'Mobile font size (px)', 'rhye' ),
		'section'     => 'paragraph',
		'default'     => 16,
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
				'property' => '--paragraph-min-font-size',
			),
			array(
				'element'  => ':root',
				'property' => '--paragraph-min-font-size',
				'context'  => array( 'editor' ),
			),
		),
	)
);

new \Kirki\Field\Color(
	array(
		'section'     => 'paragraph',
		'description' => esc_html__( 'Dark Color Preset', 'rhye' ),
		'default'     => '#262626',
		'settings'    => 'par_color_dark',
		'priority'    => $priority ++,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element'  => ':root',
				'property' => '--paragraph-color-dark',
			),
			array(
				'element'  => ':root',
				'property' => '--paragraph-color-dark',
				'context'  => array( 'editor' ),
			),
		),
	)
);

new \Kirki\Field\Color(
	array(
		'section'     => 'paragraph',
		'description' => esc_html__( 'Light Color Preset', 'rhye' ),
		'default'     => '#cccccc',
		'settings'    => 'par_color_light',
		'priority'    => $priority ++,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element'  => ':root',
				'property' => '--paragraph-color-light',
			),
			array(
				'element'  => ':root',
				'property' => '--paragraph-color-light',
				'context'  => array( 'editor' ),
			),
		),
	)
);

new \Kirki\Field\Generic(
	array(
		'settings' => 'par_generic_divider' . $priority,
		'section'  => 'paragraph',
		'priority' => $priority++,
		'choices'  => array(
			'element' => 'hr',
		),
	)
);
