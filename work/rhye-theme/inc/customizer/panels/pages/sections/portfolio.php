<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;

new \Kirki\Field\Generic(
	array(
		'label'       => esc_html__( 'This panel has been moved', 'rhye' ),
		'description' => sprintf(
			'%1$s <a href="javascript:wp.customize.panel(\'bottom_nav\').focus();">%2$s</a> %3$s',
			esc_html__( 'This panel has been moved to', 'rhye' ),
			esc_html__( 'Bottom Navigation', 'rhye' ),
			esc_html__( 'panel.', 'rhye' )
		),
		'settings'    => 'portfolio_generic_heading' . $priority,
		'section'     => 'portfolio',
		'priority'    => $priority++,
		'choices'     => array(
			'element' => 'span',
		),
	)
);
