<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;

new \Kirki\Section(
	'ajax_transitions',
	array(
		'title'    => esc_html__( 'AJAX Transitions', 'rhye' ),
		'priority' => $priority ++,
		'panel'    => 'theme_options',
	)
);
get_template_part( '/inc/customizer/panels/theme-options/sections/ajax-transitions' );

new \Kirki\Section(
	'contact_form_7',
	array(
		'title'    => esc_html__( 'Contact Form 7', 'rhye' ),
		'priority' => $priority ++,
		'panel'    => 'theme_options',
	)
);
get_template_part( '/inc/customizer/panels/theme-options/sections/contact-form-7' );

new \Kirki\Section(
	'elementor_header_footer_builder',
	array(
		'title'    => esc_html__( 'Elementor Header & Footer Builder', 'rhye' ),
		'priority' => $priority ++,
		'panel'    => 'theme_options',
	)
);
get_template_part( '/inc/customizer/panels/theme-options/sections/elementor-header-footer-builder' );

new \Kirki\Section(
	'galleries',
	array(
		'title'    => esc_html__( 'Galleries', 'rhye' ),
		'priority' => $priority ++,
		'panel'    => 'theme_options',
	)
);
get_template_part( '/inc/customizer/panels/theme-options/sections/galleries' );

if ( class_exists( 'SitePress' ) || class_exists( 'Polylang' ) || class_exists( 'TRP_Translate_Press' ) ) {
	new \Kirki\Section(
		'multilingual_cms',
		array(
			'title'    => esc_html__( 'Multilingual CMS', 'rhye' ),
			'priority' => $priority ++,
			'panel'    => 'theme_options',
		)
	);
	get_template_part( '/inc/customizer/panels/theme-options/sections/multilingual-cms' );
}

new \Kirki\Section(
	'cursor_follower',
	array(
		'title'    => esc_html__( 'Mouse Cursor', 'rhye' ),
		'priority' => $priority ++,
		'panel'    => 'theme_options',
	)
);
get_template_part( '/inc/customizer/panels/theme-options/sections/cursor-follower' );

new \Kirki\Section(
	'outdated_browsers',
	array(
		'title'    => esc_html__( 'Outdated Browsers', 'rhye' ),
		'priority' => $priority ++,
		'panel'    => 'theme_options',
	)
);
get_template_part( '/inc/customizer/panels/theme-options/sections/outdated-browsers' );

new \Kirki\Section(
	'performance',
	array(
		'title'    => esc_html__( 'Performance & Lazy Images', 'rhye' ),
		'priority' => $priority ++,
		'panel'    => 'theme_options',
	)
);
get_template_part( '/inc/customizer/panels/theme-options/sections/performance' );

new \Kirki\Section(
	'preloader',
	array(
		'title'    => esc_html__( 'Preloader', 'rhye' ),
		'priority' => $priority ++,
		'panel'    => 'theme_options',
	)
);
get_template_part( '/inc/customizer/panels/theme-options/sections/preloader' );

new \Kirki\Section(
	'scroll_down',
	array(
		'title'    => esc_html__( 'Scroll Down', 'rhye' ),
		'priority' => $priority ++,
		'panel'    => 'theme_options',
	)
);
get_template_part( '/inc/customizer/panels/theme-options/sections/scroll-down' );

new \Kirki\Section(
	'smooth_scroll',
	array(
		'title'    => esc_html__( 'Smooth Scroll', 'rhye' ),
		'priority' => $priority ++,
		'panel'    => 'theme_options',
	)
);
get_template_part( '/inc/customizer/panels/theme-options/sections/smooth-scroll' );
