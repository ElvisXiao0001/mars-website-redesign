<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$date_full  = get_the_date( DATE_W3C );
$date_day   = get_the_date( 'd' );
$date_month = get_the_date( 'M' );

$blog_posts_date_style  = get_theme_mod( 'blog_posts_date_style', 'info' );
$blog_read_more_enabled = get_theme_mod( 'blog_read_more_enabled', true );
$blog_read_more_label   = get_theme_mod( 'blog_read_more_label', esc_html__( 'Read More', 'rhye' ) );
$post_show_info         = get_theme_mod( 'post_show_info', true );
$post_meta_set          = get_theme_mod( 'post_meta_set', array( 'date', 'categories', 'comments', 'author' ) );

$post_thumbnail_size = get_theme_mod( 'blog_style_posts_thumbnail', 'large' );
$post_heading_preset = get_theme_mod( 'blog_posts_heading_preset', 'h3' );

?>

<article <?php post_class( 'post figure-post' ); ?> id="post-<?php the_ID(); ?>">
	<?php if ( has_post_thumbnail() ) : ?>
		<!-- post media -->
		<div class="figure-post__media">
			<a href="<?php the_permalink(); ?>" data-pjax-link="flyingImage" aria-labelledby="post-<?php the_ID(); ?>-title">
				<div class="js-transition-img overflow">
					<?php echo wp_get_attachment_image( get_post_thumbnail_id(), $post_thumbnail_size, false, array( 'class' => 'js-transition-img__transformed-el' ) ); ?>
				</div>
				<?php if ( $post_show_info && in_array( 'date', $post_meta_set ) && $blog_posts_date_style === 'square_box' ) : ?>
					<!-- post date -->
					<time class="figure-post__date" datetime="<?php echo esc_html( $date_full ); ?>">
						<span class="figure-post__date-day h3"><?php echo esc_html( $date_day ); ?></span>
						<span class="figure-post__date-month"><?php echo esc_html( $date_month ); ?></span>
					</time>
					<!-- - post date -->
				<?php endif; ?>
			</a>
		</div>
		<!-- - post media -->
	<?php endif; ?>

	<?php if ( $post_show_info ) : ?>
		<!-- post info -->
		<div class="figure-post__wrapper-info mt-2 subheading">
			<?php get_template_part( 'template-parts/blog/post/partials/post_info' ); ?>
		</div>
		<!-- - post info -->
	<?php endif; ?>

	<!-- post header -->
	<div class="figure-post__header mt-1">
		<a href="<?php the_permalink(); ?>" data-pjax-link="flyingImage" class="figure-post__link"><h2 class="<?php echo esc_attr( $post_heading_preset ); ?> mt-0 mb-0" id="post-<?php the_ID(); ?>-title"><?php the_title(); ?></h2></a>
	</div>
	<!-- - post header-->

	<!-- post content -->
	<div class="figure-post__content mt-1">
		<p><?php get_template_part( 'template-parts/blog/post/content/content', get_post_format() ); ?></p>
	</div>
	<!-- - post content -->

	<?php if ( $blog_read_more_enabled && ! empty( $blog_read_more_label ) ) : ?>
		<!-- more button -->
		<div class="figure-post__wrapper-readmore mt-1 mt-md-2 pb-0-5">
			<?php get_template_part( 'template-parts/blog/post/partials/post_read_more' ); ?>
		</div>
		<!-- - more button -->
	<?php endif; ?>
</article>
