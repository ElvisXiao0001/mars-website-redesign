<?php

namespace Arts\Utilities\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Shortcodes Trait
 *
 * Provides utility methods for working with WordPress shortcodes,
 * including predefined shortcodes for common use cases.
 *
 * @package Arts\Utilities\Traits
 * @since 1.0.0
 */
trait Shortcodes {
	/**
	 * Retrieves the current year.
	 *
	 * To be used with `add_shortcode` WordPress action.
	 * Useful for copyright notices that need to display the current year.
	 *
	 * @since 1.0.0
	 *
	 * @return string The current year or an empty string if not available.
	 */
	public static function get_shortcode_current_year() {
		$current_year = gmdate( 'Y' );
		$current_year = shortcode_unautop( $current_year );
		$current_year = do_shortcode( $current_year );

		if ( ! empty( $current_year ) ) {
			return $current_year;
		}

		return '';
	}
}
