<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

add_action( 'elementor/page_templates/canvas/before_content', 'arts_elementor_canvas_before_content' );
if ( ! function_exists( 'arts_elementor_canvas_before_content' ) ) {
	/**
	 * Add extra markup for Elementor Canvas template: BEFORE
	 *
	 * @return void
	 */
	function arts_elementor_canvas_before_content() {
		$preloader_enabled                                   = arts_is_preloader_enabled();
		$ajax_enabled                                        = get_theme_mod( 'ajax_enabled', false );
		$ajax_spinner_desktop_enabled                        = get_theme_mod( 'ajax_spinner_desktop_enabled', false );
		$ajax_spinner_mobile_enabled                         = get_theme_mod( 'ajax_spinner_mobile_enabled', true );
		$cursor_enabled                                      = get_theme_mod( 'cursor_enabled', false );
		$smooth_scroll                                       = arts_is_smooth_scroll();
		$smooth_scroll_elementor_canvas_template_enabled     = get_theme_mod( 'smooth_scroll_elementor_canvas_template_enabled', true );
		$page_color_theme_curtain                            = Utilities::get_document_option( 'page_masthead_background' );
		$page_curtain_color                                  = get_theme_mod( esc_attr( $page_color_theme_curtain ), '#ffffff' );
		$class_container                                     = '';
		$attrs_container                                     = 'data-barba-namespace=elementor';
		$theme_container                                     = 'dark';
		$has_hfe_header                                      = arts_hfe_header_enabled();
		$elementor_header_footer_builder_header_render_place = get_theme_mod( 'elementor_header_footer_builder_header_render_place', 'outside' );
		$elementor_header_footer_builder_header_wrapper_enabled = get_theme_mod( 'elementor_header_footer_builder_header_wrapper_enabled', true );
		$hfe_print_header_in_canvas_template                    = true;

		if ( function_exists( 'get_hfe_header_id' ) ) {
			$hfe_print_header_in_canvas_template = get_post_meta( get_hfe_header_id(), 'display-on-canvas-template', true );
		}

		if ( $ajax_enabled ) {
			$attrs_container .= ' data-barba=container';
		}

		if ( $smooth_scroll_elementor_canvas_template_enabled ) {
			if ( $smooth_scroll === 'lenis' ) {
				$class_container = 'js-smooth-scroll-lenis';
			} elseif ( $smooth_scroll === 'smooth-scrollbar' ) {
				$class_container = 'js-smooth-scroll';
			}
		}
		?>
		<?php if ( $ajax_enabled ) : ?>
			<div data-barba="wrapper">
		<?php endif; ?>

		<?php if ( $preloader_enabled ) : ?>
		<!-- PAGE PRELOADER -->
			<?php get_template_part( 'template-parts/preloader/preloader' ); ?>
		<!-- - PAGE PRELOADER -->
		<?php endif; ?>

		<?php if ( $ajax_spinner_desktop_enabled || $ajax_spinner_mobile_enabled ) : ?>
		<!-- Loading Spinner -->
			<?php get_template_part( 'template-parts/spinner/spinner' ); ?>
		<!-- - Loading Spinner -->
		<?php endif; ?>

		<!-- TRANSITION CURTAINS -->
		<?php get_template_part( 'template-parts/curtains/curtains' ); ?>
		<!-- - TRANSITION CURTAINS -->

		<?php if ( $cursor_enabled || $preloader_enabled ) : ?>
		<!-- Cursor Follower-->
			<?php get_template_part( 'template-parts/cursor/cursor' ); ?>
		<!-- - Cursor Follower-->
		<?php endif; ?>

		<?php if ( $ajax_enabled ) : ?>
			<?php if ( $has_hfe_header ) : ?>
				<?php if ( $elementor_header_footer_builder_header_render_place === 'outside' && $elementor_header_footer_builder_header_wrapper_enabled ) : ?>
					<?php arts_hfe_render_header(); // print hidden header in AJAX mode ?>
				<?php endif; ?>
			<?php else : ?>
				<!-- PAGE HEADER -->
				<?php get_template_part( 'template-parts/header/header' ); ?>
				<!-- - PAGE HEADER -->
			<?php endif; ?>
		<?php else : ?>
			<?php if ( $has_hfe_header && $hfe_print_header_in_canvas_template ) : ?>
				<?php if ( $elementor_header_footer_builder_header_render_place === 'outside' ) : ?>
					<?php arts_hfe_render_header(); ?>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>

		<div class="<?php echo esc_attr( trim( $class_container ) ); ?>" id="page-wrapper" data-arts-theme-text="<?php echo esc_attr( $theme_container ); ?>" <?php echo esc_attr( trim( $attrs_container ) ); ?>>
			<main class="page-wrapper__content">
				<?php if ( $has_hfe_header && $hfe_print_header_in_canvas_template && $elementor_header_footer_builder_header_render_place === 'inside' ) : ?>
					<?php arts_hfe_render_header(); ?>
				<?php endif; ?>
				<!-- Element to set transition background -->
				<section class="section section-masthead d-none" data-background-color="<?php echo esc_attr( $page_curtain_color ); ?>"></section>
				<!-- - Element to set transition background -->
		<?php
	}
}

add_action( 'elementor/page_templates/canvas/after_content', 'arts_elementor_canvas_after_content' );
if ( ! function_exists( 'arts_elementor_canvas_after_content' ) ) {
	/**
	 * Add extra markup for Elementor Canvas template: AFTER
	 *
	 * @return void
	 */
	function arts_elementor_canvas_after_content() {
		$ajax_enabled                        = get_theme_mod( 'ajax_enabled', false );
		$hfe_print_footer_in_canvas_template = true;

		if ( function_exists( 'get_hfe_footer_id' ) ) {
			$hfe_print_footer_in_canvas_template = get_post_meta( get_hfe_footer_id(), 'display-on-canvas-template', true );
		}
		?>
		<?php if ( function_exists( 'hfe_render_footer' ) && arts_hfe_footer_enabled() && $hfe_print_footer_in_canvas_template ) : ?>
				<?php arts_hfe_render_footer(); ?>
			<?php endif; ?>
			</main>
		</div>
			<?php if ( $ajax_enabled ) : ?>
			</div>
		<?php endif; ?>
			<?php
	}
}
