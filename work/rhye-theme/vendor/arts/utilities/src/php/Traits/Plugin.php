<?php

namespace Arts\Utilities\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Plugin Trait
 *
 * Provides utility methods for working with WordPress plugins,
 * particularly for retrieving plugin information and versions.
 *
 * @package Arts\Utilities\Traits
 * @since 1.0.0
 */
trait Plugin {
	/**
	 * Get the version of the plugin.
	 *
	 * Retrieves the version from the plugin file's header.
	 *
	 * @since 1.0.0
	 *
	 * @param string $plugin_file Optional. The path to the plugin file. Default is the current file.
	 * @return string The version of the plugin.
	 */
	public static function get_plugin_version( $plugin_file = __FILE__ ) {
		$data    = get_file_data( $plugin_file, array( 'ver' => 'Version' ), '' );
		$version = '';

		if ( isset( $data['ver'] ) ) {
			$version = $data['ver'];
		}

		return $version;
	}
}
