<?php

namespace Arts\Utilities\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * URL Trait
 *
 * Provides utility methods for working with URLs, including
 * referer checking and license URL generation.
 *
 * @package Arts\Utilities\Traits
 * @since 1.0.0
 */
trait URL {
	/**
	 * Check if the referer is from the same domain.
	 *
	 * Retrieves the raw referer URL and compares its host with the host of the site URL.
	 * If both hosts match, it indicates that the referer is from the same domain.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if the referer is from the same domain, false otherwise.
	 */
	public static function is_referer_from_same_domain() {
		$referer = wp_get_raw_referer();
		$host    = get_site_url();

		if ( $referer !== false ) {
			if ( parse_url( $referer, PHP_URL_HOST ) === parse_url( $host, PHP_URL_HOST ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Generates a URL with license arguments for use with EDD.
	 *
	 * Appends license key and site URL parameters to a base URL,
	 * typically used for API calls to an EDD-powered license server.
	 *
	 * @since 1.0.0
	 *
	 * @param string $url    The base URL to append the license arguments to.
	 * @param string $option The option name to retrieve the license key.
	 * @return string The URL with the license arguments or an empty string if parameters are missing.
	 */
	public static function get_license_args_url( $url = '', $option = '' ) {
		if ( ! $url || ! $option ) {
			return '';
		}

		$key      = self::get_string_value( get_option( $option ) );
		$site_url = home_url( '/' );

		// rawurlencode the key: add_query_arg() does NOT encode values, and this URL is fetched over GET,
		// where an un-encoded key with +, %, &, # (e.g. a Freemius sk_ key) is corrupted by query parsing
		// (+ -> space, %XX -> byte). UUID keys are unaffected (no reserved chars). esc_url_raw (not esc_url)
		// keeps the result a real request URL rather than an HTML-encoded one.
		$license_args_url = add_query_arg(
			array(
				'key' => rawurlencode( $key ),
				'url' => $site_url,
			),
			esc_url_raw( $url )
		);

		return $license_args_url;
	}

	/**
	 * Gets the URL for the directory containing a file.
	 *
	 * Determines whether the file is in a plugin or theme context and
	 * returns the appropriate URL path to its containing directory.
	 *
	 * @since 1.0.1
	 *
	 * @param string $file Path to the file (defaults to current file).
	 * @return string URL to the directory containing the file.
	 */
	public static function get_directory_url( $file = __FILE__ ) {
		// Get the absolute path to the current file
		$current_file_path = wp_normalize_path( $file );

		// Get the absolute path and URI of the theme
		$theme_directory = wp_normalize_path( get_template_directory() );
		$theme_uri       = get_template_directory_uri();

		// Check if we're in a plugin
		if ( strpos( $current_file_path, WP_PLUGIN_DIR ) !== false ) {
			return plugin_dir_url( $file );
		}

		// We're in a theme - calculate the relative path
		if ( strpos( $current_file_path, $theme_directory ) !== false ) {
			// Get path relative to theme
			$relative_path = str_replace( $theme_directory, '', dirname( $current_file_path ) );
			// Build URL
			return trailingslashit( $theme_uri . $relative_path );
		}

		// Fallback - direct parent directory
		return trailingslashit( dirname( plugin_dir_url( $file ) ) );
	}
}
