<?php

namespace Arts\Utilities\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * ACF Trait
 *
 * Provides utility methods for working with Advanced Custom Fields (ACF).
 * These methods act as proxies for ACF functions and include proper checks
 * for ACF availability.
 *
 * @package Arts\Utilities\Traits
 * @since 1.0.0
 */
trait ACF {
	/**
	 * Proxy for `get_field()` function from ACF.
	 *
	 * @since 1.0.0
	 *
	 * @param string    $selector     The field name or field key.
	 * @param int|false $post_id      Optional. The post ID where the value is saved. Defaults to the current post.
	 * @param bool      $format_value Optional. Whether to apply formatting logic. Defaults to true.
	 * @param bool      $escape_html  Optional. Whether to escape HTML. Defaults to false.
	 *
	 * @return mixed|false The value of the field or false if not found.
	 */
	public static function acf_get_field( $selector, $post_id = false, $format_value = true, $escape_html = false ) {
		if ( function_exists( 'get_field' ) ) {
			return get_field( $selector, $post_id, $format_value, $escape_html );
		} else {
			return false;
		}
	}

	/**
	 * Proxy for `have_rows()` function from ACF.
	 *
	 * @since 1.0.0
	 *
	 * @param string    $selector The field name or field key.
	 * @param int|false $post_id  Optional. The post ID where the value is saved. Defaults to the current post.
	 *
	 * @return bool Whether the field has rows or not.
	 */
	public static function acf_have_rows( $selector, $post_id = false ) {
		if ( function_exists( 'have_rows' ) ) {
			return have_rows( $selector, $post_id );
		} else {
			return false;
		}
	}

	/**
	 * Proxy for `get_field_objects()` function from ACF.
	 *
	 * @since 1.0.0
	 *
	 * @param int|false  $post_id      Optional. The post ID where the value is saved. Defaults to the current post.
	 * @param bool $format_value Optional. Whether to apply formatting logic. Defaults to true.
	 * @param bool $load_value   Optional. Whether to load the value. Defaults to true.
	 * @param bool $escape_html  Optional. Whether to escape HTML. Defaults to false.
	 *
	 * @return array<string, array<string, mixed>>|false The field objects or false if not found.
	 */
	public static function acf_get_field_objects( $post_id = false, bool $format_value = true, bool $load_value = true, bool $escape_html = false ) {
		if ( function_exists( 'get_field_objects' ) ) {
			$result = get_field_objects( $post_id, $format_value, $load_value, $escape_html );
			if ( is_array( $result ) ) {
				/** @var array<string, array<string, mixed>> $result */
				return $result;
			}
			return false;
		} else {
			return false;
		}
	}

	/**
	 * Proxy for `acf_add_options_page()` function from ACF.
	 *
	 * @since 1.0.10
	 *
	 * @param array<string, mixed>|string $page The options page settings.
	 *
	 * @return array<string, mixed>|false The options page configuration array or false if not found.
	 */
	public static function acf_add_options_page( $page = '' ) {
		if ( function_exists( 'acf_add_options_page' ) ) {
			$result = acf_add_options_page( $page );
			if ( is_array( $result ) ) {
				/** @var array<string, mixed> $result */
				return $result;
			}
			return false;
		} else {
			return false;
		}
	}

	/**
	 * Proxy for `get_sub_field()` function from ACF.
	 *
	 * @since 1.0.14
	 *
	 * @param string $selector     The field name or field key.
	 * @param bool   $format_value Optional. Whether to apply formatting logic. Defaults to true.
	 * @param bool   $escape_html  Optional. Whether to escape HTML. Defaults to false.
	 *
	 * @return mixed|false The value of the sub field or false if not found.
	 */
	public static function acf_get_sub_field( $selector, $format_value = true, $escape_html = false ) {
		if ( function_exists( 'get_sub_field' ) ) {
			return get_sub_field( $selector, $format_value, $escape_html );
		} else {
			return false;
		}
	}

	/**
	 * Proxy for `update_field()` function from ACF.
	 *
	 * @since 1.0.30
	 *
	 * @param string    $selector The field name or field key.
	 * @param mixed     $value    The new value to save.
	 * @param int|false $post_id  Optional. The post ID where the value is saved. Defaults to the current post.
	 *
	 * @return bool Whether the field was updated successfully or false if not found.
	 */
	public static function acf_update_field( $selector, $value, $post_id = false ) {
		if ( function_exists( 'update_field' ) ) {
			return update_field( $selector, $value, $post_id );
		} else {
			return false;
		}
	}

