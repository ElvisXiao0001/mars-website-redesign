<?php

namespace Arts\WizardSetup\Managers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class WordPress
 *
 * Manages WordPress settings and configurations for the theme setup wizard.
 *
 * @since      1.0.0
 * @package    Arts\WizardSetup\Managers
 * @author     Artem Semkin
 */
class WordPress extends BaseManager {
	/**
	 * Date format
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string
	 */
	private $date_format;

	/**
	 * WP navigation menu
	 *
	 * @since 1.0.0
	 * @access private
	 * @var array
	 */
	private $nav_menu = array(
		'name'     => '',
		'location' => '',
	);

	/**
	 * Permalink structure
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string
	 */
	private $permalinks;

	/**
	 * Theme mods
	 *
	 * @since 1.0.0
	 * @access private
	 * @var array
	 */
	private $theme_mods = array();

	/**
	 * Options
	 *
	 * @since 1.0.0
	 * @access private
	 * @var array
	 */
	private $options = array();

	/**
	 * Home page title
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string
	 */
	private $home_page_title;

	/**
	 * Blog page title
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string
	 */
	private $blog_page_title;

	/**
	 * Should the blog sidebar be emptied?
	 *
	 * @since 1.0.0
	 * @access private
	 * @var bool
	 */
	private $empty_blog_sidebar = false;

	/**
	 * Sets up WordPress settings after import.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array Results of various setup operations.
	 */
	public function setup() {
		$result = array(
			'date_format' => self::setup_date_format( $this->date_format ),
			'menu'        => self::setup_menu( $this->nav_menu ),
			'theme_mods'  => self::update_theme_mods( $this->theme_mods ),
			'options'     => self::update_options( $this->options ),
			'permalinks'  => self::setup_permalinks( $this->permalinks ),
		);

		return $result;
	}

	/**
	 * Initializes properties from arguments.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return $this For method chaining.
	 */
	protected function init_properties() {
		// Set date format
		if ( isset( $this->args['setup_wordpress']['date_format'] ) && $this->args['setup_wordpress']['date_format'] ) {
			$this->date_format = $this->args['setup_wordpress']['date_format'];
		}

		// Set nav menu
		if ( is_array( $this->args['setup_wordpress']['menu'] ) && ! empty( $this->args['setup_wordpress']['menu'] ) ) {
			if ( isset( $this->args['setup_wordpress']['menu']['name'] ) && $this->args['setup_wordpress']['menu']['name'] ) {
				$this->nav_menu['name'] = $this->args['setup_wordpress']['menu']['name'];
			}

			if ( isset( $this->args['setup_wordpress']['menu']['location'] ) && $this->args['setup_wordpress']['menu']['location'] ) {
				$this->nav_menu['location'] = $this->args['setup_wordpress']['menu']['location'];
			}
		}

		// Set permalinks
		if ( isset( $this->args['setup_wordpress']['permalinks'] ) && $this->args['setup_wordpress']['permalinks'] ) {
			$this->permalinks = $this->args['setup_wordpress']['permalinks'];
		}

		// Set options
		if ( isset( $this->args['setup_wordpress']['options'] ) && is_array( $this->args['setup_wordpress']['options'] ) && ! empty( $this->args['setup_wordpress']['options'] ) ) {
			$this->options = $this->args['setup_wordpress']['options'];
		}

		// Set theme mods
		if ( isset( $this->args['setup_wordpress']['theme_mods'] ) && is_array( $this->args['setup_wordpress']['theme_mods'] ) && ! empty( $this->args['setup_wordpress']['theme_mods'] ) ) {
			$this->theme_mods = $this->args['setup_wordpress']['theme_mods'];
		}

		// Set home page title
		if ( isset( $this->args['setup_wordpress']['home_page_title'] ) && $this->args['setup_wordpress']['home_page_title'] ) {
			$this->home_page_title = $this->args['setup_wordpress']['home_page_title'];
		}

		// Set blog page title
		if ( isset( $this->args['setup_wordpress']['blog_page_title'] ) && $this->args['setup_wordpress']['blog_page_title'] ) {
			$this->blog_page_title = $this->args['setup_wordpress']['blog_page_title'];
		}

		if ( isset( $this->args['setup_wordpress']['empty_blog_sidebar'] ) ) {
			$this->empty_blog_sidebar = $this->args['setup_wordpress']['empty_blog_sidebar'];
		}

		return $this;
	}

	/**
	 * Get the title of the home page.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string The home page title.
	 */
	public function get_home_page_title() {
		return $this->home_page_title;
	}

