<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

$blog_style_theme   = get_theme_mod( 'blog_style_theme', 'bg-light-1' );
$page_curtain_color = get_theme_mod( esc_attr( $blog_style_theme ), '#eeece6' );

$class_section = $blog_style_theme . ' pt-large';
$attrs_section = '';

$titles        = Utilities::get_page_titles();
$page_title    = $titles['title'];
$page_subtitle = $titles['subtitle'];
$page_text     = $titles['description'];

$blog_grid_filter_enabled       = get_theme_mod( 'blog_grid_filter_enabled', true );
$blog_grid_filter_mode          = get_theme_mod( 'blog_grid_filter_mode', 'current_page' );
$blog_grid_hide_page_subheading = get_theme_mod( 'blog_grid_hide_page_subheading', true );

$blog_os_animations_enabled = get_theme_mod( 'blog_os_animations_enabled', false );

// Don't show category subheading in Blog Grid layout
// with enabled filtering
if ( $blog_grid_hide_page_subheading || ( $blog_grid_filter_enabled && $blog_grid_filter_mode === 'current_page' ) ) {
	$page_subtitle = false;
}

if ( $blog_os_animations_enabled ) {
	$attrs_section .= ' data-arts-os-animation=true';
}

?>

<section class="section section-masthead text-center <?php echo esc_attr( trim( $class_section ) ); ?>" data-background-color="<?php echo esc_attr( $page_curtain_color ); ?>" <?php echo esc_attr( trim( $attrs_section ) ); ?>>
	<div class="container section-masthead__inner">
		<header class="row section-masthead__header">
			<div class="col">
				<?php if ( ! empty( $page_subtitle ) ) : ?>
					<div class="section-masthead__subheading subheading mt-0 mb-1 mb-md-2 split-text js-split-text" data-split-text-type="lines,words" data-split-text-set="words"><?php echo esc_html( $page_subtitle ); ?></div>
					<div class="w-100"></div>
				<?php endif; ?>
				<?php if ( ! empty( $page_title ) ) : ?>
					<div class="section-masthead__heading">
						<h1 class="entry-title h1 mt-0 mb-0 split-text js-split-text" data-split-text-type="lines,words" data-split-text-set="words"><?php echo esc_html( $page_title ); ?></h1>
					</div>
					<div class="w-100"></div>
				<?php endif; ?>
				<div class="section__headline mt-2"></div>
				<?php if ( ! empty( $page_text ) ) : ?>
					<div class="section-masthead__text mt-2 paragraph split-text js-split-text" data-split-text-type="lines" data-split-text-set="lines"><?php echo wp_kses( $page_text, wp_kses_allowed_html( 'post' ) ); ?></div>
				<?php endif; ?>
			</div>
		</header>
	</div>
</section>
