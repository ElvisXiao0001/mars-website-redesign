<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$sidebar_position  = get_theme_mod( 'sidebar_position', 'right_side' );
$is_active_sidebar = is_active_sidebar( 'blog-sidebar' );

$blog_container             = get_theme_mod( 'blog_container', 'container' );
$blog_os_animations_enabled = get_theme_mod( 'blog_os_animations_enabled', false );
$attrs_section              = '';

$posts_col_class   = 'col-lg-8 order-lg-1';
$sidebar_col_class = 'col-lg-3 order-lg-2';

if ( $sidebar_position == 'left_side' ) {
	$posts_col_class   = 'col-lg-8 order-lg-2';
	$sidebar_col_class = 'col-lg-3 order-lg-1';
}

if ( $is_active_sidebar ) {
	$blog_row_class = 'row justify-content-between';
} else {
	$blog_row_class = 'row justify-content-center';
}

if ( ! wp_doing_ajax() && $blog_os_animations_enabled ) {
	$attrs_section .= ' data-arts-os-animation=true';
}

?>

<section class="section section-blog section-grid py-medium" <?php echo esc_attr( trim( $attrs_section ) ); ?>>
	<div class="section-blog__container <?php echo esc_attr( $blog_container ); ?>">
		<div class="<?php echo esc_attr( trim( $blog_row_class ) ); ?>">
			<div class="section-blog__posts <?php echo esc_attr( trim( $posts_col_class ) ); ?>">
				<?php if ( have_posts() ) : ?>
					<!-- posts -->
					<?php get_template_part( 'template-parts/blog/loop/loop', 'list' ); ?>
					<!-- - posts -->
				<?php else : ?>
					<?php get_template_part( 'template-parts/blog/post/content/content', 'none' ); ?>
				<?php endif; ?>
				<?php if ( get_the_posts_pagination() ) : ?>
					<!-- pagination -->
					<div class="section-blog__wrapper-pagination">
						<?php arts_posts_pagination(); ?>
					</div>
					<!-- - pagination -->
				<?php endif; ?>
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

