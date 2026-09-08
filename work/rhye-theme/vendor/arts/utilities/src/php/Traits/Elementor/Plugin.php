<?php

namespace Arts\Utilities\Traits\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Plugin Trait
 *
 * Provides utility methods for checking Elementor plugin status,
 * determining if posts are built with Elementor, and checking editor status.
 *
 * @package Arts\Utilities\Traits\Elementor
 * @since 1.0.0
 */
trait Plugin {
	/**
	 * Check if Elementor plugin is active.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if Elementor plugin is active, false otherwise.
	 */
	public static function is_elementor_plugin_active() {
		return class_exists( '\Elementor\Plugin' );
	}

	/**
	 * Check if a post is built with Elementor.
	 *
	 * @since 1.0.0
	 *
	 * @param int|null $post_id The ID of the post to check. Defaults to null.
	 * @return bool True if the post is built with Elementor, false otherwise.
	 */
	public static function is_built_with_elementor( $post_id = null ) {
		if ( ! self::is_elementor_plugin_active() ) {
			return false;
		}

		// Blog page
		if ( is_home() ) {
			$post_id = get_option( 'page_for_posts' );
		}

		// WooCommerce Shop page
		if ( self::is_shop() ) {
			$post_id = get_option( 'woocommerce_shop_page_id' );
		}

		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		if ( is_singular() ) {
			if ( ! \Elementor\Plugin::$instance || ! \Elementor\Plugin::$instance->documents ) {
				return false;
			}

			$safe_post_id = self::get_post_id( $post_id );
			if ( $safe_post_id instanceof \WP_Post ) {
				$safe_post_id = $safe_post_id->ID;
			}

			if ( $safe_post_id === null ) {
				return false;
			}

			$document = \Elementor\Plugin::$instance->documents->get( $safe_post_id );

			return $document && $document->is_built_with_elementor();
		}

		return false;
	}

	/**
	 * Check if the Elementor editor is active and in preview mode.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if Elementor editor is active and in preview mode, false otherwise.
	 */
	public static function is_elementor_editor_active() {
		if ( ! self::is_elementor_plugin_active() || ! \Elementor\Plugin::$instance || ! \Elementor\Plugin::$instance->preview ) {
			return false;
		}

		return \Elementor\Plugin::$instance->preview->is_preview_mode();
	}

	/**
	 * Check if a specific Elementor feature is active.
	 *
	 * @since 1.0.0
	 *
	 * @param string $feature_name The name of the Elementor feature to check.
	 * @return bool True if the feature is active, false otherwise.
	 */
	public static function is_elementor_feature_active( $feature_name ) {
		if ( ! self::is_elementor_plugin_active() ) {
			return false;
		}

		$experiments = \Elementor\Plugin::instance();

		return $experiments && $experiments->experiments->is_feature_active( $feature_name );
	}
}
