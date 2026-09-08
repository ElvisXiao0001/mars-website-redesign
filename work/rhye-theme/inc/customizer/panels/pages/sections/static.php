<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;

new \Kirki\Field\Checkbox_Switch(
	array(
		'settings' => 'static_page_gutters_enabled',
		'label'    => esc_html__( 'Enable Page Gutters', 'rhye' ),
		'section'  => 'static_pages',
		'default'  => true,
		'priority' => $priority++,
	)
);
