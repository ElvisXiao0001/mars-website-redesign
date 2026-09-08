<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

if ( ! function_exists( 'arts_get_post_author' ) ) {
	/**
	 * Retrieves the author information for a given post.
	 * Can be used outside of WordPress loop
	 *
	 * @param int|null $post_id The ID of the post. Default is null.
	 * @return array {
	 *   Array of author information.
	 *
	 *   @type int    $id     The author ID.
	 *   @type string $name   The author's display name.
	 *   @type string $url    The URL to the author's posts.
	 *   @type string $avatar The URL to the author's avatar.
	 * }
	 * @deprecated 2.0.0 Use `Arts\Utilities\Utilities::get_post_author()` method instead.
	 */
	function arts_get_post_author( $post_id = null ) {
		return Utilities::get_post_author( $post_id );
	}
}
