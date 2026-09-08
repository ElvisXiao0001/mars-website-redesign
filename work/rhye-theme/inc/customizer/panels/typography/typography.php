<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;

/**
 * Body Text & Paragraph
 */
new \Kirki\Section(
	'paragraph',
	array(
		'title'    => esc_html__( 'Body Text & Paragraph', 'rhye' ),
		'panel'    => 'typography',
		'priority' => $priority ++,
	)
);
get_template_part( '/inc/customizer/panels/typography/sections/paragraph' );

/**
 * XL Headings
 */
new \Kirki\Section(
	'xl_headings',
	array(
		'title'    => esc_html__( 'XL & XXL Headings', 'rhye' ),
		'panel'    => 'typography',
		'priority' => $priority ++,
	)
);
get_template_part( '/inc/customizer/panels/typography/sections/xl-headings' );

/**
 * h1-h6 Headings
 */
new \Kirki\Section(
	'h1_h6_headings',
	array(
		'title'    => esc_html__( 'H1 - H6 Headings', 'rhye' ),
		'panel'    => 'typography',
		'priority' => $priority ++,
	)
);
get_template_part( '/inc/customizer/panels/typography/sections/h1-h6-headings' );

/**
 * Subheading
 */
new \Kirki\Section(
	'subheading',
	array(
		'title'    => esc_html__( 'Subheading', 'rhye' ),
		'panel'    => 'typography',
		'priority' => $priority ++,
	)
);
get_template_part( '/inc/customizer/panels/typography/sections/subheading' );

/**
 * Blockquote
 */
new \Kirki\Section(
	'blockquote',
	array(
		'title'    => esc_html__( 'Blockquote', 'rhye' ),
		'panel'    => 'typography',
		'priority' => $priority ++,
	)
);
get_template_part( '/inc/customizer/panels/typography/sections/blockquote' );

/**
 * Dropcap
 */
new \Kirki\Section(
	'drop_cap',
	array(
		'title'    => esc_html__( 'Drop Cap', 'rhye' ),
		'panel'    => 'typography',
		'priority' => $priority ++,
	)
);
get_template_part( '/inc/customizer/panels/typography/sections/drop-cap' );

/**
 * Text Logo
 */
new \Kirki\Section(
	'text_logo',
	array(
		'title'    => esc_html__( 'Text Logo', 'rhye' ),
		'panel'    => 'typography',
		'priority' => $priority ++,
	)
);
get_template_part( '/inc/customizer/panels/typography/sections/text-logo' );

/**
 * Slider Counters
 */
new \Kirki\Section(
	'typography_counters',
	array(
		'title'    => esc_html__( 'Counters', 'rhye' ),
		'panel'    => 'typography',
		'priority' => $priority ++,
	)
);
get_template_part( '/inc/customizer/panels/typography/sections/counters' );

/**
 * Miscellaneous Elements
 */
new \Kirki\Section(
	'typography_social_icons',
	array(
		'title'    => esc_html__( 'Social Icons', 'rhye' ),
		'panel'    => 'typography',
		'priority' => $priority ++,
	)
);
get_template_part( '/inc/customizer/panels/typography/sections/social-icons' );

/**
 * Miscellaneous Elements
 */
new \Kirki\Section(
	'typography_misc',
	array(
		'title'    => esc_html__( 'Misc', 'rhye' ),
		'panel'    => 'typography',
		'priority' => $priority ++,
	)
);
get_template_part( '/inc/customizer/panels/typography/sections/misc' );
