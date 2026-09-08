<?php

namespace Arts\Utilities\Traits\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Document Trait
 *
 * Provides utility methods for working with Elementor documents,
 * retrieving document options, and handling document settings.
 *
 * @package Arts\Utilities\Traits\Elementor
 * @since 1.0.0
 */
trait Document {
	/**
	 * Retrieve a specific document option for a given post.
	 *
	 * @since 1.0.0
	 *
	 * @param string   $option_name    The name of the option to retrieve.
	 * @param int|null $post_id        The ID of the post. Defaults to null.
	 * @param mixed    $fallback_value The value to return if the option is not found. Defaults to an empty string.
	 *
	 * @return mixed The value of the option, or the fallback value if not found.
	 */
	public static function get_document_option( $option_name = '', $post_id = null, $fallback_value = '' ) {
		if ( ! $option_name || ! class_exists( '\Elementor\Core\Settings\Manager' ) ) {
			return $fallback_value;
		}

		if ( ! $post_id ) {
			$post_id = self::get_theme_builder_location_id();
		}

		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		if ( ! $post_id ) {
			return $fallback_value;
		}

		// Get the page settings manager
		$page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

		// Ensure we have a Manager object, not an array or null
		if ( ! $page_settings_manager || is_array( $page_settings_manager ) ) {
			return $fallback_value;
		}

		// Get the settings model for current post
		$page_settings_model = $page_settings_manager->get_model( $post_id );

		if ( ! $page_settings_model ) {
			return $fallback_value;
		}

		// Retrieve the settings we added before
		return $page_settings_model->get_settings( $option_name );
	}

	/**
	 * Retrieve an overridden document option for a specific post.
	 *
	 * Checks if Elementor is loaded and if the post is built with Elementor.
	 * If the specified option and condition are met, it retrieves the document option
	 * based on the condition. Otherwise, it returns the theme modification option.
	 *
	 * @since 1.0.0
	 *
	 * @param string   $option           The option name to retrieve.
	 * @param string   $option_condition The condition option name to check.
	 * @param mixed    $option_default   The default value to return if the option is not set. Default is an empty string.
	 * @param int|null $post_id          The ID of the post to retrieve the option for. Default is null.
	 * @param string   $prefix           The prefix to add to the option name. Default is 'page_'.
	 *
	 * @return mixed The value of the overridden document option or the theme modification option.
	 */
	public static function get_overridden_document_option( $option, $option_condition, $option_default = '', $post_id = null, $prefix = 'page_' ) {
		if ( did_action( 'elementor/loaded' ) && self::is_built_with_elementor( $post_id ) && $option && $option_condition ) {
			$condition = self::get_document_option( $option_condition, $post_id, $option_default );

			if ( $condition ) {
				return self::get_document_option( "{$prefix}{$option}", $post_id, $option_default );
			}
		}

		return get_theme_mod( $option, $option_default );
	}

	/**
	 * Retrieve the body document option for a given post.
	 *
	 * @since 1.0.0
	 *
	 * @param string   $option_name    The name of the option to retrieve. Default is 'background_color'.
	 * @param int|null $post_id        The ID of the post. Default is null.
	 * @param string   $fallback_value The fallback value if the option is not found. Default is '#ffffff'.
	 *
	 * @return string The value of the document option or the fallback value.
	 */
	public static function get_body_document_option( $option_name = 'background_color', $post_id = null, $fallback_value = '#ffffff' ) {
		if ( ! $post_id ) {
			$post_id = self::get_theme_builder_location_id();
		}

		$document_background_type = self::get_document_option( 'background_background', $post_id );

		if ( $document_background_type ) {
			if ( $document_background_type === 'classic' ) { // Doesn't support 'gradient' type yet
				$document_background_color = self::get_document_option( $option_name, $post_id );

				// Return the actual background color
				if ( ! empty( $document_background_color ) ) {
					return self::get_string_value( $document_background_color, $fallback_value );
				} else { // Look for color in global colors
					return self::get_global_color_value( $option_name, $post_id, $fallback_value );
				}
			}

			return $fallback_value;
		} else {
			return $fallback_value;
		}
	}

