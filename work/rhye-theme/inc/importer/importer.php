<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\WizardSetup\Plugin;

add_filter( 'arts/wizard_setup/plugin/config', 'arts_wizard_setup_plugin_config' );
if ( ! function_exists( 'arts_wizard_setup_plugin_config' ) ) {
	/**
	 * Configure the ArtsWizardSetup plugin.
	 *
	 * @param array $config The configuration array to be modified.
	 *
	 * @return array The modified configuration array.
	 */
	function arts_wizard_setup_plugin_config( $config ) {
		$custom_post_types = array(
			'arts_portfolio_item',
			'arts_service',
			'arts_album',
		);

		$custom_taxonomies = array(
			'arts_portfolio_category',
		);

		$config['license_required']                = true;
		$config['regenerate_thumbnails_on_import'] = true;

		// Setup demo data
		if ( ! isset( $config['setup_demo_data'] ) ) {
			$config['setup_demo_data'] = array();
		}

		if ( isset( $config['setup_demo_data'][0] ) ) {
			$config['setup_demo_data'][0]['add_license_key_args'] = true;
		}

		/** Setup child theme */
		if ( ! isset( $config['setup_child_theme'] ) ) {
			$config['setup_child_theme'] = array();
		}

		$config['setup_child_theme']['screenshot'] = get_template_directory() . '/inc/importer/screenshot.png';

		/** Setup Elementor */
		if ( ! isset( $config['setup_elementor'] ) ) {
			$config['setup_elementor'] = array();
		}

		// Target the Rhye kit by name so a leftover kit from a previous theme isn't picked up instead
		$config['setup_elementor']['set_active_kit'] = 'Rhye Theme Kit';

		// Update CPT Support
		if ( ! isset( $config['setup_elementor']['editable_post_types'] ) || ! is_array( $config['setup_elementor']['editable_post_types'] ) ) {
			$config['setup_elementor']['editable_post_types'] = array();
		}

		$config['setup_elementor']['editable_post_types'] = $custom_post_types;

		// Update options
		if ( ! isset( $config['setup_elementor']['options'] ) || ! is_array( $config['setup_elementor']['options'] ) ) {
			$config['setup_elementor']['options'] = array();
		}

		// Update default space between widgets
		$config['setup_elementor']['options']['elementor_space_between_widgets'] = '20';

		// Update content width
		$config['setup_elementor']['options']['elementor_container_width'] = '1100';

		// Update breakpoints
		$config['setup_elementor']['options']['elementor_viewport_lg'] = '992';
		$config['setup_elementor']['options']['elementor_viewport_md'] = '768';

		// Update page title selector
		$config['setup_elementor']['options']['page_title_selector'] = '.section-masthead h1';

		// Update disable default color schemes and fonts
		$config['setup_elementor']['options']['disable_color_schemes']      = 'yes';
		$config['setup_elementor']['options']['disable_typography_schemes'] = 'yes';

		// Update CSS print method
		$config['setup_elementor']['options']['css_print_method'] = 'internal';

		// FontAwesome 4 Support
		$config['setup_elementor']['options']['load_fa4_shim'] = 'yes';

		// Enable Optimized Assets Loading
		$config['setup_elementor']['options']['experiment-e_optimized_assets_loading'] = 'active';

		// Disable Inline Font Icons
		$config['setup_elementor']['options']['experiment-e_font_icon_svg'] = 'inactive';

		// Allow SVG uploads
		$config['setup_elementor']['options']['unfiltered_files_upload'] = '1';

		// Enable flexbox containers
		$config['setup_elementor']['options']['experiment-container'] = 'active';

		/** Setup Intuitive Custom Post Order */
		if ( ! isset( $config['setup_intuitive_custom_post_order'] ) || ! is_array( $config['setup_intuitive_custom_post_order'] ) ) {
			$config['setup_intuitive_custom_post_order'] = array();
		}

		$config['setup_intuitive_custom_post_order']['sortable_post_types'] = $custom_post_types;
		$config['setup_intuitive_custom_post_order']['sortable_taxonomies'] = $custom_taxonomies;

		/** Setup WordPress */
		if ( ! isset( $config['setup_wordpress'] ) ) {
			$config['setup_wordpress'] = array();
		}

		$config['setup_wordpress']['home_page_title'] = 'Slider 1 Distortion / H';
		$config['setup_wordpress']['blog_page_title'] = 'Blog';
		$config['setup_wordpress']['menu']            = array(
			'name'     => 'Top Menu',
			'location' => 'main_menu',
		);

		return $config;
	}
}

Plugin::instance();
