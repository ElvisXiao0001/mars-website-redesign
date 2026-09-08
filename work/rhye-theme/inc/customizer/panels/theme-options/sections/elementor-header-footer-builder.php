<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Header_Footer_Elementor' ) ) {
	return;
}

$priority = 1;

new \Kirki\Field\Generic(
	array(
		'label'    => esc_html__( 'Header Rendering', 'rhye' ),
		'settings' => 'elementor_header_footer_builder_generic_heading' . $priority,
		'section'  => 'elementor_header_footer_builder',
		'priority' => $priority++,
		'choices'  => array(
			'element' => 'span',
		),
	)
);

new \Kirki\Field\Radio(
	array(
		'settings' => 'elementor_header_footer_builder_header_render_place',
		'section'  => 'elementor_header_footer_builder',
		'default'  => 'outside',
		'priority' => $priority++,
		'choices'  => array(
			'outside' => esc_html__( 'Replace the original theme header', 'rhye' ),
			'inside'  => esc_html__( 'Remove the original theme header. Add custom header to the main scrolling container', 'rhye' ),
		),
	)
);

new \Kirki\Field\Checkbox_Switch(
	array(
		'settings'        => 'elementor_header_footer_builder_header_wrapper_enabled',
		'label'           => esc_html__( 'Wrap Custom Header with Theme Header Container', 'rhye' ),
		'description'     => sprintf(
			'%1s %2s',
			esc_html__( 'Enable to handle the absolute/fixed positioning set from theme header options and AJAX transitions. This will wrap your custom header with the following element:', 'rhye' ),
			'<pre>&lt;div id=&quot;page-header&quot;&gt;...&lt;/div&gt;</pre>'
		),
		'section'         => 'elementor_header_footer_builder',
		'default'         => true,
		'priority'        => $priority++,
		'active_callback' => array(
			array(
				'setting' => 'elementor_header_footer_builder_header_render_place',
				'value'   => 'outside',
			),
		),
	)
);

new \Kirki\Field\Generic(
	array(
		'settings' => 'elementor_header_footer_builder_generic_heading' . $priority,
		'section'  => 'elementor_header_footer_builder',
		'priority' => $priority++,
		'choices'  => array(
			'element' => 'hr',
		),
	)
);

new \Kirki\Field\Generic(
	array(
		'label'    => esc_html__( 'Footer Rendering', 'rhye' ),
		'settings' => 'elementor_header_footer_builder_generic_heading' . $priority,
		'section'  => 'elementor_header_footer_builder',
		'priority' => $priority++,
		'choices'  => array(
			'element' => 'span',
		),
	)
);

new \Kirki\Field\Checkbox(
	array(
		'settings' => 'elementor_header_footer_builder_footer_wrapper_enabled',
		'label'    => esc_html__( 'Add a wrapper container', 'rhye' ),
		'section'  => 'elementor_header_footer_builder',
		'default'  => true,
		'priority' => $priority++,
	)
);
