<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

if ( ! function_exists( 'arts_print_attributes' ) ) {
	/**
	 * Prints or returns HTML attributes from an associative array.
	 *
	 * @param array $attributes Associative array of attributes and their values.
	 * @param bool  $echo Optional. Whether to echo the attributes. Default true.
	 *
	 * @return string|null The HTML attributes string if $echo is false, otherwise null.
	 * @deprecated 4.0.0 Use `\Arts\Utilities\Utilities::print_attributes()` method instead.
	 */
	function arts_print_attributes( $attributes, $echo = true ) {
		return Utilities::print_attributes( $attributes, $echo );
	}
}
