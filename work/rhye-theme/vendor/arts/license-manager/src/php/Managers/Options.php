<?php

namespace Arts\LicenseManager\Managers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Options
 *
 * Manages options storage and retrieval for the license manager.
 *
 * @package Arts\LicenseManager\Managers
 * @since 1.0.0
 */
class Options extends BaseManager {
	/**
	 * The theme slug used for option prefixing.
	 *
	 * @var string
	 */
	private $theme_slug;

	/**
	 * Initialize the Options manager.
	 *
	 * @param \stdClass $managers Other managers used by this manager.
	 * @return void
	 */
	public function init( $managers ) {
		$this->theme_slug = $this->args['theme_slug'];

		$this->add_managers( $managers );
	}

	/**
	 * Gets an option value.
	 *
	 * @param string $option        The option name without prefix.
	 * @param mixed  $default_value Optional default value.
	 * @return mixed The option value or default if not found.
	 */
	public function get( $option, $default_value = '' ) {
		return get_option( $this->get_prefixed_string( $option ), $default_value );
	}

	/**
	 * Updates an option value.
	 *
	 * @param string $option The option name without prefix.
	 * @param mixed  $value  The new value for the option.
	 * @return bool True if the option was updated, false otherwise.
	 */
	public function update( $option, $value ) {
		return update_option( $this->get_prefixed_string( $option ), $value );
	}

	/**
	 * Deletes an option.
	 *
	 * @param string $option The option name without prefix.
	 * @return bool True if the option was deleted, false otherwise.
	 */
	public function delete( $option ) {
		return delete_option( $this->get_prefixed_string( $option ) );
	}

	/**
	 * Gets a hash for a license key to use in suppression storage.
	 *
	 * @param string $license_key The license key to hash.
	 * @return string The hash of the license key.
	 */
	private function get_license_hash( $license_key ) {
		// Use WordPress's wp_hash() function with a static salt specific to this feature
		$salt = 'arts_license_manager_modal_suppression_v1';
		return wp_hash( $license_key . $salt );
	}

	/**
	 * Checks if the email modal auto-open has been suppressed for a license.
	 *
	 * @param string $license_key The license key to check.
	 * @return bool True if suppressed, false otherwise.
	 */
	public function is_modal_suppressed( $license_key ) {
		if ( empty( $license_key ) ) {
			return false;
		}

		$suppressions = $this->get( 'modal_suppressions', array() );
		$license_hash = $this->get_license_hash( $license_key );

		return isset( $suppressions[ $license_hash ] ) && ! empty( $suppressions[ $license_hash ]['shown'] );
	}

	/**
	 * Sets the suppression flag for a license key.
	 *
	 * @param string $license_key The license key to suppress.
	 * @param string $source The source that triggered the suppression (e.g., "license_screen").
	 * @return bool True if the suppression was set, false otherwise.
	 */
	public function set_modal_suppressed( $license_key, $source = 'license_screen' ) {
		if ( empty( $license_key ) ) {
			return false;
		}

		$suppressions = $this->get( 'modal_suppressions', array() );
		$license_hash = $this->get_license_hash( $license_key );

		$suppressions[ $license_hash ] = array(
			'shown'      => true,
			'first_seen' => time(),
			'source'     => sanitize_text_field( $source ),
		);

		return $this->update( 'modal_suppressions', $suppressions );
	}

	/**
	 * Adds the theme slug prefix to a string.
	 *
	 * @param string $string The string to prefix.
	 * @return string The prefixed string.
	 */
	public function get_prefixed_string( $string ) {
		return $this->theme_slug . '_' . $string;
	}
}
