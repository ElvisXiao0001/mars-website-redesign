<?php

namespace Arts\WizardSetup\Managers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Arts\Utilities\Utilities;

/**
 * Class Elementor
 *
 * Manages Elementor settings and configurations for the theme setup wizard.
 *
 * @since      1.0.0
 * @package    Arts\WizardSetup\Managers
 * @author     Artem Semkin
 * @property   array  $options Configuration options.
 * @property   array  $editable_post_types Post types that can be edited.
 * @property   bool   $set_active_kit Flag to set the active kit.
 * @property   string $replace_urls_from URL to replace from.
 * @property   string $replace_urls_to URL to replace to.
 */
class Elementor extends BaseManager {
	/**
	 * Elementor configuration options.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var array
	 */
	private $options;

	/**
	 * Post types that can be edited with Elementor.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var array
	 */
	private $editable_post_types;

	/**
	 * Whether to set active Elementor kit.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var bool
	 */
	private $set_active_kit;

	/**
	 * URL to replace from in Elementor content.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string
	 */
	private $replace_urls_from;

	/**
	 * URL to replace to in Elementor content.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string
	 */
	private $replace_urls_to;

	/**
	 * Performs various setup operations for Elementor.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array The results of all setup operations.
	 */
	public function setup() {
		$result = array(
			'replace_font_meta_urls'     => self::replace_font_meta_urls( $this->replace_urls_from, $this->replace_urls_to ),
			'update_editable_post_types' => self::update_editable_post_types( $this->editable_post_types ),
			'update_options'             => self::update_options( $this->options ),
			'set_active_kit'             => self::set_active_kit( $this->set_active_kit ),
			'clear_theme_builder_cache'  => self::clear_theme_builder_cache(),
			'replace_urls'               => self::replace_urls( $this->replace_urls_from, $this->replace_urls_to ),
			'regenerate_css_and_data'    => self::regenerate_css_and_data(),
			'regenerate_custom_fonts'    => self::regenerate_custom_fonts_taxonomy(),
		);

		return $result;
	}

	/**
	 * Remove Elementor welcome splash screen on the initial plugin activation.
	 *
	 * This method deletes the transient responsible for the Elementor activation redirect.
	 * It is used to prevent issues when the Merlin wizard is installing and activating the required plugins.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return bool Returns true after the transient is deleted.
	 */
	public function remove_welcome_screen() {
		delete_transient( 'elementor_activation_redirect' );

		return true;
	}

	/**
	 * Initializes the properties from configuration arguments.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return $this For method chaining.
	 */
	protected function init_properties() {
		if ( isset( $this->args['setup_elementor']['editable_post_types'] ) && is_array( $this->args['setup_elementor']['editable_post_types'] ) && ! empty( $this->args['setup_elementor']['editable_post_types'] ) ) {
			$this->editable_post_types = $this->args['setup_elementor']['editable_post_types'];
		}

		if ( isset( $this->args['setup_elementor']['options'] ) && is_array( $this->args['setup_elementor']['options'] ) && ! empty( $this->args['setup_elementor']['options'] ) ) {
			$this->options = $this->args['setup_elementor']['options'];
		}

		if ( isset( $this->args['setup_elementor']['set_active_kit'] ) ) {
			$this->set_active_kit = $this->args['setup_elementor']['set_active_kit'];
		}

		if ( isset( $this->args['setup_elementor']['replace_urls_from'] ) && ! empty( $this->args['setup_elementor']['replace_urls_from'] ) ) {
			$this->replace_urls_from = sprintf( $this->args['setup_elementor']['replace_urls_from'], $this->args['theme_slug'] ?? '' );
		}

		if ( isset( $this->args['setup_elementor']['replace_urls_to'] ) && ! empty( $this->args['setup_elementor']['replace_urls_to'] ) ) {
			$this->replace_urls_to = $this->args['setup_elementor']['replace_urls_to'];
		} else {
			$this->replace_urls_to = trailingslashit( get_site_url() );
		}

		return $this;
	}

