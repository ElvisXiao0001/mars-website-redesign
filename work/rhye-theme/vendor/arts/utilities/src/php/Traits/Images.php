<?php

namespace Arts\Utilities\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Images Trait
 *
 * Provides utility methods for working with WordPress images and attachments.
 * Includes functionality for image sizing, aspect ratio calculation, and retrieving
 * available image sizes.
 *
 * @package Arts\Utilities\Traits
 * @since 1.0.0
 */
trait Images {
	/**
	 * Get the aspect ratio of an image.
	 *
	 * Calculates the aspect ratio of an image based on its width and height.
	 * Prevents division by zero by returning 1 if the height is 0.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $image_id The ID of the image attachment.
	 * @param string $size     Optional. The size of the image to retrieve. Default 'full'.
	 * @return float The aspect ratio of the image (width divided by height).
	 */
	public static function get_image_aspect_ratio( $image_id, $size = 'full' ) {
		$intrinsic_image = wp_get_attachment_image_src( $image_id, $size );
		$aspect_ratio    = 1;

		if ( is_array( $intrinsic_image ) && isset( $intrinsic_image[1] ) && isset( $intrinsic_image[2] ) && $intrinsic_image[2] !== 0 ) {
			$aspect_ratio = $intrinsic_image[1] / $intrinsic_image[2];
		}

		return $aspect_ratio;
	}

	/**
	 * Get available image sizes registered in WordPress.
	 *
	 * Retrieves all registered image sizes from WordPress and formats their names
	 * for display or selection purposes.
	 *
	 * @since 1.0.0
	 *
	 * @return array<string, string> Associative array of image sizes with keys as size identifiers and values as formatted names.
	 */
	public static function get_available_image_sizes(): array {
		$image_sizes     = get_intermediate_image_sizes();
		$formatted_sizes = array();
		$image_sizes[]   = 'full'; // default image size

		if ( $image_sizes && is_array( $image_sizes ) ) {
			foreach ( $image_sizes as $size ) {
				$formatted_sizes[ $size ] = ucwords( str_replace( '_', ' ', $size ) );
			}
		}

		return $formatted_sizes;
	}
}
