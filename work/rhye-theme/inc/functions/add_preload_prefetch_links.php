<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * AJAX
 */
add_filter( 'arts/optimizer/preloads/prefetch_map', 'arts_add_pjax_prefetch_link' );
if ( ! function_exists( 'arts_add_pjax_prefetch_link' ) ) {
	function arts_add_pjax_prefetch_link( $map ) {
		$ajax_enabled = get_theme_mod( 'ajax_enabled', false );

		if ( $ajax_enabled ) {
			$map['PJAXJS'] = esc_url( ARTS_THEME_URL . '/modules/PJAX/PJAX.min.js?ver=' . ARTS_THEME_VERSION );
		}

		return $map;
	}
}

/**
 * Cursor Follower
 */
add_filter( 'arts/optimizer/preloads/prefetch_map', 'arts_add_cursor_follower_prefetch_link' );
if ( ! function_exists( 'arts_add_cursor_follower_prefetch_link' ) ) {
	function arts_add_cursor_follower_prefetch_link( $map ) {
		$cursor_enabled = get_theme_mod( 'cursor_enabled', false );

		if ( $cursor_enabled ) {
			$map['CursorJS'] = esc_url( ARTS_THEME_URL . '/modules/cursor/cursor.min.js?ver=' . ARTS_THEME_VERSION );
		}

		return $map;
	}
}

/**
 * Smooth Scrolling
 */
add_filter( 'arts/optimizer/preloads/prefetch_map', 'arts_add_smooth_scroll_prefetch_link' );
if ( ! function_exists( 'arts_add_smooth_scroll_prefetch_link' ) ) {
	function arts_add_smooth_scroll_prefetch_link( $map ) {
		$smooth_scroll = arts_is_smooth_scroll();

		if ( $smooth_scroll === 'lenis' ) {
			$map['SmoothScrollLenisJS'] = esc_url( ARTS_THEME_URL . '/modules/smoothScrollLenis/smoothScrollLenis.min.js?ver=' . ARTS_THEME_VERSION );
		} elseif ( $smooth_scroll === 'smooth-scrollbar' ) {
			$map['SmoothScrollJS'] = esc_url( ARTS_THEME_URL . '/modules/smoothScroll/smoothScroll.min.js?ver=' . ARTS_THEME_VERSION );
		}

		return $map;
	}
}

add_filter( 'arts/optimizer/preloads/prefetch_map', 'arts_add_smooth_scroll_prefetch_link' );
if ( ! function_exists( 'arts_add_smooth_scroll_prefetch_link' ) ) {
	function arts_add_smooth_scroll_prefetch_link( $map ) {
		$smooth_scroll                       = arts_is_smooth_scroll();
		$header_overlay_menu_overflow_scroll = get_theme_mod( 'header_overlay_menu_overflow_scroll', 'native' );
		$menu_style                          = get_theme_mod( 'menu_style', 'classic' );

		if ( $menu_style === 'fullscreen' && $header_overlay_menu_overflow_scroll === 'virtual' && ! $smooth_scroll ) {
			$map['SmoothScrollJS'] = esc_url( ARTS_THEME_URL . '/modules/smoothScroll/smoothScroll.min.js?ver=' . ARTS_THEME_VERSION );
		}

		return $map;
	}
}

/**
 * Prefetch links for common components
 */
add_filter( 'arts/optimizer/preloads/output', 'arts_add_common_components_prefetch_link' );
if ( ! function_exists( 'arts_add_common_components_prefetch_link' ) ) {
	function arts_add_common_components_prefetch_link( $output ) {
		if ( ! defined( 'ARTS_RHYE_CORE_PLUGIN_URL' ) ) {
			return $output;
		}

		// PSWP Galleries
		if ( preg_match( '/class=["\'].*?(js-gallery-united|js-gallery|js-album).*?["\']/', $output ) ) {
			add_filter(
				'arts/optimizer/preloads/prefetch_map',
				function( $map ) {
					$map['PSWPJS']  = esc_url( ARTS_RHYE_CORE_PLUGIN_URL . '/modules/pswp/pswp.min.js?ver=' . ARTS_RHYE_CORE_PLUGIN_VERSION );
					$map['PSWPCSS'] = esc_url( ARTS_RHYE_CORE_PLUGIN_URL . '/modules/pswp/pswp.min.css?ver=' . ARTS_RHYE_CORE_PLUGIN_VERSION );

					return $map;
				}
			);
		}

		return $output;
	}
}

