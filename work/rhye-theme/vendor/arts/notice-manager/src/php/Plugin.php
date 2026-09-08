<?php

namespace Arts\NoticeManager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Arts\NoticeManager\Managers\Frontend;
use Arts\NoticeManager\Managers\AJAX;
use Arts\NoticeManager\Managers\AdminNotices;

/**
 * Class Plugin
 *
 * Main plugin class that initializes the plugin and adds managers.
 *
 * @package Arts\NoticeManager
 */
class Plugin {
	/**
	 * The instance of this class.
	 *
	 * @var Plugin
	 */
	protected static $instance;

	/**
	 * Cached current theme object.
	 *
	 * @since 1.0.0
	 *
	 * @var \WP_Theme|null
	 */
	private static $current_theme = null;

	/**
	 * Cached parent theme object.
	 *
	 * @since 1.0.0
	 *
	 * @var \WP_Theme|null
	 */
	private static $parent_theme = null;

	/**
	 * Managers for the plugin.
	 *
	 * @var \stdClass
	 */
	private $managers;

	/**
	 * Get the instance of this class.
	 *
	 * @return object The instance of this class.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor for Plugin class.
	 */
	private function __construct() {
		$this->managers = new \stdClass();
		$this->init();
	}

	/**
	 * Queue an error notice — typed shortcut for `AdminNotices::create()`.
	 *
	 * @param array $args Notice args (see AdminNotices::get_notice_args()).
	 * @return AdminNotices The notices manager, for chaining.
	 */
	public function error( $args ) {
		return $this->managers->admin_notices->create( 'error', $args );
	}

	/**
	 * Queue a warning notice — typed shortcut for `AdminNotices::create()`.
	 *
	 * @param array $args Notice args (see AdminNotices::get_notice_args()).
	 * @return AdminNotices The notices manager, for chaining.
	 */
	public function warning( $args ) {
		return $this->managers->admin_notices->create( 'warning', $args );
	}

	/**
	 * Queue a success notice — typed shortcut for `AdminNotices::create()`.
	 *
	 * @param array $args Notice args (see AdminNotices::get_notice_args()).
	 * @return AdminNotices The notices manager, for chaining.
	 */
	public function success( $args ) {
		return $this->managers->admin_notices->create( 'success', $args );
	}

	/**
	 * Queue an info notice — typed shortcut for `AdminNotices::create()`.
	 *
	 * @param array $args Notice args (see AdminNotices::get_notice_args()).
	 * @return AdminNotices The notices manager, for chaining.
	 */
	public function info( $args ) {
		return $this->managers->admin_notices->create( 'info', $args );
	}

	/**
	 * Resolves config (via the `arts/notice_manager/plugin/config` filter), then
	 * registers the managers and their WordPress hooks.
	 *
	 * @return Plugin Returns the current instance for method chaining.
	 */
	private function init() {
		$args = apply_filters(
			'arts/notice_manager/plugin/config',
			array(
				'prefix'  => self::get_parent_theme_slug(),
				'types'   => array( 'error', 'warning', 'success', 'info' ),
				'version' => self::get_parent_theme_version(),
				'dir_url' => self::get_directory_url( __FILE__ ),
			)
		);

		$args['action_dismiss']   = "{$args['prefix']}_set_notice_dismiss";
		$args['dismissed_prefix'] = "{$args['prefix']}_dismissed";

		$this
		->add_managers( $args )
		->init_managers()
		->add_actions( $args );

		return $this;
	}

	/**
	 * Gets the URL for the directory containing a file.
	 *
	 * Determines whether the file is in a plugin or theme context and
	 * returns the appropriate URL path to its containing directory.
	 *
	 * @param string $file Path to the file (defaults to current file).
	 * @return string URL to the directory containing the file.
	 */
	public static function get_directory_url( $file = __FILE__ ) {
		// Get the absolute path to the current file
		$current_file_path = wp_normalize_path( $file );

		// Get the absolute path and URI of the theme
		$theme_directory = wp_normalize_path( get_template_directory() );
		$theme_uri       = get_template_directory_uri();

		// Check if we're in a plugin
		if ( strpos( $current_file_path, WP_PLUGIN_DIR ) !== false ) {
			return plugin_dir_url( $file );
		}

		// We're in a theme - calculate the relative path
		if ( strpos( $current_file_path, $theme_directory ) !== false ) {
			// Get path relative to theme
			$relative_path = str_replace( $theme_directory, '', dirname( $current_file_path ) );
			// Build URL
			return trailingslashit( $theme_uri . $relative_path );
		}

		// Fallback - direct parent directory
		return trailingslashit( dirname( plugin_dir_url( $file ) ) );
	}

