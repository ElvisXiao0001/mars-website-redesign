<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

if ( ! function_exists( 'arts_get_post_looped_overridden' ) ) {
	/**
	 * Retrieve the overridden previous or next post based on the provided type.
	 *
	 * @param string $type The type of post to retrieve ('prev' or 'next').
	 * @param array  $args Additional arguments for retrieving the post.
	 *
	 * @return WP_Post|null The overridden post object or null if not found.
	 */
	function arts_get_post_looped_overridden( $type, $args ) {
		$prev_post = null;
		$next_post = null;

		if ( $type === 'prev' ) {
			$prev_post_overridden = Utilities::get_overridden_document_option( 'portfolio_nav_previous_post', 'page_portfolio_nav_settings_overridden' );

			// attempt to find overridden "prev" post
			if ( is_array( $prev_post_overridden ) && array_key_exists( 'url', $prev_post_overridden ) && ! empty( $prev_post_overridden['url'] ) ) {
				$prev_post_id = url_to_postid( $prev_post_overridden['url'] );

				if ( $prev_post_id && $prev_post_id !== 0 ) {
					$prev_post = get_post( $prev_post_id );
				}
			}

			// overridden post is either not set or doesn't exist
			if ( ! $prev_post ) {
				$prev_post = arts_get_post_looped( $args );
			}

			return $prev_post;
		}

		if ( $type === 'next' ) {
			$next_post_overridden = Utilities::get_overridden_document_option( 'portfolio_nav_next_post', 'page_portfolio_nav_settings_overridden' );

			// attempt to find overridden "next" post
			if ( is_array( $next_post_overridden ) && array_key_exists( 'url', $next_post_overridden ) && ! empty( $next_post_overridden['url'] ) ) {
				$next_post_id = url_to_postid( $next_post_overridden['url'] );

				if ( $next_post_id && $next_post_id !== 0 ) {
					$next_post = get_post( $next_post_id );
				}
			}

			// overridden post is either not set or doesn't exist
			if ( ! $next_post && function_exists( 'arts_get_post_looped' ) ) {
				$next_post = arts_get_post_looped( $args );
			}

			return $next_post;
		}
	}
}
