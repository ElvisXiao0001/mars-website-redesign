<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_filter( 'comment_form_default_fields', 'arts_filter_comment_form_default_fields' );
if ( ! function_exists( 'arts_filter_comment_form_default_fields' ) ) {
	/**
	 * Set custom fields (author, email, url) for the comment form.
	 *
	 * @param array $args    Optional. An array of arguments for the comment form fields.
	 * @param int   $post_id Optional. The post ID for which the comment form is being generated.
	 *
	 * @return array The default comment form fields.
	 */
	function arts_filter_comment_form_default_fields( $args = array(), $post_id = null ) {
		if ( null === $post_id ) {
			$post_id = get_the_ID();
		}

		// Exit the function when comments for the post are closed.
		if ( ! comments_open( $post_id ) ) {
			/**
			 * Fires after the comment form if comments are closed.
			 *
			 * @since 3.0.0
			 */
			do_action( 'comment_form_comments_closed' );

			return;
		}

		$commenter     = wp_get_current_commenter();
		$user          = wp_get_current_user();
		$user_identity = $user->exists() ? $user->display_name : '';

		$args = wp_parse_args( $args );
		if ( ! isset( $args['format'] ) ) {
			$args['format'] = current_theme_supports( 'html5', 'comment-form' ) ? 'html5' : 'xhtml';
		}

		$req      = get_option( 'require_name_email' );
		$html_req = ( $req ? " required='required'" : '' );
		$html5    = 'html5' === $args['format'];

		$fields = array(
			'author' => '
				<div class="row form__row">
					<div class="col form__col">
						<label class="input-float js-input-float">
							<input class="input-float__input" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245"' . $html_req . '/>' .
								'<span class="input-float__label">' . esc_html__( 'Name', 'rhye' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</span>
						</label>
					</div>
				</div>
			',
			'email'  => '
				<div class="row form__row">
					<div class="col form__col">
						<label class="input-float js-input-float">
							<input class="input-float__input" id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $html_req . ' />' .
								'<span class="input-float__label">' . esc_html__( 'Email', 'rhye' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</span>
						</label>
					</div>
				</div>
			',
			'url'    => '
				<div class="row form__row">
					<div class="col form__col">
						<label class="input-float js-input-float">
							<input class="input-float__input" id="url" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" maxlength="200" />' .
								'<span class="input-float__label">' . esc_html__( 'Website', 'rhye' ) . '</span>
						</label>
					</div>
				</div>
			',
		);

		return $fields;
	}
}

add_filter( 'comment_form_defaults', 'arts_comment_form_defaults' );
if ( ! function_exists( 'arts_comment_form_defaults' ) ) {
	/**
	 * Set custom textarea & submit button for the comment form.
	 *
	 * @return array Default arguments for the comment form.
	 */
	function arts_comment_form_defaults() {
		$args = array(
			'comment_field' => '
		<div class="row form__row">
			<div class="col form__col">
				<label class="input-float js-input-float">
					<textarea id="comment" name="comment" class="input-float__input input-float__input_textarea" cols="45" rows="8" maxlength="65525" required="required"></textarea><span class="input-float__label">' . esc_html_x( 'Comment', 'noun', 'rhye' ) . '</span>
				</label>
			</div>
		</div>
	',
			'class_submit'  => 'button button_bordered bg-dark-1',
			'submit_button' => '<button name="%1$s" type="submit" id="%2$s" class="%3$s" data-hover="%4$s"><span class="button__label-hover">%4$s</span></button>',
			'submit_field'  => '
		<div class="row form__row form__row_submit text-right">
			<div class="col form__col form__col_submit">
				%1$s %2$s
			</div>
		</div>
	',
		);

		return $args;
	}
}
