<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

$post_meta_set                      = get_theme_mod( 'post_meta_set', array( 'date', 'categories', 'comments', 'author' ) );
$blog_posts_date_style              = get_theme_mod( 'blog_posts_date_style', 'info' );
$date_link                          = get_month_link( get_post_time( 'Y' ), get_post_time( 'm' ) );
$author                             = Utilities::get_post_author();
$blog_ajax_image_transition_enabled = get_theme_mod( 'blog_ajax_image_transition_enabled', true );

?>

<ul class="post-meta">
	<?php if ( in_array( 'date', $post_meta_set ) && ( $blog_posts_date_style === 'info' || is_single() ) ) : ?>
		<li>
			<a href="<?php echo esc_attr( $date_link ); ?>"><?php echo esc_html( get_the_date() ); ?></a>
		</li>
	<?php endif; ?>
	<?php if ( in_array( 'categories', $post_meta_set ) ) : ?>
		<?php if ( has_category() ) : ?>
			<li>
				<?php the_category( ',&nbsp;' ); ?>
			</li>
		<?php endif; ?>
	<?php endif; ?>
	<?php if ( in_array( 'comments', $post_meta_set ) ) : ?>
		<li>
			<a href="<?php echo get_comments_link( get_the_ID() ); ?>"<?php if ( $blog_ajax_image_transition_enabled ) : ?> data-pjax-link="flyingImage"<?php endif; ?>><?php comments_number(); ?></a>
		</li>
	<?php endif; ?>
	<?php if ( ! empty( $author['name'] ) && in_array( 'author', $post_meta_set ) ) : ?>
		<li>
			<span><?php esc_html_e( 'by', 'rhye' ); ?>&nbsp;</span>
			<a href="<?php echo esc_url( $author['url'] ); ?>"><?php echo esc_html( $author['name'] ); ?></a>
		</li>
	<?php endif; ?>
</ul>
