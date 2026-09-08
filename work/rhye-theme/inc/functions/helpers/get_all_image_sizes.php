<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

if ( ! function_exists( 'arts_get_all_image_sizes' ) ) {
	/**
	 * Get available image sizes registered in WordPress.
	 *
	 * @return array Associative array of image sizes with formatted names.
	 * @deprecated 4.0.0 Use `\Arts\Utilities\Utilities::get_available_image_sizes()` method instead.
	 */
	function arts_get_all_image_sizes() {
		return Utilities::get_available_image_sizes();
	}
}
