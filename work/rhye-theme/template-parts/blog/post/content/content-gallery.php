<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Content Post Type: Gallery
 */

if ( ! is_single() ) {
	// If not a single post, highlight the gallery.
	if ( get_post_gallery() ) {
		echo '<div class="post__gallery">' . get_post_gallery() . '</div>';
	};
};
