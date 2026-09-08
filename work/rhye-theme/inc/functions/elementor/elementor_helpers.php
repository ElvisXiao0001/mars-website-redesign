<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

if ( ! function_exists( 'arts_get_document_option' ) ) {
	/**
	 * Retrieve a specific document option for a given post.
	 *
	 * @param string   $option_name    The name of the option to retrieve.
	 * @param int|null $post_id        The ID of the post. Defaults to null.
	 * @param mixed    $option_default The value to return if the option is not found. Defaults to an empty string.
	 *
	 * @return mixed The value of the option, or the fallback value if not found.
	 *
	 * @deprecated 4.0.0 Use `\Arts\Utilities\Utilities::get_document_option()` method instead.
	 */
	function arts_get_document_option( $option, $post_id = null, $option_default = false ) {
		if ( did_action( 'elementor/loaded' ) ) {
			return Utilities::get_document_option( $option, $post_id, $option_default );
		} else {
			return $option_default;
		}
	}
}

if ( ! function_exists( 'arts_elementor_get_document_option' ) ) {
	/**
	 * Retrieve a specific document option for a given post.
	 *
	 * @param string   $option_name    The name of the option to retrieve.
	 * @param int|null $post_id        The ID of the post. Defaults to null.
	 * @param mixed    $option_default The value to return if the option is not found. Defaults to an empty string.
	 *
	 * @return mixed The value of the option, or the fallback value if not found.
	 * @deprecated 4.0.0 Use `\Arts\Utilities\Utilities::get_document_option()` method instead.
	 */
	function arts_elementor_get_document_option( $option, $post_id = null, $option_default = false ) {
		return Utilities::get_document_option( $option, $post_id, $option_default );
	}
}

if ( ! function_exists( 'arts_get_overridden_document_option' ) ) {
	/**
	 * Retrieve an overridden document option for a specific post.
	 *
	 * Checks if Elementor is loaded and if the post is built with Elementor.
	 * If the specified option and condition are met, it retrieves the document option
	 * based on the condition. Otherwise, it returns the theme modification option.
	 *
	 * @param string   $option The option name to retrieve.
	 * @param string   $option_condition The condition option name to check.
	 * @param mixed    $option_default The default value to return if the option is not set. Default is an empty string.
	 * @param int|null $post_id The ID of the post to retrieve the option for. Default is null.
	 * @param string   $prefix The prefix to add to the option name. Default is 'page_'.
	 *
	 * @return mixed The value of the overridden document option or the theme modification option.
	 * @deprecated 4.0.0 Use `\Arts\Utilities\Utilities::get_overridden_document_option()` method instead.
	 */
	function arts_get_overridden_document_option( $option, $option_condition, $option_default = '', $post_id = null, $prefix = 'page_' ) {
		return Utilities::get_overridden_document_option( $option, $option_condition, $option_default, $post_id, $prefix );
	}
}

if ( ! function_exists( 'arts_is_built_with_elementor' ) ) {
	/**
	 * Check if a post is built with Elementor.
	 *
	 * @param int|null $post_id The ID of the post to check. Defaults to null.
	 *
	 * @return bool True if the post is built with Elementor, false otherwise.
	 * @deprecated 4.0.0 Use `\Arts\Utilities\Utilities::is_built_with_elementor()` method instead.
	 */
	function arts_is_built_with_elementor( $post_id = null ) {
		return Utilities::is_built_with_elementor( $post_id );
	}
}

if ( ! function_exists( 'arts_is_elementor_editor_active' ) ) {
	/**
	 * Checks if the Elementor editor is active and in preview mode.
	 *
	 * @return bool True if Elementor editor is active and in preview mode, false otherwise.
	 * @deprecated 4.0.0 Use `\Arts\Utilities\Utilities::is_elementor_editor_active()` method instead.
	 */
	function arts_is_elementor_editor_active() {
		return Utilities::is_elementor_editor_active();
	}
}

if ( ! function_exists( 'arts_is_elementor_feature_active' ) ) {
	/**
	 * Check if Elementor's experimental feature
	 * is supported and active
	 *
	 * @return bool
	 * @deprecated 4.0.0 Use `\Arts\Utilities\Utilities::is_elementor_feature_active()` method instead.
	 */
	function arts_is_elementor_feature_active( $feature_name ) {
		return Utilities::is_elementor_feature_active( $feature_name );
	}
}
