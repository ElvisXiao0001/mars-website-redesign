<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;

new \Kirki\Field\Radio_Buttonset(
	array(
		'settings'  => 'footer_container',
		'label'     => esc_html__( 'Container', 'rhye' ),
		'section'   => 'footer_options',
		'default'   => 'container',
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
		'settings'  => 'footer_theme',
		'label'     => esc_html__( 'Color Theme', 'rhye' ),
		'section'   => 'footer_options',
		'default'   => '',
		'priority'  => $priority++,
		'choices'   => ARTS_THEME_COLORS_ARRAY,
		'transport' => 'postMessage',
		'tooltip'   => esc_html__( 'This option may be overriden for the current page from Elementor document settings.', 'rhye' ),
	)
);

new \Kirki\Field\Select(
	array(
		'settings'  => 'footer_main_theme',
		'label'     => esc_html__( 'Main Elements Color', 'rhye' ),
		'section'   => 'footer_options',
		'default'   => 'dark',
		'priority'  => $priority++,
		'transport' => 'postMessage',
		'choices'   => ARTS_THEME_COLOR_THEMES_ARRAY,
		'tooltip'   => esc_html__( 'This option may be overriden for the current page from Elementor document settings.', 'rhye' ),
	)
);

new \Kirki\Field\Select(
	array(
		'settings'  => 'footer_main_logo',
		'label'     => esc_html__( 'Logo to Display', 'rhye' ),
		'section'   => 'footer_options',
		'default'   => 'primary',
		'priority'  => $priority++,
		'choices'   => array(
			'primary'   => esc_html__( 'Primary', 'rhye' ),
			'secondary' => esc_html__( 'Secondary', 'rhye' ),
		),
		'transport' => 'postMessage',
		'tooltip'   => esc_html__( 'This option may be overriden for the current page from Elementor document settings.', 'rhye' ),
	)
);
