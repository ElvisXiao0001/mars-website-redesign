<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'after_setup_theme', 'arts_after_setup_theme' );
if ( ! function_exists( 'arts_after_setup_theme' ) ) {
	/**
	 * Set up theme defaults and registers support for various WordPress features.
	 *
	 * @return void
	 */
	function arts_after_setup_theme() {
		global $content_width;

		if ( ! isset( $content_width ) ) {
			$content_width = 900;
		}

		load_theme_textdomain( 'rhye', ARTS_THEME_PATH . '/languages' );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support(
			'html5',
			array(
				'comment-list',
				'comment-form',
				'search-form',
				'gallery',
				'caption',
			)
		);
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 80,
				'width'       => 80,
				'flex-height' => true,
				'flex-width'  => true,
				'header-text' => array( 'logo__text' ),
			)
		);
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'gallery',
				'link',
				'image',
				'quote',
				'status',
				'video',
				'audio',
				'chat',
			)
		);
		add_theme_support( 'title-tag' );
		add_image_size( 'rhye-1024-1024-crop', 1024, 1024, true );
		add_image_size( 'rhye-1920-1280-crop', 1920, 1280, true );
		add_image_size( 'rhye-1280-1920-crop', 1280, 1920, true );

		add_theme_support( 'header-footer-elementor' );
	}
}