	/**
	 * Get an ACF field value as a string.
	 *
	 * @since 1.1.8
	 *
	 * @param string   $selector     The field name or field key.
	 * @param int|false $post_id      Optional. The post ID where the value is saved. Defaults to the current post.
	 * @param string   $default      Optional. Default value if field is not a string. Defaults to empty string.
	 * @param bool     $format_value Optional. Whether to apply formatting logic. Defaults to true.
	 * @param bool     $escape_html  Optional. Whether to escape HTML. Defaults to false.
	 *
	 * @return string The field value as string or default.
	 */
	public static function acf_get_field_string( $selector, $post_id = false, $default = '', $format_value = true, $escape_html = false ): string {
		$value = self::acf_get_field( $selector, $post_id, $format_value, $escape_html );
		return is_string( $value ) ? $value : $default;
	}

	/**
	 * Get an ACF field value as an integer.
	 *
	 * @since 1.1.8
	 *
	 * @param string   $selector     The field name or field key.
	 * @param int|false $post_id      Optional. The post ID where the value is saved. Defaults to the current post.
	 * @param int      $default      Optional. Default value if field is not numeric. Defaults to 0.
	 * @param bool     $format_value Optional. Whether to apply formatting logic. Defaults to true.
	 * @param bool     $escape_html  Optional. Whether to escape HTML. Defaults to false.
	 *
	 * @return int The field value as integer or default.
	 */
	public static function acf_get_field_int( $selector, $post_id = false, $default = 0, $format_value = true, $escape_html = false ): int {
		$value = self::acf_get_field( $selector, $post_id, $format_value, $escape_html );
		return is_numeric( $value ) ? (int) $value : $default;
	}

	/**
	 * Get an ACF field value as an array.
	 *
	 * @since 1.1.8
	 *
	 * @param string       $selector     The field name or field key.
	 * @param int|false     $post_id      Optional. The post ID where the value is saved. Defaults to the current post.
	 * @param array<mixed> $default      Optional. Default value if field is not an array. Defaults to empty array.
	 * @param bool         $format_value Optional. Whether to apply formatting logic. Defaults to true.
	 * @param bool         $escape_html  Optional. Whether to escape HTML. Defaults to false.
	 *
	 * @return array<mixed> The field value as array or default.
	 */
	public static function acf_get_field_array( $selector, $post_id = false, $default = array(), $format_value = true, $escape_html = false ): array {
		$value = self::acf_get_field( $selector, $post_id, $format_value, $escape_html );
		return is_array( $value ) ? $value : $default;
	}

	/**
	 * Get an ACF field value as a boolean.
	 *
	 * @since 1.1.8
	 *
	 * @param string   $selector     The field name or field key.
	 * @param int|false $post_id      Optional. The post ID where the value is saved. Defaults to the current post.
	 * @param bool     $default      Optional. Default value if field is empty. Defaults to false.
	 * @param bool     $format_value Optional. Whether to apply formatting logic. Defaults to true.
	 * @param bool     $escape_html  Optional. Whether to escape HTML. Defaults to false.
	 *
	 * @return bool The field value as boolean or default.
	 */
	public static function acf_get_field_bool( $selector, $post_id = false, $default = false, $format_value = true, $escape_html = false ): bool {
		$value = self::acf_get_field( $selector, $post_id, $format_value, $escape_html );
		if ( $value === false && ! function_exists( 'get_field' ) ) {
			return $default;
		}
		return (bool) $value;
	}

	/**
	 * Retrieves the available ACF fields and values for a given post.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id The ID of the post.
	 *
	 * @return array<string,mixed> An associative array of ACF field names and their corresponding values.
	 */
	public static function acf_get_post_fields( $post_id ) {
		if ( ! $post_id ) {
			return array();
		}

		$result = array();

		$acf_fields = self::acf_get_field_objects( $post_id );

		if ( is_array( $acf_fields ) ) {
			foreach ( $acf_fields as $field ) {
				if ( is_array( $field ) && isset( $field['name'], $field['value'] ) && is_string( $field['name'] ) ) {
					$result[ $field['name'] ] = $field['value'];
				}
			}
		}

		return $result;
	}
}
