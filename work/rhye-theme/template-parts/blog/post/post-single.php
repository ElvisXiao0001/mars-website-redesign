<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$post_thumbnail_size                = get_theme_mod( 'blog_style_single_post_thumbnail', 'full' );
$post_image_layout                  = get_theme_mod( 'post_image_layout', 'normal' );
$is_active_sidebar                  = is_active_sidebar( 'blog-sidebar' );
$blog_ajax_image_transition_enabled = get_theme_mod( 'blog_ajax_image_transition_enabled', true );

?>
<article <?php post_class( 'post' ); ?> id="post-<?php the_ID(); ?>">
	<!-- post content -->
	<div class="post__content clearfix">
		<?php if ( $post_image_layout === 'normal' && has_post_thumbnail() ) : ?>
			<!-- post media -->
			<div class="post__media">
				<?php if ( $blog_ajax_image_transition_enabled ) : ?>
				<div class="js-transition-img">
					<?php the_post_thumbnail( esc_attr( $post_thumbnail_size ), array( 'class' => 'js-transition-img__transformed-el' ) ); ?>
				</div>
				<?php else : ?>
					<?php the_post_thumbnail( esc_attr( $post_thumbnail_size ) ); ?>
				<?php endif; ?>
			</div>
			<!-- - post media-->
		<?php endif; ?>
		<?php the_content(); ?>
		<?php
			wp_link_pages(
				array(
					'before'      => '<div class="page-links">' . esc_html__( 'Pages:', 'rhye' ),
					'after'       => '</div>',
					'link_before' => '<span class="page-number">',
					'link_after'  => '</span>',
				)
			);
			?>
	</div>
	<!-- - post content -->
	<?php if ( wp_get_post_tags( $post->ID ) ) : ?>
		<!-- post tags -->
		<div class="post__tags mt-xsmall">
			<div class="tagcloud">
				<?php the_tags( '', '', '' ); ?>
			</div>
		</div>
		<!-- - post tags -->
	<?php endif; ?>

	<?php if ( comments_open() || get_comments_number() ) : ?>
		<!-- post comments -->
		<div class="post__comments mt-small" data-barba-prevent="all">
			<?php comments_template(); ?>
		</div>
		<!-- - post comments -->
	<?php endif; ?>
</article>
