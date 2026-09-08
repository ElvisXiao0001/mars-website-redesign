<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;

$active_callback_is_smooth_scrollbar = array(
	array(
		'setting' => 'smooth_scroll_enabled',
		'value'   => true,
	),
	array(
		'setting' => 'smooth_scroll_library',
		'value'   => 'smooth-scrollbar',
	),
);
$active_callback_is_lenis            = array(
	array(
		'setting' => 'smooth_scroll_enabled',
		'value'   => true,
	),
	array(
		'setting' => 'smooth_scroll_library',
		'value'   => 'lenis',
	),
);

new \Kirki\Field\Checkbox_Switch(
	array(
		'settings'    => 'smooth_scroll_enabled',
		'label'       => esc_html__( 'Enable Page Smooth Scroll', 'rhye' ),
		'description' => esc_html__( 'Desktop non-touch devices only', 'rhye' ),
		'section'     => 'smooth_scroll',
		'default'     => false,
		'priority'    => $priority++,
	)
);

new \Kirki\Field\Select(
	array(
		'settings'        => 'smooth_scroll_library',
		'label'           => esc_html__( 'Library', 'rhye' ),
		'choices'         => array(
			'lenis'            => esc_html__( 'Lenis (Modern)', 'rhye' ),
			'smooth-scrollbar' => esc_html__( 'Smooth Scrollbar (Legacy)', 'rhye' ),
		),
		'section'         => 'smooth_scroll',
		'default'         => 'smooth-scrollbar',
		'priority'        => $priority++,
		'active_callback' => array(
			'setting' => 'smooth_scroll_enabled',
			'value'   => true,
		),
	)
);

new \Kirki\Field\Checkbox(
	array(
		'settings'        => 'smooth_scroll_elementor_canvas_template_enabled',
		'label'           => esc_html__( 'Enable on Elementor Canvas Pages ', 'rhye' ),
		'section'         => 'smooth_scroll',
		'default'         => true,
		'priority'        => $priority++,
		'active_callback' => array(
			array(
				'setting' => 'smooth_scroll_enabled',
				'value'   => true,
			),
		),
	)
);

new \Kirki\Field\Slider(
	array(
		'settings'        => 'smooth_scroll_damping',
		'label'           => esc_html__( 'Damping', 'rhye' ),
		'description'     => esc_html__( 'The lower the value is, the more smooth the scrolling will be.', 'rhye' ),
		'tooltip'         => esc_html__( 'A float value between 0.0 and 1.0 defining the momentum reduction damping factor.', 'rhye' ),
		'section'         => 'smooth_scroll',
		'default'         => 0.12,
		'priority'        => $priority++,
		'choices'         => array(
			'min'  => 0,
			'max'  => 1,
			'step' => 0.01,
		),
		'active_callback' => $active_callback_is_smooth_scrollbar,
	)
);

new \Kirki\Field\Checkbox_Switch(
	array(
		'settings'        => 'smooth_scroll_render_by_pixels_enabled',
		'label'           => esc_html__( 'Enable Render by Pixels', 'rhye' ),
		'description'     => esc_html__( 'Render every frame in integer pixel values, set to true to improve scrolling performance.', 'rhye' ),
		'section'         => 'smooth_scroll',
		'default'         => true,
		'priority'        => $priority++,
		'active_callback' => $active_callback_is_smooth_scrollbar,
	)
);

new \Kirki\Field\Checkbox_Switch(
	array(
		'settings'        => 'smooth_scroll_plugin_easing_enabled',
		'label'           => esc_html__( 'Enable Edge Easing', 'rhye' ),
		'description'     => esc_html__( 'The scroll will slow down with ease when reaching the page edges.', 'rhye' ),
		'section'         => 'smooth_scroll',
		'default'         => true,
		'priority'        => $priority++,
		'active_callback' => $active_callback_is_smooth_scrollbar,
	)
);

new \Kirki\Field\Slider(
	array(
		'settings'        => 'smooth_scroll_lenis_lerp',
		'label'           => esc_html__( 'Linear Interpolation', 'rhye' ),
		'description'     => esc_html__( 'Lerp value between 0 and 1', 'rhye' ),
		'section'         => 'smooth_scroll',
		'default'         => 0.12,
		'priority'        => $priority++,
		'choices'         => array(
			'min'  => 0,
			'max'  => 1,
			'step' => 0.01,
		),
		'active_callback' => $active_callback_is_lenis,
	)
);

new \Kirki\Field\Select(
	array(
		'settings'        => 'smooth_scroll_anchor_offset',
		'label'           => esc_html__( 'Anchor Scrolling Offset', 'rhye' ),
		'description'     => esc_html__( 'Vertical offset for anchor scrolling (in pixels)', 'rhye' ),
		'section'         => 'smooth_scroll',
		'default'         => 'header',
		'priority'        => $priority++,
		'choices'         => array(
			'header' => esc_html__( 'By Header Height', 'rhye' ),
			'custom' => esc_html__( 'By Custom Value', 'rhye' ),
		),
		'active_callback' => array(
			array(
				'setting' => 'smooth_scroll_enabled',
				'value'   => true,
			),
		),
	)
);

new \Kirki\Field\Slider(
	array(
		'settings'        => 'smooth_scroll_anchor_offset_custom',
		'label'           => esc_html__( 'Custom Offset', 'rhye' ),
		'description'     => esc_html__( 'Custom offset for anchor scrolling (in pixels)', 'rhye' ),
		'section'         => 'smooth_scroll',
		'default'         => 0,
		'priority'        => $priority++,
		'choices'         => array(
			'min'  => -300,
			'max'  => 300,
			'step' => 1,
		),
		'active_callback' => array(
			array(
				'setting' => 'smooth_scroll_enabled',
				'value'   => true,
			),
			array(
				'setting' => 'smooth_scroll_anchor_offset',
				'value'   => 'custom',
			),
		),
	)
);