/**
 * Preload links for common components
 */
add_filter( 'arts/optimizer/preloads/output', 'arts_add_common_components_preload_link' );
if ( ! function_exists( 'arts_add_common_components_preload_link' ) ) {
	function arts_add_common_components_preload_link( $output ) {
		if ( ! defined( 'ARTS_RHYE_CORE_PLUGIN_URL' ) ) {
			return $output;
		}

		// Scrol Down
		if ( preg_match( '/data-arts-scroll-down/', $output ) ) {
			add_filter(
				'arts/optimizer/preloads/assets_map',
				function( $map ) {
					$map[] = esc_url( ARTS_THEME_URL . '/modules/scrollDown/scrollDown.min.js?ver=' . ARTS_THEME_VERSION );

					return $map;
				}
			);
		}

		// Circle Button
		if ( preg_match( '/class=["\'].*?(js-circle-button).*?["\']/', $output ) ) {
			add_filter(
				'arts/optimizer/preloads/assets_map',
				function( $map ) {
					$map[] = esc_url( ARTS_RHYE_CORE_PLUGIN_URL . '/modules/circleButton/circleButton.min.css?ver=' . ARTS_RHYE_CORE_PLUGIN_VERSION );
					$map[] = esc_url( ARTS_RHYE_CORE_PLUGIN_URL . '/modules/circleButton/circleButton.min.js?ver=' . ARTS_RHYE_CORE_PLUGIN_VERSION );
					$map[] = esc_url( ARTS_THEME_URL . '/js/circletype.min.js?ver=2.3.1' );

					return $map;
				}
			);
		}

		// Section Nav Projects (Auto Scroll)
		if ( preg_match( '/class=["\'].*?(section-nav-projects).*?["\']/', $output ) ) {
			add_filter(
				'arts/optimizer/preloads/assets_map',
				function( $map ) {
					$map['SectionNavProjectsCSS'] = esc_url( ARTS_THEME_URL . '/modules/sectionNavProjects/sectionNavProjects.min.css' . '?ver=' . ARTS_THEME_VERSION );
					$map['SectionNavProjectsJS']  = esc_url( ARTS_THEME_URL . '/modules/sectionNavProjects/sectionNavProjects.min.js' . '?ver=' . ARTS_THEME_VERSION );

					return $map;
				}
			);
		}

		// Section Nav Projects (Prev & Next Hover)
		if ( preg_match( '/class=["\'].*?(section-list).*?["\']/', $output ) ) {
			add_filter(
				'arts/optimizer/preloads/assets_map',
				function( $map ) {
					$map['SectionListCSS'] = esc_url( ARTS_RHYE_CORE_PLUGIN_URL . '/modules/sectionList/sectionList.min.css' . '?ver=' . ARTS_RHYE_CORE_PLUGIN_VERSION );
					$map['SectionListJS']  = esc_url( ARTS_RHYE_CORE_PLUGIN_URL . '/modules/sectionList/sectionList.min.js' . '?ver=' . ARTS_RHYE_CORE_PLUGIN_VERSION );

					return $map;
				}
			);
		}

		// Section Nav Projects (Prev & Next Hover) - GL Animation
		if ( preg_match( '/class=["\'].*?(js-list-hover__canvas).*?["\']/', $output ) ) {
			add_filter(
				'arts/optimizer/preloads/assets_map',
				function( $map ) {
					$map['BaseGLAnimationJS'] = esc_url( ARTS_RHYE_CORE_PLUGIN_URL . '/modules/baseGLAnimation/baseGLAnimation.min.js' . '?ver=' . ARTS_RHYE_CORE_PLUGIN_VERSION );

					return $map;
				}
			);
		}

		// Section Blog Grid
		if ( preg_match( '/class=["\'].*?(section-blog_grid).*?["\']/', $output ) ) {
			add_filter(
				'arts/optimizer/preloads/assets_map',
				function( $map ) {
					$map = array(
						'SectionImageCSS'   => esc_url( ARTS_RHYE_CORE_PLUGIN_URL . '/modules/sectionImage/sectionImage.min.css?ver=' . ARTS_RHYE_CORE_PLUGIN_VERSION ),
						'SectionContentCSS' => esc_url( ARTS_RHYE_CORE_PLUGIN_URL . '/modules/sectionContent/sectionContent.min.css?ver=' . ARTS_RHYE_CORE_PLUGIN_VERSION ),
						'SectionGridCSS'    => esc_url( ARTS_RHYE_CORE_PLUGIN_URL . '/modules/sectionGrid/sectionGrid.min.css?ver=' . ARTS_RHYE_CORE_PLUGIN_VERSION ),
						'SectionImageJS'    => esc_url( ARTS_RHYE_CORE_PLUGIN_URL . '/modules/sectionImage/sectionImage.min.js?ver=' . ARTS_RHYE_CORE_PLUGIN_VERSION ),
						'SectionContentJS'  => esc_url( ARTS_RHYE_CORE_PLUGIN_URL . '/modules/sectionContent/sectionContent.min.js?ver=' . ARTS_RHYE_CORE_PLUGIN_VERSION ),
						'SectionGridJS'     => esc_url( ARTS_RHYE_CORE_PLUGIN_URL . '/modules/sectionGrid/sectionGrid.min.js?ver=' . ARTS_RHYE_CORE_PLUGIN_VERSION ),
						'IsotopeJS'         => esc_url( ARTS_THEME_URL . '/js/isotope.pkgd.min.js?ver=3.0.6' ),
					);
					return $map;
				}
			);
		}

		// Section Masthead
		if ( preg_match( '/<[^>]*\bclass=["\'](?:(?!\bd-none\b).)*\bsection-masthead\b(?:(?!\bd-none\b).)*["\'][^>]*>/', $output ) ) {
				add_filter(
					'arts/optimizer/preloads/assets_map',
					function( $map ) {
						$map['SectionMastheadJS'] = esc_url( ARTS_THEME_URL . '/modules/sectionMasthead/sectionMasthead.min.js?ver=' . ARTS_THEME_VERSION );

						return $map;
					}
				);
		}

		// Section Scroll (color theme change on scroll)
		if ( preg_match( '/class=["\'].*?(section-scroll).*?["\']/', $output ) ) {
			add_filter(
				'arts/optimizer/preloads/assets_map',
				function( $map ) {
					$map['SectionScrollJS'] = esc_url( ARTS_RHYE_CORE_PLUGIN_URL . '/modules/sectionScroll/sectionScroll.min.js?ver=' . ARTS_RHYE_CORE_PLUGIN_VERSION );

					return $map;
				}
			);
		}

		return $output;
	}
}

