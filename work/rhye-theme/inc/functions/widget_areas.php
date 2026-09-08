<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'widgets_init', 'arts_register_widget_areas' );
if ( ! function_exists( 'arts_register_widget_areas' ) ) {
	/**
	 * Register theme widget areas
	 *
	 * @return void
	 */
	function arts_register_widget_areas() {
		$menu_style           = get_theme_mod( 'menu_style', 'classic' );
		$footer_columns_upper = get_theme_mod( 'footer_columns_upper', 4 );
		$footer_columns_lower = get_theme_mod( 'footer_columns_lower', 3 );

		/**
		 * Header Area (Fullscreen Overlay Menu)
		 */
		register_sidebar(
			array(
				'name'          => $menu_style === 'fullscreen' ? esc_html__( 'Fullscreen Menu Widgets', 'rhye' ) : esc_html__( 'Mobile Fullscreen Menu Widgets', 'rhye' ),
				'id'            => 'header-sidebar',
				'description'   => $menu_style === 'fullscreen' ? esc_html__( 'Appears in the site header', 'rhye' ) : sprintf(
					'%1s <strong>%2s</strong> %3s',
					esc_html__( 'Appears', 'rhye' ),
					esc_html__( 'only on mobile screens', 'rhye' ),
					esc_html__( 'in the site header', 'rhye' )
				),
				'before_widget' => '<div class="col-lg-4 col-gutters header__widget split-text" id="%1$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="subheading mb-0-5">',
				'after_title'   => '</div>',
			)
		);

		/**
		 * Blog Area (Sidebar)
		 */
		register_sidebar(
			array(
				'name'          => esc_html__( 'Blog Sidebar', 'rhye' ),
				'id'            => 'blog-sidebar',
				'description'   => esc_html__( 'Appears in blog.', 'rhye' ),
				'before_widget' => '<section class="widget %2$s">',
				'after_widget'  => '</section>',
			)
		);

		/**
		 * Footer Area
		 */
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer Upper: Large Column', 'rhye' ),
				'id'            => 'footer-sidebar-upper-large',
				'description'   => esc_html__( 'Appears in Page Footer Upper Section.', 'rhye' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
			)
		);
		for ( $i = 1; $i <= $footer_columns_upper; $i++ ) {
			register_sidebar(
				array(
					'name'          => sprintf( esc_html__( 'Footer Upper: %s Column', 'rhye' ), $i ),
					'id'            => 'footer-sidebar-upper' . $i,
					'description'   => esc_html__( 'Appears in Page Footer Upper Section.', 'rhye' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
				)
			);
		}
		for ( $i = 1; $i <= $footer_columns_lower; $i++ ) {
			register_sidebar(
				array(
					'name'          => sprintf( esc_html__( 'Footer Lower: %s Column', 'rhye' ), $i ),
					'id'            => 'footer-sidebar-lower' . $i,
					'description'   => esc_html__( 'Appears in Page Footer Lower Section.', 'rhye' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
				)
			);
		}

		/**
		 * Header Language Switchers (Multilingual Plugins)
		 */
		if ( class_exists( 'SitePress' ) || class_exists( 'Polylang' ) || class_exists( 'TRP_Translate_Press' ) ) {
			register_sidebar(
				array(
					'name'          => esc_html__( 'Language Switcher Area', 'rhye' ),
					'id'            => 'lang-switcher-sidebar',
					'description'   => esc_html__( 'Appears in the top menu.', 'rhye' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
				)
			);
		}
	}
}
