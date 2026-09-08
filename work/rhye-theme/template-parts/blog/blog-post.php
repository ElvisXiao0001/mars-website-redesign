<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$sidebar_position   = get_theme_mod( 'sidebar_position', 'right_side' );
$is_active_sidebar  = is_active_sidebar( 'blog-sidebar' );
$class_section      = '';
$class_wrapper_post = '';

$blog_container                    = get_theme_mod( 'blog_container', 'container' );
$blog_style_single_post_background = get_theme_mod( 'blog_style_single_post_background', '' );
$blog_post_image_layout            = get_theme_mod( 'post_image_layout', 'normal' );

$posts_col_class   = 'col-lg-8 order-lg-1';
$sidebar_col_class = 'col-lg-3 order-lg-2';

if ( $sidebar_position == 'left_side' ) {
	$posts_col_class   = 'col-lg-8 order-lg-2';
	$sidebar_col_class = 'col-lg-3 order-lg-1';
}

if ( $is_active_sidebar ) {
	$blog_row_class = 'row justify-content-between';
} else {
	$blog_row_class  = 'row justify-content-center';
	$posts_col_class = 'col-12 d-flex justify-content-center';
}

if ( ! empty( $blog_style_single_post_background ) ) {
	$class_wrapper_post .= ' ' . $blog_style_single_post_background;
	$posts_col_class    .= ' ' . 'no-gutters';

	if ( $blog_post_image_layout !== 'normal' ) {
		$class_section .= ' section-blog_post-has-background';
	}

	if ( $is_active_sidebar ) {

		if ( $blog_container === 'container' ) {
			$class_wrapper_post .= ' p-xsmall';
		} else {
			$class_wrapper_post .= ' p-small';
		}
	} else {
		$class_wrapper_post .= ' p-small';
	}
}

?>

<section class="section section-blog py-medium <?php echo esc_attr( trim( $class_section ) ); ?>">
	<div class="<?php echo esc_attr( trim( $blog_container ) ); ?>">
		<div class="<?php echo esc_attr( trim( $blog_row_class ) ); ?>">
			<div class="section-blog__col-post <?php echo esc_attr( trim( $posts_col_class ) ); ?>">
				<div class="section-blog__wrapper-post <?php echo esc_attr( trim( $class_wrapper_post ) ); ?>">
					<?php the_post(); ?>
					<?php get_template_part( 'template-parts/blog/post/post', 'single' ); ?>
				</div>
			</div>
			<?php if ( $is_active_sidebar ) : ?>
				<!-- sidebar -->
				<div class="<?php echo esc_attr( trim( $sidebar_col_class ) ); ?>">
					<div class="section-blog__sidebar">
						<?php get_sidebar(); ?>
					</div>
				</div>
				<!-- - sidebar -->
			<?php endif; ?>
		</div>
	</div>
</section>
