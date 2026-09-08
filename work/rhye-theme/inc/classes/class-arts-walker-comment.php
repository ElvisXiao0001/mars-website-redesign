<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Custom Comment Output
 */
class Arts_Walker_Comment extends Walker_Comment {
	/**
	 * Outputs a pingback comment.
	 *
	 * @since 3.6.0
	 *
	 * @see wp_list_comments()
	 *
	 * @param WP_Comment $comment The comment object.
	 * @param int        $depth   Depth of the current comment.
	 * @param array      $args    An array of arguments.
	 */
	protected function ping( $comment, $depth, $args ) {
		$tag = ( 'div' == $args['style'] ) ? 'div' : 'li';
		?>
		<<?php echo esc_attr( $tag ); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( '', $comment ); ?>>
			<div class="comment-body">
				<?php
				esc_html_e( 'Pingback:', 'rhye' );
				echo esc_html( '&nbsp;' );
				?>
				<?php comment_author_link( $comment ); ?> <?php edit_comment_link( esc_html__( 'Edit', 'rhye' ), '<span class="edit-link">', '</span>' ); ?>
			</div>
		<?php
	}

	/**
	 * Outputs a comment in the HTML5 format.
	 *
	 * @since 3.6.0
	 *
	 * @see wp_list_comments()
	 *
	 * @param WP_Comment $comment Comment to display.
	 * @param int        $depth   Depth of the current comment.
	 * @param array      $args    An array of arguments.
	 */
	protected function html5_comment( $comment, $depth, $args ) {
		$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
		?>
		<<?php echo esc_attr( $tag ); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?>>
			<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">

				<div class="comment-author vcard">
					<?php
					if ( 0 != $args['avatar_size'] ) {
						echo get_avatar( $comment, $args['avatar_size'] );}
					?>
				</div><!-- .comment-author -->

				<div class="comment-content">

					<footer class="comment-meta">

						<?php
							/* translators: %s: comment author link */
							printf(
								sprintf( '<b class="fn">%s</b>', get_comment_author_link( $comment ) )
							);
						?>

						<div class="comment-metadata">
							<a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
								<time datetime="<?php comment_time( 'c' ); ?>">
									<?php
										/* translators: 1: comment date, 2: comment time */
										printf( esc_html__( '%1$s at %2$s', 'rhye' ), get_comment_date( '', $comment ), get_comment_time() );
									?>
								</time>
							</a>

						</div><!-- .comment-metadata -->

						<?php if ( '0' == $comment->comment_approved ) : ?>
						<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'rhye' ); ?></p>
						<?php endif; ?>
					</footer><!-- .comment-meta -->

					<?php comment_text(); ?>

					<?php
						comment_reply_link(
							array_merge(
								$args,
								array(
									'add_below' => 'div-comment',
									'depth'     => $depth,
									'max_depth' => $args['max_depth'],
									'before'    => '<div class="reply"><div class="comments-reply-link">',
									'after'     => '</div></div>',
								)
							)
						);
					?>
					<?php edit_comment_link( esc_html__( 'Edit', 'rhye' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-content -->

			</article><!-- .comment-body -->
		<?php
	}
}
