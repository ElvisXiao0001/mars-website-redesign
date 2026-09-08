<?php

namespace Arts\Utilities\Traits\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Kit Trait
 *
 * Provides utility methods for working with Elementor kits,
 * retrieving and updating kit settings, and handling global colors.
 *
 * @package Arts\Utilities\Traits\Elementor
 * @since 1.0.0
 */
trait Kit {
	/**
	 * Get the color value from the widget settings or global colors.
	 * Allows retrieving the color value even if it's stored in the
	 * global colors and not available from the widget settings directly.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed>  $settings       The widget settings array.
	 * @param string $option_name    The option name to retrieve the color for.
	 * @param string $fallback_value The fallback color value if not found.
	 *
	 * @return string The color value.
	 */
	public static function get_color_value( $settings = array(), $option_name = '', $fallback_value = '#ffffff' ) {
		if ( ! class_exists( '\Elementor\Core\Settings\Manager' ) ) {
			return $fallback_value;
		}

		if ( ! is_array( $settings ) || empty( $settings ) || ! $option_name ) {
			return $fallback_value;
		}

		$globals = array_key_exists( '__globals__', $settings ) ? $settings['__globals__'] : array();

		if ( ! is_array( $globals ) || empty( $globals ) || ! is_string( $option_name ) || ! array_key_exists( $option_name, $globals ) ) {
			return $fallback_value;
		}

		$color_control_id = self::get_global_color_control_id( self::get_string_value( $globals[ $option_name ] ) );

		if ( ! $color_control_id ) {
			return $fallback_value;
		}

		if ( ! \Elementor\Plugin::$instance || ! \Elementor\Plugin::$instance->kits_manager ) {
			return $fallback_value;
		}

		$kit_manager = \Elementor\Plugin::$instance->kits_manager;

		$system_colors   = self::get_array_value( $kit_manager->get_current_settings( 'system_colors' ) );
		$custom_colors   = self::get_array_value( $kit_manager->get_current_settings( 'custom_colors' ) );
		$global_settings = array_merge( $system_colors, $custom_colors );

		$index = array_search( $color_control_id, array_column( $global_settings, '_id' ), true );

		if ( $index !== false && isset( $global_settings[ $index ] ) && is_array( $global_settings[ $index ] ) && isset( $global_settings[ $index ]['color'] ) ) {
			return self::get_string_value( $global_settings[ $index ]['color'], $fallback_value );
		}

		return $fallback_value;
	}

	/**
	 * Get kit settings from Elementor.
	 *
	 * If Elementor is not loaded, it falls back to returning the fallback value.
	 *
	 * @since 1.0.0
	 *
	 * @param string|null $option_name    The name of the option to retrieve.
	 * @param mixed       $fallback_value The value to return if the option is not found.
	 * @param bool        $return_size    Whether to return the 'size' or 'url' if the value is an array.
	 *
	 * @return mixed The kit setting value or the fallback value.
	 */
	public static function get_kit_settings( $option_name = null, $fallback_value = null, $return_size = true ) {
		if ( ! self::is_elementor_plugin_active() || ! \Elementor\Plugin::$instance || ! \Elementor\Plugin::$instance->kits_manager ) {
			return $fallback_value;
		}

		$value = \Elementor\Plugin::$instance->kits_manager->get_current_settings( $option_name );

		if ( isset( $value ) ) {
			if ( $return_size && is_array( $value ) ) {
				$value = self::extract_value_from_option_array( $value );
			}

			return $value;
		}

		return $fallback_value;
	}


	/**
	 * Get kit settings from Elementor.
	 *
	 * If Elementor is not loaded, it falls back to retrieving the option value of the given option name.
	 *
	 * @since 1.0.0
	 *
	 * @param string|null $option_name    The name of the option to retrieve. If null, retrieves all settings.
	 * @param mixed       $fallback_value The value to return if the option is not found. Default is null.
	 * @param bool        $return_size    Whether to return the 'size' or 'url' if the value is an array. Default is true.
	 *
	 * @return mixed The kit setting value or the fallback value.
	 */
	public static function get_kit_setting_or_option( $option_name = null, $fallback_value = null, $return_size = true ) {
		if ( ! self::is_elementor_plugin_active() || ! \Elementor\Plugin::$instance || ! \Elementor\Plugin::$instance->kits_manager ) {
			if ( is_string( $option_name ) ) {
				$option_value = get_option( $option_name, $fallback_value );

				if ( $option_value !== $fallback_value ) {
					return $option_value;
				}
			}

			return $fallback_value;
		}

		$value = \Elementor\Plugin::$instance->kits_manager->get_current_settings( $option_name );

		if ( isset( $value ) ) {
			if ( $return_size && is_array( $value ) ) {
				$value = self::extract_value_from_option_array( $value );
			}

			return $value;
		}

		return $fallback_value;
	}