	/**
	 * Get the version of the parent theme.
	 *
	 * Retrieves the version from the parent theme if available,
	 * otherwise returns the current theme version.
	 *
	 * @return string The version of the parent theme.
	 */
	public static function get_parent_theme_version() {
		$current_theme = self::get_current_theme();
		$theme_version = $current_theme->get( 'Version' );

		// Use parent theme version if available
		$parent_theme = self::get_parent_theme();
		if ( $parent_theme ) {
			$theme_version = $parent_theme->get( 'Version' );
		}

		return $theme_version;
	}

	/**
	 * Get the slug of the parent theme.
	 *
	 * Retrieves the stylesheet (directory name) from the parent theme if available,
	 * otherwise returns the current theme's stylesheet.
	 *
	 * @return string The slug of the parent theme.
	 */
	public static function get_parent_theme_slug() {
		$current_theme = self::get_current_theme();
		$theme_slug    = $current_theme->get_stylesheet();

		// Use parent theme slug if available
		$parent_theme = self::get_parent_theme();
		if ( $parent_theme ) {
			$theme_slug = $parent_theme->get_stylesheet();
		}

		return $theme_slug;
	}

	/**
	 * Get the current theme object.
	 *
	 * @return \WP_Theme The current theme object.
	 */
	private static function get_current_theme() {
		if ( null === self::$current_theme ) {
			self::$current_theme = wp_get_theme();
		}

		return self::$current_theme;
	}

	/**
	 * Get the parent theme object.
	 *
	 * @return \WP_Theme|false The parent theme object or false if no parent theme exists.
	 */
	private static function get_parent_theme() {
		if ( null === self::$parent_theme ) {
			$current_theme      = self::get_current_theme();
			self::$parent_theme = $current_theme->parent();
		}

		return self::$parent_theme;
	}

	/**
	 * Adds WordPress actions for the plugin.
	 *
	 * @param array $args Arguments to pass to the action hooks.
	 *
	 * @return Plugin Returns the current instance for method chaining.
	 */
	private function add_actions( $args = array() ) {
		// Render notices in the admin panel
		add_action( 'admin_notices', array( $this->managers->admin_notices, 'action_admin_notices' ) );

		// Enqueue frontend scripts and styles in the admin panel
		add_action( 'admin_enqueue_scripts', array( $this->managers->frontend, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this->managers->frontend, 'enqueue_styles' ) );

		if ( ! isset( $args['action_dismiss'] ) || empty( $args['action_dismiss'] ) ) {
			return $this;
		}

		// AJAX hook to dismiss the notice
		add_action( "wp_ajax_{$args['action_dismiss']}", array( $this->managers->ajax, 'update_notice_dismissed' ) );

		return $this;
	}

	/**
	 * Adds manager instances to the managers property.
	 *
	 * @param array $args Arguments to pass to the manager classes.
	 *
	 * @return Plugin Returns the current instance for method chaining.
	 */
	private function add_managers( $args ) {
		$manager_classes = array(
			'frontend'      => Frontend::class,
			'ajax'          => AJAX::class,
			'admin_notices' => AdminNotices::class,
		);

		foreach ( $manager_classes as $key => $class ) {
			$this->managers->$key = $this->get_manager_instance( $class, $args );
		}

		return $this;
	}

	/**
	 * Initialize all manager classes by calling their init method if it exists.
	 *
	 * @return Plugin Returns the current instance for method chaining.
	 */
	private function init_managers() {
		$managers = $this->managers;

		foreach ( (array) $managers as $manager ) {
			if ( method_exists( $manager, 'init' ) ) {
				$manager->init( $managers );
			}
		}

		return $this;
	}

	/**
	 * Helper method to instantiate a manager class.
	 *
	 * @param string $class The manager class to instantiate.
	 * @param array  $args Arguments to pass to the manager class.
	 *
	 * @return object The instantiated manager class.
	 */
	private function get_manager_instance( $class, $args ) {
		try {
			$reflection = new \ReflectionClass( $class );
			return $reflection->newInstanceArgs( array( $args ) );
		} catch ( \ReflectionException $e ) {
			return new $class();
		}
	}
}
