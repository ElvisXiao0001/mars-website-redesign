<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

add_filter( 'arts/utilities/get_page_titles/acf_fields', 'filter_arts_utilities_get_page_titles_acf_fields' );
if ( ! function_exists( 'filter_arts_utilities_get_page_titles_acf_fields' ) ) {
	/**
	 * Remap the page titles ACF fields.
	 *
	 * @param array $acf_fields
	 *
	 * @return array
	 */
	function filter_arts_utilities_get_page_titles_acf_fields( $acf_fields ) {
		$acf_fields['subtitle']    = 'subheading';
		$acf_fields['description'] = 'text';

		return $acf_fields;
	}
}

add_filter( 'arts/utilities/get_page_titles/titles', 'filter_arts_utilities_get_page_titles_titles' );
if ( ! function_exists( 'filter_arts_utilities_get_page_titles_titles' ) ) {
	/**
	 * Remap the page subtitle with the portfolio categories when available.
	 *
	 * @param array $titles
	 * @return array $titles
	 */
	function filter_arts_utilities_get_page_titles_titles( $titles ) {
		if ( Utilities::is_built_with_elementor() ) {
			global $post;

			$categories       = Utilities::get_taxonomy_term_names( $post->ID, 'arts_portfolio_category' );
			$categories_names = array();

			if ( ! empty( $categories ) ) {
				foreach ( $categories as $item ) {
					array_push( $categories_names, $item['name'] );
				}

				$titles['subtitle'] = implode( '&nbsp;&nbsp;/&nbsp;&nbsp;', $categories_names );
			}
		}

		return $titles;
	}
}

add_filter( 'arts/utilities/get_page_titles/strings', 'filter_arts_utilities_get_page_titles_strings' );
if ( ! function_exists( 'filter_arts_utilities_get_page_titles_strings' ) ) {
	/**
	 * Filters the page titles strings for localization.
	 *
	 * @param array $strings
	 * @return array $strings
	 */
	function filter_arts_utilities_get_page_titles_strings( $strings ) {
		$strings['category'] = esc_html__( 'Posts in category', 'rhye' );
		$strings['author']   = esc_html__( 'Posts by author', 'rhye' );
		$strings['tag']      = esc_html__( 'Posts with tag', 'rhye' );
		$strings['day']      = esc_html__( 'Day archive', 'rhye' );
		$strings['month']    = esc_html__( 'Month archive', 'rhye' );
		$strings['year']     = esc_html__( 'Year archive', 'rhye' );
		$strings['search']   = esc_html__( 'Search', 'rhye' );
		$strings['blog']     = esc_html__( 'Blog', 'rhye' );

		return $strings;
	}
}

if ( ! function_exists( 'arts_set_page_title' ) ) {
	/**
	 * Retrieves the page titles, subtitles, and descriptions based on the current context.
	 *
	 * @param bool $bc_compatibility_enabled Optional. Whether to enable backward compatibility with the previous version. Default true.
	 * @return array {
	 *   Array containing the page title, subtitle, and description.
	 *
	 *   @type string $title       The page title.
	 *   @type string $subtitle    The page subtitle.
	 *   @type string $description The page description.
	 * }
	 * @deprecated 2.0.0 Use `\Arts\Utilities\Utilities::get_page_titles()` method instead.
	 */
	function arts_set_page_title( $bc_compatibility_enabled = true ) {
		$titles = Utilities::get_page_titles();

		if ( $bc_compatibility_enabled ) {
			$titles = array( $titles['title'], $titles['subtitle'], $titles['description'] );
		}

		return $titles;
	}
}
