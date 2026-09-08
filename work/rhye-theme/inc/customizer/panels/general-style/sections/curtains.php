<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;

/**
 * Curtains style
 */
new \Kirki\Field\Radio_Buttonset(
	array(
		'settings' => 'curtains_style',
		'label'    => esc_html__( 'Curtains Style', 'rhye' ),
		'section'  => 'curtains',
		'default'  => 'curved',
		'priority' => $priority++,
		'choices'  => array(
			'curved'   => esc_html__( 'Curved', 'rhye' ),
			'straight' => esc_html__( 'Straight', 'rhye' ),
		),
	)
);
