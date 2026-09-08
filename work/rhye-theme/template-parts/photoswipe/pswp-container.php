<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$galleries_captions_enabled          = get_theme_mod( 'galleries_captions_enabled', true );
$galleries_zoom_enabled              = get_theme_mod( 'galleries_zoom_enabled', true );
$galleries_close_on_scroll_enabled   = get_theme_mod( 'galleries_close_on_scroll_enabled', true );
$galleries_arrows_enabled            = get_theme_mod( 'galleries_arrows_enabled', true );
$galleries_counter_enabled           = get_theme_mod( 'galleries_counter_enabled', true );
$galleries_fullscreen_button_enabled = get_theme_mod( 'galleries_fullscreen_button_enabled', true );
$galleries_close_button_enabled      = get_theme_mod( 'galleries_close_button_enabled', true );

$attrs_cursor = arts_get_element_cursor_attributes(
	array(
		'enabled'     => get_theme_mod( 'cursor_gallery_buttons_enabled', true ),
		'scale'       => 1.2,
		'magnetic'    => true,
		'hide_native' => true,
	)
);

$class_container = 'pswp';

if ( ! $galleries_zoom_enabled ) {
	$class_container .= ' pswp--zoom-disabled';
}

if ( $galleries_close_on_scroll_enabled ) {
	$class_container .= ' pswp--close-on-scroll';
}

?>
<!-- PSWP Container -->
<div class="<?php echo esc_attr( trim( $class_container ) ); ?>" tabindex="-1" role="dialog" aria-hidden="true" data-arts-theme-text="light">
	<!-- background -->
	<div class="pswp__bg"></div>
	<!-- - background -->

	<!-- slider wrapper -->
	<div class="pswp__scroll-wrap">
		<!-- slides holder (don't modify)-->
		<div class="pswp__container">
			<div class="pswp__item">
				<div class="pswp__img pswp__img--placeholder"></div>
			</div>
			<div class="pswp__item"></div>
			<div class="pswp__item"></div>
		</div>
		<!-- - slides holder (don't modify)-->

		<!-- UI -->
		<div class="pswp__ui pswp__ui--hidden">
			<!-- top bar -->
			<div class="pswp__top-bar">
				<?php if ( $galleries_counter_enabled ) : ?>
					<div class="pswp__counter"></div>
				<?php endif; ?>

				<?php if ( $galleries_close_button_enabled ) : ?>
					<button class="pswp__button pswp__button--close" title="<?php esc_attr_e( 'Close (Esc)', 'rhye' ); ?>" <?php echo esc_attr( $attrs_cursor ); ?>></button>
				<?php endif; ?>

				<?php if ( $galleries_fullscreen_button_enabled ) : ?>
					<button class="pswp__button pswp__button--fs" title="<?php esc_attr_e( 'Toggle Fullscreen', 'rhye' ); ?>" <?php echo esc_attr( $attrs_cursor ); ?>></button>
				<?php endif; ?>
				<!-- preloader -->
				<div class="pswp__preloader">
					<div class="pswp__preloader__icn">
						<div class="pswp__preloader__cut">
							<div class="pswp__preloader__donut"></div>
						</div>
					</div>
				</div>
				<!-- - preloader -->
			</div>
			<!-- - top bar -->

			<?php if ( $galleries_arrows_enabled ) : ?>
				<!-- left arrow -->
				<div class="pswp__button pswp__arrow pswp__button--arrow--left">
					<?php
						arts_the_arrow(
							array(
								'direction' => 'left',
							)
						);
					?>
				</div>
				<!-- - left arrow -->

				<!-- right arrow -->
				<div class="pswp__button pswp__arrow pswp__button--arrow--right">
					<?php
						arts_the_arrow(
							array(
								'direction' => 'right',
							)
						);
					?>
				</div>
				<!-- - right arrow -->
			<?php endif; ?>

			<?php if ( $galleries_captions_enabled ) : ?>
				<!-- slide caption holder (don't modify) -->
				<div class="pswp__caption">
					<div class="pswp__caption__center text-center"></div>
				</div>
				<!-- - slide caption holder (don't modify) -->
			<?php endif; ?>
		</div>
		<!-- - UI -->

	</div>
	<!-- - slider wrapper -->
</div>
<!-- - PSWP Container -->
