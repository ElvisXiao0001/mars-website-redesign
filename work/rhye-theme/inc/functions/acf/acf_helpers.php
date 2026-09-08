<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

if ( ! function_exists( 'arts_have_rows' ) ) {
	/**
	 * Proxy for `have_rows()` function from ACF.
	 *
	 * @param string $selector The field name or field key.
	 * @param int    $post_id  Optional. The post ID where the value is saved. Defaults to the current post.
	 *
	 * @return bool Whether the field has rows or not.
	 * @deprecated 4.0.0 Use `Arts\Utilities\Utilities::acf_have_rows()` method instead.
	 */
	function arts_have_rows( $selector, $post_id = false ) {
		return Utilities::acf_have_rows( $selector, $post_id );
	}
}

if ( ! function_exists( 'arts_get_field' ) ) {
	/**
	 * Proxy for `get_field()` function from ACF.
	 *
	 * @param string $selector The field name or field key.
	 * @param int    $post_id  Optional. The post ID where the value is saved. Defaults to the current post.
	 * @param bool   $format_value Optional. Whether to apply formatting logic. Defaults to true.
	 * @param bool   $escape_html Optional. Whether to escape HTML. Defaults to false.
	 *
	 * @return mixed|false The value of the field or false if not found.
	 * @deprecated 4.0.0 Use `Arts\Utilities\Utilities::acf_get_field()` method instead.
	 */
	function arts_get_field( $selector, $post_id = false, $format_value = true, $escape_html = false ) {
		return Utilities::acf_get_field( $selector, $post_id, $format_value, $escape_html );
	}
}