	/**
	 * Clears the Elementor Pro Theme Builder cache if the Conditions_Cache class exists.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @return bool True if the cache was cleared, false otherwise.
	 */
	public static function clear_theme_builder_cache() {
		if ( class_exists( '\ElementorPro\Modules\ThemeBuilder\Classes\Conditions_Cache' ) ) {
			$cache = new \ElementorPro\Modules\ThemeBuilder\Classes\Conditions_Cache();
			$cache->regenerate();

			return true;
		}

		return false;
	}

	/**
	 * Replaces URLs using Elementor's Utils class.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  string $from The URL to be replaced.
	 * @param  string $to The new URL to replace with.
	 * @return bool True on success, false on failure.
	 */
	public static function replace_urls( $from, $to ) {
		if ( ! class_exists( '\Elementor\Utils' ) || ! $from || ! $to ) {
			return false;
		}

		try {
			\Elementor\Utils::replace_urls( $from, $to );
		} catch ( \Exception $e ) {
			return false;
		}

		return true;
	}

	/**
	 * Regenerates CSS and data for Elementor by clearing the cache.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @return bool True if the cache was cleared, false otherwise.
	 */
	public static function regenerate_css_and_data() {
		if ( class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance && \Elementor\Plugin::$instance->files_manager ) {
			\Elementor\Plugin::$instance->files_manager->clear_cache();

			return true;
		}

		return false;
	}

	/**
	 * Register temporary elementor_font CPT for import compatibility.
	 *
	 * When Elementor Pro isn't active during demo import, WordPress skips
	 * elementor_font posts. This registers a minimal CPT definition to allow
	 * import, then Elementor Pro takes over when activated.
	 *
	 * @since  1.1.0
	 * @return void
	 */
	public function register_import_font_cpt() {
		// Only register if CPT doesn't already exist (Pro not active)
		if ( post_type_exists( 'elementor_font' ) ) {
			return;
		}

		// Minimal registration matching Pro's structure
		register_post_type(
			'elementor_font',
			array(
				'public'          => false,
				'show_ui'         => false,
				'show_in_menu'    => false,
				'rewrite'         => false,
				'capability_type' => 'post',
				'hierarchical'    => false,
				'supports'        => array( 'title', 'custom-fields' ),
			)
		);

		// Register taxonomy matching Pro's structure
		if ( ! taxonomy_exists( 'elementor_font_type' ) ) {
			register_taxonomy(
				'elementor_font_type',
				'elementor_font',
				array(
					'hierarchical' => false,
					'public'       => false,
					'show_ui'      => false,
					'rewrite'      => false,
				)
			);
		}
	}

	/**
	 * Clear Elementor caches when Pro is fully initialized (one-time).
	 *
	 * Runs on WordPress 'init' hook at priority 20, after elementor_font_type
	 * taxonomy is registered (priority 10). This ensures taxonomy terms can be
	 * assigned to imported fonts and font URLs are updated to local site.
	 *
	 * @since  1.1.0
	 * @return bool True if caches were cleared, false otherwise.
	 */
	public function clear_caches_on_pro_init() {
		// Early return if Elementor Pro not loaded
		if ( ! class_exists( '\ElementorPro\Modules\AssetsManager\AssetTypes\Fonts_Manager' ) ) {
			return false;
		}
		// Check if this task has already been completed
		$option_name = 'arts_elementor_pro_cache_cleared';
		if ( get_option( $option_name ) ) {
			return false;
		}

		// Fix font URLs and clear caches
		$result = array(
			'replace_font_meta_urls' => self::replace_font_meta_urls(
				$this->replace_urls_from ?? '',
				trailingslashit( get_site_url() )
			),
			'regenerate_font_face'   => self::regenerate_font_face_css(),
			'theme_builder_cache'    => self::clear_theme_builder_cache(),
			'css_and_data'           => self::regenerate_css_and_data(),
			'custom_fonts'           => self::regenerate_custom_fonts_taxonomy(),
		);

		// Mark task as complete
		update_option( $option_name, true, false );

		return ! empty( array_filter( $result ) );
	}

