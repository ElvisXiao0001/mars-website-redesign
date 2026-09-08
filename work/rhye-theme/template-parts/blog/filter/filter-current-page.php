<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

$blog_grid_filter_all_label = get_theme_mod( 'blog_grid_filter_all_label', esc_html__( 'All Posts', 'rhye' ) );
$posts_categories           = Utilities::get_posts_categories( 'current_page' );

?>

<?php if ( ! empty( $posts_categories ) ) : ?>
	<div class="filter js-filter">
		<div class="filter__inner">
			<div class="container-fluid no-gutters">
				<!-- items -->
				<div class="row flex-column flex-lg-row justify-content-center">
					<?php if ( ! empty( $blog_grid_filter_all_label ) ) : ?>
						<!-- all (*) -->
						<div class="col-auto filter__item filter__item_active js-filter__item" data-filter="*">
							<div class="filter__item-inner">
								<div class="split-text js-split-text" data-split-text-type="lines,words" data-split-text-set="words"><?php echo esc_html( $blog_grid_filter_all_label ); ?></div>
							</div>
						</div>
						<!-- - all (*) -->
					<?php endif; ?>
					<?php foreach ( $posts_categories as $category ) : ?>
						<div class="col-auto filter__item js-filter__item" data-filter=".category-<?php echo esc_attr( $category['slug'] ); ?>">
							<div class="filter__item-inner">
								<div class="split-text js-split-text" data-split-text-type="lines,words" data-split-text-set="words"><?php echo esc_html( $category['name'] ); ?></div>
							</div>
						</div>
					<?php endforeach; ?>
					<!-- - items -->
				</div>
				<!-- underline -->
				<div class="filter__underline js-filter__underline"></div>
				<!-- - underline -->
			</div>
		</div>
	</div>
<?php endif; ?>
