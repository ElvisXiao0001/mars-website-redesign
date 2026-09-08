<?php

namespace Arts\Utilities\Traits;

use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Query Trait
 *
 * Provides utility methods for working with WordPress queries,
 * retrieving posts, categories, and handling various query-related tasks.
 *
 * @package Arts\Utilities\Traits
 * @since 1.0.0
 */
trait Query {
	/**
	 * Retrieves a page by its title.
	 *
	 * @since 1.0.0
	 *
	 * @param string $page_title The title of the page to retrieve.
	 * @param string $output     Optional. The output type. Default is OBJECT.
	 * @param string $post_type  Optional. The post type. Default is 'page'.
	 *
	 * @return mixed|null The retrieved page object or null if not found.
	 */
	public static function get_page_by_title( $page_title, $output = OBJECT, $post_type = 'page' ) {
		$query = new WP_Query(
			array(
				'post_type'              => $post_type,
				'title'                  => $page_title,
				'post_status'            => 'all',
				'posts_per_page'         => 1,
				'no_found_rows'          => true,
				'ignore_sticky_posts'    => true,
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false,
				'orderby'                => 'date',
				'order'                  => 'ASC',
			)
		);

		if ( ! empty( $query->post ) ) {
			$_post = $query->post;

			if ( ARRAY_A === $output ) {
				return $_post->to_array();
			} elseif ( ARRAY_N === $output ) {
				return array_values( $_post->to_array() );
			}

			return $_post;
		}

		return null;
	}

	/**
	 * Get the page titles, subtitles, and descriptions based on the current context.
	 *
	 * @since 1.0.0
	 *
	 * @return array{title: string, subtitle: string, description: string} An associative array containing the page title, subtitle, and description.
	 *
	 * @filter `arts/utilities/get_page_titles/acf_fields` Allows customization of ACF fields used for subtitle and description.
	 * @filter `arts/utilities/get_page_titles/strings`    Allows customization of default strings for various contexts.
	 * @filter `arts/utilities/get_page_titles/titles`     Allows customization of the final titles array before it is returned.
	 */
	public static function get_page_titles(): array {
		$page_title       = '';
		$page_subtitle    = '';
		$page_description = '';
		$titles           = array(
			'title'       => '',
			'subtitle'    => '',
			'description' => '',
		);
		$acf_fields       = self::get_array_value(
			apply_filters(
				'arts/utilities/get_page_titles/acf_fields',
				array(
					'subtitle'    => 'subheading',
					'description' => 'description',
				)
			)
		);
		$strings          = self::get_array_value(
			apply_filters(
				'arts/utilities/get_page_titles/strings',
				array(
					'category' => esc_html__( 'Posts in category', 'arts-utilities' ),
					'author'   => esc_html__( 'Posts by author', 'arts-utilities' ),
					'tag'      => esc_html__( 'Posts with tag', 'arts-utilities' ),
					'day'      => esc_html__( 'Day archive', 'arts-utilities' ),
					'month'    => esc_html__( 'Month archive', 'arts-utilities' ),
					'year'     => esc_html__( 'Year archive', 'arts-utilities' ),
					'search'   => esc_html__( 'Search', 'arts-utilities' ),
					'blog'     => esc_html__( 'Blog', 'arts-utilities' ),
				)
			)
		);

		if ( self::is_built_with_elementor() ) {
			$page_title = get_the_title();

			if ( isset( $acf_fields['subtitle'] ) ) {
				$page_subtitle = self::acf_get_field( self::get_string_value( $acf_fields['subtitle'] ) );
			}

			if ( isset( $acf_fields['description'] ) ) {
				$page_description = self::acf_get_field( self::get_string_value( $acf_fields['description'] ) );
			}
		} elseif ( self::is_woocommerce_archive() ) {
			$page_title = self::get_woocommerce_page_title();
		} elseif ( is_category() ) {
			$cat_id   = self::get_int_value( get_query_var( 'cat' ) );
			$category = get_category( $cat_id );
			if ( $category && ! is_wp_error( $category ) ) {
				$page_title = $category->name;
			}
			$page_subtitle = self::get_string_value( $strings['category'] );
		} elseif ( is_author() ) {
			$author_id = self::get_int_value( get_query_var( 'author' ) );
			$userdata  = get_userdata( $author_id );
			if ( $userdata ) {
				$page_title = $userdata->display_name;
			}
			$page_subtitle = self::get_string_value( $strings['author'] );
		} elseif ( is_tag() ) {
			$page_title    = single_tag_title( '', false );
			$page_subtitle = self::get_string_value( $strings['tag'] );
		} elseif ( is_day() ) {
			$page_title    = get_the_date();
			$page_subtitle = self::get_string_value( $strings['day'] );
		} elseif ( is_month() ) {
			$page_title    = get_the_date( 'F Y' );
			$page_subtitle = self::get_string_value( $strings['month'] );
		} elseif ( is_year() ) {
			$page_title    = get_the_date( 'Y' );
			$page_subtitle = self::get_string_value( $strings['year'] );
		} elseif ( is_home() ) {
			$posts_page_id = self::get_int_value( get_option( 'page_for_posts' ) );
			if ( $posts_page_id ) {
				$page_title = get_the_title( $posts_page_id );

				if ( isset( $acf_fields['subtitle'] ) ) {
					$page_subtitle = self::acf_get_field( self::get_string_value( $acf_fields['subtitle'] ), $posts_page_id );
				}

				if ( isset( $acf_fields['description'] ) ) {
					$page_description = self::acf_get_field( self::get_string_value( $acf_fields['description'] ), $posts_page_id );
				}
			} else {
				// Fallback for when no static posts page is set
				$page_title = self::get_string_value( $strings['blog'] );
			}
		} elseif ( is_search() ) {
			$page_title = self::get_string_value( $strings['search'] );
		} else {
			$page_title = get_the_title();
		}

		// Fallback page title.
		if ( ! $page_title ) {
			$page_title = self::get_string_value( $strings['blog'] );
		}

		// Fallback page description.
		if ( ! $page_description ) {
			$page_description = get_the_archive_description();
		}

		$titles['title']       = $page_title;
		$titles['subtitle']    = $page_subtitle;
		$titles['description'] = $page_description;

		$filtered_titles = apply_filters( 'arts/utilities/get_page_titles/titles', $titles );
		/** @var array{title: string, subtitle: string, description: string} $filtered_titles */
		return $filtered_titles;
	}

