<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;

new \Kirki\Field\Checkbox(
	array(
		'settings' => 'cursor_progress_enabled',
		'label'    => esc_html__( 'Enable "Progress" System Cursor', 'rhye' ),
		'section'  => 'cursor_follower',
		'default'  => true,
		'priority' => $priority++,
	)
);

new \Kirki\Field\Checkbox_Switch(
	array(
		'settings'    => 'cursor_enabled',
		'label'       => esc_html__( 'Enable Mouse Cursor Follower', 'rhye' ),
		'description' => esc_html__( 'It won\'t appear on touch devices regardless of this setting.', 'rhye' ),
		'section'     => 'cursor_follower',
		'default'     => false,
		'priority'    => $priority++,
	)
);

new \Kirki\Field\Number(
	array(
		'settings'        => 'cursor_animation_duration',
		'label'           => esc_html__( 'Animation Duration', 'rhye' ),
		'tooltip'         => esc_html__( 'A float value between 0.0 and 1.0 defining the animation time (in seconds) when the virtual mouse follower is interacting with an element.', 'rhye' ),
		'section'         => 'cursor_follower',
		'default'         => 0.25,
		'priority'        => $priority++,
		'choices'         => array(
			'min'  => 0,
			'max'  => 1.0,
			'step' => 0.01,
		),
		'active_callback' => array(
			array(
				'setting'  => 'cursor_enabled',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

new \Kirki\Field\Number(
	array(
		'settings'        => 'cursor_trailing_factor',
		'label'           => esc_html__( 'Trailing Factor', 'rhye' ),
		'description'     => esc_html__( 'The lower the value is, the faster virtual mouse follower will attract to the native cursor pointer.', 'rhye' ),
		'tooltip'         => esc_html__( 'An integer value between 1 and 20 defining how fast the virtual mouse follower will trail to the native mouse.', 'rhye' ),
		'section'         => 'cursor_follower',
		'default'         => 6,
		'priority'        => $priority++,
		'choices'         => array(
			'min'  => 1,
			'max'  => 20,
			'step' => 1,
		),
		'active_callback' => array(
			array(
				'setting'  => 'cursor_enabled',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

new \Kirki\Field\Color(
	array(
		'section'         => 'cursor_follower',
		'label'           => esc_html__( 'Main Color', 'rhye' ),
		'default'         => '#777777',
		'settings'        => 'cursor_follower_color',
		'priority'        => $priority ++,
		'transport'       => 'auto',
		'output'          => array(
			array(
				'element'  => '.cursor__follower svg #inner',
				'property' => 'stroke',
			),
			array(
				'element'  => '.cursor',
				'property' => 'color',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'cursor_enabled',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

new \Kirki\Field\Color(
	array(
		'section'         => 'cursor_follower',
		'label'           => esc_html__( 'Loading Color', 'rhye' ),
		'default'         => '#C5C6C9',
		'settings'        => 'cursor_follower_loading_color',
		'priority'        => $priority ++,
		'transport'       => 'auto',
		'output'          => array(
			array(
				'element'  => '.cursor__follower svg #outer',
				'property' => 'stroke',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'cursor_enabled',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

new \Kirki\Field\Generic(
	array(
		'settings'        => 'cursor_generic_divider' . $priority,
		'section'         => 'cursor_follower',
		'priority'        => $priority++,
		'choices'         => array(
			'element' => 'hr',
		),
		'active_callback' => array(
			array(
				'setting'  => 'cursor_enabled',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

new \Kirki\Field\Generic(
	array(
		'label'           => esc_html__( 'Helper Label', 'rhye' ),
		'settings'        => 'cursor_follower_heading' . $priority++,
		'section'         => 'cursor_follower',
		'priority'        => $priority++,
		'choices'         => array(
			'element' => 'span',
		),
		'active_callback' => array(
			array(
				'setting'  => 'cursor_enabled',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

new \Kirki\Field\Text(
	array(
		'settings'        => 'cursor_label_slider',
		'description'     => esc_html__( 'Draggable Sliders', 'rhye' ),
		'section'         => 'cursor_follower',
		'default'         => esc_html__( 'Drag Me', 'rhye' ),
		'priority'        => $priority++,
		'active_callback' => array(
			array(
				'setting'  => 'cursor_enabled',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

new \Kirki\Field\Generic(
	array(
		'label'           => esc_html__( 'Helper Icon', 'rhye' ),
		'settings'        => 'cursor_follower_heading' . $priority++,
		'section'         => 'cursor_follower',
		'priority'        => $priority++,
		'choices'         => array(
			'element' => 'span',
		),
		'active_callback' => array(
			array(
				'setting'  => 'cursor_enabled',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

new \Kirki\Field\Slider(
	array(
		'settings'        => 'cursor_icon_size',
		'description'     => esc_html__( 'Icon Size (px)', 'rhye' ),
		'section'         => 'cursor_follower',
		'default'         => 28,
		'priority'        => $priority++,
		'choices'         => array(
			'min'  => 8,
			'max'  => 50,
			'step' => 1,
		),
		'transport'       => 'auto',
		'output'          => array(
			array(
				'element'  => '.cursor__icon',
				'property' => 'font-size',
				'units'    => 'px',
			),
			array(
				'element'  => '.cursor__icon',
				'property' => 'width',
				'units'    => 'px',
			),
			array(
				'element'  => '.cursor__icon',
				'property' => 'height',
				'units'    => 'px',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'cursor_enabled',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

new \Kirki\Field\Generic(
	array(
		'settings' => 'cursor_generic_divider' . $priority,
		'section'  => 'cursor_follower',
		'priority' => $priority++,
		'choices'  => array(
			'element' => 'hr',
		),
	)
);

new \Kirki\Field\Checkbox_Switch(
	array(
		'settings'        => 'cursor_interactive_enabled',
		'label'           => esc_html__( 'Enable Interaction with Elements', 'rhye' ),
		'section'         => 'cursor_follower',
		'default'         => false,
		'priority'        => $priority++,
		'active_callback' => array(
			array(
				'setting'  => 'cursor_enabled',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

new \Kirki\Field\Generic(
	array(
		'description'     => esc_html__( 'Interact with...', 'rhye' ),
		'settings'        => 'cursor_follower_generic_heading' . $priority,
		'section'         => 'cursor_follower',
		'priority'        => $priority++,
		'choices'         => array(
			'element' => 'span',
		),
		'active_callback' => array(
			array(
				'setting'  => 'cursor_enabled',
				'operator' => '==',
				'value'    => true,
			),
			array(
				'setting'  => 'cursor_interactive_enabled',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

new \Kirki\Field\Checkbox(
	array(
		'settings'        => 'cursor_burger_menu_enabled',
		'label'           => esc_html__( 'Burger Menu', 'rhye' ),
		'section'         => 'cursor_follower',
		'default'         => true,
		'priority'        => $priority++,
		'active_callback' => array(
			array(
				'setting'  => 'cursor_enabled',
				'operator' => '==',
				'value'    => true,
			),
			array(
				'setting'  => 'cursor_interactive_enabled',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

new \Kirki\Field\Checkbox(
	array(
		'settings'        => 'cursor_circle_arrows_enabled',
		'label'           => esc_html__( 'Circle Arrows', 'rhye' ),
		'section'         => 'cursor_follower',
		'default'         => true,
		'priority'        => $priority++,
		'active_callback' => array(
			array(
				'setting'  => 'cursor_enabled',
				'operator' => '==',
				'value'    => true,
			),
			array(
				'setting'  => 'cursor_interactive_enabled',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

new \Kirki\Field\Checkbox(
	array(
		'settings'        => 'cursor_dots_enabled',
		'label'           => esc_html__( 'Sliders Dots', 'rhye' ),
		'section'         => 'cursor_follower',
		'default'         => true,
		'priority'        => $priority++,
		'active_callback' => array(
			array(
				'setting'  => 'cursor_enabled',
				'operator' => '==',
				'value'    => true,
			),
			array(
				'setting'  => 'cursor_interactive_enabled',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

new \Kirki\Field\Checkbox(
	array(
		'settings'        => 'cursor_social_items_enabled',
		'label'           => esc_html__( 'Social Icons', 'rhye' ),
		'section'         => 'cursor_follower',
		'default'         => true,
		'priority'        => $priority++,
		'active_callback' => array(
			array(
				'setting'  => 'cursor_enabled',
				'operator' => '==',
				'value'    => true,
			),
			array(
				'setting'  => 'cursor_interactive_enabled',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

new \Kirki\Field\Checkbox(
	array(
		'settings'        => 'cursor_blog_pagination_enabled',
		'label'           => esc_html__( 'Blog Pagination', 'rhye' ),
		'section'         => 'cursor_follower',
		'default'         => true,
		'priority'        => $priority++,
		'active_callback' => array(
			array(
				'setting'  => 'cursor_enabled',
				'operator' => '==',
				'value'    => true,
			),
			array(
				'setting'  => 'cursor_interactive_enabled',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

new \Kirki\Field\Checkbox(
	array(
		'settings'        => 'cursor_gallery_buttons_enabled',
		'label'           => esc_html__( 'Gallery Top Bar Buttons', 'rhye' ),
		'section'         => 'cursor_follower',
		'default'         => true,
		'priority'        => $priority++,
		'active_callback' => array(
			array(
				'setting'  => 'cursor_enabled',
				'operator' => '==',
				'value'    => true,
			),
			array(
				'setting'  => 'cursor_interactive_enabled',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);
