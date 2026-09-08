<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

global $post;

$titles        = Utilities::get_page_titles();
$page_title    = $titles['title'];
$page_subtitle = $titles['subtitle'];
$page_text     = $titles['description'];

$featured_video                   = Utilities::acf_get_field( 'featured_video' );
$page_masthead_use_featured_video = Utilities::get_document_option( 'page_masthead_use_featured_video', null, 'yes' );
$has_post_thumbnail               = has_post_thumbnail() || ( $page_masthead_use_featured_video && $featured_video );

$attrs_section  = '';
$class_section  = ' ' . Utilities::get_document_option( 'page_masthead_background' );
$class_section .= ' ' . Utilities::get_document_option( 'page_masthead_content_alignment' );
$class_section .= ' ' . Utilities::get_document_option( 'page_masthead_pt' );
$class_section .= ' ' . Utilities::get_document_option( 'page_masthead_pb' );
$class_section .= ' ' . Utilities::get_document_option( 'page_masthead_mt' );
$class_section .= ' ' . Utilities::get_document_option( 'page_masthead_mb' );

$class_inner      = Utilities::get_document_option( 'page_masthead_content_container' );
$class_image      = Utilities::get_document_option( 'page_masthead_image_alignment' );
$class_split_text = ' split-text js-split-text';

$image_parallax_enabled = Utilities::get_document_option( 'page_masthead_image_parallax_enabled' );
$image_parallax_speed   = Utilities::get_document_option( 'page_masthead_image_parallax_speed' );

$page_masthead_heading_preset     = Utilities::get_document_option( 'page_masthead_heading_preset' );
$page_masthead_subheading_preset  = Utilities::get_document_option( 'page_masthead_subheading_preset' );
$page_masthead_text_preset        = Utilities::get_document_option( 'page_masthead_text_preset' );
$page_masthead_headline_enabled   = Utilities::get_document_option( 'page_masthead_headline_enabled' );
$page_masthead_subheading_enabled = Utilities::get_document_option( 'page_masthead_subheading_enabled' );
$page_masthead_text_enabled       = Utilities::get_document_option( 'page_masthead_text_enabled' );
$page_masthead_image_placement    = Utilities::get_document_option( 'page_masthead_image_placement' );

$page_color_theme_curtain        = Utilities::get_document_option( 'page_masthead_background' );
$page_curtain_color              = get_theme_mod( esc_attr( $page_color_theme_curtain ), '#eeece6' );
$page_masthead_animation_enabled = Utilities::get_document_option( 'page_masthead_animation_enabled', null, true );
$page_masthead_theme             = Utilities::get_document_option( 'page_masthead_theme' );

if ( $page_masthead_theme ) {
	$attrs_section = ' data-arts-theme-text=' . $page_masthead_theme;
}

if ( $page_masthead_animation_enabled ) {
	$attrs_section                   .= ' data-arts-os-animation=true';
	$page_masthead_heading_preset    .= $class_split_text;
	$page_masthead_subheading_preset .= $class_split_text;
	$page_masthead_text_preset       .= $class_split_text;
}

?>

<section class="section section-masthead <?php echo esc_attr( trim( $class_section ) ); ?>" data-background-color="<?php echo esc_attr( $page_curtain_color ); ?>" <?php echo esc_attr( trim( $attrs_section ) ); ?>>
	<div class="section-masthead__inner <?php echo esc_attr( $class_inner ); ?>">
		<header class="row section-masthead__header">
			<div class="col-12">
				<?php if ( $page_masthead_subheading_enabled && ! empty( $page_subtitle ) ) : ?>
					<div class="section-masthead__subheading mt-0 mb-1 mb-md-2 <?php echo esc_attr( $page_masthead_subheading_preset ); ?>" data-split-text-type="lines,words" data-split-text-set="words"><?php echo esc_html( $page_subtitle ); ?></div>
					<div class="w-100"></div>
				<?php endif; ?>
				<?php if ( ! empty( $page_title ) ) : ?>
					<h1 class="entry-title section-masthead__heading my-0 <?php echo esc_attr( $page_masthead_heading_preset ); ?>" data-split-text-type="lines,words" data-split-text-set="words"><?php echo esc_html( $page_title ); ?></h1>
					<div class="w-100"></div>
				<?php endif; ?>
				<?php if ( $page_masthead_headline_enabled ) : ?>
					<div class="section-masthead__headline section__headline mt-2"></div>
				<?php endif; ?>
				<?php if ( $page_masthead_text_enabled && ! empty( $page_text ) ) : ?>
					<div class="section-masthead__text mt-2 <?php echo esc_attr( $page_masthead_text_preset ); ?>" data-split-text-type="lines" data-split-text-set='lines'><?php echo wp_kses( $page_text, wp_kses_allowed_html( 'post' ) ); ?></div>
				<?php endif; ?>
			</div>
		</header>
	</div>
	<?php if ( $has_post_thumbnail ) : ?>
		<?php
			$video_id = false;

			if ( $page_masthead_use_featured_video && $featured_video && is_array( $featured_video ) ) {
				$video_id = $featured_video['id'];
			}

			if ( $video_id ) {
				arts_the_lazy_image(
					array(
						'id'        => $video_id,
						'type'      => 'background-video',
						'class'     => array(
							'section' => array( 'section-image', 'section-masthead__background', 'overflow', 'mt-medium', $class_image ),
							'wrapper' => array( 'section-image__wrapper', 'js-transition-img' ),
							'image'   => array( 'js-transition-img__transformed-el' ),
						),
						'parallax'  => array(
							'enabled' => $image_parallax_enabled,
							'factor'  => is_array( $image_parallax_speed ) ? $image_parallax_speed['size'] : 0,
						),
						'animation' => false,
						'mask'      => true,
						'overlay'   => false,
					)
				);
			} else {
				arts_the_lazy_image(
					array(
						'id'            => get_post_thumbnail_id(),
						'type'          => $page_masthead_image_placement,
						'fetchpriority' => 'high',
						'class'         => array(
							'section' => array( 'section-image', 'section-masthead__background', 'overflow', 'mt-medium', $class_image ),
							'wrapper' => array( 'section-image__wrapper', 'js-transition-img' ),
							'image'   => array( 'js-transition-img__transformed-el' ),
						),
						'parallax'      => array(
							'enabled' => $image_parallax_enabled,
							'factor'  => is_array( $image_parallax_speed ) ? $image_parallax_speed['size'] : 0,
						),
						'animation'     => false,
						'mask'          => true,
						'overlay'       => false,
					)
				);
			}
		?>
	<?php endif; ?>
</section>