	/**
	 * Retrieve all uploaded font attachments.
	 *
	 * @since 1.0.0
	 *
	 * @return array<int, array{ID: int, permalink: string|false, filetype: array<string, string|false>}> List of uploaded font files with their ID, permalink, and filetype.
	 */
	public static function get_uploaded_fonts(): array {
		$result     = array();
		$query_args = array(
			'posts_per_page' => -1,
			'post_type'      => 'attachment',
			'post_mime_type' => 'font',
			'post_status'    => array(
				'publish',
				'inherit',
			),
			'fields'         => 'ids', // Optimize query by retrieving only IDs
		);

		$loop = new WP_Query( $query_args );

		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) {
				$loop->the_post();
				$id = get_the_ID();

				if ( ! $id ) {
					continue;
				}

				$permalink = wp_get_attachment_url( $id );
				if ( ! is_string( $permalink ) ) {
					$permalink = false;
				}

				$filetype = $permalink ? wp_check_filetype( $permalink ) : array(
					'ext'  => false,
					'type' => false,
				);

				$result[] = array(
					'ID'        => $id,
					'permalink' => $permalink,
					'filetype'  => $filetype,
				);
			}

			wp_reset_postdata();
		}

		return $result;
	}

	/**
	 * WORKAROUND for Intuitive Custom Post Order plugin conflict.
	 *
	 * The HICPO plugin intercepts WP_Query and incorrectly handles orderby=post__in,
	 * breaking custom post ordering in Elementor. This method temporarily sets
	 * $_GET['orderby'] to bypass the plugin's faulty check.
	 *
	 * IMPORTANT: This is technical debt for third-party plugin compatibility.
	 * - Sets known-safe value ('post__in'), NOT user input
	 * - Restored immediately via fix_query_hicpo_after()
	 * - Called right before WP_Query execution
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $query_args WP_Query arguments to check for orderby=post__in.
	 *
	 * @return string Original $_GET['orderby'] value for restoration.
	 *
	 * @see fix_query_hicpo_after() Restores original $_GET value.
	 * @link https://wordpress.org/plugins/intuitive-custom-post-order/
	 *
	 * @todo Consider using 'pre_get_posts' filter instead of global manipulation.
	 */
	public static function fix_query_hicpo_before( $query_args = array() ) {
		/** @var array<string, mixed> $query_args_validated */
		$query_args_validated    = self::get_array_value( $query_args );
		$should_alter_global_get = isset( $query_args_validated['orderby'] ) && self::get_string_value( $query_args_validated['orderby'] ) === 'post__in';
		$get_orderby             = isset( $_GET['orderby'] ) ? $_GET['orderby'] : null;

		if ( $should_alter_global_get ) {
			// Temporarily modify $_GET to work around HICPO plugin bug
			global $_GET;
			if ( is_array( $_GET ) ) {
				$_GET['orderby'] = 'post__in';
			}
		}

		return self::get_string_value( $get_orderby );
	}

	/**
	 * Restores the original $_GET['orderby'] value
	 * just in case it's used somewhere else.
	 * Call right after \WP_Query.
	 *
	 * @since 1.0.0
	 *
	 * @param string $get_orderby_value
	 *
	 * @return void
	 */
	public static function fix_query_hicpo_after( $get_orderby_value ) {
		$_GET['orderby'] = $get_orderby_value;
	}

	/**
	 * Retrieves categories of posts based on the specified mode.
	 *
	 * @deprecated 2.0.0 Use get_posts_terms() instead.
	 * @since 1.0.0
	 *
	 * @param string               $mode Mode of retrieval.
	 * @param array<string, mixed> $args Optional arguments.
	 *
	 * @return array<int|string, array{id: int, name: string, slug: string, total: int, current: bool, url?: string}> An array of terms with their details.
	 */
	public static function get_posts_categories( $mode = 'all', $args = array() ) {
		// Forward to the new method
		return self::get_posts_terms( $mode, $args, 'category' );
	}

	/**
	 * Retrieves terms (categories/tags) of posts based on the specified mode.
	 *
	 * @since 1.0.24
	 *
	 * @param string               $mode The mode of retrieval ('all' or 'current_page').
	 * @param array<string, mixed> $args Optional arguments.
	 * @param string               $taxonomy The taxonomy to retrieve. Default 'category'.
	 *
	 * @return array<int|string, array{id: int, name: string, slug: string, total: int, current: bool, url?: string}> An array of terms with their details.
	 */
	public static function get_posts_terms( string $mode = 'all', array $args = array(), string $taxonomy = 'category' ): array {
		$result = array();

		$defaults = array(
			'post_type'      => 'post',
			'posts_per_page' => null,
			'orderby'        => null,
			'order'          => null,
		);

		$args = wp_parse_args( $args, $defaults );

		$query_args = array(
			'post_type'      => $args['post_type'],
			'posts_per_page' => $args['posts_per_page'],
			'orderby'        => $args['orderby'],
			'order'          => $args['order'],
			'no_found_rows'  => true,
		);

		if ( ! is_singular() && $mode === 'current_page' ) {
			$sync_global_query_vars = array(
				'paged',
				'cat',
				'tag_id',
				'tag',
			);

			foreach ( $sync_global_query_vars as $var ) {
				$value = get_query_var( $var );
				if ( $value ) {
					$query_args[ $var ] = $value;
				}
			}
		}

		$loop = new WP_Query( $query_args );

		if ( $loop->have_posts() ) {
			if ( $mode === 'current_page' ) {
				while ( $loop->have_posts() ) {
					$loop->the_post();
					$post_id = get_the_ID();

					if ( ! $post_id ) {
						continue;
					}

					$terms = get_the_terms( $post_id, $taxonomy );

					if ( is_array( $terms ) ) {
						foreach ( $terms as $term ) {
							if ( array_key_exists( $term->term_id, $result ) ) {
								$result[ $term->term_id ]['total'] += 1;
							} else {
								$result[ $term->term_id ] = array(
									'id'      => $term->term_id,
									'name'    => $term->name,
									'slug'    => $term->slug,
									'total'   => 1,
									'current' => ( $taxonomy === 'category' && is_category( $term->term_id ) ) || ( $taxonomy === 'post_tag' && is_tag( $term->term_id ) ),
								);
							}
						}
					}
				}
			} else {
				$posts_terms = get_terms( array( 'taxonomy' => $taxonomy ) );

				if ( ! is_wp_error( $posts_terms ) && is_array( $posts_terms ) ) {
					foreach ( $posts_terms as $term ) {
						$term_url = get_term_link( $term->term_id, $taxonomy );

						$term_data = array(
							'id'      => $term->term_id,
							'slug'    => $term->slug,
							'name'    => $term->name,
							'total'   => $term->count,
							'current' => ( $taxonomy === 'category' && is_category( $term->term_id ) ) || ( $taxonomy === 'post_tag' && is_tag( $term->term_id ) ),
						);

						if ( is_string( $term_url ) ) {
							$term_data['url'] = $term_url;
						}

						$result[] = $term_data;
					}
				}
			}

			wp_reset_postdata();
		}

		return $result;
	}

	/**
	 * Retrieves the author information for a given post.
	 * Can be used outside of WordPress loop
	 *
	 * @since 1.0.0
	 *
	 * @param int|null $post_id The ID of the post. Default is null.
	 * @return array<string, mixed> {
	 *   Array of author information.
	 *
	 *   @type int    $id     The author ID.
	 *   @type string $name   The author's display name.
	 *   @type string $url    The URL to the author's posts.
	 *   @type string $avatar The URL to the author's avatar.
	 * }
	 */
	public static function get_post_author( $post_id = null ) {
		$result = array(
			'id'     => 0,
			'name'   => '',
			'url'    => '',
			'avatar' => '',
		);

		if ( ! $post_id ) {
			global $post;

			if ( $post instanceof \WP_Post ) {
				$post_id = $post->ID;
			} else {
				return $result;
			}
		}

		$post_id_validated = self::get_post_id( $post_id );
		if ( $post_id_validated === null ) {
			return $result;
		}

		$author_id = get_post_field( 'post_author', $post_id_validated );

		if ( ! $author_id || ! is_numeric( $author_id ) ) {
			return $result;
		}

		$author_id = (int) $author_id;

		$result['id'] = $author_id;

		$author_name = get_the_author_meta( 'display_name', $author_id );

		if ( $author_name ) {
			$result['name'] = esc_html( $author_name );
		}

		$author_avatar = get_avatar_url( $author_id );

		if ( $author_avatar ) {
			$result['avatar'] = esc_url( $author_avatar );
		}

		$author_url = get_author_posts_url( $author_id );

		if ( $author_url ) {
			$result['url'] = esc_url( $author_url );
		}

		return $result;
	}
}
