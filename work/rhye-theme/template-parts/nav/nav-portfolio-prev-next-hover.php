<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

$portfolio_nav_direction                    = get_theme_mod( 'portfolio_nav_direction', 'forward' );
$portfolio_nav_image_transition_enabled     = get_theme_mod( 'portfolio_nav_image_transition_enabled', true );
$portfolio_nav_headings_preset              = get_theme_mod( 'portfolio_nav_headings_preset', 'h1' );
$portfolio_nav_labels_preset                = get_theme_mod( 'portfolio_nav_labels_preset', 'subheading' );
$portfolio_nav_next_label                   = get_theme_mod( 'portfolio_nav_next_label', esc_html__( 'Next Project', 'rhye' ) );
$portfolio_nav_prev_label                   = get_theme_mod( 'portfolio_nav_prev_label', esc_html__( 'Previous Project', 'rhye' ) );
$portfolio_loop_enabled                     = get_theme_mod( 'portfolio_loop_enabled', true );
$portfolio_next_first_mobile                = get_theme_mod( 'portfolio_next_first_mobile', true );
$portfolio_nav_include_portfolio_taxonomies = get_theme_mod( 'portfolio_nav_include_portfolio_taxonomies', '' );
$portfolio_nav_background                   = Utilities::get_overridden_document_option( 'portfolio_nav_background', 'page_portfolio_nav_settings_overridden', 'bg-light-1' );
$portfolio_nav_theme                        = Utilities::get_overridden_document_option( 'portfolio_nav_theme', 'page_portfolio_nav_settings_overridden', 'dark' );
$portfolio_nav_divider_enabled              = Utilities::get_overridden_document_option( 'portfolio_nav_divider_enabled', 'page_portfolio_nav_settings_overridden', true );

$prev_args = array(
	'direction' => $portfolio_nav_direction === 'forward' ? 'backward' : 'forward',
	'loop'      => $portfolio_loop_enabled,
);
$next_args = array(
	'direction' => $portfolio_nav_direction === 'forward' ? 'forward' : 'backward',
	'loop'      => $portfolio_loop_enabled,
);

if ( ! empty( $portfolio_nav_include_portfolio_taxonomies ) ) {
	$prev_args['in_same_term'] = true;
	$prev_args['taxonomy']     = $portfolio_nav_include_portfolio_taxonomies;

	$next_args['in_same_term'] = true;
	$next_args['taxonomy']     = $portfolio_nav_include_portfolio_taxonomies;
}

$next_post  = arts_get_post_looped_overridden( 'next', $next_args );
$next_link  = '';
$next_title = '';
$next_img   = '';
$next_video = '';

$prev_post  = arts_get_post_looped_overridden( 'prev', $prev_args );
$prev_link  = '';
$prev_title = '';
$prev_img   = '';
$prev_video = '';

$attr_link_prev  = '';
$attr_link_next  = '';
$class_link      = 'col';
$class_link_prev = '';
$class_link_next = '';

if ( $next_post ) {
	$next_link  = get_permalink( $next_post );
	$next_title = $next_post->post_title;
	$next_img   = get_post_thumbnail_id( $next_post->ID );

	$next_page_masthead_use_featured_video = Utilities::get_document_option( 'page_masthead_use_featured_video', $next_post->ID, 'yes' );

	if ( $next_page_masthead_use_featured_video ) {
		$video = Utilities::acf_get_field( 'featured_video', $next_post->ID );
		if ( $video && is_array( $video ) ) {
			$next_video = $video['id'];
		}
	}
}

if ( $prev_post ) {
	$prev_link  = get_permalink( $prev_post );
	$prev_title = $prev_post->post_title;
	$prev_img   = get_post_thumbnail_id( $prev_post->ID );

	$prev_page_masthead_use_featured_video = Utilities::get_document_option( 'page_masthead_use_featured_video', $prev_post->ID, 'yes' );

	if ( $prev_page_masthead_use_featured_video ) {
		$video = Utilities::acf_get_field( 'featured_video', $prev_post->ID );
		if ( $video && is_array( $video ) ) {
			$prev_video = $video['id'];
		}
	}
}

if ( $next_post && $prev_post ) {
	$class_link = 'col-md-6';
}

if ( $portfolio_next_first_mobile ) {
	$class_link_prev = 'order-md-0 order-2';
	$class_link_next = 'order-md-1 order-1';
}

if ( $portfolio_nav_image_transition_enabled && ( $next_img || $next_video ) ) {
	$attr_link_next = 'data-pjax-link=flyingImage';
}

if ( $portfolio_nav_image_transition_enabled && ( $prev_img || $prev_video ) ) {
	$attr_link_prev = 'data-pjax-link=flyingImage';
}

?>

