<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

$is_elementor_page           = Utilities::is_built_with_elementor();
$static_page_gutters_enabled = get_theme_mod( 'static_page_gutters_enabled', true );
$footer_has_upper_section    = arts_footer_has_active_sidebars( 'upper' );
$footer_has_lower_section    = arts_footer_has_active_sidebars( 'lower' );
$class_container             = 'container-fluid_paddings';
$masthead_template           = '';
$post_type                   = get_post_type();

$portfolio_nav_enabled    = get_theme_mod( 'portfolio_nav_enabled', true );
$portfolio_nav_style      = get_theme_mod( 'portfolio_nav_style', 'portfolio-auto-scroll' );
$portfolio_nav_post_types = get_theme_mod( 'portfolio_nav_post_types', array( 'arts_portfolio_item' ) );
$has_bottom_navigation    = $is_elementor_page && $portfolio_nav_enabled && in_array( $post_type, $portfolio_nav_post_types );

if ( $is_elementor_page ) {
	$masthead_template = 'elementor-' . Utilities::get_document_option( 'page_masthead_layout' );
}

if ( $footer_has_upper_section || $footer_has_lower_section ) {
	$class_container = '';
}

get_header();

/**
 * Page Masthead
 */
get_template_part( 'template-parts/masthead/masthead', esc_attr( $masthead_template ) );
the_post();
?>

<?php if ( ! $is_elementor_page ) : ?>
	<?php if ( $static_page_gutters_enabled ) : ?>
		<div class="container-fluid pt-medium pb-medium container_p-xs-0 bg-light-1 <?php echo esc_attr( $class_container ); ?>">
	<?php endif; ?>
		<section class="section section-blog pt-medium pb-medium bg-white">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-lg-10 section-blog__post">
						<div class="post">
							<!-- post content -->
							<div class="post__content clearfix">
								<?php the_content(); ?>
							</div>
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
							<!-- - post content -->
							<?php if ( comments_open() || get_comments_number() ) : ?>
								<!-- post comments -->
								<div class="post__comments mt-small" data-barba-prevent="all">
									<?php comments_template(); ?>
								</div>
								<!-- - post comments -->
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php if ( $static_page_gutters_enabled ) : ?>
		</div>
	<?php endif; ?>
<?php else : ?>
	<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single-page' ) ) : // Elementor "page" location ?>
		<?php the_content(); ?>
	<?php endif; ?>
<?php endif; ?>

<?php

/**
 * Page Bottom Navigation
 */
if ( $has_bottom_navigation ) {
	get_template_part( 'template-parts/nav/nav', esc_attr( $portfolio_nav_style ) );
}

get_footer();
