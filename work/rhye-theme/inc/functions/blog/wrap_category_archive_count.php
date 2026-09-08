<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_filter( 'wp_list_categories', 'arts_cat_count_span' );
if ( ! function_exists( 'arts_cat_count_span' ) ) {
	/**
	 * Modify category archive links to wrap post count in a span element.
	 *
	 * @param string $links The category archive links.
	 * @return string Modified category archive links with post count wrapped in a span.
	 */
	function arts_cat_count_span( $links ) {
		$links = str_replace( '</a> (', '</a><span>', $links );
		$links = str_replace( ')', '</span>', $links );

		return $links;
	}
}

add_filter( 'get_archives_link', 'arts_archive_count_span' );
if ( ! function_exists( 'arts_archive_count_span' ) ) {
	/**
	 * Modify archive links to wrap post count in a span element.
	 *
	 * @param string $links The archive links.
	 * @return string Modified archive links with post count wrapped in a span.
	 */
	function arts_archive_count_span( $links ) {
		// Only handle list format (li elements) - leave dropdown format unchanged
		if ( strpos( $links, '</a>&nbsp;(' ) !== false ) {
			$links = str_replace( '</a>&nbsp;(', '</a><span>', $links );
			$links = str_replace( ')', '</span>', $links );
		}

		return $links;
	}
}
