<?php

namespace Arts\Utilities\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Markup Trait
 *
 * Provides utility methods for generating HTML markup,
 * working with HTML attributes, and handling components.
 *
 * @package Arts\Utilities\Traits
 * @since 1.0.0
 */
trait Markup {
	/**
	 * Like wp_parse_args but supports recursivity
	 * By default converts the returned type based on the $args and $defaults
	 *
	 * @since 1.0.0
	 *
	 * @param array<int|string, mixed>|object $args           Array or object that contains the user-defined values.
	 * @param array<int|string, mixed>|object $defaults       Array, Object that serves as the defaults or string.
	 * @param boolean      $preserve_type  Optional. Convert output array into object if $args or $defaults if it is. Default true.
	 * @param boolean      $preserve_integer_keys Optional. If given, integer keys will be preserved and merged instead of appended.
	 *
	 * @return array<int|string, mixed>|object $output Merged user defined values with defaults.
	 */
	public static function parse_args_recursive( $args, $defaults, $preserve_type = true, $preserve_integer_keys = false ) {
		$output = array();

		foreach ( array( $defaults, $args ) as $list ) {
			$output = self::merge_lists( $output, $list, $preserve_integer_keys );
		}

		return self::convert_output_type( $output, $args, $defaults, $preserve_type );
	}

	/**
	 * Get component attributes as an array.
	 *
	 * This method modifies the given attributes array by adding component-specific
	 * attributes based on the provided arguments. It ensures that the component
	 * name, options, and animation status are included in the attributes array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed>        $attributes    Existing attributes.
	 * @param array<string, mixed>        $args {
	 *  Optional. Arguments to modify component attributes.
	 *
	 *  @type string $name         Component name. Default 'MyComponent'.
	 *  @type array  $options      Component options. Default empty array.
	 *  @type bool   $hasAnimation Whether the component has animation. Default false.
	 * }
	 * @param array<int, string>|string $exclude_names Names to exclude from adding to attributes. Default empty array.
	 *
	 * @return array<string, mixed> Modified attributes.
	 */
	public static function get_component_attributes( array $attributes = array(), array $args = array(), $exclude_names = array( 'PSWP' ) ): array {
		$defaults = array(
			'name'         => 'MyComponent',
			'options'      => array(),
			'dependencies' => array(),
			'hasAnimation' => false,
		);

		$args = wp_parse_args( $args, $defaults );

		// Ensure $exclude_names is an array
		if ( ! is_array( $exclude_names ) ) {
			$exclude_names = (array) $exclude_names;
		}

		if ( ! empty( $args['name'] ) ) {
			if ( ! in_array( $args['name'], $exclude_names, true ) ) {
				$kebab_case_name = self::convert_camel_to_kebab_case( self::get_string_value( $args['name'] ) );

				if ( array_key_exists( 'class', $attributes ) && is_array( $attributes['class'] ) ) {
					$attributes['class'][] = $kebab_case_name;
					$attributes['class'][] = 'js-' . $kebab_case_name;
				} else {
					$attributes['class'] = array( $kebab_case_name, 'js-' . $kebab_case_name );
				}
			}

			$attribute_component_name                = esc_attr( self::get_string_value( apply_filters( 'arts/utilities/markup/add_component_attributes/attribute_name', 'data-arts-component-name' ) ) );
			$attributes[ $attribute_component_name ] = esc_attr( self::get_string_value( $args['name'] ) );

			if ( is_array( $args['options'] ) && ! empty( $args['options'] ) ) {
				$attribute_component_options                = esc_attr( self::get_string_value( apply_filters( 'arts/utilities/markup/add_component_attributes/attribute_options', 'data-arts-component-options' ) ) );
				$attributes[ $attribute_component_options ] = wp_json_encode( $args['options'] );
			}

			if ( is_array( $args['dependencies'] ) && ! empty( $args['dependencies'] ) ) {
				$attr_deps                = esc_attr( self::get_string_value( apply_filters( 'arts/utilities/markup/add_component_attributes/attribute_dependencies', 'data-arts-component-dependencies' ) ) );
				$attributes[ $attr_deps ] = wp_json_encode( $args['dependencies'] );
			}

			if ( $args['hasAnimation'] ) {
				$attribute_component_animation                = esc_attr( self::get_string_value( apply_filters( 'arts/utilities/markup/add_component_attributes/attribute_animation', 'data-arts-os-animation' ) ) );
				$attributes[ $attribute_component_animation ] = 'true';
			}
		}

		return $attributes;
	}

