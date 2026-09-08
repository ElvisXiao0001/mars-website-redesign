<?php

namespace Arts\LicenseManager\Managers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Updates
 *
 * Manages theme update checks and processing.
 *
 * @package Arts\LicenseManager\Managers
 * @since 1.0.0
 */
class Updates extends BaseManager {
	/**
	 * Theme slug identifier.
	 *
	 * @var string
	 */
	private $theme_slug;

	/**
	 * Remote API URL for update checks.
	 *
	 * @var string
	 */
	private $remote_api_url;

	/**
	 * Current theme version.
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Transient key for storing update response data.
	 *
	 * @var string
	 */
	private $response_key;

	/**
	 * Initialize the Updates manager.
	 *
	 * @param \stdClass $managers Other managers used by this manager.
	 * @return void
	 */
	public function init( $managers ) {
		$this->theme_slug     = $this->args['theme_slug'];
		$this->remote_api_url = $this->args['remote_api_url'];
		$this->version        = $this->args['version'];
		$this->response_key   = $this->args['theme_slug'] . '-update-response';

		$this->add_managers( $managers );
	}

	/**
	 * Modifies the theme update transient to include custom update data.
	 *
	 * Checks if an update is available and adds it to the WordPress update data.
	 *
	 * @param object $transient The WordPress update transient object.
	 * @return object Modified transient with theme update data.
	 */
	public function modify_theme_update_transient( $transient ) {
		// Check if $transient is an object before trying to modify its properties
		if ( ! is_object( $transient ) ) {
			$transient            = new \stdClass();
			$transient->response  = array();
			$transient->no_update = array();
		}

		$theme_remote_update_data = get_transient( $this->response_key );

		// Cache the remote check for a day so update-screen renders don't hit the license API on every load.
		if ( ! $theme_remote_update_data ) {
			$theme_remote_update_data = $this->fetch_remote_theme_data();
			set_transient( $this->response_key, $theme_remote_update_data, DAY_IN_SECONDS );
		}

		// Remote responses may omit fields, so guard each access: a missing version must not trip
		// version_compare, and absent download_url/url default to '' rather than erroring in esc_url.
		if ( $theme_remote_update_data && version_compare( $this->version, isset( $theme_remote_update_data->version ) ? $theme_remote_update_data->version : '', '<' ) ) {
			$transient->response[ $this->theme_slug ] = array(
				'theme'       => $this->theme_slug,
				'new_version' => esc_html( $theme_remote_update_data->version ),
				'package'     => esc_url( isset( $theme_remote_update_data->download_url ) ? $theme_remote_update_data->download_url : '' ),
				'url'         => esc_url( isset( $theme_remote_update_data->url ) ? $theme_remote_update_data->url : '' ),
			);
		} else {
			// WordPress expects every theme in either 'response' or 'no_update'; the no_update entry keeps
			// the theme out of the update list and preserves its "up to date" state on the Updates screen.
			$item                                      = array(
				'theme'        => $this->theme_slug,
				'new_version'  => $this->version,
				'url'          => '',
				'package'      => '',
				'requires'     => '',
				'requires_php' => '',
			);
			$transient->no_update[ $this->theme_slug ] = $item;
		}

		return $transient;
	}

	/**
	 * Deletes the theme update transient data.
	 *
	 * Forces WordPress to check for updates again.
	 *
	 * @return void
	 */
	public function delete_theme_update_transient() {
		delete_transient( $this->response_key );
	}

	/**
	 * Fetches update data from the remote server.
	 *
	 * Makes an API request to check for theme updates.
	 *
	 * @return object|null Update data object or null on failure.
	 */
	private function fetch_remote_theme_data() {
		// Trim stray whitespace that can sneak into a pasted/stored key before sending it to the API.
		$license = trim( $this->managers->options->get( 'license_key' ) );
		$result  = null;

		$api_url    = trailingslashit( $this->remote_api_url ) . 'edd/v1/update/' . $this->theme_slug . '/theme';
		$api_params = array(
			'key'               => $license,
			'url'               => esc_url( home_url( '/' ) ),
			// The store records the running theme version (attributed by the /theme endpoint segment).
			'installed_version' => (string) ( $this->args['version'] ?? '' ),
		);

		$response = $this->get_api_response( $api_params, $api_url );

		if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
			$result = json_decode( wp_remote_retrieve_body( $response ) );
		}

		return $result;
	}

	/**
	 * Makes an API request to the update server.
	 *
	 * @param array  $api_params Parameters to send to the API.
	 * @param string $url        The API URL to call.
	 * @return array|\WP_Error The API response or WP_Error on failure.
	 */
	private function get_api_response( $api_params, $url ) {
		$verify_ssl = (bool) apply_filters( 'edd_sl_api_request_verify_ssl', true );
		$response   = wp_remote_post(
			$url,
			array(
				'timeout'   => 15,
				'sslverify' => $verify_ssl,
				'body'      => $api_params,
			)
		);

		return $response;
	}

	/**
	 * Clears theme update cache data.
	 *
	 * Forces WordPress to check for theme updates again.
	 *
	 * @return void
	 */
	public function clear_theme_update_data() {
		wp_clean_themes_cache( true );
	}
}
