<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;

/**
 * Indicators
 */
new \Kirki\Field\Generic(
	array(
		'label'    => esc_html__( 'Indicators', 'rhye' ),
		'settings' => 'galleries_generic_heading' . $priority,
		'section'  => 'galleries',
		'priority' => $priority++,
		'choices'  => array(
			'element' => 'span',
		),
	)
);

new \Kirki\Field\Checkbox(
	array(
		'settings' => 'galleries_counter_enabled',
		'label'    => esc_html__( 'Enable Slides Counter', 'rhye' ),
		'section'  => 'galleries',
		'default'  => true,
		'priority' => $priority++,
	)
);

new \Kirki\Field\Checkbox(
	array(
		'settings' => 'galleries_captions_enabled',
		'label'    => esc_html__( 'Enable Captions', 'rhye' ),
		'section'  => 'galleries',
		'default'  => true,
		'priority' => $priority++,
	)
);

new \Kirki\Field\Generic(
	array(
		'settings' => 'galleries_generic_divider' . $priority,
		'section'  => 'galleries',
		'priority' => $priority++,
		'choices'  => array(
			'element' => 'hr',
		),
	)
);

/**
 * Controls
 */
new \Kirki\Field\Generic(
	array(
		'label'    => esc_html__( 'Controls', 'rhye' ),
		'settings' => 'galleries_generic_heading' . $priority,
		'section'  => 'galleries',
		'priority' => $priority++,
		'choices'  => array(
			'element' => 'span',
		),
	)
);

new \Kirki\Field\Checkbox(
	array(
		'settings' => 'galleries_close_on_scroll_enabled',
		'label'    => esc_html__( 'Enable "Close on Scroll" feature', 'rhye' ),
		'section'  => 'galleries',
		'default'  => true,
		'priority' => $priority++,
	)
);

new \Kirki\Field\Checkbox(
	array(
		'settings' => 'galleries_zoom_enabled',
		'label'    => esc_html__( 'Enable Zoom Functionality', 'rhye' ),
		'section'  => 'galleries',
		'default'  => true,
		'priority' => $priority++,
	)
);

new \Kirki\Field\Checkbox(
	array(
		'settings' => 'galleries_arrows_enabled',
		'label'    => esc_html__( 'Enable Prev & Next Arrows', 'rhye' ),
		'section'  => 'galleries',
		'default'  => true,
		'priority' => $priority++,
	)
);

new \Kirki\Field\Checkbox(
	array(
		'settings' => 'galleries_fullscreen_button_enabled',
		'label'    => esc_html__( 'Enable Fullscreen Toggle', 'rhye' ),
		'section'  => 'galleries',
		'default'  => true,
		'priority' => $priority++,
	)
);

new \Kirki\Field\Checkbox(
	array(
		'settings' => 'galleries_close_button_enabled',
		'label'    => esc_html__( 'Enable Close Button', 'rhye' ),
		'section'  => 'galleries',
		'default'  => true,
		'priority' => $priority++,
	)
);
