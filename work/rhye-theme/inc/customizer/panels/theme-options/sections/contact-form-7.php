<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;

new \Kirki\Field\Checkbox_Switch(
	array(
		'settings'    => 'cf_7_modals_enabled',
		'label'       => esc_html__( 'Enable Custom Modal Windows', 'rhye' ),
		'description' => esc_html__( 'Styled success and error windows that appear when a visitor submits a form', 'rhye' ),
		'section'     => 'contact_form_7',
		'default'     => true,
		'priority'    => $priority++,
	)
);
