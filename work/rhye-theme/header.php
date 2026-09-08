<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

$preloader_enabled                                   = arts_is_preloader_enabled();
$ajax_enabled                                        = get_theme_mod( 'ajax_enabled', false );
$ajax_spinner_desktop_enabled                        = get_theme_mod( 'ajax_spinner_desktop_enabled', false );
$ajax_spinner_mobile_enabled                         = get_theme_mod( 'ajax_spinner_mobile_enabled', true );
$cursor_enabled                                      = get_theme_mod( 'cursor_enabled', false );
$attrs_container                                     = arts_get_container_attributes();
$has_hfe_header                                      = arts_hfe_header_enabled();
$elementor_header_footer_builder_header_render_place = get_theme_mod( 'elementor_header_footer_builder_header_render_place', 'outside' );

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> >
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<?php
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
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

	<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) : ?>
		<?php if ( $has_hfe_header ) : ?>
			<?php if ( $elementor_header_footer_builder_header_render_place === 'outside' ) : ?>
				<?php arts_hfe_render_header(); ?>
			<?php endif; ?>
		<?php else : ?>
			<!-- PAGE HEADER -->
			<?php get_template_part( 'template-parts/header/header' ); ?>
			<!-- - PAGE HEADER -->
		<?php endif; ?>
	<?php endif; ?>

	<!-- PAGE MAIN -->
	<div class="<?php echo esc_attr( $attrs_container['class'] ); ?>" id="page-wrapper" data-arts-theme-text="<?php echo esc_attr( $attrs_container['theme'] ); ?>" <?php echo esc_attr( trim( $attrs_container['attributes'] ) ); ?>>
		<?php if ( function_exists( 'elementor_theme_do_location' ) ) : ?>
			<?php elementor_theme_do_location( 'popup' ); ?>
		<?php endif; ?>
	  <main class="page-wrapper__content">
			<?php if ( $has_hfe_header && $elementor_header_footer_builder_header_render_place === 'inside' ) : ?>
				<?php arts_hfe_render_header(); ?>
			<?php endif; ?>

			<?php if ( Utilities::get_document_option( 'template' ) === 'elementor_header_footer' ) : ?>
				<?php
					$page_color_theme_curtain = Utilities::get_document_option( 'page_masthead_background' );
					$page_curtain_color       = get_theme_mod( esc_attr( $page_color_theme_curtain ) );
				?>
				<!-- Element to set transition background -->
				<section class="section section-masthead d-none" data-background-color="<?php echo esc_attr( $page_curtain_color ); ?>"></section>
				<!-- - Element to set transition background -->
			<?php endif; ?>
