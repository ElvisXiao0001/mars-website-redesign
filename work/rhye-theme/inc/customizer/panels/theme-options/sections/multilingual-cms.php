<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;

new \Kirki\Field\Checkbox_Switch(
	array(
		'settings' => 'multilingual_cms_elementor_query_all_languages',
		'label'    => esc_html__( 'Query Posts for All Languages in Elementor Dynamic Widgets', 'rhye' ),
		'section'  => 'multilingual_cms',
		'default'  => false,
		'priority' => $priority++,
	)
);