	/**
	 * Get the title of the blog page.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string The blog page title.
	 */
	public function get_blog_page_title() {
		return $this->blog_page_title;
	}

	/**
	 * Enables SVG upload support in WordPress.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $args Arguments passed to the function.
	 * @return array The original arguments.
	 */
	public function enable_svg_uploads( $args ) {
		add_filter( 'upload_mimes', array( $this, 'add_svg_mime_type' ) );
		add_filter( 'wp_check_filetype_and_ext', array( $this, 'check_svg_filetype_and_ext' ), 10, 4 );

		return $args;
	}

	/**
	 * Adds SVG mime type to allowed upload types.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $mimes Array of allowed mime types.
	 * @return array Modified array of mime types.
	 */
	public function add_svg_mime_type( $mimes ) {
		$mimes['svg'] = 'image/svg+xml';

		return $mimes;
	}

	/**
	 * Validates SVG filetype for uploads.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array  $data     Data for the file.
	 * @param  string $file     Full path to the file.
	 * @param  string $filename The name of the file.
	 * @param  array  $mimes    Array of allowed mime types.
	 * @return array  Modified data array.
	 */
	public function check_svg_filetype_and_ext( $data, $file, $filename, $mimes ) {
		$ext = pathinfo( $filename, PATHINFO_EXTENSION );

		if ( 'svg' === $ext ) {
			$data['ext']  = 'svg';
			$data['type'] = 'image/svg+xml';
		}

		return $data;
	}

	/**
	 * Sets up the permalink structure for WordPress.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  string $structure Optional. The permalink structure. Default is '/%postname%/'.
	 * @return bool True on success, false on failure.
	 */
	public static function setup_permalinks( $structure = '/%postname%/' ) {
		if ( ! $structure && ! is_string( $structure ) ) {
			return false;
		}

		global $wp_rewrite;

		// Set permalinks structure
		$wp_rewrite->set_permalink_structure( $structure );

		// Refresh permalinks
		$wp_rewrite->rewrite_rules();
		$wp_rewrite->wp_rewrite_rules();
		$wp_rewrite->flush_rules();

		return true;
	}

	/**
	 * Sets up a WordPress navigation menu.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  array $nav_menu Array containing 'name' and 'location' of the menu.
	 * @return bool True if the menu was successfully set up, false otherwise.
	 */
	public static function setup_menu( $nav_menu = array() ) {
		if ( ! is_array( $nav_menu ) || ! isset( $nav_menu['name'] ) || ! isset( $nav_menu['location'] ) ) {
			return false;
		}

		$menu_object = get_term_by( 'name', $nav_menu['name'], 'nav_menu' );

		if ( $menu_object && $menu_object->term_id ) {
			set_theme_mod( 'nav_menu_locations', array( $nav_menu['location'] => $menu_object->term_id ) );

			return true;
		}

		return false;
	}

	/**
	 * Sets the date format for WordPress.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  string $format The date format to set. Default is 'd M Y'.
	 * @return bool True on success, false on failure.
	 */
	public static function setup_date_format( $format = 'd M Y' ) {
		if ( ! $format && ! is_string( $format ) ) {
			return false;
		}

		update_option( 'date_format', $format );

		return true;
	}

	/**
	 * Empties the blog sidebar by updating the 'sidebars_widgets' option.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @return bool True on success.
	 */
	public static function empty_blog_sidebar() {
		$widget_areas = array(
			'blog-sidebar' => array(),
		);

		update_option( 'sidebars_widgets', $widget_areas );

		return true;
	}

	/**
	 * Updates WordPress options.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  array $options Associative array of options to update.
	 * @return bool True on success, false on failure.
	 */
	public static function update_options( $options ) {
		if ( ! is_array( $options ) || empty( $options ) ) {
			return false;
		}

		foreach ( $options as $option => $value ) {
			update_option( $option, $value );
		}

		return true;
	}

	/**
	 * Updates the theme modifications.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  array $theme_mods An associative array of theme modifications.
	 * @return bool True on success, false on failure.
	 */
	public static function update_theme_mods( $theme_mods ) {
		if ( ! is_array( $theme_mods ) || empty( $theme_mods ) ) {
			return false;
		}

		foreach ( $theme_mods as $name => $value ) {
			set_theme_mod( $name, $value );
		}

		return true;
	}

	/**
	 * Actions to perform before importing widgets.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return bool False if blog sidebar shouldn't be emptied, true otherwise.
	 */
	public function before_widgets_import() {
		if ( ! $this->empty_blog_sidebar ) {
			return false;
		}

		self::empty_blog_sidebar();

		return true;
	}
}