	/**
	 * Retrieve the body styles model based on document options.
	 *
	 * @since 1.0.0
	 *
	 * @param string $fallback_color_value The fallback color value. Default is '#ffffff'.
	 *
	 * @return array<string, mixed> The body styles model including background, padding, and margin properties.
	 */
	public static function get_body_styles_model( $fallback_color_value = '#ffffff' ) {
		$model = array();

		$background_color = self::get_body_document_option( 'background_color', null, $fallback_color_value );
		if ( $background_color ) {
			$model['backgroundColor'] = $background_color;
		}

		$background_image = self::get_body_document_option( 'background_image' );
		if ( $background_image && is_array( $background_image ) && array_key_exists( 'url', $background_image ) && ! empty( $background_image['url'] ) ) {
			$model['backgroundImage'] = 'url(' . self::get_string_value( $background_image['url'] ) . ')';

			$background_position = self::get_body_document_option( 'background_position' );
			if ( $background_position ) {
				$model['backgroundPosition'] = $background_position;
			}

			$background_attachment = self::get_body_document_option( 'background_attachment' );
			if ( $background_attachment ) {
				$model['backgroundAttachment'] = $background_attachment;
			}

			$background_repeat = self::get_body_document_option( 'background_repeat' );
			if ( $background_repeat ) {
				$model['backgroundRepeat'] = $background_repeat;
			}

			$background_size = self::get_body_document_option( 'background_size' );
			if ( $background_size ) {
				$model['backgroundSize'] = $background_size;
			}
		} else {
			$model['backgroundImage'] = 'none';
		}

		$padding = self::get_body_document_option( 'padding' );
		if ( $padding && is_array( $padding ) ) {
			if ( ! empty( $padding['top'] ) ) {
				$model['paddingTop'] = self::get_string_value( $padding['top'] ) . self::get_string_value( $padding['unit'] );
			}

			if ( ! empty( $padding['right'] ) ) {
				$model['paddingRight'] = self::get_string_value( $padding['right'] ) . self::get_string_value( $padding['unit'] );
			}

			if ( ! empty( $padding['bottom'] ) ) {
				$model['paddingBottom'] = self::get_string_value( $padding['bottom'] ) . self::get_string_value( $padding['unit'] );
			}

			if ( ! empty( $padding['left'] ) ) {
				$model['paddingLeft'] = self::get_string_value( $padding['left'] ) . self::get_string_value( $padding['unit'] );
			}
		}

		$margin = self::get_body_document_option( 'margin' );
		if ( $margin && is_array( $margin ) ) {
			if ( ! empty( $margin['top'] ) ) {
				$model['marginTop'] = self::get_string_value( $margin['top'] ) . self::get_string_value( $margin['unit'] );
			}

			if ( ! empty( $margin['right'] ) ) {
				$model['marginRight'] = self::get_string_value( $margin['right'] ) . self::get_string_value( $margin['unit'] );
			}

			if ( ! empty( $margin['bottom'] ) ) {
				$model['marginBottom'] = self::get_string_value( $margin['bottom'] ) . self::get_string_value( $margin['unit'] );
			}

			if ( ! empty( $margin['left'] ) ) {
				$model['marginLeft'] = self::get_string_value( $margin['left'] ) . self::get_string_value( $margin['unit'] );
			}
		}

		return $model;
	}

	/**
	 * Retrieve the global color value from Elementor settings.
	 *
	 * @since 1.0.0
	 *
	 * @param string   $option_name    The name of the color option.
	 * @param int|null $post_id        The ID of the post to retrieve settings for. Defaults to null.
	 * @param string   $fallback_value The fallback color value if the option is not found. Defaults to '#ffffff'.
	 *
	 * @return string The color value.
	 */
	public static function get_global_color_value( $option_name = '', $post_id = null, $fallback_value = '#ffffff' ) {
		if ( ! class_exists( '\Elementor\Core\Settings\Manager' ) ) {
			return $fallback_value;
		}

		if ( ! $post_id ) {
			$post_id = self::get_theme_builder_location_id();
		}

		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		if ( ! $post_id ) {
			return $fallback_value;
		}

		// Get the page settings manager
		$page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

		// Ensure we have a Manager object, not an array or null
		if ( ! $page_settings_manager || is_array( $page_settings_manager ) ) {
			return $fallback_value;
		}

		// Get the settings model for current post
		$page_settings_model = $page_settings_manager->get_model( $post_id );

		if ( ! $page_settings_model ) {
			return $fallback_value;
		}

		$settings = $page_settings_model->get_settings();

		if ( is_array( $settings ) && array_key_exists( '__globals__', $settings ) ) {
			$settings = $settings['__globals__'];
		}

		if ( is_array( $settings ) && array_key_exists( $option_name, $settings ) ) {
			$color_control_id = self::get_global_color_control_id( self::get_string_value( $settings[ $option_name ] ) );

			if ( $color_control_id ) {
				if ( ! \Elementor\Plugin::$instance || ! \Elementor\Plugin::$instance->kits_manager ) {
					return $fallback_value;
				}

				$kit_manager = \Elementor\Plugin::$instance->kits_manager;

				$system_colors = self::get_array_value( $kit_manager->get_current_settings( 'system_colors' ) );
				$custom_colors = self::get_array_value( $kit_manager->get_current_settings( 'custom_colors' ) );
				$settings      = array_merge( $system_colors, $custom_colors );

				$index = array_search( $color_control_id, array_column( $settings, '_id' ), true );

				if ( is_int( $index ) && array_key_exists( $index, $settings ) && is_array( $settings[ $index ] ) && isset( $settings[ $index ]['color'] ) ) {
					return self::get_string_value( $settings[ $index ]['color'], $fallback_value );
				} else {
					return $fallback_value;
				}
			} else {
				return $fallback_value;
			}
		} else {
			return $fallback_value;
		}
	}
}
