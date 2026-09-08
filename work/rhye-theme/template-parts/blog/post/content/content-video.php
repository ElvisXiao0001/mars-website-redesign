<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Content Post Type: Video
 */

$content = apply_filters( 'the_content', get_the_content() );
$video   = false;

// Only get video from the content if a playlist isn't present.
if ( false === strpos( $content, 'wp-playlist-script' ) ) {
	$video = get_media_embedded_in_content( $content, array( 'video', 'object', 'embed', 'iframe' ) );
}

if ( ! is_single() ) {
	// If not a single post, highlight the video file.
	if ( ! empty( $video ) ) {
		foreach ( $video as $video_html ) {
			echo '<div class="entry-video">' . $video_html . '</div>';
		}
	};
};
