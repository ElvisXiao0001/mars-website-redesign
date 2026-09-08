<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Arts\Utilities\Utilities;

add_action( 'tgmpa_register', 'arts_register_required_plugins' );
if ( ! function_exists( 'arts_register_required_plugins' ) ) {
	/**
	 * Registers the required plugins for the theme.
	 *
	 * Defines an array of plugins to be installed and activated,
	 * along with their configuration settings. It uses the TGMPA library to
	 * handle the plugin installation and activation process.
	 *
	 * @return void
	 */
	function arts_register_required_plugins() {
		$theme_slug             = ARTS_THEME_SLUG;
		$rhye_core_download_url = Utilities::get_license_args_url( "https://artemsemkin.com/wp-json/edd/v1/file/{$theme_slug}/core-plugin", "{$theme_slug}_license_key" );

		/*
		 * Array of plugin arrays. Required keys are name and slug.
		 * If the source is NOT from the .org repo, then source is also required.
		 */
		$plugins = array(
			array(
				'name'     => esc_html__( 'Advanced Custom Fields PRO', 'rhye' ),
				'slug'     => 'advanced-custom-fields-pro',
				'source'   => esc_url_raw( 'https://artemsemkin.com/wp-json/edd/v1/file/acf-pro/plugin' ),
				'required' => true,
			),
			array(
				'name'     => esc_html__( 'Contact Form 7', 'rhye' ),
				'slug'     => 'contact-form-7',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'Elementor', 'rhye' ),
				'slug'     => 'elementor',
				'required' => true,
			),
			array(
				'name'     => esc_html__( 'Kirki', 'rhye' ),
				'slug'     => 'kirki',
				'source'   => esc_url_raw( 'https://artemsemkin.com/wp-json/edd/v1/file/kirki/plugin' ),
				'version'  => '5.2.3',
				'required' => true,
			),
			array(
				'name'     => esc_html__( 'Intuitive Custom Post Order', 'rhye' ),
				'slug'     => 'intuitive-custom-post-order',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'Rhye Core', 'rhye' ),
				'slug'     => 'rhye-core',
				'source'   => esc_url_raw( $rhye_core_download_url ),
				'version'  => '4.2.8',
				'required' => true,
			),
		);

		/*
		* Array of configuration settings. Amend each line as needed.
		*/
		$config = array(
			'id'           => ARTS_THEME_SLUG,                 // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',                      // Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,                   // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.
		);

		tgmpa( $plugins, $config );
	}
}
