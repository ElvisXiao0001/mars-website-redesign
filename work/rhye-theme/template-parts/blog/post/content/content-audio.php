<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Content Post Type: Audio
 */

$content = apply_filters( 'the_content', get_the_content() );
$audio   = false;

// Only get audio from the content if a playlist isn't present.
if ( false === strpos( $content, 'wp-playlist-script' ) ) {
	$audio = get_media_embedded_in_content( $content, array( 'audio' ) );
}

if ( ! is_single() ) {
	// If not a single post, highlight the audio file.
	if ( ! empty( $audio ) ) {
		foreach ( $audio as $audio_html ) {
			echo '<p>' . $audio_html . '</p>';
		}
	};
};