	/**
	 * Sets the active Elementor kit.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  string|int $active_kit The kit name or ID to set as active.
	 * @return int|false The kit post ID if successful, false otherwise.
	 */
	public static function set_active_kit( $active_kit ) {
		if ( ! $active_kit || ! class_exists( '\Elementor\TemplateLibrary\Source_Local' ) ) {
			return false;
		}

		$kit_post_id   = null;
		$kit_post_name = '';

		if ( is_string( $active_kit ) ) {
			$kit_post_name = $active_kit;
		} elseif ( is_int( $active_kit ) ) {
			$kit_post_id = $active_kit;
		}

		if ( ! $kit_post_id ) {
			$kit_post_type   = \Elementor\TemplateLibrary\Source_Local::CPT;
			$kit_post_object = Utilities::get_page_by_title( $kit_post_name, OBJECT, $kit_post_type );

			if ( $kit_post_object && $kit_post_object->ID ) {
				$kit_post_id = $kit_post_object->ID;
			}
		}

		update_option( 'elementor_active_kit', $kit_post_id );

		return $kit_post_id;
	}

	/**
	 * Updates Elementor options.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  array $options Array of options to update.
	 * @return bool True on success, false on failure.
	 */
	public static function update_options( $options ) {
		if ( ! is_array( $options ) || empty( $options ) ) {
			return false;
		}

		$prefix = 'elementor_';

		foreach ( $options as $option => $value ) {
			update_option( $prefix . $option, $value );
		}

		return true;
	}

	/**
	 * Updates the editable post types for Elementor.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  array $editable_post_types Array of post types to be made editable.
	 * @return bool True on success, false on failure.
	 */
	public static function update_editable_post_types( $editable_post_types ) {
		if ( ! array( $editable_post_types ) || empty( $editable_post_types ) ) {
			return false;
		}

		$option = 'elementor_cpt_support';
		$value  = get_option( $option );

		if ( empty( $value ) ) {
			$default_editable_post_types = array( 'page', 'post' );
			$value                       = array_merge( $default_editable_post_types, $editable_post_types );
		} else {
			foreach ( $editable_post_types as $post_type ) {
				if ( ! in_array( $post_type, $value, true ) ) {
					$value[] = $post_type;
				}
			}
		}

		update_option( $option, $value );

		return true;
	}

	/**
	 * Regenerate custom fonts taxonomy assignments and cache.
	 *
	 * Assigns missing taxonomy terms to imported font posts and clears
	 * fonts cache to force regeneration.
	 *
	 * @since  1.1.0
	 * @return bool True if fonts were processed, false otherwise.
	 */
	public static function regenerate_custom_fonts_taxonomy() {
		// Check if Elementor Pro/PRO Elements is loaded
		if ( ! class_exists( '\ElementorPro\Modules\AssetsManager\AssetTypes\Fonts_Manager' ) ) {
			return false;
		}

		// Get all custom font posts
		$fonts = get_posts(
			array(
				'post_type'      => 'elementor_font',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
			)
		);

		if ( empty( $fonts ) ) {
			return false;
		}

		$taxonomy = 'elementor_font_type';
		$updated  = 0;

		// Assign taxonomy term to fonts missing it
		foreach ( $fonts as $font ) {
			$terms = get_the_terms( $font->ID, $taxonomy );

			// If no terms assigned, set to 'custom'
			if ( empty( $terms ) || is_wp_error( $terms ) ) {
				wp_set_object_terms( $font->ID, 'custom', $taxonomy );
				++$updated;
			}
		}

		// Clear fonts cache to force regeneration
		delete_option( 'elementor_fonts_manager_fonts' );
		delete_option( 'elementor_fonts_manager_font_types' );

		return $updated > 0;
	}

