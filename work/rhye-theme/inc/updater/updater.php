<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\LicenseManager\Plugin;

add_filter( 'arts/license_manager/plugin/strings', 'arts_license_manager_plugin_strings' );
if ( ! function_exists( 'arts_license_manager_plugin_strings' ) ) {
	/**
	 * Configure strings for the Arts License Manager plugin.

	 * @param array $strings An array of strings to be modified.
	 *
	 * @return array Modified array of strings with ThemeForest URLs.
	 */
	function arts_license_manager_plugin_strings( $strings ) {
		$strings['item-page-url']     = esc_url( 'https://themeforest.net/item/rhye-ajax-portfolio-wordpress-theme/28453694?aid=artemsemkin&aso=buyer_admin_panel&aca=theme_license_page' );
		$strings['item-checkout-url'] = esc_url( 'https://themeforest.net/checkout/from_item/28453694?license=regular&support=bundle_6month&aid=artemsemkin&aso=buyer_admin_panel&aca=theme_license_page' );

		return $strings;
	}
}

Plugin::instance();
