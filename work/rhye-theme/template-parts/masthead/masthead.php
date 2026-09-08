<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

global $post;

$titles        = Utilities::get_page_titles();
$page_title    = $titles['title'];
$page_subtitle = $titles['subtitle'];

$is_elementor_page  = Utilities::is_built_with_elementor();
$is_post            = is_singular( 'post' );
$has_post_thumbnail = has_post_thumbnail();
$page_curtain_color = '';
$attrs_section      = '';
$class_section      = 'pt-large';
$class_inner        = '';

$blog_grid_filter_enabled        = ! is_single() && get_theme_mod( 'blog_grid_filter_enabled', true );
$blog_grid_filter_mode           = get_theme_mod( 'blog_grid_filter_mode', 'current_page' );
$blog_grid_hide_page_subheading  = get_theme_mod( 'blog_grid_hide_page_subheading', true );
$blog_single_post_heading_preset = 'h1';
$blog_os_animations_enabled      = get_theme_mod( 'blog_os_animations_enabled', false );
$blog_post_image_layout          = get_theme_mod( 'post_image_layout', 'normal' );

$post_image_masthead_fixed_enabled      = false;
$post_image_masthead_scrolldown_enabled = false;

$style_theme        = get_theme_mod( 'blog_style_theme', 'bg-light-1' );
$class_section     .= ' ' . $style_theme;
$page_curtain_color = get_theme_mod( esc_attr( $style_theme ), '#eeece6' );

if ( $is_post ) {
	$style_theme                       = get_theme_mod( 'blog_style_single_post_theme', 'bg-light-1' );
	$blog_single_post_heading_preset   = get_theme_mod( 'blog_single_post_heading_preset', 'h1' );
	$post_image_parallax_enabled       = get_theme_mod( 'post_image_parallax_enabled', true );
	$post_image_parallax_speed         = get_theme_mod( 'post_image_parallax_speed', 0.15 );
	$post_image_masthead_fixed_enabled = false;
	$post_show_info                    = get_theme_mod( 'post_show_info', true );
	$post_meta_set                     = get_theme_mod( 'post_meta_set', array( 'date', 'categories', 'comments', 'author' ) );
	$page_curtain_color                = get_theme_mod( esc_attr( $style_theme ) );

	if ( $blog_post_image_layout === 'fullscreen' ) {
		$class_section                          = 'section-fullheight';
		$class_inner                           .= ' section-fullheight__inner';
		$post_image_masthead_fixed_enabled      = get_theme_mod( 'post_image_masthead_fixed_enabled', true );
		$post_image_masthead_scrolldown_enabled = get_theme_mod( 'post_image_masthead_scrolldown_enabled', true );
		$post_image_masthead_scrolldown_label   = get_theme_mod( 'post_image_masthead_scrolldown_label', esc_html__( 'Start Reading', 'rhye' ) );
		if ( $has_post_thumbnail ) {
			$attrs_section = 'data-arts-theme-text=light';
		}
	}

	// disable parallax in fullscreen fixed layout
	if ( $blog_post_image_layout === 'fullscreen' && $post_image_masthead_fixed_enabled ) {
		$post_image_parallax_enabled = false;
	}

	if ( $post_image_masthead_fixed_enabled ) {
		$attrs_section .= ' data-arts-scroll-fixed=true';
		$class_section .= ' section-masthead_fixed overflow';
	}
}

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
	<div class="container section-masthead__inner <?php echo esc_attr( $class_inner ); ?>">
		<header class="row section-masthead__header justify-content-center">
			<div class="col">
				<?php if ( $is_post && $post_show_info && ! empty( $post_meta_set ) ) : ?>
					<div class="section-masthead__meta subheading mt-0 mb-2 split-text js-split-text" data-split-text-type="lines,words" data-split-text-set="words">
						<?php get_template_part( 'template-parts/blog/post/partials/post_info' ); ?>
					</div>
					<div class="w-100"></div>
				<?php endif; ?>
				<?php if ( ! empty( $page_subtitle ) ) : ?>
					<div class="section-masthead__subheading subheading mt-0 mb-1 mb-md-2 split-text js-split-text" data-split-text-type="lines,words" data-split-text-set="words"><?php echo esc_html( $page_subtitle ); ?></div>
					<div class="w-100"></div>
				<?php endif; ?>
				<h1 class="section-masthead__heading <?php echo esc_attr( $blog_single_post_heading_preset ); ?> mt-0 mb-0 split-text js-split-text" data-split-text-type="lines,words" data-split-text-set="words"><?php echo esc_html( $page_title ); ?></h1>
				<div class="w-100"></div>
				<div class="section__headline mt-2"></div>
			</div>
		</header>
	</div>
	<?php if ( $is_post && $has_post_thumbnail ) : ?>
		<?php if ( $blog_post_image_layout === 'fullwidth' ) : ?>
			<?php
				arts_the_lazy_image(
					array(
						'id'            => get_post_thumbnail_id( $post->ID ),
						'fetchpriority' => 'high',
						'class'         => array(
							'section' => array( 'section', 'section-image', 'section-image_single-post', 'section-masthead__background', 'mt-medium' ),
							'wrapper' => array( 'section-image__wrapper' ),
						),
						'parallax'      => array(
							'enabled' => $post_image_parallax_enabled,
							'factor'  => $post_image_parallax_speed,
						),
					)
				);
			?>
		<?php elseif ( $blog_post_image_layout === 'fullscreen' ) : ?>
			<?php
				arts_the_lazy_image(
					array(
						'id'       => get_post_thumbnail_id( $post->ID ),
						'class'    => array(
							'section' => array( 'section', 'section-image', 'section-masthead__background', 'section-masthead__background_fullscreen' ),
							'wrapper' => array( 'section-image__wrapper' ),
							'overlay' => array( 'overlay', 'overlay_dark', 'section-masthead__overlay', 'section-masthead__overlay_fullscreen' ),
						),
						'parallax' => array(
							'enabled' => $post_image_parallax_enabled,
							'factor'  => $post_image_parallax_speed,
						),
						'overlay'  => true,
					)
				);
			?>
		<?php endif; ?>
	<?php endif; ?>
		<?php if ( $blog_post_image_layout === 'fullscreen' && $post_image_masthead_scrolldown_enabled ) : ?>
			<?php if ( $post_image_masthead_fixed_enabled ) : ?>
				<?php
				arts_the_scroll_down_button(
					array(
						'label'     => $post_image_masthead_scrolldown_label,
						'animation' => false,
					)
				);
				?>
			<?php else : ?>
				<?php
				arts_the_scroll_down_button(
					array(
						'label'     => $post_image_masthead_scrolldown_label,
						'animation' => true,
					)
				);
				?>
			<?php endif; ?>
		<?php endif; ?>
</section>
