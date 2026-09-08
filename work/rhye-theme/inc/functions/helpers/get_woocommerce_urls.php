<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

if ( ! function_exists( 'arts_get_woocommerce_urls' ) ) {
	/**
	 * Retrieve an array of WooCommerce URLs for various pages and permalink structures.
	 *
	 * This method fetches URLs for WooCommerce pages such as shop, cart, my account, checkout, and terms pages.
	 * It also retrieves URLs for product, category, tag, and attribute bases if they are set in the WooCommerce permalink structure.
	 *
	 * @param bool $bypass_cache Optional. Whether to bypass the cache and retrieve fresh URLs. Default false.
	 *
	 * @return string[] List of WooCommerce URLs.
	 * @deprecated 2.0.0 Use `\Arts\Utilities\Utilities::get_woocommerce_urls()` method instead.
	 */
	function arts_get_woocommerce_urls( $bypass_cache = false ) {
		return Utilities::get_woocommerce_urls( $bypass_cache );
	}
}
