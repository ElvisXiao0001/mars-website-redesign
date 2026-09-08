<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;

new \Kirki\Field\Radio_Buttonset(
	array(
		'settings'  => 'header_container',
		'label'     => esc_html__( 'Container', 'rhye' ),
		'section'   => 'header_options',
		'default'   => 'container-fluid',
		'priority'  => $priority++,
		'choices'   => array(
			'container-fluid' => esc_html__( 'Fullwidth', 'rhye' ),
			'container'       => esc_html__( 'Boxed', 'rhye' ),
		),
		'transport' => 'postMessage',
	)
);

new \Kirki\Field\Select(
	array(
		'settings'  => 'header_main_theme',
		'label'     => esc_html__( 'Main Elements Color', 'rhye' ),
		'section'   => 'header_options',
		'default'   => 'dark',
		'priority'  => $priority++,
		'transport' => 'postMessage',
		'choices'   => ARTS_THEME_COLOR_THEMES_ARRAY,
		'tooltip'   => esc_html__( 'This option may be overriden for the current page from Elementor document settings.', 'rhye' ),
	)
);

new \Kirki\Field\Select(
	array(
		'settings' => 'header_main_logo',
		'label'    => esc_html__( 'Main Logo to Display', 'rhye' ),
		'section'  => 'header_options',
		'default'  => 'primary',
		'priority' => $priority++,
		'choices'  => array(
			'primary'   => esc_html__( 'Primary', 'rhye' ),
			'secondary' => esc_html__( 'Secondary', 'rhye' ),
		),
		'tooltip'  => esc_html__( 'This option may be overriden for the current page from Elementor document settings.', 'rhye' ),
	)
);

new \Kirki\Field\Radio_Buttonset(
	array(
		'settings' => 'header_position',
		'label'    => esc_html__( 'Position', 'rhye' ),
		'section'  => 'header_options',
		'default'  => 'sticky',
		'priority' => $priority++,
		'choices'  => array(
			'absolute' => esc_html__( 'Absolute', 'rhye' ),
			'sticky'   => esc_html__( 'Sticky', 'rhye' ),
		),
	)
);

new \Kirki\Field\Select(
	array(
		'settings'        => 'header_sticky_theme',
		'label'           => esc_html__( 'Sticky Background Color', 'rhye' ),
		'section'         => 'header_options',
		'default'         => 'bg-dark-1',
		'priority'        => $priority++,
		'choices'         => ARTS_THEME_COLORS_ARRAY,
		'tooltip'         => esc_html__( 'This option may be overriden for the current page from Elementor document settings.', 'rhye' ),
		'active_callback' => array(
			array(
				'setting' => 'header_position',
				'value'   => 'sticky',
			),
		),
	)
);

new \Kirki\Field\Select(
	array(
		'settings'        => 'header_sticky_logo',
		'label'           => esc_html__( 'Sticky Logo to Display', 'rhye' ),
		'section'         => 'header_options',
		'default'         => 'secondary',
		'priority'        => $priority++,
		'choices'         => array(
			'primary'   => esc_html__( 'Primary', 'rhye' ),
			'secondary' => esc_html__( 'Secondary', 'rhye' ),
		),
		'tooltip'         => esc_html__( 'This option may be overriden for the current page from Elementor document settings.', 'rhye' ),
		'active_callback' => array(
			array(
				'setting' => 'header_position',
				'value'   => 'sticky',
			),
		),
	)
);
