<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

if ( ! function_exists( 'arts_get_pagination_links_attributes' ) ) {
	/**
	 * Get custom attributes for pagination links.
	 *
	 * @param string $type The type of pagination link ('prev', 'next', 'num').
	 * @return string The custom attributes for the pagination link.
	 */
	function arts_get_pagination_links_attributes( $type ) {
		$attributes = array(
			'class' => array( 'material-icons' ),
		);
		return Utilities::get_pagination_link_attributes( $attributes, $type );
	}
}

add_filter( 'previous_posts_link_attributes', 'arts_filter_previous_posts_link_attributes', 10, 2 );
if ( ! function_exists( 'arts_filter_previous_posts_link_attributes' ) ) {
	/**
	 * Add custom class to the "Previous" pagination link.
	 *
	 * @param string $attributes The attributes for the previous posts link.
	 *
	 * @return string The modified attributes.
	 */
	function arts_filter_previous_posts_link_attributes() {
		return arts_get_pagination_links_attributes( 'prev' );
	}
}

add_filter( 'next_posts_link_attributes', 'arts_filter_next_posts_link_attributes' );
if ( ! function_exists( 'arts_filter_next_posts_link_attributes' ) ) {
	/**
	 * Add custom class to the "Next" pagination link.
	 *
	 * @param string $attributes The attributes for the next posts link.
	 *
	 * @return string The modified attributes.
	 */
	function arts_filter_next_posts_link_attributes() {
		return arts_get_pagination_links_attributes( 'next' );
	}
}

add_filter( 'get_pagenum_link', 'arts_filter_base_get_pagenum_link' );
if ( ! function_exists( 'arts_filter_base_get_pagenum_link' ) ) {
	/**
	 * Remove 'wp-admin/admin-ajax.php' portion from the pagination URL.
	 * To use with AJAX pagination.
	 *
	 * @param string $result The original pagination URL.
	 *
	 * @return string The modified pagination URL.
	 */
	function arts_filter_base_get_pagenum_link( $result ) {
		$result = str_replace( 'wp-admin/admin-ajax.php', '', $result );

		return $result;
	}
}

if ( ! function_exists( 'arts_posts_pagination' ) ) {
	/**
	 * Create a custom pagination markup for posts.
	 *
	 * Removes the default h2 heading from pagination,
	 * hides the default previous/next links, and adds custom ones.
	 * It also adds a container for the pagination links.
	 *
	 * @param array  $args  Optional. Arguments for pagination. Default empty array.
	 * @param string $class Optional. CSS class for the pagination container. Default 'pagination'.
	 * @param bool   $echo  Optional. Whether to echo the pagination. Default true.
	 *
	 * @return void|string Void if $echo is true, otherwise the pagination HTML markup.
	 */
	function arts_posts_pagination( $args = array(), $class = 'pagination', $echo = true ) {
		$args = wp_parse_args(
			$args,
			array(
				'prev_next'          => false, // hide default prev/next
				'prev_text'          => 'keyboard_arrow_left',
				'next_text'          => 'keyboard_arrow_right',
				'screen_reader_text' => esc_html__( 'Posts navigation', 'rhye' ),
			)
		);

		$class .= ' js-pagination';

		$links     = paginate_links( $args );
		$prev_link = get_previous_posts_link( $args['prev_text'] );
		$next_link = get_next_posts_link( $args['next_text'] );

		$template = apply_filters(
			'arts_navigation_markup_template',
			'<nav class="navigation %1$s" role="navigation"><div class="screen-reader-text d-none">%2$s</div><div class="nav-links">%3$s<div class="nav-links__container">%4$s</div>%5$s</div></nav>',
			$args,
			$class
		);

		if ( $echo ) {
			echo sprintf( $template, $class, $args['screen_reader_text'], $prev_link, $links, $next_link );
		} else {
			return sprintf( $template, $class, $args['screen_reader_text'], $prev_link, $links, $next_link );
		}
	}
}
