<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority    = 1;
$max_columns = 4;
$suffix      = '_upper';

/**
 * Footer Layout
 */
new \Kirki\Field\Radio_Buttonset(
	array(
		'settings'  => 'footer_layout' . $suffix,
		'label'     => esc_html__( 'Layout', 'rhye' ),
		'section'   => 'section_footer' . $suffix,
		'default'   => '12',
		'priority'  => $priority++,
		'choices'   => array(
			'12'  => esc_html__( 'Fullwidth', 'rhye' ),
			'7_5' => esc_html__( '7  /  5', 'rhye' ),
			'6_6' => esc_html__( '6  /  6', 'rhye' ),
			'5_7' => esc_html__( '5  /  7', 'rhye' ),
		),
		'transport' => 'refresh',
	)
);

/**
 * Footer Columns
 */
new \Kirki\Field\Slider(
	array(
		'settings'    => 'footer_columns' . $suffix,
		'label'       => esc_html__( 'Number of Columns', 'rhye' ),
		'description' => sprintf(
			'%1$s <a href="javascript:wp.customize.panel(\'widgets\').focus();">%2$s</a>',
			esc_html__( 'This setting creates a widget area per each column. You can edit your widgets from', 'rhye' ),
			esc_html__( 'Widgets panel.', 'rhye' )
		),
		'section'     => 'section_footer' . $suffix,
		'default'     => 4,
		'priority'    => $priority++,
		'choices'     => array(
			'min'  => 1,
			'max'  => $max_columns,
			'step' => 1,
		),
		'transport'   => 'refresh',
	)
);

/**
 * Mobile Ordering Info
 */
new \Kirki\Field\Custom(
	array(
		'settings'        => 'footer_columns_info' . $suffix,
		'label'           => esc_html__( 'Mobile Columns Stack Order', 'rhye' ),
		'tooltip'         => esc_html__( 'Toggle mobile view in Customizer to preview the result', 'rhye' ),
		'description'     => esc_html__( 'You can separately control how your columns stack on mobile screens.', 'rhye' ),
		'section'         => 'section_footer' . $suffix,
		'priority'        => $priority++,
		'active_callback' => array(
			array(
				'setting'  => 'footer_columns' . $suffix,
				'operator' => '>',
				'value'    => 1,
			),
		),
	)
);

/**
 * Mobile Column Order
 */
for ( $i = 1; $i <= $max_columns; $i++ ) {
	$descr = sprintf( '%1$s (%2$s %3$s)', esc_html__( 'Mobile Order', 'rhye' ), esc_html__( 'Column', 'rhye' ), $i );

	new \Kirki\Field\Slider(
		array(
			'settings'        => 'order_column_' . $i . $suffix,
			'description'     => $descr,
			'section'         => 'section_footer' . $suffix,
			'default'         => 1,
			'priority'        => $priority++,
			'choices'         => array(
				'min'  => 1,
				'max'  => $max_columns,
				'step' => 1,
			),
			'active_callback' => array(
				array(
					'setting'  => 'footer_columns' . $suffix,
					'operator' => '>=',
					'value'    => $i,
				),
				array(
					'setting'  => 'footer_columns' . $suffix,
					'operator' => '!=',
					'value'    => 1,
				),
			),
		)
	);
}

/**
 * Adjust Text Alignment
 */
new \Kirki\Field\Checkbox(
	array(
		'settings' => 'text_align_enabled' . $suffix,
		'label'    => esc_html__( 'Adjust Widgets Text Alignment Depending on Columns Layout', 'rhye' ),
		'section'  => 'section_footer' . $suffix,
		'default'  => false,
		'priority' => $priority++,
	)
);

/**
 * Border
 */
new \Kirki\Field\Checkbox(
	array(
		'settings'  => 'footer_border_enabled' . $suffix,
		'label'     => esc_html__( 'Enable Section Divider', 'rhye' ),
		'section'   => 'section_footer' . $suffix,
		'default'   => true,
		'priority'  => $priority++,
		'tooltip'   => esc_html__( 'This option may be overriden for the current page from Elementor document settings.', 'rhye' ),
		'transport' => 'postMessage',
	)
);
