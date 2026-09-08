<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;
$choices  = arts_add_fonts_custom_choice();

/**
 * Blockquote
 */
new \Kirki\Field\Generic(
	array(
		'label'    => esc_html__( 'Blockquote', 'rhye' ),
		'settings' => 'blockquote_generic_heading' . $priority,
		'section'  => 'blockquote',
		'priority' => $priority++,
		'choices'  => array(
			'element' => 'span',
		),
	)
);

new \Kirki\Field\Typography(
	array(
		'settings'  => 'blockquote_font',
		'section'   => 'blockquote',
		'default'   => array(
			'font-family'    => 'Raleway',
			'variant'        => 'italic',
			'line-height'    => 1.6,
			'letter-spacing' => 0,
			'text-transform' => 'none',
		),
		'priority'  => $priority++,
		'choices'   => $choices,
		'transport' => 'auto',
		'output'    => array(
			array(
				'element' => 'blockquote, .blockquote, blockquote p, .blockquote p',
			),
		),
	)
);

new \Kirki\Field\Slider(
	array(
		'settings'    => 'blockquote_max_font_size',
		'description' => esc_html__( 'Desktop font size (px)', 'rhye' ),
		'section'     => 'blockquote',
		'default'     => 24,
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
				'property' => '--blockquote-max-font-size',
			),
			array(
				'element'  => ':root',
				'property' => '--blockquote-max-font-size',
				'context'  => array( 'editor' ),
			),
		),
	)
);

new \Kirki\Field\Slider(
	array(
		'settings'    => 'blockquote_min_font_size',
		'description' => esc_html__( 'Mobile font size (px)', 'rhye' ),
		'section'     => 'blockquote',
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
				'property' => '--blockquote-min-font-size',
			),
			array(
				'element'  => ':root',
				'property' => '--blockquote-min-font-size',
				'context'  => array( 'editor' ),
			),
		),
	)
);

new \Kirki\Field\Color(
	array(
		'section'     => 'blockquote',
		'description' => esc_html__( 'Dark Color Preset', 'rhye' ),
		'default'     => '#262626',
		'settings'    => 'blockquote_color_dark',
		'priority'    => $priority ++,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element'  => ':root',
				'property' => '--blockquote-color-dark',
			),
			array(
				'element'  => ':root',
				'property' => '--blockquote-color-dark',
				'context'  => array( 'editor' ),
			),
		),
	)
);

new \Kirki\Field\Color(
	array(
		'section'     => 'blockquote',
		'description' => esc_html__( 'Light Color Preset', 'rhye' ),
		'default'     => '#ffffff',
		'settings'    => 'blockquote_color_light',
		'priority'    => $priority ++,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element'  => ':root',
				'property' => '--blockquote-color-light',
			),
			array(
				'element'  => ':root',
				'property' => '--blockquote-color-light',
				'context'  => array( 'editor' ),
			),
		),
	)
);
