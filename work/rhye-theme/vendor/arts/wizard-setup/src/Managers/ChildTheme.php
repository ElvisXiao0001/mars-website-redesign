<?php

namespace Arts\WizardSetup\Managers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class ChildTheme
 *
 * Manages the creation and configuration of a child theme during the setup process.
 *
 * @since      1.0.0
 * @package    Arts\WizardSetup\Managers
 * @author     Artem Semkin
 */
class ChildTheme extends BaseManager {
	/**
	 * The slug of the parent theme.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string
	 */
	private $theme_slug;

	/**
	 * The name of the parent theme.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string
	 */
	private $theme_name;

	/**
	 * The URL to the child theme screenshot.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string
	 */
	private $screenshot;

	/**
	 * Initializes the properties from configuration arguments.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return $this For method chaining.
	 */
	protected function init_properties() {
		if ( isset( $this->args['theme_slug'] ) ) {
			$this->theme_slug = $this->args['theme_slug'];
		}

		if ( isset( $this->args['theme_name'] ) ) {
			$this->theme_name = $this->args['theme_name'];
		}

		if ( is_array( $this->args['setup_child_theme'] ) && isset( $this->args['setup_child_theme']['screenshot'] ) ) {
			$this->screenshot = $this->args['setup_child_theme']['screenshot'];
		}

		return $this;
	}

	/**
	 * Generates the style.css content for the child theme.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string The content of the child theme's style.css file.
	 */
	public function get_contents_style_css() {
		if ( ! $this->theme_name || ! $this->theme_slug ) {
			return '';
		}

		$defaults = array(
			'name'        => "{$this->theme_name} Child",
			'uri'         => "https://artemsemkin.com/{$this->theme_slug}/wp/",
			'description' => "This is a child theme of {$this->theme_name}, used for codebase customizations",
			'author'      => 'Artem Semkin',
			'authorUri'   => 'https://artemsemkin.com/',
			'template'    => $this->theme_slug,
			'version'     => '1.0.0',
		);

		$args = wp_parse_args( $this->args['setup_child_theme'], $defaults );

		return self::get_template_style_css( $args );
	}

	/**
	 * Generates the functions.php content for the child theme.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string The content of the child theme's functions.php file.
	 */
	public function get_contents_functions_php() {
		if ( ! $this->theme_name || ! $this->theme_slug ) {
			return '';
		}

		return self::get_template_functions_php( $this->theme_slug, $this->theme_name );
	}

	/**
	 * Gets the screenshot URL for the child theme.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string The URL to the child theme screenshot.
	 */
	public function get_screenshot() {
		return $this->screenshot;
	}

	/**
	 * Retrieves a template functions.php content for a child theme.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  string $slug The slug for the theme.
	 * @param  string $name The name of the parent theme.
	 * @return string The generated PHP code for the child theme functions.
	 */
	public static function get_template_functions_php( $slug, $name ) {
		$slug_no_hyphens = strtolower( preg_replace( '#[^a-zA-Z]#', '', $slug ) );

		$template = '
		<?php
			/**
			  * Theme functions and definitions.
			  * This is a child theme of ' . $name . ".
			  *
			  * @link https://developer.wordpress.org/themes/basics/theme-functions/
			  */

			/**
			  * If your child theme has more than one .css file (eg. ie.css, style.css, main.css) then
			  * you will have to make sure to maintain all of the parent theme dependencies.
			  *
			  * Make sure you're using the correct handle for loading the parent theme's styles.
			  * Failure to use the proper tag will result in a CSS file needlessly being loaded twice.
			  * This will usually not affect the site appearance, but it's inefficient and extends your page's loading time.
			  *
			  * @link https://codex.wordpress.org/Child_Themes
			  */
			add_action(  'wp_enqueue_scripts', '{$slug_no_hyphens}_child_enqueue_styles', 99 );
			function {$slug_no_hyphens}_child_enqueue_styles() {
			  wp_enqueue_style( '{$slug}-style' , get_template_directory_uri() . '/style.css' );
			  wp_enqueue_style( '{$slug}-child-style',
			    get_stylesheet_directory_uri() . '/style.css',
			    array( '{$slug}-style' ),
			    wp_get_theme()->get( 'Version' )
			  );
			}\n";

		return trim( preg_replace( '/\t+/', '', $template ) );
	}

	/**
	 * Retrieves a template style.css content for a child theme.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  array $args Associative array of theme attributes.
	 * @return string The generated style.css content.
	 */
	public static function get_template_style_css( $args = array() ) {
		$fields_map = array(
			'Theme Name'        => 'name',
			'Template'          => 'template',
			'Theme URI'         => 'uri',
			'Description'       => 'description',
			'Author'            => 'author',
			'Author URI'        => 'authorUri',
			'Update URI'        => 'updateUri',
			'Version'           => 'version',
			'Requires at least' => 'requiresAtLeast',
			'Requires PHP'      => 'requiresPHP',
			'Requires Plugins'  => 'requiresPlugins',
			'License'           => 'license',
			'License URI'       => 'licenseUri',
			'Text Domain'       => 'textDomain',
			'Tested up to'      => 'testedUpTo',
			'Tags'              => 'tags',
		);

		$template = "/*\n";

		foreach ( $fields_map as $key => $value ) {
			$args_value = isset( $args[ $value ] ) && ! empty( $args[ $value ] ) ? $args[ $value ] : '';

			if ( $args_value ) {
				$template .= "  {$key}: {$args_value}\n";
			}
		}

		$template .= "*/\n";

		return $template;
	}
}
