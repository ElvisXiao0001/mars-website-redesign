<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$preloader_style             = get_theme_mod( 'preloader_style', 'custom_text' );
$preloader_logo              = get_theme_mod( 'preloader_logo', 'primary' );
$preloader_theme             = get_theme_mod( 'preloader_theme', 'light' );
$preloader_background        = get_theme_mod( 'preloader_background', 'bg-dark-2' );
$preloader_heading           = get_theme_mod( 'preloader_heading', esc_html__( 'Loading...', 'rhye' ) );
$preloader_heading_preset    = get_theme_mod( 'preloader_heading_preset', 'h2' );
$preloader_subheading        = get_theme_mod( 'preloader_subheading' );
$preloader_subheading_preset = get_theme_mod( 'preloader_subheading_preset', 'subheading' );
$preloader_image_url         = get_theme_mod( 'preloader_image_url', '' );
$preloader_counter_enabled   = get_theme_mod( 'preloader_counter_enabled', true );
$preloader_counter_preset    = get_theme_mod( 'preloader_counter_preset', 'h5' );
$class_header                = 'my-auto';

if ( $preloader_counter_enabled ) {
	$class_header = 'mt-auto';
}

?>

<div class="preloader text-center <?php echo esc_attr( $preloader_background ); ?>" id="js-preloader" data-arts-theme-text="<?php echo esc_attr( $preloader_theme ); ?>" data-arts-preloader-logo="<?php echo esc_attr( $preloader_logo ); ?>">
	<div class="preloader__content">
		<!-- header -->
		<div class="preloader__header <?php echo esc_attr( $class_header ); ?>">
			<?php if ( $preloader_style === 'logo' ) : ?>
				<?php get_template_part( 'template-parts/logo/logo' ); ?>
			<?php elseif ( $preloader_style == 'custom_text' ) : ?>
				<?php if ( ! empty( $preloader_heading ) ) : ?>
					<div class="preloader__heading <?php echo esc_attr( $preloader_heading_preset ); ?>"><?php echo esc_html( $preloader_heading ); ?></div>
				<?php endif; ?>
				<?php if ( ! empty( $preloader_subheading ) ) : ?>
					<div class="preloader__subline mt-1 <?php echo esc_attr( $preloader_subheading_preset ); ?>"><?php echo esc_html( $preloader_subheading ); ?></div>
				<?php endif; ?>
			<?php elseif ( $preloader_style == 'custom_image' ) : ?>
				<?php if ( ! empty( $preloader_image_url ) ) : ?>
					<img src="<?php echo esc_attr( $preloader_image_url ); ?>" alt />
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<!-- - header -->
		<?php if ( $preloader_counter_enabled ) : ?>
			<!-- counter -->
			<div class="preloader__counter <?php echo esc_attr( $preloader_counter_preset ); ?>">
				<span class="preloader__counter-number preloader__counter-current">0</span>
				<span class="preloader__counter-divider">&nbsp;&nbsp;/&nbsp;&nbsp;</span>
				<span class="preloader__counter-number preloader__counter-total">100</span>
			</div>
			<!-- - counter -->
		<?php endif; ?>
		<!-- circle holder -->
		<div class="preloader__circle"></div>
		<!-- - circle holder -->
	</div>
</div>
