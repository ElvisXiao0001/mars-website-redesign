<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

if ( ! function_exists( 'arts_hfe_get_header_attributes' ) ) {
	/**
	 * Get the header attributes for the Elementor Header Footer Builder.
	 *
	 * @return array The footer attributes.
	 */
	function arts_hfe_get_header_attributes() {
		$elementor_header_footer_builder_header_render_place    = get_theme_mod( 'elementor_header_footer_builder_header_render_place', 'outside' );
		$elementor_header_footer_builder_header_wrapper_enabled = get_theme_mod( 'elementor_header_footer_builder_header_wrapper_enabled', true );

		if ( ! $elementor_header_footer_builder_header_wrapper_enabled || $elementor_header_footer_builder_header_render_place === 'inside' ) {
			return;
		}

		$attributes = array(
			'class' => array( 'hfe-rhye-header-wrapper' ),
		);

		$header_position                     = get_theme_mod( 'header_position', 'sticky' );
		$header_main_theme                   = Utilities::get_overridden_document_option( 'header_main_theme', 'page_header_settings_overridden', 'dark' );
		$header_main_logo                    = Utilities::get_overridden_document_option( 'header_main_logo', 'page_header_settings_overridden', 'primary' );
		$ajax_enabled                        = get_theme_mod( 'ajax_enabled', false );
		$is_elementor_canvas_template        = Utilities::get_document_option( 'template' ) === 'elementor_canvas';
		$hfe_print_header_in_canvas_template = true;

		if ( function_exists( 'get_hfe_header_id' ) ) {
			$hfe_print_header_in_canvas_template = get_post_meta( get_hfe_header_id(), 'display-on-canvas-template', true );
		}

		if ( $header_main_theme ) {
			$attributes['data-arts-theme-text'] = $header_main_theme;
		}

		if ( $header_main_logo ) {
			$attributes['data-arts-header-logo'] = $header_main_logo;
		}

		if ( $header_position === 'sticky' ) {
			$attributes['class'][] = 'header_fixed';
			$attributes['class'][] = 'js-header-sticky';

			$header_sticky_theme = Utilities::get_overridden_document_option( 'header_sticky_theme', 'page_header_settings_overridden', 'bg-dark-1' );
			$header_sticky_logo  = Utilities::get_overridden_document_option( 'header_sticky_logo', 'page_header_settings_overridden', 'secondary' );

			if ( $header_sticky_theme ) {
				$attributes['data-arts-header-sticky-theme'] = $header_sticky_theme;
			}

			if ( $header_sticky_logo ) {
				$attributes['data-arts-header-sticky-logo'] = $header_sticky_logo;
			}
		} else {
			$attributes['class'][]                     = 'header_absolute';
			$attributes['data-arts-scroll-absolute'][] = 'true';
		}

		if ( $ajax_enabled && $is_elementor_canvas_template && ! $hfe_print_header_in_canvas_template ) {
			$attributes['class'][] = 'hidden';
		}

		$attributes = apply_filters( 'arts/page_hfe_header/attributes', $attributes );

		return $attributes;
	}
}
