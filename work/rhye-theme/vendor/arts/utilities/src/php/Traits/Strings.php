<?php

namespace Arts\Utilities\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Strings Trait
 *
 * Provides utility methods for string manipulation and conversion between
 * different naming conventions (camelCase, kebab-case, snake_case).
 *
 * @package Arts\Utilities\Traits
 * @since 1.0.0
 */
trait Strings {
	/**
	 * Converts a camelCase string to lowercase with separators.
	 *
	 * @since 1.0.24
	 *
	 * @param string $string The input string to be converted.
	 * @return string The converted string in lowercase with hyphens.
	 */
	private static function convert_camel_case_base( $string ) {
		$result = preg_replace( '/([a-z])([A-Z])/', "\\1-\\2", $string );
		$string = is_string( $result ) ? $result : $string;

		$result = preg_replace( '/([a-z])([A-Z])/', "\\1 - \\2", $string );
		$string = is_string( $result ) ? $result : $string;

		return strtolower( $string );
	}

	/**
	 * Converts a string from camel case to kebab case.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string The input string to be converted.
	 * @example `myCamelCaseString` becomes `my-camel-case-string`
	 *
	 * @return string The converted string in kebab case.
	 */
	public static function convert_camel_to_kebab_case( $string ) {
		return self::convert_camel_case_base( $string );
	}

	/**
	 * Converts a camel case string to snake case.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string The input string in camel case.
	 * @example `myCamelCaseString` becomes `my_camel_case_string`
	 *
	 * @return string The converted string in snake case.
	 */
	public static function convert_camel_to_snake_case( $string ) {
		$string = self::convert_camel_case_base( $string );
		$string = str_replace( '-', '_', $string );
		$string = str_replace( '\\', '_', $string );

		return $string;
	}

	/**
	 * Converts a kebab case string to camel case.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string The input string in kebab case.
	 * @example `my-kebab-case-string` becomes `myKebabCaseString`
	 *
	 * @return string The converted string in camel case.
	 */
	public static function convert_kebab_case_to_camel( $string ) {
		// Handle empty strings or non-string inputs
		if ( empty( $string ) || ! is_string( $string ) ) {
			return '';
		}

		// If string doesn't contain hyphens, consider it's already in camelCase format
		// and should be converted to lowercase
		if ( strpos( $string, '-' ) === false ) {
			return strtolower( $string );
		}

		// Trim any leading or trailing hyphens
		$string = trim( $string, '-' );

		// Replace consecutive hyphens with a single hyphen
		$string = preg_replace( '/-+/', '-', $string );

		// If the string is empty after trimming, return empty string
		if ( empty( $string ) ) {
			return '';
		}

		$string = lcfirst( str_replace( ' ', '', ucwords( str_replace( '-', ' ', $string ) ) ) );

		return $string;
	}

	/**
	 * Converts a string to PascalCase format suitable for filenames.
	 *
	 * Sanitizes the input by trimming whitespace and normalizing all whitespace
	 * characters (spaces, tabs, newlines, etc.) to dashes, then converts to PascalCase.
	 * This is useful for generating consistent filenames from user-provided titles.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string The input string to be converted.
	 * @example `Regular` becomes `Regular`
	 * @example `My Custom Skin` becomes `MyCustomSkin`
	 * @example `my custom skin` becomes `MyCustomSkin`
	 *
	 * @return string The converted string in PascalCase format.
	 */
	public static function convert_to_pascal_case( $string ) {
		if ( empty( $string ) || ! is_string( $string ) ) {
			return '';
		}

		// Normalize all whitespace sequences to single dashes
		$string = preg_replace( '/\s+/', '-', trim( $string ) );

		if ( empty( $string ) ) {
			return '';
		}

		// Convert to PascalCase: capitalize first letter of each dash-separated word, remove dashes
		$string = str_replace( '-', '', ucwords( $string, '-' ) );

		return $string;
	}

	/**
	 * Replaces special characters in a string and converts it to a slug format.
	 *
	 * This function replaces characters like '_', '-', '/', '&', ' ', '.', '?', '#', and '=' with a specified replacement character
	 * (default is an underscore '_') and converts the string to lowercase. This is useful for generating slugs from strings,
	 * which can be used in URLs or as unique identifiers.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string  The input string to convert to a slug.
	 * @param string $replace The character to replace special characters with. Default is '_'.
	 *
	 * @return string The slug version of the input string.
	 */
	public static function get_slug_from_string( $string, $replace = '_' ) {
		return str_replace( array( '_', '-', '/', '&', ' ', '.', '?', '#', '=' ), $replace, strtolower( $string ) );
	}

	/**
	 * Returns the file extension of a given URL.
	 *
	 * @since 1.0.0
	 *
	 * @param string $url The URL to extract the file extension from.
	 * @return string The file extension of the URL.
	 */
	public static function get_asset_url_file_extension( $url ) {
		$url_path = parse_url( $url, PHP_URL_PATH );
		if ( ! is_string( $url_path ) ) {
			return '';
		}
		$ext = pathinfo( $url_path, PATHINFO_EXTENSION );

		return $ext;
	}
}
