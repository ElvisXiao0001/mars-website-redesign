<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;
$choices  = arts_add_fonts_custom_choice();

/**
 * Drop Cap
 */
new \Kirki\Field\Generic(
	array(
		'label'    => esc_html__( 'Drop Cap', 'rhye' ),
		'settings' => 'drop_cap_generic_heading' . $priority,
		'section'  => 'drop_cap',
		'priority' => $priority++,
		'choices'  => array(
			'element' => 'span',
		),
	)
);

new \Kirki\Field\Typography(
	array(
		'settings'  => 'drop_cap_font',
		'section'   => 'drop_cap',
		'default'   => array(
			'font-family'    => 'Cinzel',
			'variant'        => 'regular',
			'line-height'    => 0.7,
			'letter-spacing' => 0,
			'text-transform' => 'uppercase',
		),
		'priority'  => $priority++,
		'choices'   => $choices,
		'transport' => 'auto',
		'output'    => array(
			array(
				'element' => '.has-drop-cap:not(:focus):not(.has-drop-cap_split):first-letter, .drop-cap',
			),
		),
	)
);

new \Kirki\Field\Slider(
	array(
		'settings'    => 'drop_cap_max_font_size',
		'description' => esc_html__( 'Desktop font size (px)', 'rhye' ),
		'section'     => 'drop_cap',
		'default'     => 94,
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
				'property' => '--dropcap-max-font-size',
			),
			array(
				'element'  => ':root',
				'property' => '--dropcap-max-font-size',
				'context'  => array( 'editor' ),
			),
		),
	)
);

new \Kirki\Field\Slider(
	array(
		'settings'    => 'drop_cap_min_font_size',
		'description' => esc_html__( 'Mobile font size (px)', 'rhye' ),
		'section'     => 'drop_cap',
		'default'     => 60,
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
				'property' => '--dropcap-min-font-size',
			),
			array(
				'element'  => ':root',
				'property' => '--dropcap-min-font-size',
				'context'  => array( 'editor' ),
			),
		),
	)
);

new \Kirki\Field\Color(
	array(
		'section'     => 'drop_cap',
		'description' => esc_html__( 'Dark Color Preset', 'rhye' ),
		'default'     => '#111111',
		'settings'    => 'drop_cap_color_dark',
		'priority'    => $priority ++,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element'  => ':root',
				'property' => '--dropcap-color-dark',
			),
			array(
				'element'  => ':root',
				'property' => '--dropcap-color-dark',
				'context'  => array( 'editor' ),
			),
		),
	)
);

new \Kirki\Field\Color(
	array(
		'section'     => 'drop_cap',
		'description' => esc_html__( 'Light Color Preset', 'rhye' ),
		'default'     => '#ffffff',
		'settings'    => 'drop_cap_color_light',
		'priority'    => $priority ++,
		'transport'   => 'auto',
		'output'      => array(
			array(
				'element'  => ':root',
				'property' => '--dropcap-color-light',
			),
			array(
				'element'  => ':root',
				'property' => '--dropcap-color-light',
				'context'  => array( 'editor' ),
			),
		),
	)
);
