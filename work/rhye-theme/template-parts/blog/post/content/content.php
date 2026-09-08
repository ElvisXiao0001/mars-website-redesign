<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_filter(
	'excerpt_length',
	function() {
		return get_theme_mod( 'blog_posts_excerpt_words_number', 55 );
	}
);

add_filter(
	'excerpt_more',
	function() {
		return '...';
	}
);

/**
 * Content Post Type: Any
 */
echo get_the_excerpt();
