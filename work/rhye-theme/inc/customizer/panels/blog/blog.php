<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;

/**
 * Style
 */
new \Kirki\Section(
	'blog_style',
	array(
		'title'    => esc_html__( 'Style', 'rhye' ),
		'priority' => $priority ++,
		'panel'    => 'blog',
	)
);
get_template_part( '/inc/customizer/panels/blog/sections/style' );

/**
 * Blog Page
 */
new \Kirki\Section(
	'blog_layout',
	array(
		'title'    => esc_html__( 'Blog Page', 'rhye' ),
		'priority' => $priority ++,
		'panel'    => 'blog',
	)
);
get_template_part( '/inc/customizer/panels/blog/sections/blog-page' );

/**
 * Single Post
 */
new \Kirki\Section(
	'blog_post',
	array(
		'title'    => esc_html__( 'Single Post', 'rhye' ),
		'priority' => $priority ++,
		'panel'    => 'blog',
	)
);
get_template_part( '/inc/customizer/panels/blog/sections/post' );

/**
 * Sidebar
 */
new \Kirki\Section(
	'blog_sidebar',
	array(
		'title'    => esc_html__( 'Sidebar', 'rhye' ),
		'priority' => $priority ++,
		'panel'    => 'blog',
	)
);
get_template_part( '/inc/customizer/panels/blog/sections/sidebar' );
