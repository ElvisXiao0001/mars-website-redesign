<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

if ( ! function_exists( 'arts_get_taxonomy_term_names' ) ) {
	/**
	 * Retrieves the names of taxonomy terms associated with a post.
	 *
	 * @param int|WP_Post $post     The post ID or object.
	 * @param string      $taxonomy The taxonomy name.
	 *
	 * @return array An array of taxonomy term names, slugs, and URLs.
	 * @deprecated 2.5.0 Use `\Arts\Utilities\Utilities::get_taxonomy_term_names()` instead.
	 */
	function arts_get_taxonomy_term_names( $post, $taxonomy ) {
		return Utilities::get_taxonomy_term_names( $post, $taxonomy );
	}
}
