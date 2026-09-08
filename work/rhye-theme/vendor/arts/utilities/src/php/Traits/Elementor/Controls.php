<?php

namespace Arts\Utilities\Traits\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Controls Trait
 *
 * Provides utility methods for working with Elementor controls and settings,
 * particularly for handling image and dimension controls.
 *
 * @package Arts\Utilities\Traits\Elementor
 * @since 1.0.0
 */
trait Controls {
	/**
	 * Get the thumbnail size settings for an image.
	 *
	 * Retrieves the thumbnail size settings based on the provided settings array,
	 * image ID, and group control prefix. It supports custom dimensions and uses BFITHUMB
	 * for cropping and aspect ratio logic if necessary.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $settings              The settings array.
	 * @param mixed                $image_id              The image ID or array containing the image ID.
	 * @param string               $group_control_prefix  The prefix for the group control.
	 * @param string               $size_control_suffix   The suffix for the size control. Default is 'size'.
	 * @param string               $custom_dimension_suffix The suffix for the custom dimension control. Default is 'custom_dimension'.
	 *
	 * @return mixed The thumbnail size settings or 'full' if no custom size is set.
	 */
	public static function get_settings_thumbnail_size( $settings = array(), $image_id = '', $group_control_prefix = 'image', $size_control_suffix = 'size', $custom_dimension_suffix = 'custom_dimension' ) {
		if ( empty( $image_id ) ) {
			return false;
		}

		// Ensure BFITHUMB is available before proceeding.
		if ( ! defined( 'ELEMENTOR_PATH' ) ) {
			return false;
		}

		$bfi_thumb_path = ELEMENTOR_PATH . 'includes/libraries/bfi-thumb/bfi-thumb.php';
		if ( ! file_exists( $bfi_thumb_path ) ) {
			return false;
		}

		/** @phpstan-ignore-next-line */
		require_once $bfi_thumb_path;

		// Extract image ID from array if needed.
		if ( is_array( $image_id ) && array_key_exists( 'id', $image_id ) ) {
			$image_id = $image_id['id'];
		}

		// Default to 'full' if no custom size is defined in settings.
		$thumbnail_size = self::get_group_control_value( $settings, $group_control_prefix, $size_control_suffix, 'full' );

		// If custom size is selected, build an array with BFITHUMB cropping & aspect ratio logic.
		if ( array_key_exists( "{$group_control_prefix}_{$size_control_suffix}", $settings ) && $settings[ "{$group_control_prefix}_{$size_control_suffix}" ] === 'custom' ) {
			$aspect_ratio = self::get_image_aspect_ratio( self::get_int_value( $image_id ) );

			$dimensions    = self::get_group_control_value( $settings, $group_control_prefix, $custom_dimension_suffix, array() );
			$custom_width  = is_array( $dimensions ) && array_key_exists( 'width', $dimensions ) ? $dimensions['width'] : 0;
			$custom_height = is_array( $dimensions ) && array_key_exists( 'height', $dimensions ) ? $dimensions['height'] : 0;

			// Convert to floats or set them to null if zero.
			$float_width  = null;
			$float_height = null;

			if ( $custom_width ) {
				$float_width = $custom_width;
			}
			if ( $custom_height ) {
				$float_height = $custom_height;
			}

			$thumbnail_size = array(
				0           => $float_width,
				1           => $float_height,
				'bfi_thumb' => true,
				'crop'      => true,
			);

			// Restore missing width using the intrinsic aspect ratio.
			if ( empty( $custom_width ) && ! empty( $custom_height ) && ! empty( $aspect_ratio ) && is_numeric( $custom_height ) && is_numeric( $aspect_ratio ) ) {
				$thumbnail_size[0] = (float) $custom_height * (float) $aspect_ratio;
			}

			// Restore missing height using the intrinsic aspect ratio.
			if ( empty( $custom_height ) && ! empty( $custom_width ) && ! empty( $aspect_ratio ) && is_numeric( $custom_width ) && is_numeric( $aspect_ratio ) ) {
				$thumbnail_size[1] = (float) $custom_width / (float) $aspect_ratio;
			}

			// If no width/height was set, fall back to 'full'.
			if ( empty( $custom_width ) && empty( $custom_height ) ) {
				$thumbnail_size = 'full';
			}
		}

		return $thumbnail_size;
	}

	/**
	 * Retrieves the value of a group control setting.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $settings            The settings array.
	 * @param string               $group_control_prefix The prefix for the group control.
	 * @param string               $type                The type of control value to retrieve. Default is 'size'.
	 * @param mixed                $fallback            The fallback value if the setting is not found. Default is 'full'.
	 *
	 * @return mixed The value of the group control setting or the fallback value.
	 */
	public static function get_group_control_value( $settings, $group_control_prefix, $type = 'size', $fallback = 'full' ) {
		$value = $fallback;

		if ( array_key_exists( "{$group_control_prefix}_{$type}", $settings ) ) {
			$value = $settings[ "{$group_control_prefix}_{$type}" ];
		}

		return $value;
	}
}
