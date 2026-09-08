<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

/**
 * Add Custom Fonts & Typekit Fonts
 */
class Arts_Add_Custom_Fonts {
	/**
	 * New fonts array
	 */
	public $new_fonts = array();

	/**
	 * Children array
	 */
	public static $children = array();

	/**
	 * Variants array
	 */
	public static $variants = array();

	/**
	 * CSS for the custom fonts
	 */
	public static $css_to_print = '';

	/**
	 * The single class instance.
	 *
	 * @var object
	 */
	private static $_instance = null;

	/**
	 * Main Instance
	 * Ensures only one instance of this class exists in memory at any one time.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
			self::$_instance->init_hooks();
			self::$_instance->prepare_custom_fonts();
			self::$_instance->prepare_custom_fonts_acf();
			self::$_instance->prepare_typekit_fonts();
			self::$_instance->print_custom_css();
		}
		return self::$_instance;
	}

	public function __construct() {
		// We do nothing here!
	}

	/**
	 * Init hooks
	 */
	public function init_hooks() {
		add_action( 'init', array( $this, 'get_custom_fonts' ) );
		add_filter( 'arts/kirki_font_choices', array( $this, 'add_custom_fonts' ) );
	}

	/**
	 * Get custom fonts from Bsf_Custom_Fonts_Taxonomy
	 */
	public function get_custom_fonts() {
		if ( ! class_exists( 'Bsf_Custom_Fonts_Taxonomy' ) ) {
			return;
		}

		/** @disregard P1009 Assuming that the class is exists here */
		update_option( 'arts-custom-fonts', Bsf_Custom_Fonts_Taxonomy::get_fonts() );
	}

	/**
	 * Get custom fonts from ACF
	 */
	public function prepare_custom_fonts_acf() {
		if ( Utilities::acf_have_rows( 'custom_fonts', 'option' ) ) {
			while ( Utilities::acf_have_rows( 'custom_fonts', 'option' ) ) {
				the_row();

				$font_name    = get_sub_field( 'font_name' );
				$font_display = get_sub_field( 'font_display' );

				// add font name to Kirki typography control
				$this->new_fonts[ $font_name ] = array(
					'id'      => $font_name,
					'text'    => $font_name,
					'variant' => array(),
				);

				if ( ! empty( $font_name ) && Utilities::acf_have_rows( 'font_files', 'option' ) ) {

					while ( Utilities::acf_have_rows( 'font_files', 'option' ) ) {

						the_row();

						$font_weight = get_sub_field( 'font_weight' );
						$font_file   = get_sub_field( 'font_file' );

						$ext    = pathinfo( $font_file['filename'], PATHINFO_EXTENSION );
						$ext    = strtolower( $ext );
						$url    = $font_file['url'];
						$style  = 'normal';
						$weight = $font_weight;

						if ( $font_weight === '400' ) {
							$font_weight = 'regular';
							$weight      = 'normal';
							$style       = 'normal';
						} elseif ( $font_weight === '400italic' ) {
							$font_weight = 'italic';
							$weight      = 'normal';
							$style       = 'italic';
						} elseif ( strpos( $font_weight, 'italic' ) ) {
							$weight = str_replace( 'italic', '', $font_weight );
							$style  = 'italic';
						}

						// add font variant to custom fonts array
						array_push( $this->new_fonts[ $font_name ]['variant'], $font_weight );

						self::$css_to_print .= "
						@font-face {
							font-family: '" . $font_name . "';
							src: url('" . $url . "') format('" . $ext . "');
							font-weight: " . $weight . ';
							font-style: ' . $style . ';
							font-display: ' . $font_display . ';
						}
						';
					}
				}
			}
		}

	}


	/**
	 * Prepare custom fonts
	 */
	public function prepare_custom_fonts() {

		$fonts = get_option( 'arts-custom-fonts' );

		if ( ! empty( $fonts ) ) {
			foreach ( $fonts as $font => $key ) {
				$this->new_fonts[ $font ] = array(
					'id'      => $font,
					'text'    => $font,
					'variant' => array( '100', '100italic', '200', '200italic', '300', '300italic', '400', '400italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic', 'regular', 'italic' ),
				);
			}
		}
	}

	/**
	 * Prepare Typekit fonts
	 */
	public function prepare_typekit_fonts() {
		$fonts = get_option( 'custom-typekit-fonts' );

		if ( ! empty( $fonts ) && is_array( $fonts ) && array_key_exists( 'custom-typekit-font-details', $fonts ) ) {
			$fonts = $fonts['custom-typekit-font-details'];

			if ( is_array( $fonts ) ) {
				foreach ( $fonts as $key => $font ) {
					$this->new_fonts[ $key ] = array(
						'id'      => implode( $font['css_names'] ),
						'text'    => $font['family'],
						'variant' => $font['weights'],
					);
				}
			}
		}
	}

	/**
	 * Check is font in array
	 */
	public function is_in_array( $array, $key, $key_value ) {
		$within_array = 'no';

		foreach ( $array as $k => $v ) {

			if ( is_array( $v ) ) {
				$within_array = $this->is_in_array( $v, $key, $key_value );

				if ( $within_array == 'yes' ) {
					break;
				}
			} else {

				if ( $v == $key_value && $k == $key ) {
					$within_array = 'yes';
					break;
				}
			}
		}

		return $within_array;
	}

	/**
	 * Add custom fonts to Kirki
	 */
	public function add_custom_fonts( $custom_choice ) {
		if ( ! empty( $this->new_fonts ) ) {

			foreach ( $this->new_fonts as $new_font ) {
				if ( $this->is_in_array( self::$children, 'id', $new_font['id'] ) == 'no' ) {
					self::$children[] = array(
						'id'   => $new_font['id'],
						'text' => $new_font['text'],
					);

					self::$variants[ $new_font['id'] ] = $new_font['variant'];
				}
			}
		}

		$custom_choice['families']['custom'] = array(
			'text'     => esc_attr__( 'Custom Fonts', 'rhye' ),
			'children' => self::$children,
		);

		$custom_choice['variants'] = self::$variants;

		return $custom_choice;
	}

	/**
	 * Print Custom Fonts CSS
	 *
	 * @return void
	 */
	public function print_custom_css() {
		if ( ! empty( self::$css_to_print ) ) {
			add_action(
				'wp_enqueue_scripts',
				function() {
					wp_add_inline_style( 'rhye-main-style', trim( self::$css_to_print ) );
				},
				30
			);
		}
	}
}
