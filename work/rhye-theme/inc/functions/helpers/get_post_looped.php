<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

if ( ! function_exists( 'arts_get_post_looped' ) ) {
	/**
	 * Get the adjacent post based on specified criteria or loop through all posts if none found.
	 *
	 * @param array $args {
	 *   Optional. Arguments to retrieve the adjacent post.
	 *
	 *   @type string  $direction      Direction to retrieve the post. Accepts 'next' or 'previous'. Default 'next'.
	 *   @type bool    $in_same_term   Whether post should be in the same term. Default false.
	 *   @type string  $excluded_terms Comma-separated list of excluded term IDs. Default ''.
	 *   @type string  $taxonomy       Taxonomy to use for terms. Default 'category'.
	 * }
	 * @return WP_Post|null The adjacent post object if found, otherwise null.
	 */
	function arts_get_post_looped( $args ) {
		$defaults = array(
			'loop'           => true,
			'direction'      => 'backward',
			'in_same_term'   => false,
			'excluded_terms' => '',
			'taxonomy'       => 'category',
		);

		$args        = wp_parse_args( $args, $defaults );
		$is_previous = $args['direction'] === 'forward';

		$post = get_adjacent_post( $args['in_same_term'], $args['excluded_terms'], $is_previous, $args['taxonomy'] );

		if ( $post ) {
			return $post;
		} elseif ( ! $args['loop'] ) {
			return null;
		} else {

			$posts      = array();
			$query_args = array(
				'post_type'      => get_post_type(),
				'posts_per_page' => -1,
				'no_found_rows'  => true,
			);

			// filter next/prev post by the same term
			if ( $args['in_same_term'] ) {
				$terms         = array();
				$current_terms = Utilities::get_taxonomy_term_names( get_the_ID(), $args['taxonomy'] );

				if ( ! empty( $current_terms ) ) {
					foreach ( $current_terms as $term ) {
						array_push( $terms, $term['slug'] );
					}

					$query_args['tax_query'] = array(
						array(
							'taxonomy' => $args['taxonomy'],
							'field'    => 'slug',
							'terms'    => $terms, // include only posts of the current terms (slugs)
						),
					);
				}
			}

			$loop = new WP_Query( $query_args );

			if ( $loop->have_posts() ) {
				while ( $loop->have_posts() ) {
					$loop->the_post();
					$posts[] = get_post();
				}

				wp_reset_postdata();
			}

			if ( ! empty( $posts ) ) {
				if ( $is_previous ) {
					return $posts[0];
				} else {
					return $posts[ count( $posts ) - 1 ];
				}
			}
		}
	}
}
