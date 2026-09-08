<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;

new \Kirki\Field\Radio_Buttonset(
	array(
		'settings'    => 'menu_style',
		'label'       => esc_html__( 'Menu Style', 'rhye' ),
		'description' => esc_html__( 'This option has an effect only on desktop. On mobile there is always a fullscreen overlay menu.', 'rhye' ),
		'section'     => 'menu',
		'default'     => 'classic',
		'priority'    => $priority++,
		'choices'     => array(
			'classic'    => esc_html__( 'Classic', 'rhye' ),
			'fullscreen' => esc_html__( 'Fullscreen', 'rhye' ),
		),
	)
);

new \Kirki\Field\Generic(
	array(
		'label'           => esc_html__( 'Mobile Burger Button Style', 'rhye' ),
		'settings'        => 'menu_generic_heading' . $priority,
		'section'         => 'menu',
		'priority'        => $priority++,
		'choices'         => array(
			'element' => 'span',
		),
		'active_callback' => array(
			array(
				'setting' => 'menu_style',
				'value'   => 'classic',
			),
		),
	)
);

new \Kirki\Field\Generic(
	array(
		'label'           => esc_html__( 'Burger Button Style', 'rhye' ),
		'settings'        => 'menu_generic_heading' . $priority,
		'section'         => 'menu',
		'priority'        => $priority++,
		'choices'         => array(
			'element' => 'span',
		),
		'active_callback' => array(
			array(
				'setting' => 'menu_style',
				'value'   => 'fullscreen',
			),
		),
	)
);

new \Kirki\Field\Select(
	array(
		'settings'  => 'header_overlay_menu_burger_style',
		'section'   => 'menu',
		'default'   => 'light',
		'priority'  => $priority++,
		'default'   => '',
		'choices'   => array(
			''                       => esc_html__( '3 Lines', 'rhye' ),
			'header__burger_2-lines' => esc_html__( '2 Lines', 'rhye' ),
		),
		'transport' => 'postMessage',
	)
);

new \Kirki\Field\Select(
	array(
		'label'           => esc_html__( 'Overlay Scroll Bar', 'rhye' ),
		'settings'        => 'header_overlay_menu_overflow_scroll',
		'section'         => 'menu',
		'default'         => 'native',
		'priority'        => $priority++,
		'default'         => '',
		'choices'         => array(
			'native'  => esc_html__( 'Native', 'rhye' ),
			'virtual' => esc_html__( 'Virtual', 'rhye' ),
		),
		'active_callback' => array(
			array(
				'setting' => 'menu_style',
				'value'   => 'fullscreen',
			),
		),
	)
);

new \Kirki\Field\Generic(
	array(
		'label'           => esc_html__( 'Mobile Overlay Theme Elements', 'rhye' ),
		'settings'        => 'menu_generic_heading' . $priority,
		'section'         => 'menu',
		'priority'        => $priority++,
		'choices'         => array(
			'element' => 'span',
		),
		'tooltip'         => esc_html__( 'This option may be overriden for the current page from Elementor document settings.', 'rhye' ),
		'active_callback' => array(
			array(
				'setting' => 'menu_style',
				'value'   => 'classic',
			),
		),
	)
);

new \Kirki\Field\Generic(
	array(
		'label'           => esc_html__( 'Overlay Theme Elements', 'rhye' ),
		'settings'        => 'menu_generic_heading' . $priority,
		'section'         => 'menu',
		'priority'        => $priority++,
		'choices'         => array(
			'element' => 'span',
		),
		'tooltip'         => esc_html__( 'This option may be overriden for the current page from Elementor document settings.', 'rhye' ),
		'active_callback' => array(
			array(
				'setting' => 'menu_style',
				'value'   => 'fullscreen',
			),
		),
	)
);

new \Kirki\Field\Select(
	array(
		'settings' => 'header_overlay_menu_theme',
		'section'  => 'menu',
		'default'  => 'light',
		'priority' => $priority++,
		'choices'  => ARTS_THEME_COLOR_THEMES_ARRAY,
	)
);

new \Kirki\Field\Generic(
	array(
		'label'           => esc_html__( 'Mobile Overlay Background', 'rhye' ),
		'settings'        => 'menu_generic_heading' . $priority,
		'section'         => 'menu',
		'priority'        => $priority++,
		'choices'         => array(
			'element' => 'span',
		),
		'tooltip'         => esc_html__( 'This option may be overriden for the current page from Elementor document settings.', 'rhye' ),
		'active_callback' => array(
			array(
				'setting' => 'menu_style',
				'value'   => 'classic',
			),
		),
	)
);

new \Kirki\Field\Generic(
	array(
		'label'           => esc_html__( 'Overlay Background', 'rhye' ),
		'settings'        => 'menu_generic_heading' . $priority,
		'section'         => 'menu',
		'priority'        => $priority++,
		'choices'         => array(
			'element' => 'span',
		),
		'tooltip'         => esc_html__( 'This option may be overriden for the current page from Elementor document settings.', 'rhye' ),
		'active_callback' => array(
			array(
				'setting' => 'menu_style',
				'value'   => 'fullscreen',
			),
		),
	)
);

new \Kirki\Field\Color(
	array(
		'settings'  => 'menu_overlay_background_color',
		'section'   => 'menu',
		'default'   => 'rgba(0,0,0,1)',
		'priority'  => $priority++,
		'transport' => 'auto',
		'choices'   => array(
			'alpha' => true,
		),
	)
);

/**
 * Typography
 */
new \Kirki\Field\Generic(
	array(
		'settings' => 'menu_overlay_generic_heading' . $priority,
		'section'  => 'menu',
		'priority' => $priority++,
		'choices'  => array(
			'element' => 'hr',
		),
	)
);

new \Kirki\Field\Generic(
	array(
		'label'           => esc_html__( 'Mobile Menu Typography', 'rhye' ),
		'settings'        => 'menu_generic_heading' . $priority,
		'section'         => 'menu',
		'priority'        => $priority++,
		'choices'         => array(
			'element' => 'span',
		),
		'active_callback' => array(
			array(
				'setting' => 'menu_style',
				'value'   => 'classic',
			),
		),
	)
);

new \Kirki\Field\Generic(
	array(
		'label'           => esc_html__( 'Typography', 'rhye' ),
		'settings'        => 'menu_generic_heading' . $priority,
		'section'         => 'menu',
		'priority'        => $priority++,
		'choices'         => array(
			'element' => 'span',
		),
		'active_callback' => array(
			array(
				'setting' => 'menu_style',
				'value'   => 'fullscreen',
			),
		),
	)
);

new \Kirki\Field\Select(
	array(
		'settings'    => 'menu_overlay_top_heading_preset',
		'description' => esc_html__( 'Top Level Items Preset', 'rhye' ),
		'section'     => 'menu',
		'default'     => 'h2',
		'priority'    => $priority++,
		'choices'     => ARTS_THEME_TYPOGRAHY_ARRAY,
	)
);

new \Kirki\Field\Select(
	array(
		'settings'    => 'menu_overlay_sub_heading_preset',
		'description' => esc_html__( 'Sub Menu Items Preset', 'rhye' ),
		'section'     => 'menu',
		'default'     => 'h3',
		'priority'    => $priority++,
		'choices'     => ARTS_THEME_TYPOGRAHY_ARRAY,
	)
);
