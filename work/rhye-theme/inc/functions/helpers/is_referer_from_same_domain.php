<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

if ( ! function_exists( 'arts_is_referer_from_same_domain' ) ) {
	/**
	 * Check if the referer is from the same domain.
	 *
	 * Retrieves the raw referer URL and compares its host with the host of the site URL.
	 * If both hosts match, it indicates that the referer is from the same domain.
	 *
	 * @return bool True if the referer is from the same domain, false otherwise.
	 * @deprecated 2.0.0 Use `\Arts\Utilities\Utilities::is_referer_from_same_domain()` method instead.
	 */
	function arts_is_referer_from_same_domain() {
		return Utilities::is_referer_from_same_domain();
	}
}
