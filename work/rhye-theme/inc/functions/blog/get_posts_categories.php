<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

if ( ! function_exists( 'arts_get_posts_categories' ) ) {
	/**
	 * Retrieves categories of posts based on the specified mode.
	 *
	 * @param string $mode The mode of retrieval. Can be 'all' to get all categories or 'current_page' to get categories of posts displayed on the current page.
	 * @param array  $args Optional. An array of arguments to modify the query. Default empty array.
	 *   @type string $post_type      The post type to query. Default 'post'.
	 *   @type int    $posts_per_page The number of posts per page. Default null.
	 *   @type string $orderby        The field to order by. Default null.
	 *   @type string $order          The order direction. Default null.
	 *
	 * @return array An array of categories with their details.
	 * @deprecated 4.0.0 Use `\Arts\Utilities\Utilities::get_posts_categories()` method instead.
	 */
	function arts_get_posts_categories( $mode = 'all' ) {
		return Utilities::get_posts_categories( $mode );
	}
}
