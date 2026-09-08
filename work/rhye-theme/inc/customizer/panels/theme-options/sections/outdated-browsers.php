<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;

new \Kirki\Field\Checkbox_Switch(
	array(
		'settings'    => 'outdated_browsers_enabled',
		'label'       => esc_html__( 'Enable Outdated Browser Notification', 'rhye' ),
		'description' => esc_html__( 'The theme is compatible only with modern browsers. In case a visitor landed on the website using an outdated browser (like Internet Explorer) there will appear a banner with the proposal to update a browser.', 'rhye' ),
		'section'     => 'outdated_browsers',
		'default'     => false,
		'priority'    => $priority++,
	)
);

new \Kirki\Field\Generic(
	array(
		'settings'        => 'outdated_browsers_prevew_button',
		'section'         => 'outdated_browsers',
		'priority'        => $priority++,
		'default'         => esc_html__( 'Simulate Outdated Browser', 'rhye' ),
		'choices'         => array(
			'element' => 'input',
			'type'    => 'button',
			'class'   => 'button button-secondary',
			'onclick' => 'javascript:wp.customize.previewer.preview.iframe[0].contentWindow.document.dispatchEvent(new CustomEvent("arts/outdatedbrowser/test"));',
		),
		'active_callback' => array(
			array(
				'setting'  => 'outdated_browsers_enabled',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);
