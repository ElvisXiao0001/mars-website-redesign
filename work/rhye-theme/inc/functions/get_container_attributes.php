<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

if ( ! function_exists( 'arts_get_container_attributes' ) ) {
	/**
	 * Get attributes for the <main> page container
	 *
	 * @return array The attributes for the main container.
	 */
	function arts_get_container_attributes() {
		$smooth_scroll   = arts_is_smooth_scroll();
		$ajax_enabled    = get_theme_mod( 'ajax_enabled', false );
		$attrs_container = array(
			'class'      => '',
			'attributes' => '',
			'theme'      => 'dark',
		);

		if ( $smooth_scroll === 'smooth-scrollbar' ) {
			$attrs_container['class'] = 'js-smooth-scroll';
		} elseif ( $smooth_scroll === 'lenis' ) {
			$attrs_container['class'] = 'js-smooth-scroll-lenis';
		}

		if ( $ajax_enabled ) {
			$attrs_container['attributes'] .= ' data-barba=container';
		}

		if ( is_home() || is_category() || is_archive() || is_search() ) {
			$attrs_container['class'] .= ' ' . get_theme_mod( 'blog_style_theme', 'bg-light-1' );
			$attrs_container['theme']  = get_theme_mod( 'blog_style_main_theme', 'dark' );

			if ( $ajax_enabled ) {
				$attrs_container['attributes'] .= ' data-barba-namespace=archive';
			}
		}

		if ( is_singular( 'post' ) ) {
			$attrs_container['class'] .= ' ' . get_theme_mod( 'blog_style_single_post_theme', 'bg-light-1' );
			$attrs_container['theme']  = get_theme_mod( 'blog_style_single_post_main_theme', 'dark' );

			if ( $ajax_enabled ) {
				$attrs_container['attributes'] .= ' data-barba-namespace=post';
			}
		}

		if ( Utilities::is_built_with_elementor() && $ajax_enabled ) {
			$attrs_container['attributes'] .= ' data-barba-namespace=elementor';
		}

		return $attrs_container;
	}
}
