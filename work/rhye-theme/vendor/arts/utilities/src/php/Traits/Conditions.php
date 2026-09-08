<?php

namespace Arts\Utilities\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Conditions Trait
 *
 * Provides utility methods for checking various WordPress conditional states.
 * Includes methods to check for archive pages and other common conditions.
 *
 * @package Arts\Utilities\Traits
 * @since 1.0.0
 */
trait Conditions {
	/**
	 * Checks if the current page is an archive page.
	 *
	 * Determines if the current page is any type of archive page
	 * including home, category, search, or standard archives.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if the current page is an archive page, false otherwise.
	 */
	public static function is_archive() {
		return is_home() || is_search() || is_archive();
	}
}