	/**
	 * Prints or returns HTML attributes from an associative array.
	 *
	 * Accepts mixed input for compatibility with WordPress filters that return mixed.
	 * Non-array values are silently ignored and return empty string.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $attributes Associative array of attributes and their values. Non-arrays are ignored.
	 * @param bool  $echo       Optional. Whether to echo the attributes. Default true.
	 *
	 * @return string|null The HTML attributes string if $echo is false, otherwise null.
	 */
	public static function print_attributes( $attributes = array(), $echo = true ) {
		// Check if the provided attributes array is valid and non-empty
		// This ensures that we have attributes to process and print
		if ( ! is_array( $attributes ) || empty( $attributes ) ) {
			return '';
		}

		/** @var list<string> $attribute_pairs */
		$attribute_pairs = array();

		foreach ( $attributes as $key => $val ) {
			// No need to print an empty array
			if ( is_array( $val ) && empty( $val ) ) {
				continue;
			}
			// If the attribute value is an array, it is assumed to be a set of CSS classes.
			// We remove duplicates, filter out empty values, and then convert the array to a space-separated string.
			// Usually we prepare a class set in this way
			if ( is_array( $val ) ) {
				// Cast all values to strings, remove duplicates, and filter empty values
				$string_values = array_filter( $val, 'is_scalar' );
				/** @var array<scalar> $string_values */
				$string_values = array_map( 'strval', $string_values );
				$string_values = array_unique( $string_values );
				$string_values = array_filter( $string_values );

				// Convert the array to a space-separated string
				$val = implode( ' ', $string_values );
			}

			if ( is_int( $key ) ) {
				$attribute_pairs[] = self::get_string_value( $val );
			} else {
				$val = htmlspecialchars( self::get_string_value( $val ), ENT_QUOTES | ENT_HTML5 );

				// Different escaping function for URLs
				if ( $key === 'href' ) {
					$attribute_pairs[] = esc_attr( $key ) . '="' . esc_attr( esc_url( $val ) ) . '"';
				} else {
					$attribute_pairs[] = esc_attr( $key ) . '="' . esc_attr( $val ) . '"';
				}
			}
		}

		if ( $echo ) {
			// All attributes and values are escaped and are safe to output
			echo join( ' ', $attribute_pairs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return null;
		} else {
			return join( ' ', $attribute_pairs );
		}
	}

	/**
	 * Add classes to an array of HTML attributes.
	 *
	 * This method adds one or more classes to the 'class' attribute of the provided
	 * attributes array. If the 'class' attribute already exists, it merges the new
	 * classes with the existing ones.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $attributes Associative array of HTML attributes.
	 * @param string|array<int, string> $classes    One or more classes to add.
	 *
	 * @return array<string, mixed> Modified attributes with added classes.
	 */
	public static function add_classes_to_attributes( array $attributes, $classes ): array {
		// Normalize classes to array
		if ( is_string( $classes ) ) {
			$classes = explode( ' ', trim( $classes ) );
		} elseif ( ! is_array( $classes ) ) {
			$classes = array();
		}

		// Filter out empty strings
		$classes = array_filter( $classes, fn( $class ) => $class !== '' );

		if ( empty( $classes ) ) {
			return $attributes;
		}

		// Handle existing class attribute
		$existing_classes = array();
		if ( isset( $attributes['class'] ) ) {
			if ( is_string( $attributes['class'] ) ) {
				$existing_classes = explode( ' ', trim( $attributes['class'] ) );
			} elseif ( is_array( $attributes['class'] ) ) {
				$existing_classes = $attributes['class'];
			}
			// Filter out empty strings from existing classes
			$existing_classes = array_filter( $existing_classes, fn( $class ) => $class !== '' );
		}

		// Merge and assign
		$attributes['class'] = array_merge( $existing_classes, $classes );

		return $attributes;
	}

	/**
	 * Get post terms classes.
	 *
	 * This method retrieves the terms associated with the post's taxonomies
	 * and returns them as an array of class names in the format "taxonomy-term".
	 * Can be used in Masonry grids for filtering posts by terms.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed>  $post    Associative array containing post data with 'taxonomies' key.
	 * @param string $divider Optional. The divider between taxonomy and term. Default is '-'.
	 *
	 * @return array<int, string> An array of class names for the post's terms.
	 */
	public static function get_post_terms_classes( array $post, string $divider = '-' ): array {
		$terms_classes = array();

		if ( isset( $post['taxonomies'] ) && ! empty( $post['taxonomies'] ) ) {
			$post_taxonomies = $post['taxonomies'];

			if ( ! is_array( $post_taxonomies ) ) {
				return $terms_classes;
			}

			foreach ( $post_taxonomies as $taxonomy ) {
				if ( ! is_array( $taxonomy ) || ! isset( $taxonomy['id'], $taxonomy['terms'] ) ) {
					continue;
				}

				$tax_id = self::get_string_value( $taxonomy['id'] );

				if ( ! empty( $taxonomy['terms'] ) ) {
					$tax_terms = $taxonomy['terms'];

					if ( ! is_array( $tax_terms ) ) {
						continue;
					}

					foreach ( $tax_terms as $term ) {
						if ( ! is_array( $term ) || ! isset( $term['slug'] ) ) {
							continue;
						}
						$term_slug       = self::get_string_value( $term['slug'] );
						$terms_classes[] = "{$tax_id}{$divider}{$term_slug}";
					}
				}
			}
		}

		return $terms_classes;
	}

	/**
	 * Merges two lists recursively.
	 *
	 * @since 1.0.0
	 *
	 * @param array<int|string, mixed>|object $output              The initial list to merge into.
	 * @param array<int|string, mixed>|object $list                The list to merge from.
	 * @param bool         $preserve_integer_keys Whether to preserve integer keys.
	 *
	 * @return array<int|string, mixed>|object The merged list.
	 */
	private static function merge_lists( $output, $list, $preserve_integer_keys ) {
		if ( ! is_array( $output ) ) {
			$output = (array) $output;
		}
		foreach ( (array) $list as $key => $value ) {
			$output = self::merge_list_item( $output, $key, $value, $preserve_integer_keys );
		}

		return $output;
	}

	/**
	 * Merges a list item into the output array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<int|string, mixed> $output               The output array to merge into.
	 * @param mixed $key                  The key of the item to merge.
	 * @param mixed $value                The value of the item to merge.
	 * @param bool  $preserve_integer_keys Whether to preserve integer keys.
	 *
	 * @return array<int|string, mixed> The merged output array.
	 */
	private static function merge_list_item( $output, $key, $value, $preserve_integer_keys ) {
		// Type guard for array key
		if ( ! is_string( $key ) && ! is_int( $key ) ) {
			return $output;
		}

		if ( is_integer( $key ) && ! $preserve_integer_keys ) {
			$output[] = $value;
		} elseif ( self::should_merge_recursively( $output, $key, $value ) ) {
			// Validate before recursion
			if ( ! is_array( $output ) || ! isset( $output[ $key ] ) ) {
				$output[ $key ] = $value;
			} else {
				$safe_current   = self::get_array_value( $output[ $key ] );
				$safe_new       = self::get_array_value( $value );
				$output[ $key ] = self::merge_lists( $safe_current, $safe_new, $preserve_integer_keys );
			}
		} else {
			$output[ $key ] = $value;
		}
		return $output;
	}

	/**
	 * Converts the output type based on the provided arguments and defaults.
	 *
	 * @since 1.0.0
	 *
	 * @param array<int|string, mixed>|object $output       The output to be converted.
	 * @param mixed $args         The arguments to check for object type.
	 * @param mixed $defaults     The default values to check for object type.
	 * @param bool  $preserve_type Whether to preserve the object type.
	 *
	 * @return array<int|string, mixed>|object The converted output, either as an object or the original type.
	 */
	private static function convert_output_type( $output, $args, $defaults, $preserve_type ) {
		return $preserve_type && ( is_object( $args ) || is_object( $defaults ) ) ? (object) $output : $output;
	}

	/**
	 * Determines if two items should be merged recursively.
	 *
	 * @since 1.0.0
	 *
	 * @param array<int|string, mixed>|object $output The output array or object.
	 * @param mixed        $key    The key of the item to check.
	 * @param mixed        $value  The value of the item to check.
	 *
	 * @return bool True if the items should be merged recursively, false otherwise.
	 */
	private static function should_merge_recursively( $output, $key, $value ) {
		if ( ! ( is_array( $output ) || is_object( $output ) ) ) {
			return false;
		}

		if ( ! ( is_array( $value ) || is_object( $value ) ) ) {
			return false;
		}

		if ( ! is_array( $output ) ) {
			return false;
		}

		if ( ! is_string( $key ) && ! is_int( $key ) ) {
			return false;
		}

		return isset( $output[ $key ] ) &&
						( is_array( $output[ $key ] ) || is_object( $output[ $key ] ) );
	}

	/**
	 * Get link attributes from link data array.
	 *
	 * This method accepts an array with link data and returns HTML attributes
	 * for anchor tags including href, target, and rel attributes.
	 *
	 * @since 1.0.25
	 *
	 * @param array<string, mixed> $link_data {
	 *     Optional. Link data array.
	 *
	 *     @type string $url         The URL for the link. Default empty string.
	 *     @type bool   $is_external Whether the link is external. Default false.
	 *     @type bool   $nofollow    Whether to add nofollow rel attribute. Default false.
	 * }
	 *
	 * @return array<string, string> Array of HTML attributes for the link.
	 */
	public static function get_link_attributes( array $link_data = array() ): array {
		$defaults = array(
			'url'         => '',
			'is_external' => false,
			'nofollow'    => false,
		);

		$link_data  = wp_parse_args( $link_data, $defaults );
		$attributes = array();

		// Validate URL
		if ( ! is_string( $link_data['url'] ) || empty( trim( $link_data['url'] ) ) ) {
			return $attributes;
		}

		$url                = trim( $link_data['url'] );
		$attributes['href'] = $url;

		// Handle external links
		if ( is_bool( $link_data['is_external'] ) && $link_data['is_external'] ) {
			$attributes['target'] = '_blank';
			$attributes['rel']    = 'noopener';
		}

		// Handle nofollow
		if ( is_bool( $link_data['nofollow'] ) && $link_data['nofollow'] ) {
			if ( isset( $attributes['rel'] ) ) {
				$attributes['rel'] .= ' nofollow';
			} else {
				$attributes['rel'] = 'nofollow';
			}
		}

		return $attributes;
	}

	/**
	 * Add link attributes to an existing attributes array.
	 *
	 * This method merges link attributes generated from link data with existing
	 * HTML attributes array. If the URL is invalid, returns original attributes.
	 *
	 * @since 1.0.25
	 *
	 * @param array<string, mixed> $attributes Existing HTML attributes array.
	 * @param array<string, mixed> $link_data  Link data array (same format as get_link_attributes).
	 *
	 * @return array<string, mixed> Modified attributes array with link attributes added.
	 */
	public static function add_link_attributes( $attributes = array(), $link_data = array() ) {
		// Validate attributes parameter
		if ( ! is_array( $attributes ) ) {
			$attributes = array();
		}

		// Get link attributes
		$link_attributes = self::get_link_attributes( $link_data );

		// If no valid link attributes, return original
		if ( empty( $link_attributes ) ) {
			return $attributes;
		}

		// Merge with existing attributes
		return array_merge( $attributes, $link_attributes );
	}

	/**
	 * Print an HTML tag, automatically choosing 'a' tag for valid links.
	 *
	 * This method checks if the provided attributes contain a valid href attribute.
	 * If so, it prints an 'a' tag; otherwise, it prints the specified fallback tag.
	 *
	 * @since 1.0.25
	 *
	 * @param array<string, mixed>  $attributes   HTML attributes array.
	 * @param string $fallback_tag Optional. Fallback tag to use if no valid link. Default 'div'.
	 * @param bool   $echo         Optional. Whether to echo the tag. Default true.
	 *
	 * @return string|null The HTML tag if $echo is false, otherwise null.
	 */
	public static function print_tag_link( $attributes = array(), $fallback_tag = 'div', $echo = true ) {
		// Flatten all array values (supports Elementor render attributes)
		// This matches the pattern used in print_attributes() for all attribute values
		foreach ( $attributes as $key => $value ) {
			if ( is_array( $value ) ) {
				$scalar_values = array_filter( $value, 'is_scalar' );
				/** @var array<scalar> $scalar_values */
				$attributes[ $key ] = implode( ' ', array_map( 'strval', $scalar_values ) );
			}
		}

		// Check if we have a valid link
		$has_valid_link = isset( $attributes['href'] ) &&
							is_string( $attributes['href'] ) &&
							! empty( trim( $attributes['href'] ) );

		$tag = $has_valid_link ? 'a' : $fallback_tag;

		return self::print_html_tag( $tag, $echo );
	}

	/**
	 * Print a validated and escaped HTML tag.
	 *
	 * Accepts mixed input for compatibility with WordPress filters that return mixed.
	 * Non-string values default to 'div'.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $tag  The HTML tag to be validated and printed. Non-strings default to 'div'.
	 * @param bool  $echo Optional. Whether to echo the tag. Default true.
	 *
	 * @return string|null The validated and escaped HTML tag if $echo is false, otherwise null.
	 */
	public static function print_html_tag( $tag, $echo = true ) {
		$tag = self::get_valid_html_tag( $tag );

		if ( $echo ) {
			echo esc_html( $tag );
			return null;
		} else {
			return esc_html( $tag );
		}
	}

	/**
	 * Get a valid HTML tag.
	 *
	 * This function checks if the provided tag is in the list of allowed HTML tags.
	 * If the tag is valid, it returns the tag; otherwise, it returns 'div'.
	 * Accepts mixed input for compatibility with WordPress filters that return mixed.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $tag The HTML tag to validate. Non-strings return 'div'.
	 *
	 * @return string The validated HTML tag or 'div' if invalid.
	 *
	 * @filter `arts/utilities/markup/allows_html_tags` Allows filtering the list of allowed HTML tags.
	 */
	public static function get_valid_html_tag( $tag ) {
		// Handle non-string input gracefully
		if ( ! is_string( $tag ) ) {
			return 'div';
		}

		$allowed_html_tags = self::get_array_value(
			apply_filters(
				'arts/utilities/markup/allows_html_tags',
				array(
					'a',
					'article',
					'aside',
					'button',
					'div',
					'footer',
					'h1',
					'h2',
					'h3',
					'h4',
					'h5',
					'h6',
					'header',
					'main',
					'nav',
					'p',
					'section',
					'span',
				)
			)
		);

		return $tag && in_array( strtolower( $tag ), $allowed_html_tags, true ) ? $tag : 'div';
	}

	/**
	 * Add one or more classes to the HTML element.
	 *
	 * This method can be used with the WordPress 'language_attributes' filter
	 * to add classes to the <html> element.
	 *
	 * @since 1.0.0
	 *
	 * @param string       $attributes Current HTML element attributes.
	 * @param list<string>|string $classes    One or more classes to add.
	 * @return string Modified attributes with added classes.
	 */
	public static function add_root_html_classes( $attributes, $classes ) {
		// Convert to array if string provided
		if ( ! is_array( $classes ) ) {
			$classes = array( $classes );
		}

		// Filter out empty values
		$classes = array_filter( $classes );

		if ( empty( $classes ) ) {
			return $attributes;
		}

		// Join classes with spaces
		$class_string = esc_attr( implode( ' ', $classes ) );

		// Check if class attribute already exists
		if ( strpos( $attributes, 'class=' ) !== false ) {
			// Add our classes to existing class attribute
			$result     = preg_replace( '/class="([^"]*)"/', 'class="$1 ' . $class_string . '"', $attributes );
			$attributes = is_string( $result ) ? $result : $attributes;
		} else {
			// Add new class attribute
			$attributes .= ' class="' . $class_string . '"';
		}

		return $attributes;
	}
}
