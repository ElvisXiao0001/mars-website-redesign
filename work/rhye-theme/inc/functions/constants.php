<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! defined( 'ARTS_THEME_COLORS_ARRAY' ) ) {
	/**
	 * Background Color Classes
	 */
	define(
		'ARTS_THEME_COLORS_ARRAY',
		array(
			''           => 'Auto',
			'bg-dark-1'  => 'Dark 1',
			'bg-dark-2'  => 'Dark 2',
			'bg-dark-3'  => 'Dark 3',
			'bg-dark-4'  => 'Dark 4',
			'bg-light-1' => 'Light 1',
			'bg-light-2' => 'Light 2',
			'bg-light-3' => 'Light 3',
			'bg-light-4' => 'Light 4',
			'bg-white'   => 'White',
			'bg-gray-1'  => 'Gray 1',
			'bg-gray-2'  => 'Gray 2',
		)
	);
}

if ( ! defined( 'ARTS_THEME_COLOR_THEMES_ARRAY' ) ) {
	/**
	 * Color Themes
	 */
	define(
		'ARTS_THEME_COLOR_THEMES_ARRAY',
		array(
			''      => 'Auto',
			'dark'  => 'Dark',
			'light' => 'Light',
		)
	);
}

if ( ! defined( 'ARTS_THEME_TYPOGRAHY_ARRAY' ) ) {
	/**
	 * Typography Presets
	 */
	define(
		'ARTS_THEME_TYPOGRAHY_ARRAY',
		array(
			'xl'         => 'Heading XL',
			'h1'         => 'Heading 1',
			'h2'         => 'Heading 2',
			'h3'         => 'Heading 3',
			'h4'         => 'Heading 4',
			'h5'         => 'Heading 5',
			'h6'         => 'Heading 6',
			'paragraph'  => 'Paragraph',
			'blockquote' => 'Blockquote',
			'subheading' => 'Subheading',
			'small'      => 'Small',
		)
	);
}

if ( ! defined( 'ARTS_THEME_HTML_TAGS_ARRAY' ) ) {
	/**
	 * HTML Tags
	 */
	define(
		'ARTS_THEME_HTML_TAGS_ARRAY',
		array(
			'div'        => 'div',
			'span'       => 'span',
			'h1'         => 'h1',
			'h2'         => 'h2',
			'h3'         => 'h3',
			'h4'         => 'h4',
			'h5'         => 'h5',
			'h6'         => 'h6',
			'p'          => 'p',
			'blockquote' => 'blockquote',
		)
	);
}
