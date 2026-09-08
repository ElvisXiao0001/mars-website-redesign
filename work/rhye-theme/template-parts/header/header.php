<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

$has_menu                         = has_nav_menu( 'main_menu' );
$menu_style                       = get_theme_mod( 'menu_style', 'classic' );
$ajax_enabled                     = get_theme_mod( 'ajax_enabled', false );
$ajax_prevent_header_widgets_area = get_theme_mod( 'ajax_prevent_header_widgets_area', true );
$is_elementor_canvas_template     = Utilities::get_document_option( 'template' ) === 'elementor_canvas';

$attrs_widgets = '';

$class_header               = '';
$attrs_header               = '';
$class_burger_column        = '';
$class_header_container     = get_theme_mod( 'header_container', 'container-fluid' );
$class_menu                 = 'header__wrapper-overlay-menu container-fluid';
$class_lang_switcher_column = 'header__col-lang-switcher';
$class_classic_menu_column  = '';

$attrs_cursor = arts_get_element_cursor_attributes(
	array(
		'enabled'     => get_theme_mod( 'cursor_burger_menu_enabled', true ),
		'scale'       => 1.7,
		'magnetic'    => true,
		'hide_native' => true,
	)
);

$header_position                     = get_theme_mod( 'header_position', 'sticky' );
$header_widgets_id                   = 'header-sidebar';
$header_has_widgets                  = is_active_sidebar( $header_widgets_id );
$header_has_lang_switcher            = is_active_sidebar( 'lang-switcher-sidebar' ) && ( class_exists( 'SitePress' ) || class_exists( 'Polylang' ) || class_exists( 'TRP_Translate_Press' ) );
$header_fullscreen_menu_class        = 'header__wrapper-menu';
$header_overlay_menu_burger_style    = get_theme_mod( 'header_overlay_menu_burger_style', '' );
$header_overlay_menu_overflow_scroll = get_theme_mod( 'header_overlay_menu_overflow_scroll', 'native' );

$header_main_theme         = Utilities::get_overridden_document_option( 'header_main_theme', 'page_header_settings_overridden', 'dark' );
$header_main_logo          = Utilities::get_overridden_document_option( 'header_main_logo', 'page_header_settings_overridden', 'primary' );
$header_overlay_menu_theme = Utilities::get_overridden_document_option( 'header_overlay_menu_theme', 'page_header_settings_overridden', 'light' );
$header_overlay_background = Utilities::get_overridden_document_option( 'menu_overlay_background_color', 'page_header_settings_overridden', 'rgba(0,0,0,1)' );

if ( $header_main_theme ) {
	$attrs_header .= ' data-arts-theme-text=' . $header_main_theme;
}

if ( $header_main_logo ) {
	$attrs_header .= ' data-arts-header-logo=' . $header_main_logo;
}

if ( $header_overlay_menu_theme ) {
	$attrs_header .= ' data-arts-header-overlay-theme-text=' . $header_overlay_menu_theme;
}

if ( $header_overlay_background ) {
	$attrs_header .= ' data-arts-header-overlay-background=' . str_replace( ' ', '', $header_overlay_background );
}

if ( $header_position === 'sticky' ) {
	$class_header       .= ' header_fixed js-header-sticky';
	$header_sticky_theme = Utilities::get_overridden_document_option( 'header_sticky_theme', 'page_header_settings_overridden', 'bg-dark-1' );
	$header_sticky_logo  = Utilities::get_overridden_document_option( 'header_sticky_logo', 'page_header_settings_overridden', 'secondary' );

	if ( $header_sticky_theme ) {
		$attrs_header .= ' data-arts-header-sticky-theme=' . $header_sticky_theme;
	}

	if ( $header_sticky_logo ) {
		$attrs_header .= ' data-arts-header-sticky-logo=' . $header_sticky_logo;
	}
} else {
	$class_header .= ' header_absolute';
	$attrs_header .= ' data-arts-scroll-absolute=true';
}

if ( $menu_style === 'classic' ) {
	$class_burger_column         = 'd-lg-none';
	$class_lang_switcher_column .= ' order-lg-2';
}

