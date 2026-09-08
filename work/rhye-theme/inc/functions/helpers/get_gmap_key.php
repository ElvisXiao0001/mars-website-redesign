<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'arts_get_gmap_key' ) ) {
	/**
	 * Retrieve the Google Maps API key.
	 *
	 * It first checks for the key stored under the `elementor_google_maps_api_key` option.
	 * If that key is not found or is invalid, it then checks for a legacy key stored under the 'arts_gmap' option.
	 *
	 * @return string The Google Maps API key if found, otherwise an empty string.
	 */
	function arts_get_gmap_key() {
		$gmap_key = get_option( 'elementor_google_maps_api_key' );

		if ( is_string( $gmap_key ) && ! empty( $gmap_key ) ) {
			return $gmap_key;
		}

		$gmap_key_legacy = get_option( 'arts_gmap' );

		if ( is_array( $gmap_key_legacy ) && array_key_exists( 'key', $gmap_key_legacy ) && is_string( $gmap_key_legacy['key'] ) && ! empty( $gmap_key_legacy['key'] ) ) {
			return $gmap_key_legacy['key'];
		}

		return '';
	}
}
