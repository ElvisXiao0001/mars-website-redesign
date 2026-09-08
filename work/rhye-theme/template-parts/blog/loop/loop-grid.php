<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$blog_grid_columns       = get_theme_mod( 'blog_grid_columns', 4 );
$blog_grid_space_between = get_theme_mod( 'blog_grid_space_between', 3 );
$blog_grid_fancy_enabled = get_theme_mod( 'blog_grid_fancy_enabled', true );
$grid_class              = '';
$grid_item_class         = '';

switch ( $blog_grid_columns ) {
	case 1:
		$grid_item_class = 'grid__item_desktop-12 grid__item_tablet-12 grid__item_mobile-12';
		break;
	case 2:
		$grid_item_class = 'grid__item_desktop-6 grid__item_tablet-6 grid__item_mobile-12';
		break;
	case 3:
		$grid_item_class = 'grid__item_desktop-4 grid__item_tablet-6 grid__item_mobile-12';
		break;
	case 4:
		$grid_item_class = 'grid__item_desktop-3 grid__item_tablet-6 grid__item_mobile-12';
		break;
	case 5:
		$grid_item_class = 'grid__item_desktop-2dot4 grid__item_tablet-6 grid__item_mobile-12';
		break;
	case 6:
		$grid_item_class = 'grid__item_desktop-2 grid__item_tablet-6 grid__item_mobile-12';
		break;
	default:
		$grid_item_class = 'grid__item_desktop-6 grid__item_tablet-6 grid__item_mobile-12';
		break;
}

if ( $blog_grid_fancy_enabled ) {
	$grid_item_class .= " grid__item_fluid-{$blog_grid_space_between}-fancy";
}

?>


<div class="js-grid grid grid_fluid-<?php echo esc_attr( $blog_grid_space_between ); ?>">
	<div class="js-grid__sizer grid__item grid__sizer grid__item_fluid-<?php echo esc_attr( $blog_grid_space_between ); ?> <?php echo esc_attr( $grid_item_class ); ?>"></div>
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<?php
			$categories       = get_the_category();
			$categories_slugs = '';
		if ( ! empty( $categories ) ) {
			foreach ( $categories as $category ) {
				$categories_slugs .= ' category-' . $category->slug;
			}
		}
		?>
		<div class="js-grid__item grid__item grid__item_fluid-<?php echo esc_attr( $blog_grid_space_between ); ?> <?php echo esc_attr( trim( $grid_item_class ) ); ?> <?php echo esc_attr( trim( $categories_slugs ) ); ?>">
			<div class="section-grid__item">
				<?php get_template_part( 'template-parts/blog/post/post' ); ?>
			</div>
		</div>
	<?php endwhile; ?>
</div>
