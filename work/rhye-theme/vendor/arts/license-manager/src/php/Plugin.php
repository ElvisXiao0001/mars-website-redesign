<?php

namespace Arts\LicenseManager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Arts\LicenseManager\Managers\AdminNotices;
use Arts\LicenseManager\Managers\AJAX;
use Arts\LicenseManager\Managers\Frontend;
use Arts\LicenseManager\Managers\License;
use Arts\LicenseManager\Managers\Options;
use Arts\LicenseManager\Managers\Scheduler;
use Arts\LicenseManager\Managers\Strings;
use Arts\LicenseManager\Managers\Updates;

/**
 * Main plugin class.
 *
 * Sets up and coordinates all license manager functionality.
 *
 * @package Arts\LicenseManager
 * @since 1.0.0
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
	 * @var \WP_Theme|null
	 */
	private static $current_theme = null;

	/**
	 * Cached parent theme object.
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
	 * Constructor for the class.
	 */
	private function __construct() {
		$this->managers = new \stdClass();
		$this->init();
	}

	/**
	 * Singleton should not be cloneable.
	 */
	private function __clone() { }

	/**
	 * Initializes the plugin by adding managers, filters, and actions.
	 *
	 * @return Plugin Returns the current instance for method chaining.
	 */
	private function init() {
		$args = apply_filters(
			'arts/license_manager/plugin/config',
			array(
				'remote_api_url' => 'https://artemsemkin.com/wp-json/',
				'theme_name'     => self::get_parent_theme_name(), // Theme name
				'theme_slug'     => self::get_parent_theme_slug(), // Theme slug
				'dir_url'        => self::get_directory_url( __FILE__ ), // Directory URL
				'version'        => self::get_parent_theme_version(), // The current version of this theme
				'date_format'    => get_option( 'date_format' ), // Date format
			)
		);

		$strings = apply_filters(
			'arts/license_manager/plugin/strings',
			array(
				'theme-license'                          => esc_html__( 'Theme License', 'arts-license-manager' ),
				'enter-key'                              => esc_html__( 'Please enter your license key.', 'arts-license-manager' ),
				'license-key'                            => esc_html__( 'License Key', 'arts-license-manager' ),
				'license-action'                         => esc_html__( 'License Action', 'arts-license-manager' ),
				'deactivate-license'                     => esc_html__( 'Deactivate License', 'arts-license-manager' ),
				'activate-license'                       => esc_html__( 'Activate License', 'arts-license-manager' ),
				'refresh-license'                        => esc_html__( 'Refresh', 'arts-license-manager' ),
				'clear-license'                          => esc_html__( 'Clear License', 'arts-license-manager' ),
				'license-key-is-active'                  => esc_html__( 'License key is active', 'arts-license-manager' ),
				'license-key-activated'                  => esc_html__( 'Activated', 'arts-license-manager' ),
				/* translators: the license expiration date */
				'expires%s'                              => esc_html__( 'Expires %s.', 'arts-license-manager' ),
				'expires-never'                          => esc_html__( 'Never', 'arts-license-manager' ),
				/* translators: 1. the number of sites activated 2. the total number of activations allowed. */
				'%1$s/%2$-sites'                         => esc_html__( 'You have %1$s / %2$s sites activated.', 'arts-license-manager' ),
				'activation-limit'                       => esc_html__( 'Your license key has reached its activation limit.', 'arts-license-manager' ),
				/* translators: the license expiration date */
				'license-key-expired-%s'                 => esc_html__( 'License key expired %s.', 'arts-license-manager' ),
				'license-key-expired'                    => esc_html__( 'License key has expired.', 'arts-license-manager' ),
				/* translators: the license expiration date */
				'license-expired-on'                     => esc_html__( 'Your license key expired on %s.', 'arts-license-manager' ),
				'license-keys-do-not-match'              => esc_html__( 'License keys do not match.', 'arts-license-manager' ),
				'license-is-inactive'                    => esc_html__( 'Not activated', 'arts-license-manager' ),
				'license-key-is-disabled'                => esc_html__( 'License key is disabled.', 'arts-license-manager' ),
				'license-key-invalid'                    => esc_html__( 'Invalid license.', 'arts-license-manager' ),
				'license-key-cleared'                    => esc_html__( 'License key has been removed.', 'arts-license-manager' ),
				'site-is-inactive'                       => esc_html__( 'Site is inactive.', 'arts-license-manager' ),
				/* translators: the theme name */
				'item-mismatch'                          => esc_html__( 'This appears to be an invalid license key for %s.', 'arts-license-manager' ),
				'license-status-unknown'                 => esc_html__( 'License status is unknown.', 'arts-license-manager' ),
				'error-generic'                          => esc_html__( 'An error occurred while validating this license key. Please try again later.', 'arts-license-manager' ),
				'license-status'                         => esc_html__( 'Status', 'arts-license-manager' ),
				'license-expiration-date'                => esc_html__( 'Expiration Date', 'arts-license-manager' ),
				'license-data-refreshed'                 => esc_html__( 'License data refreshed successfully.', 'arts-license-manager' ),
				'license-supported-until'                => esc_html__( 'Technical Support', 'arts-license-manager' ),
				'license-updates-provided-until'         => esc_html__( 'Automatic Updates', 'arts-license-manager' ),
				'license-lifetime-updates'               => esc_html__( 'Available Lifetime', 'arts-license-manager' ),
				'license-never-expires'                  => esc_html__( 'Lifetime', 'arts-license-manager' ),
				'license-purchase-date'                  => esc_html__( 'Purchase Date', 'arts-license-manager' ),
				'license-activations'                    => esc_html__( 'Activations', 'arts-license-manager' ),
				'license-help-purchase-code'             => esc_html__( 'Where is my license key?', 'arts-license-manager' ),
				'license-help-purchase-code-url'         => esc_url( 'https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-' ),
				'license-help-no-purchase-code-heading'  => esc_html__( 'Don\'t have a license key?', 'arts-license-manager' ),
				/* translators: %1$s: link to Envato Market */
				'license-help-no-purchase-code-text'     => esc_html__( 'Get one today from %1$s!', 'arts-license-manager' ),
				'license-help-no-purchase-code-link'     => esc_html__( 'Envato Market', 'arts-license-manager' ),
				'license-help-no-purchase-code-benefits-before' => esc_html__( 'Every license holder gets:', 'arts-license-manager' ),
				'license-help-no-purchase-code-benefits' => array(
					esc_html__( '6 months of personalized technical support.', 'arts-license-manager' ),
					esc_html__( 'Availability of the theme author to answer questions.', 'arts-license-manager' ),
					esc_html__( 'Help with reported bugs and issues.', 'arts-license-manager' ),
					esc_html__( 'Lifetime automatic theme updates.', 'arts-license-manager' ),
					esc_html__( 'Lifetime automatic core plugin updates.', 'arts-license-manager' ),
				),
				'license-local-info'                     => esc_html__( 'Local or staging installations don\'t count towards the license activation limit.', 'arts-license-manager' ),
				'support-forum-url'                      => esc_url( 'https://artemsemkin.ticksy.com/' ),
				'support-forum-link'                     => esc_html__( 'View Support Forum', 'arts-license-manager' ),
				'support-renew'                          => esc_html__( 'Renew Support Period', 'arts-license-manager' ),
				'support-extend'                         => esc_html__( 'Extend Support Period', 'arts-license-manager' ),
				'support-supported-until'                => esc_html__( 'Supported until', 'arts-license-manager' ),
				'support-expired'                        => esc_html__( 'Expired on', 'arts-license-manager' ),
				'item-page-url'                          => esc_url( '#' ),
				'item-checkout-url'                      => esc_url( '#' ),
				'item-checkout-link'                     => esc_html__( 'Buy Now', 'arts-license-manager' ),
				'item-page-link'                         => esc_html__( 'View Pricing & Details', 'arts-license-manager' ),
				'date-unknown'                           => esc_html__( 'Unknown', 'arts-license-manager' ),
				'license-cta-heading'                    => esc_html__( 'Action Required', 'arts-license-manager' ),
				/* translators: %s: theme name */
				'license-cta-message-1'                  => sprintf( esc_html__( 'Thank you for using %s theme!', 'arts-license-manager' ), $args['theme_name'] ),
				'license-cta-message-2'                  => esc_html__( 'To enable remote updates, please activate your', 'arts-license-manager' ),
				'license-cta-message-3'                  => esc_html__( 'Theme License', 'arts-license-manager' ),
				'license-cta-message-4'                  => esc_html__( 'with a license key.', 'arts-license-manager' ),
				'license-cta-link-text'                  => esc_html__( 'View More', 'arts-license-manager' ),
				'owner'                                  => esc_html__( 'Owner', 'arts-license-manager' ),
				'owner-not-linked'                       => esc_html__( 'Not Linked', 'arts-license-manager' ),
				'owner-linked'                           => esc_html__( 'Linked', 'arts-license-manager' ),
				/* translators: %s: the masked account email, e.g. j***@gmail.com */
				'owner-linked-to'                        => esc_html__( 'Linked to %s', 'arts-license-manager' ),
				'owner-connect'                          => esc_html__( 'Connect to Account', 'arts-license-manager' ),
				'owner-manage-online'                    => esc_html__( 'Manage on artemsemkin.com', 'arts-license-manager' ),
				'owner-helper-1'                         => esc_html__( 'Link your email so you can recover, transfer, or deactivate this license even if you lose access to this site.', 'arts-license-manager' ),
				'owner-helper-2'                         => esc_html__( 'No marketing unless you opt-in.', 'arts-license-manager' ),
				// Email modal strings
				'modal-continue'                         => esc_html__( 'Continue', 'arts-license-manager' ),
				'modal-skip-for-now'                     => esc_html__( 'Skip for now', 'arts-license-manager' ),
				'modal-close-modal'                      => esc_html__( 'Close modal', 'arts-license-manager' ),
				'modal-email-label'                      => esc_html__( 'Email', 'arts-license-manager' ),
				'modal-enter-valid-email'                => esc_html__( 'Enter a valid email address.', 'arts-license-manager' ),
				/* translators: %1$s: Terms of Service link, %2$s: Privacy Policy link */
				'modal-agree-legal'                      => esc_html__( 'I agree to the %1$s and acknowledge the %2$s.', 'arts-license-manager' ),
				'modal-terms-of-service'                 => esc_html__( 'Terms of Service', 'arts-license-manager' ),
				'modal-privacy-policy'                   => esc_html__( 'Privacy Policy', 'arts-license-manager' ),
				'modal-marketing-consent'                => esc_html__( 'Notify me about new theme releases and offers.', 'arts-license-manager' ),
				'modal-agree-to-continue'                => esc_html__( 'Please agree to the Terms of Service and Privacy Policy to continue.', 'arts-license-manager' ),
				'modal-title'                            => esc_html__( 'Connect Your Email to Secure this Purchase Code', 'arts-license-manager' ),
				'modal-description'                      => esc_html__( 'Enter your email to link this license to your account. We\'ll email a confirmation link.', 'arts-license-manager' ),
				/* translators: %s: the account site domain, e.g. artemsemkin.com */
				'modal-creating-account-info'            => esc_html__( 'You are creating an account on %s. We\'ll send a confirmation link to verify this e-mail address.', 'arts-license-manager' ),
				// Email modal URLs
				'modal-terms-of-service-url'             => esc_url( 'https://artemsemkin.com/terms-of-service/' ),
				'modal-privacy-policy-url'               => esc_url( 'https://artemsemkin.com/privacy-policy/' ),
				'modal-account-site-url'                 => esc_url( 'https://artemsemkin.com' ),
			)
		);

		$this
			->add_managers( $args, $strings )
			->init_managers()
			->add_filters( $args )
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
	 * Get the name of the parent theme.
	 *
	 * Retrieves the name from the parent theme if available,
	 * otherwise returns the current theme's name.
	 *
	 * @return string The name of the parent theme.
	 */
	public static function get_parent_theme_name() {
		$current_theme = self::get_current_theme();
		$theme_name    = $current_theme->get( 'Name' );

		// Use parent theme name if available
		$parent_theme = self::get_parent_theme();
		if ( $parent_theme ) {
			$theme_name = $parent_theme->get( 'Name' );
		}

		return $theme_name;
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
	 * Adds manager instances to the managers property.
	 *
	 * @param array $args Arguments to pass to the manager classes.
	 * @param array $strings Strings to pass to the manager classes.
	 *
	 * @return Plugin Returns the current instance for method chaining.
	 */
	private function add_managers( $args, $strings ) {
		$manager_classes = array(
			'admin_notices' => AdminNotices::class,
			'ajax'          => AJAX::class,
			'scheduler'     => Scheduler::class,
			'frontend'      => Frontend::class,
			'license'       => License::class,
			'options'       => Options::class,
			'strings'       => Strings::class,
			'updates'       => Updates::class,
		);
		foreach ( $manager_classes as $key => $class ) {
			$this->managers->$key = $this->get_manager_instance( $class, $args, $strings );
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

		foreach ( get_object_vars( $managers ) as $manager ) {
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
	 * @param array  $strings Strings to pass to the manager class.
	 *
	 * @return object The instantiated manager class.
	 */
	private function get_manager_instance( $class, $args, $strings ) {
		try {
			$reflection = new \ReflectionClass( $class );
			return $reflection->newInstanceArgs( array( $args, $strings ) );
		} catch ( \ReflectionException $e ) {
			return new $class();
		}
	}

	/**
	 * Adds WordPress actions for the plugin.
	 *
	 * @param array $args Arguments to pass to the action hooks.
	 *
	 * @return Plugin Returns the current instance for method chaining.
	 */
	private function add_actions( $args = array() ) {
		if ( ! isset( $args['theme_slug'] ) ) {
			return $this;
		}

		// Clear the theme update transient when the license key is activated or deactivated
		add_action( 'update_option_' . $args['theme_slug'] . '_license_key_status', array( $this->managers->updates, 'clear_theme_update_data' ), 10, 2 );

		// Force a fresh update check by clearing the cached response when the Updates screen opens.
		add_action( 'load-update-core.php', array( $this->managers->updates, 'delete_theme_update_transient' ) );

		// Clear the theme update transient after the upgrader process is complete
		add_action( 'upgrader_process_complete', array( $this->managers->updates, 'clear_theme_update_data' ), 999 );

		// Register license actions during admin initialization
		add_action( 'admin_init', array( $this->managers->license, 'register_license_actions' ) );

		// Add a notice for license activation during admin initialization
		add_action( 'admin_init', array( $this->managers->admin_notices, 'add_license_activation_notice' ) );

		// Add a notice for invalid license during admin initialization
		add_action( 'admin_init', array( $this->managers->admin_notices, 'add_license_invalid_notice' ) );

		// Auto-activate as soon as the key option is saved (Settings API path). License.php removes this
		// same hook before its own option writes to avoid re-entrancy during the AJAX flow.
		add_action( 'update_option_' . $args['theme_slug'] . '_license_key', array( $this->managers->license, 'do_activate_license' ), 10, 2 );

		// Clear the scheduled license check when the theme is switched or deactivated
		add_action( 'switch_theme', array( $this->managers->scheduler, 'clear_scheduled_license_check' ) );

		// Schedule the license check event
		add_action( 'init', array( $this->managers->scheduler, 'schedule_license_check' ) );

		// Refresh the license key when the scheduled event is triggered
		add_action( $args['theme_slug'] . '_license_check_event', array( $this->managers->license, 'do_refresh_license' ) );

		// Register AJAX actions for license operations
		add_action( 'wp_ajax_' . $args['theme_slug'] . '_license_refresh', array( $this->managers->ajax, 'refresh_license' ) );
		add_action( 'wp_ajax_' . $args['theme_slug'] . '_license_clear', array( $this->managers->ajax, 'clear_license' ) );
		add_action( 'wp_ajax_' . $args['theme_slug'] . '_license_activate', array( $this->managers->ajax, 'activate_license' ) );
		add_action( 'wp_ajax_' . $args['theme_slug'] . '_license_deactivate', array( $this->managers->ajax, 'deactivate_license' ) );
		add_action( 'wp_ajax_arts_update_email_link_state', array( $this->managers->ajax, 'update_email_link_state' ) );

		// Enqueue frontend scripts and styles in the admin panel
		add_action( 'admin_enqueue_scripts', array( $this->managers->frontend, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this->managers->frontend, 'enqueue_styles' ) );

		// Register settings for the theme license
		add_action( 'admin_init', array( $this->managers->frontend, 'register_setting' ) );

		// Add the theme license menu to the admin panel
		add_action( 'admin_menu', array( $this->managers->frontend, 'add_theme_license_menu' ) );

		return $this;
	}

	/**
	 * Adds WordPress filters for the plugin.
	 *
	 * @param array $args Arguments to pass to the filter hooks.
	 *
	 * @return Plugin Returns the current instance for method chaining.
	 */
	private function add_filters( $args = array() ) {
		if ( ! isset( $args['theme_slug'] ) ) {
			return $this;
		}

		// Modify the theme update transient before it is set
		add_filter( 'pre_set_site_transient_update_themes', array( $this->managers->updates, 'modify_theme_update_transient' ) );

		// Delete the theme update transient
		add_filter( 'delete_site_transient_update_themes', array( $this->managers->updates, 'delete_theme_update_transient' ) );

		return $this;
	}
}
