<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$blog_style_theme   = get_theme_mod( 'blog_style_single_post_theme', 'bg-light-1' );
$page_curtain_color = get_theme_mod( esc_attr( $blog_style_theme ), '#f2f1ed' );

$class_section = $blog_style_theme . ' pt-large';
$class_inner   = '';
$attrs_section = '';

$page_title = get_the_title();

$has_post_thumbnail = has_post_thumbnail();

$blog_os_animations_enabled         = get_theme_mod( 'blog_os_animations_enabled', false );
$blog_post_image_layout             = get_theme_mod( 'post_image_layout', 'normal' );
$blog_ajax_image_transition_enabled = get_theme_mod( 'blog_ajax_image_transition_enabled', true );

$blog_single_post_heading_preset               = get_theme_mod( 'blog_single_post_heading_preset', 'h1' );
$post_image_parallax_enabled                   = get_theme_mod( 'post_image_parallax_enabled', true );
$post_image_parallax_speed                     = get_theme_mod( 'post_image_parallax_speed', 0.15 );
$post_image_masthead_fixed_enabled             = false;
$post_image_masthead_scrolldown_enabled        = get_theme_mod( 'post_image_masthead_scrolldown_enabled', true );
$post_image_masthead_scrolldown_label          = get_theme_mod( 'post_image_masthead_scrolldown_label', esc_html__( 'Start Reading', 'rhye' ) );
$post_show_info                                = get_theme_mod( 'post_show_info', true );
$post_meta_set                                 = get_theme_mod( 'post_meta_set', array( 'date', 'categories', 'comments', 'author' ) );
$post_image_masthead_force_light_theme_enabled = get_theme_mod( 'post_image_masthead_force_light_theme_enabled', true );

if ( $blog_post_image_layout === 'fullscreen' ) {
	$class_section                     = $blog_style_theme . ' section-fullheight';
	$class_inner                      .= ' section-fullheight__inner section-fullheight__inner_mobile section-fullheight__inner_mobile-post';
	$post_image_masthead_fixed_enabled = get_theme_mod( 'post_image_masthead_fixed_enabled', true );
}

// force light elements theme if there is a fullscreen background
if ( $blog_post_image_layout === 'fullscreen' && $post_image_masthead_force_light_theme_enabled && $has_post_thumbnail ) {
	$attrs_section = 'data-arts-theme-text=light';
}

// force disable parallax in fullscreen fixed layout
if ( $blog_post_image_layout === 'fullscreen' && $post_image_masthead_fixed_enabled ) {
	$post_image_parallax_enabled = false;
}

if ( $post_image_masthead_fixed_enabled ) {
	$attrs_section .= ' data-arts-scroll-fixed=true';
	$class_section .= ' section-masthead_fixed overflow';
}

if ( $blog_os_animations_enabled ) {
	$attrs_section .= ' data-arts-os-animation=true';
}

?>

<section class="section section-masthead text-center <?php echo esc_attr( trim( $class_section ) ); ?>" data-background-color="<?php echo esc_attr( $page_curtain_color ); ?>" <?php echo esc_attr( trim( $attrs_section ) ); ?>>
	<div class="container section-masthead__inner <?php echo esc_attr( trim( $class_inner ) ); ?>">
		<header class="row section-masthead__header">
			<div class="col">
				<?php if ( $post_show_info && ! empty( $post_meta_set ) ) : ?>
					<div class="section-masthead__meta subheading mt-0 mb-2 split-text js-split-text" data-split-text-type="lines,words" data-split-text-set="words">
						<?php get_template_part( 'template-parts/blog/post/partials/post_info' ); ?>
					</div>
					<div class="w-100"></div>
				<?php endif; ?>
				<?php if ( ! empty( $page_title ) ) : ?>
					<h1 class="entry-title section-masthead__heading <?php echo esc_attr( $blog_single_post_heading_preset ); ?> mt-0 mb-0 split-text js-split-text" data-split-text-type="lines,words" data-split-text-set="words"><?php echo wp_kses( $page_title, wp_kses_allowed_html( 'post' ) ); ?></h1>
					<div class="w-100"></div>
				<?php endif; ?>
				<div class="section__headline mt-2"></div>
			</div>
		</header>
	</div>
	<?php if ( $has_post_thumbnail ) : ?>
		<?php if ( $blog_post_image_layout === 'fullwidth' ) : ?>
			<?php
				arts_the_lazy_image(
					array(
						'id'       => get_post_thumbnail_id(),
						'class'    => array(
							'section' => array( 'section', 'section-image', 'section-image_single-post', 'section-masthead__background', 'mt-medium' ),
							'wrapper' => $blog_ajax_image_transition_enabled ? array( 'section-image__wrapper', 'js-transition-img' ) : array( 'section-image__wrapper' ),
							'image'   => $blog_ajax_image_transition_enabled ? array( 'of-cover', 'js-transition-img__transformed-el' ) : array(),
						),
						'parallax' => array(
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
						'id'            => get_post_thumbnail_id(),
						'fetchpriority' => 'high',
						'class'         => array(
							'section' => array( 'section', 'section-image', 'section-masthead__background', 'section-masthead__background_fullscreen' ),
							'wrapper' => $blog_ajax_image_transition_enabled ? array( 'section-image__wrapper', 'js-transition-img' ) : array( 'section-image__wrapper' ),
							'image'   => $blog_ajax_image_transition_enabled ? array( 'js-transition-img__transformed-el' ) : array(),
							'overlay' => array( 'overlay', 'overlay_dark', 'section-masthead__overlay', 'section-masthead__overlay_fullscreen' ),
						),
						'parallax'      => array(
							'enabled' => $post_image_parallax_enabled,
							'factor'  => $post_image_parallax_speed,
						),
						'overlay'       => true,
					)
				);
			?>
		<?php endif; ?>
	<?php endif; ?>
	<?php if ( $blog_post_image_layout === 'fullscreen' && $post_image_masthead_scrolldown_enabled ) : ?>
		<!-- scroll down -->
		<div class="section-masthead__wrapper-scroll-down">
			<?php
				arts_the_scroll_down_button(
					array(
						'label'     => $post_image_masthead_scrolldown_label,
						'animation' => $post_image_masthead_fixed_enabled ? false : true,
					)
				);
			?>
		</div>
		<!-- - scroll down -->
	<?php endif; ?>
</section>
