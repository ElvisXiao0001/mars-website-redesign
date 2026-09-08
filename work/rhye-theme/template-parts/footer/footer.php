<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

$classes_upper_column          = arts_get_footer_columns( get_theme_mod( 'footer_layout_upper', '12' ) );
$class_upper_area              = 'pt-md-4 pt-sm-3 pt-2 pb-md-2 pb-sm-1 pb-0';
$class_lower_area              = 'pb-sm-1 pb-0';
$class_container               = get_theme_mod( 'footer_container', 'container' );
$class_footer                  = 'footer';
$footer_sidebar_upper_large_id = 'footer-sidebar-upper-large';
$footer_theme                  = Utilities::get_overridden_document_option( 'footer_theme', 'page_footer_settings_overridden', 'bg-light-1' );
$footer_main_theme             = Utilities::get_overridden_document_option( 'footer_main_theme', 'page_footer_settings_overridden', 'dark' );
$footer_logo                   = Utilities::get_overridden_document_option( 'footer_main_logo', 'page_footer_settings_overridden', 'primary' );
$attrs_footer                  = '';

if ( $footer_theme ) {
	$class_footer .= ' ' . $footer_theme;
}

if ( $footer_main_theme ) {
	$attrs_footer .= ' data-arts-theme-text=' . $footer_main_theme;
}

if ( $footer_logo ) {
	$attrs_footer .= ' data-arts-footer-logo=' . $footer_logo;
}

$has_upper_large_section     = is_active_sidebar( $footer_sidebar_upper_large_id );
$has_upper_section           = arts_footer_has_active_sidebars( 'upper' );
$has_lower_section           = arts_footer_has_active_sidebars( 'lower' );
$footer_border_enabled_upper = Utilities::get_overridden_document_option( 'footer_border_enabled_upper', 'page_footer_settings_overridden', true );
$footer_border_enabled_lower = Utilities::get_overridden_document_option( 'footer_border_enabled_lower', 'page_footer_settings_overridden', true );
$post_prev_next_nav_enabled  = get_theme_mod( 'post_prev_next_nav_enabled', true );

if ( $has_upper_section && ! $has_lower_section ) {
	$class_upper_area = 'pt-md-2 pt-sm-2 pt-2 pb-md-3 pb-sm-1 pb-0';
}

if ( $footer_border_enabled_upper ) {
	$class_upper_area .= ' footer__area-border-top';
}

if ( $footer_border_enabled_lower ) {
	$class_lower_area = 'pt-sm-2 pb-sm-0-5 pt-2 pb-0 footer__area-border-top';
}

?>

<footer class="<?php echo esc_attr( trim( $class_footer ) ); ?>" id="page-footer" <?php echo esc_attr( trim( $attrs_footer ) ); ?>>
	<div class="footer__container <?php echo esc_attr( $class_container ); ?>">
		<?php if ( is_singular( 'post' ) && $post_prev_next_nav_enabled ) : ?>
			<!-- prev & next posts navigation -->
			<?php get_template_part( 'template-parts/blog/post/partials/post_prev_next' ); ?>
			<!-- - prev & next posts navigation -->
		<?php endif; ?>
		<?php if ( $has_upper_section ) : ?>
			<!-- widgets upper area -->
			<div class="footer__area footer__area_upper <?php echo esc_attr( $class_upper_area ); ?>">
				<div class="row">
					<?php if ( $has_upper_large_section ) : ?>
						<!-- large column -->
						<div class="<?php echo esc_attr( $classes_upper_column[0] ); ?>">
							<?php dynamic_sidebar( $footer_sidebar_upper_large_id ); ?>
						</div>
						<!-- - large column -->
					<?php endif; ?>
					<div class="<?php echo esc_attr( $classes_upper_column[1] ); ?>">
						<div class="row justify-content-lg-between">
							<?php arts_render_footer_widgets( 'upper', 4 ); ?>
						</div>
					</div>
				</div>
			</div>
			<!-- - widgets upper area -->
			<?php endif; ?>

			<?php if ( $has_lower_section ) : ?>
				<!-- widgets lower area -->
				<div class="footer__area footer__area_lower <?php echo esc_attr( $class_lower_area ); ?>">
					<div class="row align-items-center">
						<?php arts_render_footer_widgets( 'lower', 3 ); ?>
					</div>
				</div>
				<!-- - widgets lower area -->
			<?php endif; ?>
		</div>
</footer>
