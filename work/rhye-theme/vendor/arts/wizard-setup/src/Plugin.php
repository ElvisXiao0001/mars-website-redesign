<?php

namespace Arts\WizardSetup;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Arts\WizardSetup\Managers\ChildTheme;
use Arts\WizardSetup\Managers\Elementor;
use Arts\WizardSetup\Managers\Import;
use Arts\WizardSetup\Managers\IntuitiveCustomPostOrder;
use Arts\WizardSetup\Managers\WooCommerce;
use Arts\WizardSetup\Managers\WordPress;
use Arts\Merlin\Plugin as Merlin;

/**
 * Class Plugin
 *
 * Main plugin class that initializes the plugin and adds managers.
 *
 * @since      1.0.0
 * @package    Arts\WizardSetup
 * @author     Artem Semkin
 */
class Plugin {
	/**
	 * The instance of this class.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var Plugin
	 */
	protected static $instance;

	/**
	 * Managers for the plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var \stdClass Contains manager instances.
	 */
	private $managers;

	/**
	 * The instance of the Merlin setup wizard.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var Merlin
	 */
	public $merlin;

	/**
	 * Get the instance of this class.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $args    Configuration arguments (kept for backward compatibility, unused).
	 * @param  array $strings Localization strings (kept for backward compatibility, unused).
	 * @return object The instance of this class.
	 * @phpstan-param array<string, mixed> $args
	 * @phpstan-param array<string, mixed> $strings
	 */
	public static function instance( $args = array(), $strings = array() ) {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self( $args, $strings );
		}