	/**
	 * Replace demo site URLs in elementor_font_files meta with local site URLs.
	 *
	 * Handles both http/https protocol variants and resolves correct local
	 * attachment IDs. Also fixes URLs in elementor_font_face CSS meta.
	 *
	 * @since  1.1.0
	 * @param  string $from Demo site base URL.
	 * @param  string $to   Local site base URL.
	 * @return bool True if any fonts were updated, false otherwise.
	 */
	public static function replace_font_meta_urls( $from, $to ) {
		if ( empty( $from ) || empty( $to ) ) {
			return false;
		}

		$font_posts = get_posts(
			array(
				'post_type'      => 'elementor_font',
				'posts_per_page' => -1,
				'post_status'    => 'any',
			)
		);

		if ( empty( $font_posts ) ) {
			return false;
		}

		$from_no_protocol = preg_replace( '#^https?://#', '', $from );
		$from_http        = 'http://' . $from_no_protocol;
		$from_https       = 'https://' . $from_no_protocol;
		$meta_key         = 'elementor_font_files';
		$font_extensions  = array( 'woff2', 'woff', 'ttf', 'svg', 'eot' );
		$updated          = 0;

		foreach ( $font_posts as $font_post ) {
			$font_files = get_post_meta( $font_post->ID, $meta_key, true );

			if ( ! is_array( $font_files ) || empty( $font_files ) ) {
				continue;
			}

			$changed = false;

			foreach ( $font_files as $variant_index => $variant ) {
				foreach ( $font_extensions as $ext ) {
					if ( ! isset( $variant[ $ext ] ) || ! is_array( $variant[ $ext ] ) || empty( $variant[ $ext ]['url'] ) ) {
						continue;
					}

					$url = $variant[ $ext ]['url'];

					// Guard: only touch URLs containing the demo site base
					if ( strpos( $url, $from_http ) === false && strpos( $url, $from_https ) === false ) {
						continue;
					}

					// Replace both protocol variants
					$new_url = str_replace( array( $from_http, $from_https ), $to, $url );

					$font_files[ $variant_index ][ $ext ]['url'] = $new_url;

					// Resolve correct local attachment ID
					$attachment_id = attachment_url_to_postid( $new_url );

					// Fallback: find by filename for multisite→single-site path mismatch
					if ( ! $attachment_id ) {
						$fallback = self::find_attachment_by_filename( basename( $new_url ) );

						if ( $fallback ) {
							$attachment_id                               = $fallback['id'];
							$font_files[ $variant_index ][ $ext ]['url'] = $fallback['url'];
						}
					}

					if ( $attachment_id ) {
						$font_files[ $variant_index ][ $ext ]['id'] = $attachment_id;
					}

					$changed = true;
				}
			}

			if ( $changed ) {
				update_post_meta( $font_post->ID, $meta_key, $font_files );
				++$updated;
			}

			// Also fix font-face CSS meta for this post
			$font_face = get_post_meta( $font_post->ID, 'elementor_font_face', true );

			if ( ! empty( $font_face ) ) {
				$new_font_face = str_replace( array( $from_http, $from_https ), $to, $font_face );

				if ( $new_font_face !== $font_face ) {
					update_post_meta( $font_post->ID, 'elementor_font_face', $new_font_face );
				}
			}
		}

		return $updated > 0;
	}

	/**
	 * Find an attachment by its filename when attachment_url_to_postid() fails.
	 *
	 * @since  1.1.0
	 * @param  string $filename The font filename to search for.
	 * @return array|null Array with 'id' and 'url' keys, or null if not found.
	 */
	private static function find_attachment_by_filename( $filename ) {
		$attachments = get_posts(
			array(
				'post_type'      => 'attachment',
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'meta_query'     => array(
					array(
						'key'     => '_wp_attached_file',
						'value'   => $filename,
						'compare' => 'LIKE',
					),
				),
			)
		);

		if ( empty( $attachments ) ) {
			return null;
		}

		$url = wp_get_attachment_url( $attachments[0]->ID );

		if ( ! $url ) {
			return null;
		}

		return array(
			'id'  => (int) $attachments[0]->ID,
			'url' => $url,
		);
	}

	/**
	 * Regenerate elementor_font_face CSS from corrected elementor_font_files data.
	 *
	 * @since  1.1.0
	 * @return bool True if any font-face CSS was regenerated.
	 */
	private static function regenerate_font_face_css() {
		if ( ! class_exists( '\ElementorPro\Modules\AssetsManager\AssetTypes\Fonts\Custom_Fonts' ) ) {
			return false;
		}

		$font_posts = get_posts(
			array(
				'post_type'      => 'elementor_font',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
			)
		);

		if ( empty( $font_posts ) ) {
			return false;
		}

		$custom_fonts = new \ElementorPro\Modules\AssetsManager\AssetTypes\Fonts\Custom_Fonts();
		$updated      = 0;

		foreach ( $font_posts as $font_post ) {
			$font_face = $custom_fonts->generate_font_face( $font_post->ID );

			if ( $font_face ) {
				update_post_meta( $font_post->ID, 'elementor_font_face', $font_face );
				++$updated;
			}
		}

		return $updated > 0;
	}
}
