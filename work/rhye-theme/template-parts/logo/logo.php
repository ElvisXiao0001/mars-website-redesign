<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$title                 = get_bloginfo( 'name' );
$tagline               = get_bloginfo( 'description' );
$display_title_tagline = get_theme_mod( 'header_text', true );
$logo_class            = 'logo ';
$logo_wrapper_class    = 'logo__wrapper-img ';
$has_custom_logo       = has_custom_logo();

if ( ! $display_title_tagline ) {
	$logo_wrapper_class .= 'logo__wrapper-img_no-margin ';
}

?>

<a class="<?php echo esc_attr( $logo_class ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>">
	<?php if ( $has_custom_logo ) : ?>
		<?php
			$logo               = get_theme_mod( 'custom_logo' );
			$logo_url           = wp_get_attachment_url( $logo );
			$logo_secondary_url = get_theme_mod( 'custom_logo_secondary' );
		?>
		<div class="<?php echo esc_attr( trim( $logo_wrapper_class ) ); ?>">
			<img class="logo__img-primary" src="<?php echo esc_attr( $logo_url ); ?>" alt="<?php echo esc_attr( $title ); ?>">
			<?php if ( $logo_secondary_url ) : ?>
				<img class="logo__img-secondary" src="<?php echo esc_attr( $logo_secondary_url ); ?>" alt="<?php echo esc_attr( $title ); ?>">
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<?php if ( ! empty( $title ) && $display_title_tagline ) : ?>
		<div class="logo__text">
			<span class="logo__text-title"><?php echo esc_html( $title ); ?></span>
			<?php if ( ! empty( $tagline ) ) : ?>
				<span class="logo__text-tagline"><?php echo esc_html( $tagline ); ?></span>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</a>
