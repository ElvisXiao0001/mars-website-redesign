<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;

new \Kirki\Field\Checkbox_Switch(
	array(
		'settings'    => 'mobile_bar_fix_enabled',
		'label'       => esc_html__( 'Fit Fullscreen Height Elements', 'rhye' ),
		'description' => esc_html__( 'This option calculates the full height elements to fit the entire screen considering the height of bottom navigation bar.', 'rhye' ),
		'section'     => 'layout',
		'default'     => true,
		'priority'    => $priority++,
	)
);

new \Kirki\Field\Checkbox_Switch(
	array(
		'settings'        => 'mobile_bar_fix_update',
		'label'           => esc_html__( 'Update Fullscreen Height Elements on Window Resize', 'rhye' ),
		'description'     => esc_html__( 'Disable to avoid page jump when scrolling on mobile devices.', 'rhye' ),
		'section'         => 'layout',
		'default'         => true,
		'priority'        => $priority++,
		'active_callback' => array(
			array(
				'setting'  => 'mobile_bar_fix_enabled',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);