	/**
	 * Update Elementor kit settings based on the given option and value.
	 *
	 * @since 1.0.0
	 *
	 * @param string $option_elementor The Elementor option to update.
	 * @param mixed  $value The value to set for the given option.
	 * @return bool True on success, false on failure.
	 */
	public static function update_kit_settings( $option_elementor, $value ) {
		if ( ! self::is_elementor_plugin_active() || ! \Elementor\Plugin::$instance || ! \Elementor\Plugin::$instance->kits_manager ) {
			return false;
		}

		\Elementor\Plugin::$instance->kits_manager->update_kit_settings_based_on_option( $option_elementor, $value );

		return true;
	}

	/**
	 * Get the URL to the Elementor Site Settings editor.
	 *
	 * Generates a URL to access the Elementor Site Settings editor with an optional active tab.
	 * Returns an empty string if Elementor is not active or no recently edited posts found.
	 *
	 * @since 1.0.0
	 *
	 * @param string $tab_id Optional. The tab ID to activate in the Site Settings. Default empty string.
	 * @return string The URL to the Elementor Site Settings editor or an empty string if not available.
	 */
	public static function get_elementor_editor_site_settings_url( $tab_id = '' ) {
		// Check if Elementor is active
		if ( ! class_exists( '\Elementor\Plugin' ) || ! class_exists( '\Elementor\Utils' ) ) {
			return '';
		}

		$recent_edited_post = \Elementor\Utils::get_recently_edited_posts_query(
			array(
				'posts_per_page' => 1,
			)
		);

		if ( $recent_edited_post->post_count ) {
			$posts      = $recent_edited_post->get_posts();
			$first_post = reset( $posts );

			// Ensure we have a WP_Post object
			if ( ! $first_post instanceof \WP_Post ) {
				return '';
			}

			if ( ! \Elementor\Plugin::$instance || ! \Elementor\Plugin::$instance->kits_manager ) {
				return '';
			}

			$post_id = $first_post->ID;
			$kit_id  = \Elementor\Plugin::$instance->kits_manager->get_active_id();

			/** @var int|string $kit_id */
			$url = admin_url( 'post.php?post=' . $post_id . '&action=elementor&active-document=' . $kit_id );

			if ( ! empty( $tab_id ) ) {
				$url .= '&active-tab=' . sanitize_text_field( $tab_id );
			}

			return esc_url( $url );
		}

		return '';
	}

	/**
	 * Extract a specific value from an option array.
	 *
	 * Checks if the provided value is an array and attempts to extract
	 * a specific value based on predefined keys ('size' or 'url'). If the value is
	 * not an array or the keys are not found, it returns the original value.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value The value to be processed, which can be an array or any other type.
	 * @return mixed The extracted value if the input is an array with specific keys, otherwise the original value.
	 */
	private static function extract_value_from_option_array( $value ) {
		if ( is_array( $value ) ) {
			if ( array_key_exists( 'size', $value ) ) {
				return $value['size'];
			}

			if ( array_key_exists( 'url', $value ) ) {
				return $value['url'];
			}
		}

		return $value;
	}

	/**
	 * Extract the global color control ID from the given input string.
	 *
	 * @since 1.0.0
	 *
	 * @param string $input The input string containing the global color control ID.
	 * @return string The extracted global color control ID, or an empty string if not found.
	 */
	private static function get_global_color_control_id( $input ) {
		if ( ! is_string( $input ) ) {
			return '';
		}

		$regex = '/(?<=globals\/colors\?id=)[a-z0-9]+/i';
		preg_match( $regex, $input, $match );

		return $match ? $match[0] : '';
	}
}
