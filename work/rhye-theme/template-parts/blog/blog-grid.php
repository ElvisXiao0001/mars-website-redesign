<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$sidebar_position  = get_theme_mod( 'sidebar_position', 'right_side' );
$is_active_sidebar = is_active_sidebar( 'blog-sidebar' );

$blog_grid_filter_enabled   = get_theme_mod( 'blog_grid_filter_enabled', true );
$blog_grid_filter_mode      = get_theme_mod( 'blog_grid_filter_mode', 'current_page' );
$blog_container             = get_theme_mod( 'blog_container', 'container-fluid' );
$blog_row_class             = 'row justify-content-between';
$blog_os_animations_enabled = get_theme_mod( 'blog_os_animations_enabled', false );
$blog_grid_columns          = get_theme_mod( 'blog_grid_columns', 4 );

$posts_col_class   = 'col-lg-8 order-lg-1';
$sidebar_col_class = 'col-lg-3 order-lg-2';
$class_section     = get_theme_mod( 'blog_style_theme' );
$attrs_section     = '';
$class_pagination  = '';
$theme_section     = get_theme_mod( 'blog_style_main_theme', 'dark' );

switch ( $blog_grid_columns ) {
	case 1:
		$attrs_section .= ' data-grid-columns=1 data-grid-columns-tablet=1 data-grid-columns-mobile=1';
		break;
	case 2:
		$attrs_section .= ' data-grid-columns=2 data-grid-columns-tablet=2 data-grid-columns-mobile=1';
		break;
	case 3:
		$attrs_section .= ' data-grid-columns=3 data-grid-columns-tablet=2 data-grid-columns-mobile=1';
		break;
	case 4:
		$attrs_section .= ' data-grid-columns=4 data-grid-columns-tablet=2 data-grid-columns-mobile=1';
		break;
	case 5:
		$attrs_section .= ' data-grid-columns=5 data-grid-columns-tablet=2 data-grid-columns-mobile=1';
		break;
	case 6:
		$attrs_section .= ' data-grid-columns=6 data-grid-columns-tablet=2 data-grid-columns-mobile=1';
		break;
	default:
		$attrs_section .= ' data-grid-columns=2 data-grid-columns-tablet=2 data-grid-columns-mobile=1';
		break;
}

if ( $is_active_sidebar ) {
	if ( $sidebar_position == 'left_side' ) {
		$posts_col_class   = 'col-lg-8 order-lg-2';
		$sidebar_col_class = 'col-lg-3 order-lg-1';
	}
} else {
	$blog_row_class = 'row justify-content-center';
	if ( $blog_container === 'container-fluid' ) {
		$posts_col_class = 'col-12';
	}
}

if ( $blog_grid_filter_enabled ) {
	$class_section .= ' pt-small pb-medium';
	if ( $blog_grid_filter_mode === 'all' ) {
		$class_section           .= ' js-ajax-filter';
		$class_pagination        .= ' js-grid-ajax__pagination';
		$blog_grid_filter_enabled = is_archive() && ! is_category() ? false : true;
	} else {
		$class_pagination .= ' js-ajax-pagination';
		// Filter is not needed on a single category or archive pages
		// with only one category itself in "Current Page" mode
		$blog_grid_filter_enabled = is_category() ? false : true;
	}
} else {
	$class_section .= ' py-medium';
}

if ( ! wp_doing_ajax() && $blog_os_animations_enabled ) {
	$attrs_section .= ' data-arts-os-animation=true';
}

?>

<section class="section section-blog section-blog_grid section-grid section-content overflow <?php echo esc_attr( trim( $class_section ) ); ?>" data-arts-theme-text="<?php echo esc_attr( $theme_section ); ?>" <?php echo esc_attr( trim( $attrs_section ) ); ?>>
	<div class="section-blog__container <?php echo esc_attr( $blog_container ); ?>">
		<?php if ( $blog_grid_filter_enabled ) : ?>
			<!-- filter -->
			<div class="row pb-small">
				<div class="col-12">
					<?php if ( $blog_grid_filter_mode === 'current_page' ) : ?>
						<?php get_template_part( 'template-parts/blog/filter/filter', 'current-page' ); ?>
					<?php else : ?>
						<?php get_template_part( 'template-parts/blog/filter/filter', 'ajax' ); ?>
					<?php endif; ?>
				</div>
			</div>
			<!-- - filter -->
		<?php endif; ?>
		<div class="<?php echo esc_attr( trim( $blog_row_class ) ); ?>">
			<div class="section-blog__posts <?php echo esc_attr( trim( $posts_col_class ) ); ?>">
				<?php if ( have_posts() ) : ?>
					<!-- posts -->
					<?php get_template_part( 'template-parts/blog/loop/loop', 'grid' ); ?>
					<!-- - posts -->
				<?php else : ?>
					<?php get_template_part( 'template-parts/blog/post/content/content', 'none' ); ?>
				<?php endif; ?>
				<?php if ( get_the_posts_pagination() ) : ?>
					<!-- pagination -->
					<div class="section-blog__wrapper-pagination <?php echo esc_attr( trim( $class_pagination ) ); ?>">
						<?php arts_posts_pagination(); ?>
					</div>
					<!-- - pagination -->
				<?php endif; ?>
			</div>
			<?php if ( $is_active_sidebar ) : ?>
				<!-- sidebar -->
				<div class="section-blog__sidebar <?php echo esc_attr( trim( $sidebar_col_class ) ); ?>">
					<?php get_sidebar(); ?>
				</div>
				<!-- - sidebar -->
			<?php endif; ?>
		</div>
	</div>
</section>
