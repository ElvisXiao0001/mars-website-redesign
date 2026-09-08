<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

$portfolio_nav_direction                    = get_theme_mod( 'portfolio_nav_direction', 'forward' );
$portfolio_nav_background                   = Utilities::get_overridden_document_option( 'portfolio_nav_background', 'page_portfolio_nav_settings_overridden', 'bg-light-1' );
$portfolio_nav_theme                        = Utilities::get_overridden_document_option( 'portfolio_nav_theme', 'page_portfolio_nav_settings_overridden', 'dark' );
$portfolio_nav_divider_enabled              = Utilities::get_overridden_document_option( 'portfolio_nav_divider_enabled', 'page_portfolio_nav_settings_overridden', true );
$portfolio_nav_image_transition_enabled     = get_theme_mod( 'portfolio_nav_image_transition_enabled', true );
$portfolio_nav_scroll_down_enabled          = get_theme_mod( 'portfolio_nav_scroll_down_enabled', true );
$portfolio_nav_scroll_down_label            = get_theme_mod( 'portfolio_nav_scroll_down_label', esc_html__( 'Keep Scrolling', 'rhye' ) );
$portfolio_nav_headings_preset              = get_theme_mod( 'portfolio_nav_headings_preset', 'h1' );
$portfolio_nav_labels_preset                = get_theme_mod( 'portfolio_nav_labels_preset', 'subheading' );
$portfolio_nav_next_label                   = get_theme_mod( 'portfolio_nav_next_label', esc_html__( 'Next Project', 'rhye' ) );
$portfolio_nav_include_portfolio_taxonomies = get_theme_mod( 'portfolio_nav_include_portfolio_taxonomies', '' );

$prev_next_args = array( 'direction' => $portfolio_nav_direction );

if ( ! empty( $portfolio_nav_include_portfolio_taxonomies ) ) {
	$prev_next_args['in_same_term'] = true;
	$prev_next_args['taxonomy']     = $portfolio_nav_include_portfolio_taxonomies;
}

$next_post = arts_get_post_looped_overridden( 'next', $prev_next_args );
$next_link     = null;
$next_title    = null;
$next_image_id = null;
$next_video_id = null;
$attrs_link    = '';

if ( $next_post ) {
	$next_link     = get_permalink( $next_post );
	$next_title    = $next_post->post_title;
	$next_image_id = get_post_thumbnail_id( $next_post->ID );

	$next_page_masthead_use_featured_video = Utilities::get_document_option( 'page_masthead_use_featured_video', $next_post->ID, 'yes' );

	if ( $next_page_masthead_use_featured_video ) {
		$video = Utilities::acf_get_field( 'featured_video', $next_post->ID );
		if ( $video && is_array( $video ) ) {
			$next_video_id = $video['id'];
		}
	}
}

if ( $portfolio_nav_image_transition_enabled && ( $next_image_id || $next_video_id ) ) {
	$attrs_link = 'data-pjax-link=flyingImage';
}

?>

<?php if ( $next_post ) : ?>
  <section class="container-fluid section section-nav-projects section-fullheight text-center <?php echo esc_attr( $portfolio_nav_background ); ?>" data-arts-os-animation="true" data-arts-theme-text="<?php echo esc_attr( $portfolio_nav_theme ); ?>">
		<div class="section-fullheight__inner section-nav-projects__inner_actual">
			<?php if ( $portfolio_nav_divider_enabled ) : ?>
				<div class="section__divider section__divider_top"></div>
			<?php else : ?>
				<div class="section__divider d-none"></div>
			<?php endif; ?>
			<header class="section-nav-projects__header">
				<a class="section-nav-projects__link" href="<?php echo esc_url( $next_link ); ?>" <?php echo esc_attr( $attrs_link ); ?>>
					<div class="section-nav-projects__subheading mb-1 mb-md-2 <?php echo esc_attr( $portfolio_nav_labels_preset ); ?>"><?php echo esc_html( $portfolio_nav_next_label ); ?></div>
					<h2 class="section-nav-projects__heading mt-0 mb-0 <?php echo esc_attr( $portfolio_nav_headings_preset ); ?>"><?php echo esc_html( $next_title ); ?></h2>
				</a>
			</header>
			<?php if ( $portfolio_nav_scroll_down_enabled ) : ?>
				<!-- scroll down -->
				<div class="section-nav-projects__wrapper-scroll-down text-center">
					<?php
					arts_the_scroll_down_button(
						array(
							'label'     => $portfolio_nav_scroll_down_label,
							'animation' => false,
							'enabled'   => false,
						)
					);
					?>
				</div>
				<!-- - scroll down -->
			<?php endif; ?>
		</div>
		<?php if ( $portfolio_nav_image_transition_enabled && ( $next_image_id || $next_video_id ) ) : ?>
			<!-- featured image/video -->
			<div class="container section-nav-projects__next-image section-fullheight__inner">
				<?php
					if ( $next_video_id ) {
						arts_the_lazy_image(
							array(
								'id'    => $next_video_id,
								'type'  => 'video',
								'class' => array(
									'section' => array( 'section-nav-projects__wrapper-image', 'js-transition-img', 'overflow' ),
									'wrapper' => array(),
									'image'   => array( 'js-transition-img__transformed-el', 'of-cover' ),
								),
							)
						);
					} else {
						arts_the_lazy_image(
							array(
								'id'    => $next_image_id,
								'type'  => 'image',
								'size'  => 'medium_large',
								'class' => array(
									'section' => array( 'section-nav-projects__wrapper-image', 'js-transition-img', 'overflow' ),
									'wrapper' => array(),
									'image'   => array( 'js-transition-img__transformed-el', 'of-cover' ),
								),
							)
						);
					}
				?>
			</div>
			<!-- - featured image/video -->
		<?php endif; ?>
  </section>
<?php endif; ?>