if ( $header_has_lang_switcher && $menu_style === 'classic' ) {
	$class_classic_menu_column .= ' ml-auto';
}

if ( $ajax_prevent_header_widgets_area ) {
	$attrs_widgets = 'data-barba-prevent=all';
}

if ( $ajax_enabled && $is_elementor_canvas_template ) {
	$class_header .= ' hidden';
}

if ( $header_has_widgets ) {
	$class_menu                   .= ' pb-0';
	$header_fullscreen_menu_class .= ' header__wrapper-menu_has-widgets';
}

if ( $header_overlay_menu_overflow_scroll ) {
	$class_menu .= ' js-smooth-scroll-container';
}

?>

<header class="header container-fluid header_menu-right <?php echo esc_attr( trim( $class_header ) ); ?>" id="page-header" <?php echo esc_attr( trim( $attrs_header ) ); ?>>
  <!-- top bar -->
  <div class="header__container header__controls <?php echo esc_attr( $class_header_container ); ?>">
		<div class="row justify-content-between align-items-center">
			<!-- logo -->
			<div class="col-auto header__col header__col-left">
				<?php get_template_part( 'template-parts/logo/logo' ); ?>
			</div>
			<!-- - logo -->

			<?php if ( $header_has_lang_switcher ) : ?>
			  <div class="col-auto header__col header__col-left <?php echo esc_attr( trim( $class_lang_switcher_column ) ); ?>">
					<div class="lang-switcher">
						<?php dynamic_sidebar( 'lang-switcher-sidebar' ); ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( $has_menu ) : ?>
				<?php if ( $menu_style === 'classic' ) : ?>
					<!-- desktop menu -->
					<div class="col-auto header__col d-none d-lg-block <?php echo esc_attr( trim( $class_classic_menu_column ) ); ?>">
						<?php
							wp_nav_menu(
								array(
									'theme_location' => 'main_menu',
									'container'      => false,
								)
							);
						?>
					</div>
					<!-- - desktop menu -->
				<?php endif; ?>

				<!-- burger icon -->
				<div class="col-auto header__col <?php echo esc_attr( $class_burger_column ); ?>">
					<div class="header__burger <?php echo esc_attr( $header_overlay_menu_burger_style ); ?>" id="js-burger" <?php echo esc_attr( $attrs_cursor ); ?>>
						<div class="header__burger-line"></div>
						<div class="header__burger-line"></div>
						<div class="header__burger-line"></div>
					</div>
				</div>
				<!-- - burger icon -->

				<!-- "back" button for submenu nav -->
				<div class="header__overlay-menu-back" id="js-submenu-back">
					<?php
						arts_the_arrow(
							array(
								'direction' => 'left',
								'mini'      => true,
							)
						);
					?>
				</div>
				<!-- - "back" button for submenu nav -->
			<?php endif; ?>
		</div>
  </div>
  <!-- - top bar -->

  <!-- fullscreen overlay container -->
  <div class="<?php echo esc_attr( trim( $class_menu ) ); ?>" data-lenis-prevent="true">
		<!-- fullscreen menu -->
		<div class="<?php echo esc_attr( trim( $header_fullscreen_menu_class ) ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'main_menu',
					'container'      => false,
					'menu_class'     => 'menu-overlay js-menu-overlay',
					'link_before'    => '<div class="menu-overlay__item-wrapper split-text js-split-text" data-split-text-type="lines">',
					'link_after'     => '</div>',
					'walker'         => new Arts_Walker_Nav_Menu_Overlay(),
				)
			);
			?>
		</div>
		<!-- - fullscreen menu -->

		<?php if ( $header_has_widgets ) : ?>
			<!-- fullscreen widgets -->
			<div class="container header__wrapper-overlay-widgets split-text" <?php echo esc_attr( $attrs_widgets ); ?>>
				<div class="row row-gutters justify-content-center">
					<div class="header__wrapper-overlay-widgets__border"></div>
					<?php dynamic_sidebar( $header_widgets_id ); ?>
				</div>
			</div>
			<!-- - fullscreen widgets -->
		<?php endif; ?>
  </div>
  <!-- - fullscreen overlay container -->
</header>
