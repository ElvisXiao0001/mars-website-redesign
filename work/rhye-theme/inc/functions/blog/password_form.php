<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

/**
 * Style Password Form in Protected Posts
 */
add_filter( 'the_password_form', 'arts_password_form' );
if ( ! function_exists( 'arts_password_form' ) ) {
	/**
	 * Generates a password-protected form for posts.
	 *
	 * @global WP_Post $post The current post object.
	 *
	 * @return string The HTML output of the password form.
	 */
	function arts_password_form() {
		global $post;

		$post          = get_post( $post );
		$label         = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
		$classes_array = array(
			'post-password-form-wrapper',
		);

		if ( Utilities::is_built_with_elementor() ) {
			$classes_array[] = 'container';
			$classes_array[] = 'py-small';
		}

		$classes_string = implode( ' ', $classes_array );

		$output_wrapper_start = '<div class="' . esc_attr( $classes_string ) . '">';
		$output_wrapper_end   = '</div>';

		$output = '<form action="' . esc_url( home_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="js-ajax-form-password post-password-form" method="post">
	<p class="post-password-form-message"><i class="post-password-form-message-icon material-icons">lock</i>' . esc_html__( 'This content is password protected. To view it please enter your password below:', 'rhye' ) . '</p>
	<div class="input-float input-search js-input-float">' . ' <input class="input-float__input input-search__input" name="post_password" id="' . $label . '" type="password" size="20" /><span class="input-float__label">' . esc_html__( 'Password', 'rhye' ) . '</span><button class="input-search__submit" type="submit" name="Submit"><i class="material-icons">keyboard_arrow_right</i></button></div></form>
	';

		// No cookie, the user has not sent anything until now.
		if ( ! isset( $_COOKIE[ 'wp-postpass_' . COOKIEHASH ] ) ) {
			return $output_wrapper_start . $output . $output_wrapper_end;
		}

		// The refresh came from a different page, the user has not sent anything until now.
		if ( wp_get_raw_referer() !== get_permalink() ) {
			return $output_wrapper_start . $output . $output_wrapper_end;
		}

		$error_message_html = '<div class="post-password-form-error"><strong>' . esc_html__( 'The password you entered is incorrect', 'rhye' ) . '</strong></div>';

		// We have a cookie, but it doesn’t match the password. Output error message.
		return $output_wrapper_start . $output . $error_message_html . $output_wrapper_end;
	}
}