<?php if ( $next_post || $prev_post ) : ?>
	<section class="section section-list section-list_dividers section-list_2 text-center <?php echo esc_attr( $portfolio_nav_background ); ?>" data-arts-os-animation="true" data-arts-theme-text="<?php echo esc_attr( $portfolio_nav_theme ); ?>" id="page-bottom-nav">
		<div class="container-fluid no-gutters list-projects list-demos py-medium pt-sm-0 pb-sm-0 js-list-hover" data-arts-hover-strength="0.35" data-arts-hover-scale-texture="1.2" data-arts-hover-scale-plane="0.33">
			<?php if ( $portfolio_nav_divider_enabled ) : ?>
				<div class="section__divider section__divider_top"></div>
			<?php else : ?>
				<div class="section__divider d-none"></div>
			<?php endif; ?>
			<div class="row no-gutters">
				<?php if ( $prev_post ) : ?>
					<div class="section-list__wrapper-item section-list__wrapper-item_prev <?php echo esc_attr( $class_link ); ?> <?php echo esc_attr( $class_link_prev ); ?>">
						<a class="container list-projects__item list-demos__item js-list-hover__link py-medium px-md-3" href="<?php echo esc_attr( $prev_link ); ?>" data-arts-cursor="true" data-arts-cursor-hide-native="true" data-arts-cursor-scale="0.0" <?php echo esc_attr( $attr_link_prev ); ?>>
							<div class="row no-gutters align-items-center justify-content-center">
								<?php if ( $prev_img || $prev_video ) : ?>
									<!-- hidden AJAX transition image/video -->
									<div class="col-12 d-md-none">
										<div class="js-transition-img list-projects__cover overflow js-list-hover__cover">
											<?php
												if ( $prev_video ) {
													arts_the_lazy_image(
														array(
															'id'    => $prev_video,
															'type'  => 'video',
															'class' => array(
																'wrapper' => array(),
																'image'   => array( 'js-transition-img__transformed-el', 'texture' ),
															),
														)
													);
												} else {
													arts_the_lazy_image(
														array(
															'id'    => $prev_img,
															'type'  => 'image',
															'class' => array(
																'wrapper' => array(),
																'image'   => array( 'js-transition-img__transformed-el', 'texture' ),
															),
														)
													);
												}
											?>
										</div>
									</div>
									<!-- - hidden AJAX transition image/video -->
								<?php endif; ?>
								<!-- header -->
								<div class="col-12">
									<div class="list-projects__header mt-1 mt-md-0 mb-2 mb-md-0">
										<?php if ( $portfolio_nav_prev_label ) : ?>
											<div class="list-projects__subheading mb-1 mb-md-2 split-text js-split-text <?php echo esc_attr( $portfolio_nav_labels_preset ); ?>" data-split-text-type="lines, words" data-split-text-set="words"><?php echo esc_html( $portfolio_nav_prev_label ); ?></div>
										<?php endif; ?>
										<h2 class="list-projects__heading split-text js-split-text <?php echo esc_attr( $portfolio_nav_headings_preset ); ?>" data-split-text-type="lines, words" data-split-text-set="words"><?php echo esc_html( $prev_title ); ?></h2>
									</div>
								</div>
								<!-- - header -->
							</div>
						</a>
					</div>
				<?php endif; ?>
				<?php if ( $next_post ) : ?>
					<div class="section-list__wrapper-item section-list__wrapper-item_next <?php echo esc_attr( $class_link ); ?> <?php echo esc_attr( $class_link_next ); ?>">
						<a class="container list-projects__item list-demos__item js-list-hover__link py-medium px-md-3" href="<?php echo esc_attr( $next_link ); ?>" data-arts-cursor="true" data-arts-cursor-hide-native="true" data-arts-cursor-scale="0.0" <?php echo esc_attr( $attr_link_next ); ?>>
							<div class="row no-gutters align-items-center justify-content-center">
								<?php if ( $next_img || $next_video ) : ?>
									<!-- hidden AJAX transition image/video -->
									<div class="col-12 d-md-none">
										<div class="js-transition-img list-projects__cover overflow js-list-hover__cover">
											<?php
												if ( $next_video ) {
													arts_the_lazy_image(
														array(
															'id'    => $next_video,
															'type'  => 'video',
															'class' => array(
																'wrapper' => array(),
																'image'   => array( 'js-transition-img__transformed-el', 'texture' ),
															),
														)
													);
												} else {
													arts_the_lazy_image(
														array(
															'id'    => $next_img,
															'type'  => 'image',
															'class' => array(
																'wrapper' => array(),
																'image'   => array( 'js-transition-img__transformed-el', 'texture' ),
															),
														)
													);
												}
											?>
										</div>
									</div>
									<!-- - hidden AJAX transition image/video -->
								<?php endif; ?>
								<!-- header -->
								<div class="col-12">
									<div class="list-projects__header mt-1 mt-md-0 mb-2 mb-md-0">
										<?php if ( $portfolio_nav_next_label ) : ?>
											<div class="list-projects__subheading mb-1 mb-md-2 split-text js-split-text <?php echo esc_attr( $portfolio_nav_labels_preset ); ?>" data-split-text-type="lines, words" data-split-text-set="words"><?php echo esc_html( $portfolio_nav_next_label ); ?></div>
										<?php endif; ?>
										<h2 class="list-projects__heading split-text js-split-text <?php echo esc_attr( $portfolio_nav_headings_preset ); ?>" data-split-text-type="lines, words" data-split-text-set="words"><?php echo esc_html( $next_title ); ?></h2>
									</div>
								</div>
								<!-- - header -->
							</div>
						</a>
					</div>
				<?php endif; ?>
			</div>
			<!-- fixed canvas -->
			<canvas class="list-project__canvas js-list-hover__canvas" data-arts-scroll-fixed="true"></canvas>
			<!-- - fixed canvas -->
		</div>
	</section>
<?php endif; ?>