/**
 * LCP Image
 */
add_filter( 'arts/optimizer/preloads/output', 'arts_lcp_preload_link' );
if ( ! function_exists( 'arts_lcp_preload_link' ) ) {
	function arts_lcp_preload_link( $output ) {
		$matches       = array();
		$lcp_image_url = null;
		$lcp_image_id  = null;

		if ( preg_match( '/<img[^>]*fetchpriority=["\']high["\'][^>]*data-id=["\']([^"\']+)["\'][^>]*>/', $output, $matches ) ) {
			$lcp_image_id = $matches[1];
		} elseif ( preg_match( '/<img[^>]*fetchpriority=["\']high["\'][^>]*data-src=["\']([^"\']+)["\'][^>]*>/', $output, $matches ) ) {
			$lcp_image_url = $matches[1];
		}

		if ( ! $lcp_image_id && $lcp_image_url ) {
			$lcp_image_id = attachment_url_to_postid( $lcp_image_url );
		}

		if ( $lcp_image_id ) {
			add_filter(
				'arts/optimizer/preloads/images_map',
				function( $preload_images_map ) use ( $lcp_image_id ) {
					$preload_images_map['LCPImage'] = $lcp_image_id;

					return $preload_images_map;
				}
			);
		}

		return $output;
	}
}
