<?php

namespace Arts\Utilities\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * TypeGuards Trait
 *
 * Provides utility methods for type validation and conversion,
 * useful for PHPStan level 10 compatibility where mixed types
 * need to be narrowed before use.
 *
 * @package Arts\Utilities\Traits
 * @since 1.1.11
 */
trait TypeGuards {
	/**
	 * Get a valid post ID from a mixed value.
	 *
	 * Validates that the value is either an integer or WP_Post object,
	 * which are the valid types for WordPress post-related functions.
	 *
	 * @since 1.1.11
	 *
	 * @param mixed $value The value to validate (typically from $args['id']).
	 *
	 * @return int|\WP_Post|null The validated post ID/object, or null if invalid.
	 */
	public static function get_post_id( $value ) {
		if ( is_int( $value ) || $value instanceof \WP_Post ) {
			return $value;
		}
		return null;
	}

	/**
	 * Get a string value from a mixed value.
	 *
	 * @since 1.1.11
	 *
	 * @param mixed  $value   The value to validate.
	 * @param string $default Default value if not a string. Default empty string.
	 *
	 * @return string The validated string or default.
	 */
	public static function get_string_value( $value, $default = '' ) {
		if ( is_string( $value ) ) {
			return $value;
		}

		// Explicitly handle numeric values
		if ( is_int( $value ) || is_float( $value ) ) {
			return (string) $value;
		}

		// For bool, null, arrays, objects, return default
		return $default;
	}

	/**
	 * Get an integer value from a mixed value.
	 *
	 * @since 1.1.11
	 *
	 * @param mixed $value   The value to validate.
	 * @param int   $default Default value if not numeric. Default 0.
	 *
	 * @return int The validated integer or default.
	 */
	public static function get_int_value( $value, $default = 0 ) {
		return is_numeric( $value ) ? (int) $value : $default;
	}

	/**
	 * Get a float value from a mixed value.
	 *
	 * @since 1.1.11
	 *
	 * @param mixed $value   The value to validate.
	 * @param float $default Default value if not numeric. Default 0.0.
	 *
	 * @return float The validated float or default.
	 */
	public static function get_float_value( $value, $default = 0.0 ) {
		return is_numeric( $value ) ? (float) $value : $default;
	}

	/**
	 * Get a boolean value from a mixed value.
	 *
	 * @since 1.1.11
	 *
	 * @param mixed $value   The value to validate.
	 * @param bool  $default Default value if not a boolean. Default false.
	 *
	 * @return bool The validated boolean or default.
	 */
	public static function get_bool_value( $value, $default = false ) {
		return is_bool( $value ) ? $value : $default;
	}

	/**
	 * Get an array value from a mixed value.
	 *
	 * @since 1.1.11
	 *
	 * @param mixed                     $value   The value to validate.
	 * @param array<string|int, mixed>  $default Default value if not an array. Default empty array.
	 *
	 * @return array<string|int, mixed> The validated array or default.
	 */
	public static function get_array_value( $value, $default = array() ) {
		return is_array( $value ) ? $value : $default;
	}

	/**
	 * Get an object value from a mixed value.
	 *
	 * @since 1.1.11
	 *
	 * @param mixed       $value   The value to validate.
	 * @param object|null $default Default value if not an object. Default null.
	 *
	 * @return object|null The validated object or default.
	 */
	public static function get_object_value( $value, $default = null ) {
		return is_object( $value ) ? $value : $default;
	}

	/**
	 * Get template args as an array.
	 *
	 * Useful for template parts that receive $args from get_template_part().
	 * Ensures the value is an array before passing to wp_parse_args().
	 *
	 * @since 1.1.11
	 *
	 * @param mixed                     $args     The args value (typically global $args in template).
	 * @param array<string, mixed>      $defaults Default values to merge with.
	 *
	 * @return array<string, mixed> The parsed args array.
	 */
	public static function parse_template_args( $args, $defaults = array() ) {
		$args   = is_array( $args ) ? $args : array();
		$result = wp_parse_args( $args, $defaults );
		/** @var array<string, mixed> $result */
		return $result;
	}
}
