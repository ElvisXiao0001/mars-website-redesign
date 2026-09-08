<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;

/**
 * Sidebar Position
 */
new \Kirki\Field\Radio_Buttonset(
	array(
		'settings'    => 'sidebar_position',
		'label'       => esc_html__( 'Sidebar Position', 'rhye' ),
		'description' => esc_html__( ' This option has an effect only on desktop. On mobile the sidebar is always below the content.', 'rhye' ),
		'tooltip'     => esc_html__( 'To remove sidebar from blog, remove all the widgets placed into it.', 'rhye' ),
		'section'     => 'blog_sidebar',
		'default'     => 'right_side',
		'priority'    => $priority++,
		'choices'     => array(
			'left_side'  => esc_html__( 'Left Side', 'rhye' ),
			'right_side' => esc_html__( 'Right Side', 'rhye' ),
		),
		'transport'   => 'postMessage',
	)
);
