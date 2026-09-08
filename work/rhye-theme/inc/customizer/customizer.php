<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Kirki' ) ) {
	return;
}

// Don't gather telemetry data
add_filter( 'kirki_telemetry', '__return_false' );

add_action( 'after_setup_theme', 'arts_register_customizer_panels' );
if ( ! function_exists( 'arts_register_customizer_panels' ) ) {
	/**
	 * Register extra Customizer panels via Kirki
	 *
	 * @return void
	 */
	function arts_register_customizer_panels() {
		$priority = 1;

		/** @disregard P1009 Assuming that the class exists in here */
		Kirki::add_config(
			'arts',
			array(
				'capability'  => 'edit_theme_options',
				'option_type' => 'theme_mod',
			)
		);

		/**
		 * Panel General Style
		 */
		new \Kirki\Panel(
			'general-style',
			array(
				'priority' => $priority++,
				'title'    => esc_html__( 'General Style', 'rhye' ),
				'icon'     => 'dashicons-admin-appearance',
			)
		);
		get_template_part( '/inc/customizer/panels/general-style/general-style' );

		/**
		 * Panel Typography
		 */
		new \Kirki\Panel(
			'typography',
			array(
				'priority' => $priority++,
				'title'    => esc_html__( 'Typography', 'rhye' ),
				'icon'     => 'dashicons-editor-paragraph',
			)
		);
		get_template_part( '/inc/customizer/panels/typography/typography' );

		/**
		 * Panel Options
		 */
		new \Kirki\Panel(
			'theme_options',
			array(
				'priority' => $priority++,
				'title'    => esc_html__( 'Theme Options', 'rhye' ),
				'icon'     => 'dashicons-admin-tools',
			)
		);
		get_template_part( '/inc/customizer/panels/theme-options/theme-options' );

		/**
		 * Panel Header
		 */
		new \Kirki\Panel(
			'header',
			array(
				'priority' => $priority++,
				'title'    => esc_html__( 'Header', 'rhye' ),
				'icon'     => 'dashicons-arrow-up-alt',
			)
		);
		get_template_part( '/inc/customizer/panels/header/header' );

		/**
		 * Section Bottom Navigation
		 */
		new \Kirki\Section(
			'bottom_nav',
			array(
				'priority' => $priority++,
				'title'    => esc_html__( 'Bottom Navigation', 'rhye' ),
				'icon'     => 'dashicons-editor-insertmore',
			)
		);
		get_template_part( '/inc/customizer/sections/bottom-navigation' );

		/**
		 * Panel Footer
		 */
		new \Kirki\Panel(
			'footer',
			array(
				'priority' => $priority++,
				'title'    => esc_html__( 'Footer', 'rhye' ),
				'icon'     => 'dashicons-arrow-down-alt',
			)
		);
		get_template_part( '/inc/customizer/panels/footer/footer' );

		/**
		 * Panel Pages
		 */
		new \Kirki\Panel(
			'pages',
			array(
				'title'    => esc_html__( 'Pages', 'rhye' ),
				'priority' => $priority++,
				'icon'     => 'dashicons-media-document',
			)
		);
		get_template_part( '/inc/customizer/panels/pages/pages' );

		/**
		 * Panel Blog
		 */
		new \Kirki\Panel(
			'blog',
			array(
				'priority' => $priority++,
				'title'    => esc_html__( 'Blog', 'rhye' ),
				'icon'     => 'dashicons-editor-bold',
			)
		);
		get_template_part( '/inc/customizer/panels/blog/blog' );

		/**
		 * Extend Title & Tagline Section
		 */
		get_template_part( 'inc/customizer/title-tagline/title-tagline' );
	}
}

// Pin Kirki at v5.2.3 — block WP.org's v6.x page-builder update from ever reaching customers.
add_filter( 'pre_set_site_transient_update_plugins', 'arts_freeze_kirki_version' );
if ( ! function_exists( 'arts_freeze_kirki_version' ) ) {
	/**
	 * Move any incoming Kirki update entry from ->response to ->no_update so
	 * WordPress treats Kirki as up-to-date across every UI path (Plugins screen,
	 * admin bar, dashboard, auto-updates, manual "Update Now", WP-CLI).
	 *
	 * @param object $transient The update_plugins site transient before persistence.
	 * @return object
	 */
	function arts_freeze_kirki_version( $transient ) {
		if ( ! is_object( $transient ) ) {
			return $transient;
		}

		$kirki_file = 'kirki/kirki.php';

		if ( isset( $transient->response[ $kirki_file ] ) ) {
			$transient->no_update[ $kirki_file ]              = $transient->response[ $kirki_file ];
			$transient->no_update[ $kirki_file ]->new_version =
				$transient->response[ $kirki_file ]->new_version ?? '5.2.3';
			unset( $transient->response[ $kirki_file ] );
		}

		return $transient;
	}
}
