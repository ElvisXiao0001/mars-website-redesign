<?php

namespace Arts\WizardSetup\Managers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Arts\Utilities\Utilities;

/**
 * Class Import
 *
 * Manages the demo content import configuration and processes.
 *
 * @since      1.0.0
 * @package    Arts\WizardSetup\Managers
 * @author     Artem Semkin
 */
class Import extends BaseManager {
	/**
	 * Array of demo files configurations.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var array
	 */
	private $demo_files = array();

	/**
	 * The current theme slug.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string
	 */
	private $theme_slug;

	/**
	 * The current theme name.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string
	 */
	private $theme_name;

	/**
	 * The option name for storing the license key.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string
	 */
	private $license_key_option;

	/**
	 * Initializes the properties from configuration arguments.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return $this For method chaining.
	 */
	protected function init_properties() {
		if ( isset( $this->args['setup_demo_data'] ) && is_array( $this->args['setup_demo_data'] ) ) {
			$this->demo_files = $this->args['setup_demo_data'];
		}

		if ( isset( $this->args['theme_slug'] ) ) {
			$this->theme_slug         = $this->args['theme_slug'];
			$this->license_key_option = "{$this->args['theme_slug']}_license_key";
		}

		if ( isset( $this->args['theme_name'] ) ) {
			$this->theme_name = $this->args['theme_name'];
		}

		return $this;
	}

	/**
	 * Retrieves the configuration for import files.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array The configuration array for the import files, or an empty array if conditions are not met.
	 */
	public function get_import_files_config() {
		if ( ! is_array( $this->demo_files ) || empty( $this->demo_files ) || ! $this->theme_slug || ! $this->theme_name ) {
			return array();
		}

		$result = array();

		foreach ( $this->demo_files as $files ) {
			$files_config = $this->get_files_config( $files );

			if ( ! empty( $files_config ) ) {
				$result[] = $files_config;
			}
		}

		return $result;
	}

	/**
	 * Generate configuration for import files.
	 *
	 * @since  1.0.0
	 * @access private
	 * @param  array $files Array containing file URLs and additional options.
	 * @return array Configuration array for import files.
	 */
	private function get_files_config( $files ) {
		$files_config = array();

		$xml_url = $this->get_file_url( $files, 'xml_url' );
		$dat_url = $this->get_file_url( $files, 'dat_url' );
		$wie_url = $this->get_file_url( $files, 'wie_url' );

		if ( ! $xml_url && ! $dat_url && ! $wie_url ) {
			return array();
		}

		$add_license_key_args = isset( $files['add_license_key_args'] ) && $files['add_license_key_args'];

		$files_config['import_file_name'] = sprintf( '%1$s %2$s', $this->theme_name, esc_html__( 'Demo Data', 'merlin-wp' ) );

		if ( $xml_url ) {
			$files_config['import_file_url'] = $this->get_sanitized_url( $xml_url, $add_license_key_args );
		}

		if ( $dat_url ) {
			$files_config['import_customizer_file_url'] = $this->get_sanitized_url( $dat_url, $add_license_key_args );
		}

		if ( $wie_url ) {
			$files_config['import_widget_file_url'] = $this->get_sanitized_url( $wie_url, $add_license_key_args );
		}

		if ( isset( $files['preview_url'] ) ) {
			$files_config['preview_url'] = sanitize_url( sprintf( $files['preview_url'], $this->theme_slug ) );
		}

		return $files_config;
	}

	/**
	 * Retrieves the URL of a file from the provided array.
	 *
	 * @since  1.0.0
	 * @access private
	 * @param  array  $files Array of file URLs.
	 * @param  string $key   Key to identify the file URL in the array.
	 * @return string The formatted file URL or an empty string if not found.
	 */
	private function get_file_url( $files, $key ) {
		return isset( $files[ $key ] ) && ! empty( $files[ $key ] ) ? sprintf( $files[ $key ], $this->theme_slug ) : '';
	}

	/**
	 * Sanitizes the given URL and optionally adds license key arguments.
	 *
	 * @since  1.0.0
	 * @access private
	 * @param  string $url The URL to be sanitized.
	 * @param  bool   $add_license_key_args Whether to add license key arguments to the URL.
	 * @return string The sanitized URL.
	 */
	private function get_sanitized_url( $url, $add_license_key_args ) {
		if ( $add_license_key_args ) {
			$url = Utilities::get_license_args_url( $url, $this->license_key_option );
		}

		return sanitize_url( $url );
	}
}
