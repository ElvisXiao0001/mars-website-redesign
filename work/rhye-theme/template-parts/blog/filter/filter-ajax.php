<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

$blog_grid_filter_all_label = get_theme_mod( 'blog_grid_filter_all_label', esc_html__( 'All Posts', 'rhye' ) );
$posts_categories           = Utilities::get_posts_categories( 'all' );
$class_all                  = is_home() ? 'filter__item_active' : '';

?>

<?php if ( ! empty( $posts_categories ) ) : ?>
	<div class="filter js-filter js-grid-ajax__filter">
		<div class="filter__inner">
			<div class="container-fluid no-gutters">
				<!-- items -->
				<div class="row flex-column flex-lg-row justify-content-center">
					<?php if ( ! empty( $blog_grid_filter_all_label ) ) : ?>
					<!-- posts home -->
					<a class="col-auto filter__item js-filter__item <?php echo esc_attr( $class_all ); ?>" href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ); ?>">
						<div class="filter__item-inner">
						<div class="split-text js-split-text" data-split-text-type="lines,words" data-split-text-set="words"><?php echo esc_html( $blog_grid_filter_all_label ); ?></div>
						</div>
					</a>
					<!-- - posts home -->
					<?php endif; ?>
					<?php foreach ( $posts_categories as $category ) : ?>
						<?php
						$category_name = get_cat_ID( $category['name'] );
						$category_link = get_category_link( $category_name );
						$class_item    = is_category( $category_name ) ? 'filter__item_active' : '';
						?>
						<a href="<?php echo esc_url( $category_link ); ?>" class="col-auto filter__item js-filter__item <?php echo esc_attr( $class_item ); ?>">
							<div class="filter__item-inner">
							<div class="split-text js-split-text" data-split-text-type="lines,words" data-split-text-set="words"><?php echo esc_html( $category['name'] ); ?></div>
							</div>
						</a>
					<?php endforeach; ?>
				</div>
				<!-- - items -->
				<!-- underline -->
				<div class="filter__underline js-filter__underline"></div>
				<!-- - underline -->
			</div>
		</div>
	</div>
<?php endif; ?>
