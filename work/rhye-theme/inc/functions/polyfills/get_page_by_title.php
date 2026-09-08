<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

if ( ! function_exists( 'arts_get_page_by_title' ) ) {
	/**
	 * Retrieves a page by its title.
	 *
	 * @param string $page_title The title of the page to retrieve.
	 * @param string $output     Optional. The output type. Default is OBJECT.
	 * @param string $post_type  Optional. The post type. Default is 'page'.
	 *
	 * @return mixed|null The retrieved page object or null if not found.
	 * @deprecated 2.0.0 Use `\Arts\Utilities\Utilities::get_page_by_title()` method instead.
	 */
	function arts_get_page_by_title( $page_title, $output = OBJECT, $post_type = 'page' ) {
		return Utilities::get_page_by_title( $page_title, $output, $post_type );
	}
}