		return self::$instance;
	}

	/**
	 * Constructor for Plugin class.
	 *
	 * @since  1.0.0
	 * @access private
	 * @param  array $args    Configuration arguments (kept for backward compatibility, unused).
	 * @param  array $strings Localization strings (kept for backward compatibility, unused).
	 * @phpstan-param array<string, mixed> $args
	 * @phpstan-param array<string, mixed> $strings
	 */
	private function __construct( $args = array(), $strings = array() ) {
		// Parameters kept for backward compatibility but are unused.
		unset( $args, $strings );

		$this->managers = new \stdClass();
		$this->init();
	}

	/**
	 * Initializes the plugin by adding filters and actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return Plugin Returns the current instance for method chaining.
	 */
	private function init() {
		$theme_data = $this->get_theme_data();
		$args       = apply_filters(
			'arts/wizard_setup/plugin/config',
			array(
				'theme_slug'                        => $theme_data['slug'],
				'theme_name'                        => $theme_data['name'],
				'license_required'                  => false,
				'regenerate_thumbnails_on_import'   => true,
				'setup_child_theme'                 => array(
					'screenshot' => '',
				),
				'setup_demo_data'                   => array(
					array(
						'add_license_key_args' => false,
						'xml_url'              => 'https://artemsemkin.com/wp-json/edd/v1/file/%1$s/demo-data',
						'dat_url'              => 'https://artemsemkin.com/wp-json/edd/v1/file/%1$s/demo-customizer',
						'wie_url'              => 'https://artemsemkin.com/wp-json/edd/v1/file/%1$s/demo-widgets',
						'preview_url'          => 'https://artemsemkin.com/%1$s/wp/',
					),
				),
				'setup_elementor'                   => array(
					'editable_post_types' => array( 'arts_portfolio_item', 'arts_service' ),
					'set_active_kit'      => true,
					'replace_urls_from'   => 'https://artemsemkin.com/%1$s/wp/',
					'options'             => array(
						'css_print_method'        => 'internal',
						'unfiltered_files_upload' => '1',
						'editor_break_lines'      => '1',
					),
				),
				'setup_intuitive_custom_post_order' => array(
					'sortable_post_types' => array( 'arts_portfolio_item', 'arts_service' ),
					'sortable_taxonomies' => array( 'arts_portfolio_category', 'arts_portfolio_year' ),
				),
				'setup_woocommerce'                 => array(),
				'setup_wordpress'                   => array(
					'home_page_title'    => '',
					'blog_page_title'    => '',
					'permalinks'         => '/%postname%/',
					'date_format'        => 'd M Y',
					'empty_blog_sidebar' => true,
					'menu'               => array(
						'name'     => 'Top Menu All',
						'location' => 'main_menu',
					),
					'theme_mods'         => array(),
					'options'            => array(),
				),
			)
		);

		$this
		->add_managers( $args )
		->init_managers()
		->add_actions( $args )
		->add_filters( $args )
		->init_merlin( $args );

		return $this;
	}

	/**
	 * Retrieves the current theme data.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return array An associative array containing the theme's name and slug.
	 */
	private function get_theme_data() {
		$theme  = wp_get_theme();
		$result = array(
			'name' => $theme->get( 'Name' ),
			'slug' => $theme->get( 'TextDomain' ),
		);

		// Try to get the parent theme object
		$theme_parent = $theme->parent();

		if ( $theme_parent ) {
			$result['name'] = $theme_parent->get( 'Name' );
			$result['slug'] = $theme_parent->get( 'TextDomain' );
		}

		return $result;
	}

	/**
	 * Adds manager instances to the managers property.
	 *
	 * @since  1.0.0
	 * @access private
	 * @param  array $args Arguments to pass to the manager classes.
	 * @return Plugin Returns the current instance for method chaining.
	 */
	private function add_managers( $args ) {
		$manager_classes = array(
			'child_theme'                 => ChildTheme::class,
			'elementor'                   => Elementor::class,
			'import'                      => Import::class,
			'intuitive_custom_post_order' => IntuitiveCustomPostOrder::class,
			'woocommerce'                 => WooCommerce::class,
			'wordpress'                   => WordPress::class,
		);

		foreach ( $manager_classes as $key => $class ) {
			$this->managers->$key = $this->get_manager_instance( $class, $args );
		}

		return $this;
	}

	/**
	 * Initialize all manager classes by calling their init method if it exists.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return Plugin Returns the current instance for method chaining.
	 */
	private function init_managers() {
		$managers = $this->managers;

		foreach ( $managers as $manager ) {
			if ( method_exists( $manager, 'init' ) ) {
				$manager->init( $managers );
			}
		}

		return $this;
	}

	/**
	 * Initialize the Merlin setup wizard with custom configuration and strings.
	 *
	 * @since  1.0.0
	 * @access private
	 * @param  array $args Configuration arguments.
	 * @return Plugin Returns the current instance for method chaining.
	 */
	private function init_merlin( $args = array() ) {
		$theme_slug       = $args['theme_slug'];
		$license_required = $args['license_required'];

		$config = apply_filters(
			'arts/wizard_setup/custom_merlin/config',
			array(
				'directory'            => 'vendor/arts/merlin-wp/src/php', // Location / directory where Merlin WP is placed in your theme.
				'merlin_url'           => 'merlin', // The wp-admin page slug where Merlin WP loads.
				'parent_slug'          => 'themes.php', // The wp-admin parent page slug for the admin menu item.
				'capability'           => 'manage_options', // The capability required for this menu to be displayed to the user.
				'child_action_btn_url' => 'https://codex.wordpress.org/child_themes', // URL for the 'child-action-link'.
				'dev_mode'             => true, // Enable development mode for testing.
				'license_step'         => true, // EDD license activation step.
				'license_required'     => $license_required, // Require the license activation step.
				'license_help_url'     => 'https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code', // URL for the 'license-tooltip'.
				'edd_remote_api_url'   => "https://artemsemkin.com/wp-json/edd/v1/activate/{$theme_slug}/theme", // EDD_Theme_Updater_Admin remote_api_url.
				'email_api_url'        => 'https://artemsemkin.com/wp-json/edd/v1/email/link',
				'edd_item_name'        => '', // EDD_Theme_Updater_Admin item_name.
				'edd_theme_slug'       => $theme_slug, // EDD_Theme_Updater_Admin item_slug.
				'ready_big_button_url' => home_url( '/' ), // URL for the 'ready-big-button'.
			)
		);

		$strings = apply_filters(
			'arts/wizard_setup/custom_merlin/strings',
			array(
				'admin-menu'               => esc_html__( 'Theme Setup', 'merlin-wp' ),
				/* translators: 1: Title Tag 2: Theme Name 3: Closing Title Tag */
				'title%s%s%s%s'            => esc_html__( '%1$s%2$s Themes &lsaquo; Theme Setup: %3$s%4$s', 'merlin-wp' ),
				'return-to-dashboard'      => esc_html__( 'Return to the dashboard', 'merlin-wp' ),
				'ignore'                   => esc_html__( 'Disable this wizard', 'merlin-wp' ),
				'btn-skip'                 => esc_html__( 'Skip', 'merlin-wp' ),
				'btn-next'                 => esc_html__( 'Next', 'merlin-wp' ),
				'btn-start'                => esc_html__( 'Start', 'merlin-wp' ),
				'btn-no'                   => esc_html__( 'Cancel', 'merlin-wp' ),
				'btn-plugins-install'      => esc_html__( 'Install', 'merlin-wp' ),
				'btn-child-install'        => esc_html__( 'Install', 'merlin-wp' ),
				'btn-content-install'      => esc_html__( 'Install', 'merlin-wp' ),
				'btn-import'               => esc_html__( 'Import', 'merlin-wp' ),
				'btn-license-activate'     => esc_html__( 'Activate', 'merlin-wp' ),
				'btn-license-skip'         => esc_html__( 'Skip', 'merlin-wp' ),
				/* translators: Theme Name */
				'license-header%s'         => esc_html__( 'Activate %s', 'merlin-wp' ),
				/* translators: Theme Name */
				'license-header-success%s' => esc_html__( '%s is Activated', 'merlin-wp' ),
				/* translators: Theme Name */
				'license%s'                => esc_html__( 'Enter your license key to unlock demo import and activate remote updates.', 'merlin-wp' ),
				'license-label'            => esc_html__( 'License key', 'merlin-wp' ),
				'license-success%s'        => esc_html__( 'The theme is already registered, so you can go to the next step!', 'merlin-wp' ),
				'license-json-success%s'   => esc_html__( 'Your theme is activated! Remote updates are activated and demo import is unlocked.', 'merlin-wp' ),
				'license-tooltip'          => esc_html__( 'Need help?', 'merlin-wp' ),
				/* translators: Theme Name */
				'welcome-header%s'         => esc_html__( 'Welcome to %s', 'merlin-wp' ),
				'welcome-header-success%s' => esc_html__( 'Hi. Welcome back', 'merlin-wp' ),
				'welcome%s'                => esc_html__( 'This wizard will set up your theme, install plugins, and import content. It is optional & should take only a few minutes.', 'merlin-wp' ),
				'welcome-success%s'        => esc_html__( 'You may have already run this theme setup wizard. If you would like to proceed anyway, click on the "Start" button below.', 'merlin-wp' ),
				'child-header'             => esc_html__( 'Install Child Theme', 'merlin-wp' ),
				'child-header-success'     => esc_html__( 'You\'re good to go!', 'merlin-wp' ),
				'child'                    => esc_html__( 'Let\'s build & activate a child theme so you may easily make theme changes.', 'merlin-wp' ),
				'child-success%s'          => esc_html__( 'Your child theme has already been installed and is now activated, if it wasn\'t already.', 'merlin-wp' ),
				'child-action-link'        => esc_html__( 'Learn about child themes', 'merlin-wp' ),
				'child-json-success%s'     => esc_html__( 'Awesome. Your child theme has already been installed and is now activated.', 'merlin-wp' ),
				'child-json-already%s'     => esc_html__( 'Awesome. Your child theme has been created and is now activated.', 'merlin-wp' ),
				'plugins-header'           => esc_html__( 'Install Plugins', 'merlin-wp' ),
				'plugins-header-success'   => esc_html__( 'You\'re up to speed!', 'merlin-wp' ),
				'plugins'                  => esc_html__( 'Let\'s install some essential WordPress plugins to get your site up to speed.', 'merlin-wp' ),
				'plugins-success%s'        => esc_html__( 'The required WordPress plugins are all installed and up to date. Press "Next" to continue the setup wizard.', 'merlin-wp' ),
				'plugins-action-link'      => esc_html__( 'Advanced', 'merlin-wp' ),
				'import-header'            => esc_html__( 'Import Content', 'merlin-wp' ),
				'import'                   => esc_html__( 'Let\'s import content to your website, to help you get familiar with the theme.', 'merlin-wp' ),
				'import-action-link'       => esc_html__( 'Advanced', 'merlin-wp' ),
				'ready-header'             => esc_html__( 'All done. Have fun!', 'merlin-wp' ),
				/* translators: Theme Author */
				'ready%s'                  => esc_html__( 'Your theme has been all set up. Enjoy your new theme by %s.', 'merlin-wp' ),
				'ready-action-link'        => esc_html__( 'Extras', 'merlin-wp' ),
				'ready-big-button'         => esc_html__( 'View your website', 'merlin-wp' ),
				'ready-link-1'             => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://wordpress.org/support/', esc_html__( 'Explore WordPress', 'merlin-wp' ) ),
				'ready-link-2'             => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://themeforest.net/user/artemsemkin', esc_html__( 'Get Theme Support', 'merlin-wp' ) ),
				'ready-link-3'             => sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'customize.php' ), esc_html__( 'Start Customizing', 'merlin-wp' ) ),
			)
		);

		$this->merlin = new Merlin( $config, $strings );
		$this->apply_fix_php_warning( $config );

		return $this;
	}

	/**
	 * Fixes PHP warning on wizard setup page.
	 *
	 * @since  1.0.0
	 * @access private
	 * @param  array $config Merlin config.
	 * @return $this
	 */
	private function apply_fix_php_warning( $config ) {
		if ( isset( $_GET['page'] ) && $_GET['page'] === $config['merlin_url'] ) {
			remove_all_actions( 'admin_footer', 10 );
			add_action( 'admin_footer', array( $this->merlin, 'svg_sprite' ) );
		}

		return $this;
	}

	/**
	 * Helper method to instantiate a manager class.
	 *
	 * @since  1.0.0
	 * @access private
	 * @param  string $class The manager class to instantiate.
	 * @param  array  $args Arguments to pass to the manager class.
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

	/**
	 * Adds WordPress actions for the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @param  array $args Arguments to pass to the action hooks.
	 * @return Plugin Returns the current instance for method chaining.
	 */
	private function add_actions( $args = array() ) {
		add_action( 'merlin_after_all_import', array( $this, 'setup_after_all_import' ) );
		add_action( 'pt-ocdi/after_import', array( $this, 'setup_after_all_import' ) );

		if ( isset( $args['setup_wordpress'] ) ) {
			add_action( 'merlin_widget_importer_before_widgets_import', array( $this->managers->wordpress, 'before_widgets_import' ) );
			add_action( 'pt-ocdi/widget_importer_before_widgets_import', array( $this->managers->wordpress, 'before_widgets_import' ) );
		}

		if ( isset( $args['setup_elementor'] ) ) {
			add_action( 'init', array( $this->managers->elementor, 'register_import_font_cpt' ), 1 );
			add_action( 'init', array( $this->managers->elementor, 'remove_welcome_screen' ) );
			add_action( 'init', array( $this->managers->elementor, 'clear_caches_on_pro_init' ), 20 );
		}

		if ( isset( $args['setup_woocommerce'] ) ) {
			add_action( 'init', array( $this->managers->woocommerce, 'remove_setup_wizard_screen' ) );
		}

		return $this;
	}

	/**
	 * Adds WordPress filters for the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @param  array $args Arguments to pass to the filter hooks.
	 * @return Plugin Returns the current instance for method chaining.
	 */
	private function add_filters( $args = array() ) {
		// Set files for demo import
		add_filter( 'merlin_import_files', array( $this->managers->import, 'get_import_files_config' ) );
		add_filter( 'ocdi/import_files', array( $this->managers->import, 'get_import_files_config' ) );

		$regenerate_thumbnails_on_import = $args['regenerate_thumbnails_on_import'] ? '__return_true' : '__return_false';

		add_filter( 'merlin_regenerate_thumbnails_in_content_import', $regenerate_thumbnails_on_import );

		// Set child theme configuration
		if ( isset( $args['setup_child_theme'] ) ) {
			add_filter( 'merlin_generate_child_screenshot', array( $this->managers->child_theme, 'get_screenshot' ) );
			add_filter( 'merlin_generate_child_style_css', array( $this->managers->child_theme, 'get_contents_style_css' ) );
			add_filter( 'merlin_generate_child_functions_php', array( $this->managers->child_theme, 'get_contents_functions_php' ) );
		}

		if ( isset( $args['setup_woocommerce'] ) ) {
			add_filter( 'merlin_get_base_content', array( $this->managers->woocommerce, 'unset_default_woocommerce_pages' ) );
		}

		if ( isset( $args['setup_wordpress'] ) ) {
			add_filter( 'merlin_content_home_page_title', array( $this->managers->wordpress, 'get_home_page_title' ) );
			add_filter( 'merlin_content_blog_page_title', array( $this->managers->wordpress, 'get_blog_page_title' ) );
			add_filter( 'merlin_get_base_content', array( $this->managers->wordpress, 'enable_svg_uploads' ) );
		}

		return $this;
	}

	/**
	 * Sets up various managers after all imports are completed.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function setup_after_all_import() {
		$this->managers->elementor->setup();
		$this->managers->intuitive_custom_post_order->setup();
		$this->managers->woocommerce->setup();
		$this->managers->wordpress->setup();
	}
}
