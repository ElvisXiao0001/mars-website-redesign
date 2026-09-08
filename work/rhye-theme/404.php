<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$page_404_theme      = get_theme_mod( '404_theme', 'bg-white' );
$page_404_main_theme = get_theme_mod( '404_main_theme', 'dark' );

$page_404_title      = get_theme_mod( '404_title', esc_html__( '404 Error', 'rhye' ) );
$page_404_subheading = get_theme_mod( '404_message', esc_html__( 'It looks like nothing found here. Try to navigate the menu or return to the home page.', 'rhye' ) );

$page_404_button_label = get_theme_mod( '404_button_label', esc_html__( 'Back to homepage', 'rhye' ) );
$page_404_button_style = get_theme_mod( '404_button_style', 'button_bordered' );
$page_404_button_theme = get_theme_mod( '404_button_theme', 'bg-dark-1' );

?>

<?php get_header(); ?>

<!-- section 404 -->
<section class="section section-404 section-content section-fullheight overflow <?php echo esc_attr( $page_404_theme ); ?>" data-arts-theme-text="<?php echo esc_attr( $page_404_main_theme ); ?>" data-arts-os-animation="true">
	<div class="section-fullheight__inner section__content section pt-medium pb-medium">
		<div class="container">
			<div class="row justify-content-center text-center">
				<div class="col-lg-8">
					<div class="section-content__heading split-text js-split-text" data-split-text-type="lines" data-split-text-set="lines">
						<h1 class="h1"><?php echo esc_html( $page_404_title ); ?></h1>
					</div>
					<div class="w-100"></div>
					<div class="section-content__text split-text js-split-text mt-1" data-split-text-type="lines" data-split-text-set="lines">
						<p><?php echo esc_html( $page_404_subheading ); ?></p>
					</div>
					<div class="w-100"></div>
					<div class="section-content__button mt-2 mt-md-3">
						<a class="button <?php echo esc_attr( $page_404_button_style ); ?> <?php echo esc_attr( $page_404_button_theme ); ?>" data-hover="<?php echo esc_html( $page_404_button_label ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>">
							<span class="button__label-hover"><?php echo esc_html( $page_404_button_label ); ?></span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="section__circle"></div>
</section>
<!-- - section 404 -->

<?php get_footer(); ?>
