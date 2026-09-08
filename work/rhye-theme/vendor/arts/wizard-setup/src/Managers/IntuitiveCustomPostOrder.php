<?php

namespace Arts\WizardSetup\Managers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class IntuitiveCustomPostOrder
 *
 * Manages the Intuitive Custom Post Order plugin settings and configurations.
 *
 * @since      1.0.0
 * @package    Arts\WizardSetup\Managers
 * @author     Artem Semkin
 */
class IntuitiveCustomPostOrder extends BaseManager {
	/**
	 * Array of post types that should be sortable.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var array
	 */
	private $sortable_post_types = array();

	/**
	 * Array of taxonomies that should be sortable.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var array
	 */
	private $sortable_taxonomies = array();

	/**
	 * Sets up sortable post types and taxonomies.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array Results of the setup operations.
	 */
	public function setup() {
		$result = array(
			'update_sortable_objects' => $this->update_sortable_objects( $this->sortable_post_types, $this->sortable_taxonomies ),
		);

		return $result;
	}

	/**
	 * Initializes the properties from configuration arguments.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return $this For method chaining.
	 */
	protected function init_properties() {
		if ( isset( $this->args['setup_intuitive_custom_post_order'] ) && is_array( $this->args['setup_intuitive_custom_post_order'] ) ) {
			if ( isset( $this->args['setup_intuitive_custom_post_order']['sortable_post_types'] ) && is_array( $this->args['setup_intuitive_custom_post_order']['sortable_post_types'] ) ) {
				$this->sortable_post_types = $this->args['setup_intuitive_custom_post_order']['sortable_post_types'];
			}

			if ( isset( $this->args['setup_intuitive_custom_post_order']['sortable_taxonomies'] ) && is_array( $this->args['setup_intuitive_custom_post_order']['sortable_taxonomies'] ) ) {
				$this->sortable_taxonomies = $this->args['setup_intuitive_custom_post_order']['sortable_taxonomies'];
			}
		}

		return $this;
	}

	/**
	 * Updates the sortable objects and taxonomies in the 'hicpo_options' option.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  array $post_types Array of post types to be updated.
	 * @param  array $taxonomies Array of taxonomies to be updated.
	 * @return bool Returns false if both $post_types and $taxonomies are empty, true otherwise.
	 */
	public static function update_sortable_objects( $post_types = array(), $taxonomies = array() ) {
		if ( empty( $post_types ) && empty( $taxonomies ) ) {
			return false;
		}

		$hicpo_options = get_option( 'hicpo_options', array() );

		if ( is_array( $post_types ) ) {
			// Ensure 'objects' key exists and contains default objects
			if ( ! isset( $hicpo_options['objects'] ) || ! is_array( $hicpo_options['objects'] ) ) {
				$hicpo_options['objects'] = $post_types;
			} else {
				$hicpo_options['objects'] = array_unique( array_merge( $hicpo_options['objects'], $post_types ) );
			}
		}

		if ( is_array( $taxonomies ) ) {
			// Ensure 'tags' key exists and contains default tags
			if ( ! isset( $hicpo_options['tags'] ) || ! is_array( $hicpo_options['tags'] ) ) {
				$hicpo_options['tags'] = $taxonomies;
			} else {
				$hicpo_options['tags'] = array_unique( array_merge( $hicpo_options['tags'], $taxonomies ) );
			}
		}

		update_option( 'hicpo_options', $hicpo_options );

		return true;
	}
}
