<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

add_action( 'wp_enqueue_scripts', 'arts_enqueue_styles', 20 );
if ( ! function_exists( 'arts_enqueue_styles' ) ) {
	/**
	 * Enqueue Theme CSS Files
	 *
	 * @return void
	 */
	function arts_enqueue_styles() {
		$is_elementor_canvas_template                    = Utilities::get_document_option( 'template' ) === 'elementor_canvas';
		$ajax_enabled                                    = get_theme_mod( 'ajax_enabled', false );
		$preloader_enabled                               = arts_is_preloader_enabled();
		$smooth_scroll                                   = arts_is_smooth_scroll();
		$is_smooth_scrollbar                             = $smooth_scroll === 'smooth-scrollbar';
		$is_smooth_scroll_lenis                          = $smooth_scroll === 'lenis';
		$smooth_scroll_elementor_canvas_template_enabled = get_theme_mod( 'smooth_scroll_elementor_canvas_template_enabled', true );
		$cf_7_modals_enabled                             = get_theme_mod( 'cf_7_modals_enabled', true );
		$cursor_progress_enabled                         = get_theme_mod( 'cursor_progress_enabled', true );
		$main_style_deps                                 = array();

		// Force load Elementor assets
		// on non-Elementor pages with AJAX turned on
		if ( class_exists( '\Elementor\Frontend' ) && ! Utilities::is_built_with_elementor() && $ajax_enabled ) {
			\Elementor\Frontend::instance()->enqueue_styles();
		}

		if ( $preloader_enabled ) {
			wp_enqueue_style( 'preloader', ARTS_THEME_URL . '/modules/preloader/preloader.min.css', array(), ARTS_THEME_VERSION );
			$main_style_deps[] = 'preloader';

			wp_enqueue_style( 'cursor', ARTS_THEME_URL . '/modules/cursor/cursor.min.css', array(), ARTS_THEME_VERSION );
			$main_style_deps[] = 'cursor';
		}

		// fallback font if fonts are not set
		if ( ! class_exists( 'Kirki' ) ) {
			wp_enqueue_style( 'rhye-fonts', '//fonts.googleapis.com/css?family=Cinzel:400,700%7CRaleway:500,500i,600,700&display=swap', array(), ARTS_THEME_VERSION );
		}

		if ( ! Utilities::is_elementor_feature_active( 'e_optimized_assets_loading' ) ) {
			wp_enqueue_style( 'swiper', ARTS_THEME_URL . '/css/swiper.min.css', array(), '6.4.15' );
		}

		if ( $is_smooth_scroll_lenis ) {
			wp_enqueue_style( 'lenis', ARTS_THEME_URL . '/css/lenis.min.css', array(), '1.1.18' );
			$main_style_deps[] = 'lenis';
		}

		wp_enqueue_style( 'bootstrap-grid', ARTS_THEME_URL . '/css/bootstrap-grid.min.css', array(), '4.3.1' );
		wp_enqueue_style( 'bootstrap-reboot', ARTS_THEME_URL . '/css/bootstrap-reboot.min.css', array(), '4.3.1' );
		wp_enqueue_style( 'elementor-icons-fa-brands' ); // FontAwesome 5 Brands from Elementor
		wp_enqueue_style( 'elementor-icons-fa-solid' ); // FontAwesome 5 Solid from Elementor
		wp_enqueue_style( 'material-icons', ARTS_THEME_URL . '/css/material-icons.min.css', array(), '3.0.1' );
		wp_enqueue_style( 'rhye-icons', ARTS_THEME_URL . '/css/rhye-icons.min.css', array(), ARTS_THEME_VERSION );
		wp_enqueue_style( 'rhye-main-style', ARTS_THEME_URL . '/css/main.css', $main_style_deps, ARTS_THEME_VERSION );
		wp_enqueue_style( 'rhye-theme-style', ARTS_THEME_URL . '/style.css', array(), ARTS_THEME_VERSION );

		// hide default Contact Form 7 response boxes if custom modals are enabled
		if ( $cf_7_modals_enabled ) {
			wp_add_inline_style( 'contact-form-7', wp_strip_all_tags( '.wpcf7-mail-sent-ok, .wpcf7 form.sent .wpcf7-response-output, .wpcf7-mail-sent-ng, .wpcf7 form.failed .wpcf7-response-output, .wpcf7 form.invalid .wpcf7-response-output { display: none !important; }' ) );
		}

		// Progress Cursor
		if ( $cursor_progress_enabled ) {
			wp_add_inline_style( 'rhye-main-style', trim( '.cursor-progress, .cursor-progress * { cursor: progress !important; }' ) );
		}

		// Minimize Cumulative Layout Shift (CLS) by applying overflow: hidden
		// in case the smooth scrolling is enabled
		if ( ! Utilities::is_elementor_editor_active() &&
				! isset( $_GET['smooth_scrolling'] ) &&
				( ( $is_smooth_scrollbar && ! $is_elementor_canvas_template ) ||
					( $is_smooth_scrollbar && $smooth_scroll_elementor_canvas_template_enabled && $is_elementor_canvas_template ) )
			) {
			wp_add_inline_style( 'rhye-main-style', trim( 'html.no-touchevents { overflow: hidden; }' ) );
		}
	}
}

add_action( 'wp_enqueue_scripts', 'arts_enqueue_polyfills', 20 );
if ( ! function_exists( 'arts_enqueue_polyfills' ) ) {
	/**
	 * Enqueue polyfills in <head>
	 *
	 * @return void
	 */
	function arts_enqueue_polyfills() {
		$outdated_browsers_enabled = get_theme_mod( 'outdated_browsers_enabled', false );

		if ( $outdated_browsers_enabled ) {
			wp_enqueue_script( 'outdated-browser-rework', ARTS_THEME_URL . '/js/outdated-browser-rework.min.js', array(), '1.1.0', false );
		}
	}
}

add_action( 'wp_enqueue_scripts', 'arts_enqueue_scripts', 50 );
if ( ! function_exists( 'arts_enqueue_scripts' ) ) {
	/**
	 * Enqueue Theme JS Files
	 *
	 * @return void
	 */
	function arts_enqueue_scripts() {
		$ajax_enabled = get_theme_mod( 'ajax_enabled', false );

		// Force load Elementor assets
		// on non-Elementor pages with AJAX turned on
		if ( class_exists( '\Elementor\Frontend' ) && ! Utilities::is_built_with_elementor() && $ajax_enabled ) {
			\Elementor\Frontend::instance()->enqueue_scripts();
		}

		if ( is_singular() && comments_open() ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// GSAP libraries
		$gsap_libraries_version = '3.9.1';

		wp_enqueue_script( 'imagesloaded' );
		wp_enqueue_script( 'drawsvg-plugin', ARTS_THEME_URL . '/js/DrawSVGPlugin.min.js', array( 'gsap' ), $gsap_libraries_version, true );
		wp_enqueue_script( 'gsap', ARTS_THEME_URL . '/js/gsap.min.js', array(), $gsap_libraries_version, true );
		wp_enqueue_script( 'jquery-lazy', ARTS_THEME_URL . '/js/jquery.lazy.min.js', array( 'jquery' ), '1.7.10', true );
		wp_enqueue_script( 'jquery-lazy-plugins', ARTS_THEME_URL . '/js/jquery.lazy.plugins.min.js', array( 'jquery', 'jquery-lazy' ), '1.7.10', true );
		wp_enqueue_script( 'morph-svg-plugin', ARTS_THEME_URL . '/js/MorphSVGPlugin.min.js', array( 'gsap' ), $gsap_libraries_version, true );
		wp_enqueue_script( 'split-text', ARTS_THEME_URL . '/js/SplitText.min.js', array( 'gsap' ), $gsap_libraries_version, true );
		wp_enqueue_script( 'scrolltrigger', ARTS_THEME_URL . '/js/ScrollTrigger.min.js', array( 'gsap' ), $gsap_libraries_version, true );
		wp_enqueue_script( 'rhye-base-components', ARTS_THEME_URL . '/modules/base/base.min.js', array( 'jquery', 'gsap', 'scrolltrigger' ), ARTS_THEME_VERSION, true );
		wp_enqueue_script( 'rhye-components', ARTS_THEME_URL . '/js/components.js', array( 'rhye-base-components' ), ARTS_THEME_VERSION, true );
	}
}

add_action( 'elementor/editor/after_enqueue_scripts', 'arts_enqueue_elementor_preview_scripts', 99 );
if ( ! function_exists( 'arts_enqueue_elementor_preview_scripts' ) ) {
	/**
	 * Enqueue scripts and styles for Elementor editor preview
	 *
	 * @return void
	 */
	function arts_enqueue_elementor_preview_scripts() {
		wp_enqueue_style( 'material-icons', ARTS_THEME_URL . '/css/material-icons.min.css', array(), '3.0.1' );
		wp_enqueue_style( 'rhye-icons', ARTS_THEME_URL . '/css/rhye-icons.min.css', array( 'elementor-icons' ), ARTS_THEME_VERSION );
		wp_enqueue_script( 'rhye-elementor-preview', ARTS_THEME_URL . '/js/elementor-preview.min.js', array(), ARTS_THEME_VERSION, true );
	}
}

add_action( 'wp_enqueue_scripts', 'arts_localize_data', 60 );
if ( ! function_exists( 'arts_localize_data' ) ) {
	/**
	 * Localize theme options
	 *
	 * @return void
	 */
	function arts_localize_data() {
		$smc_trigger_hook = get_theme_mod( 'smc_trigger_hook', 0.85 );

		$animations_on_scroll_reveal_timescale        = get_theme_mod( 'animations_on_scroll_reveal_timescale', 1 );
		$animations_overlay_menu_open_timescale       = get_theme_mod( 'animations_overlay_menu_open_timescale', 1 );
		$animations_overlay_menu_close_timescale      = get_theme_mod( 'animations_overlay_menu_close_timescale', 1 );
		$animations_preloader_timescale               = get_theme_mod( 'animations_preloader_timescale', 0.9 );
		$animations_ajax_transition_curtain_timescale = get_theme_mod( 'animations_ajax_transition_curtain_timescale', 1.0 );
		$animations_ajax_transition_image_timescale   = get_theme_mod( 'animations_ajax_transition_image_timescale', 1.0 );
		$animations_scroll_down_enabled               = get_theme_mod( 'animations_scroll_down_enabled', true );

		$ajax_enabled                       = get_theme_mod( 'ajax_enabled', false );
		$ajax_prevent_rules                 = rtrim( get_theme_mod( 'ajax_prevent_rules' ), ',' ); // remove all commas off the string
		$ajax_prevent_woocommerce_pages     = get_theme_mod( 'ajax_prevent_woocommerce_pages', false );
		$ajax_eval_inline_container_scripts = get_theme_mod( 'ajax_eval_inline_container_scripts', false );
		$ajax_load_missing_scripts          = get_theme_mod( 'ajax_load_missing_scripts', true );
		$ajax_load_missing_styles           = get_theme_mod( 'ajax_load_missing_styles', true );
		$ajax_update_script_nodes           = get_theme_mod( 'ajax_update_script_nodes' );

		$smooth_scroll_enabled                  = arts_is_smooth_scroll();
		$smooth_scroll_damping                  = get_theme_mod( 'smooth_scroll_damping', 0.12 );
		$smooth_scroll_render_by_pixels_enabled = get_theme_mod( 'smooth_scroll_render_by_pixels_enabled', true );
		$smooth_scroll_plugin_easing_enabled    = get_theme_mod( 'smooth_scroll_plugin_easing_enabled', true );
		$smooth_scroll_lenis_lerp               = get_theme_mod( 'smooth_scroll_lenis_lerp', 0.1 );
		$smooth_scroll_anchor_offset            = get_theme_mod( 'smooth_scroll_anchor_offset', 'header' );
		$smooth_scroll_anchor_offset_custom     = get_theme_mod( 'smooth_scroll_anchor_offset_custom', 0 );

		$ajax_custom_js         = get_theme_mod( 'ajax_custom_js' );
		$ajax_update_head_nodes = get_theme_mod( 'ajax_update_head_nodes' );

		$cursor_enabled                 = get_theme_mod( 'cursor_enabled', false );
		$cursor_label_slider            = get_theme_mod( 'cursor_label_slider', esc_html__( 'Drag Me', 'rhye' ) );
		$cursor_trailing_factor         = get_theme_mod( 'cursor_trailing_factor', 6 );
		$cursor_animation_duration      = get_theme_mod( 'cursor_animation_duration', 0.25 );
		$cursor_circle_arrows_enabled   = get_theme_mod( 'cursor_circle_arrows_enabled', true );
		$cursor_dots_enabled            = get_theme_mod( 'cursor_dots_enabled', true );
		$cursor_social_items_enabled    = get_theme_mod( 'cursor_social_items_enabled', true );
		$cursor_blog_pagination_enabled = get_theme_mod( 'cursor_blog_pagination_enabled', true );

		$cf_7_modals_enabled = get_theme_mod( 'cf_7_modals_enabled', true );

		$mobile_bar_fix_enabled = get_theme_mod( 'mobile_bar_fix_enabled', true );
		$mobile_bar_fix_update  = get_theme_mod( 'mobile_bar_fix_update', true );

		$high_performance_gpu_enabled = get_theme_mod( 'high_performance_gpu_enabled', true );

		$scroll_down_offset_desktop = get_theme_mod( 'scroll_down_offset_desktop', 0.0 );
		$scroll_down_offset_mobile  = get_theme_mod( 'scroll_down_offset_mobile', 0.0 );

		if ( $ajax_enabled &&
		 $ajax_prevent_woocommerce_pages &&
		 class_exists( 'woocommerce' ) &&
		 function_exists( 'arts_get_woocommerce_urls' ) ) {

			// add AJAX rules that prevents all "TO" WooCommerce pages
			$woocommerce_urls        = Utilities::get_woocommerce_urls();
			$woocommerce_urls_string = '';

			foreach ( $woocommerce_urls as $url ) {
				if ( ! empty( $url ) ) {
					$woocommerce_urls_string .= 'a[href*="' . $url . '"],';
				}
			}

			$ajax_prevent_rules .= $woocommerce_urls_string;

			// add AJAX rule that prevents all the links "FROM" WooCommerce pages to other website pages
			$ajax_prevent_rules .= '.woocommerce-page a';

		}

		wp_localize_script(
			'rhye-components',
			'theme',
			array(
				'isFirstLoad'     => esc_js( true ),
				'themeURL'        => esc_js( ARTS_THEME_URL ),
				'ajaxURL'         => admin_url( 'admin-ajax.php' ),
				'ajax'            => array(
					'enabled'                    => esc_js( $ajax_enabled ),
					'preventRules'               => $ajax_prevent_rules,
					'evalInlineContainerScripts' => esc_js( $ajax_eval_inline_container_scripts ),
					'loadMissingScripts'         => esc_js( $ajax_load_missing_scripts ),
					'loadMissingStyles'          => esc_js( $ajax_load_missing_styles ),
					'updateScriptNodes'          => $ajax_update_script_nodes,
				),
				'animations'      => array(
					'triggerHook' => esc_js( $smc_trigger_hook ),
					'timeScale'   => array(
						'onScrollReveal'            => esc_js( $animations_on_scroll_reveal_timescale ),
						'overlayMenuOpen'           => esc_js( $animations_overlay_menu_open_timescale ),
						'overlayMenuClose'          => esc_js( $animations_overlay_menu_close_timescale ),
						'preloader'                 => esc_js( $animations_preloader_timescale ),
						'ajaxCurtainTransition'     => esc_js( $animations_ajax_transition_curtain_timescale ),
						'ajaxFlyingImageTransition' => esc_js( $animations_ajax_transition_image_timescale ),
					),
					'scrollDown'  => array(
						'enabled'       => esc_js( $animations_scroll_down_enabled ),
						'offsetDesktop' => esc_js( $scroll_down_offset_desktop ),
						'offsetMobile'  => esc_js( $scroll_down_offset_mobile ),
					),
				),
				'smoothScroll'    => array(
					'enabled'             => esc_js( $smooth_scroll_enabled === 'smooth-scrollbar' ),
					'damping'             => esc_js( $smooth_scroll_damping ),
					'renderByPixels'      => esc_js( $smooth_scroll_render_by_pixels_enabled ),
					'continuousScrolling' => $smooth_scroll_plugin_easing_enabled ? false : true,
					'plugins'             => array(
						'edgeEasing' => esc_js( $smooth_scroll_plugin_easing_enabled ),
					),
					'lenisLerp'           => esc_js( $smooth_scroll_lenis_lerp ),
					'anchorOffset'        => esc_js( $smooth_scroll_anchor_offset ),
					'anchorOffsetCustom'  => esc_js( $smooth_scroll_anchor_offset_custom ),
				),
				'cursorFollower'  => array(
					'enabled'           => esc_js( $cursor_enabled ),
					'labels'            => array(
						'slider' => esc_js( $cursor_label_slider ),
					),
					'factorTrailing'    => esc_js( $cursor_trailing_factor ),
					'animationDuration' => esc_js( $cursor_animation_duration ),
					'elements'          => array(
						'circleArrows'   => esc_js( $cursor_circle_arrows_enabled ),
						'sliderDots'     => esc_js( $cursor_dots_enabled ),
						'socialItems'    => esc_js( $cursor_social_items_enabled ),
						'blogPagination' => esc_js( $cursor_blog_pagination_enabled ),
					),
				),
				'contactForm7'    => array(
					'customModals' => esc_js( $cf_7_modals_enabled ),
				),
				'customJSInit'    => trim( $ajax_custom_js ),
				'updateHeadNodes' => esc_js( $ajax_update_head_nodes ),
				'mobileBarFix'    => array(
					'enabled' => esc_js( $mobile_bar_fix_enabled ),
					'update'  => esc_js( $mobile_bar_fix_update ),
				),
				'elementor'       => array(
					'isEditor' => esc_js( Utilities::is_elementor_editor_active() ),
					'features' => array(
						'optimizedAssetsLoading' => esc_js( Utilities::is_elementor_feature_active( 'e_optimized_assets_loading' ) ),
					),
				),
				'promises'        => array(),
				'highPerformance' => esc_js( $high_performance_gpu_enabled ),
				'offscreenCanvas' => false,
				'assets'          => apply_filters(
					'rhye/theme/frontend/assets',
					array(
						'swiper-js'                 => array(
							'id'      => 'swiper-js',
							'type'    => 'script',
							'src'     => ARTS_THEME_URL . '/js/swiper.min.js',
							'cache'   => true,
							'version' => '6.4.15',
						),
						'bootstrap-modal-js'        => array(
							'id'      => 'bootstrap-modal-js',
							'type'    => 'script',
							'src'     => ARTS_THEME_URL . '/js/bootstrap-modal.min.js',
							'cache'   => true,
							'version' => '4.3.1',
						),
						'bootstrap-util-js'         => array(
							'id'      => 'bootstrap-util-js',
							'type'    => 'script',
							'src'     => ARTS_THEME_URL . '/js/bootstrap-util.min.js',
							'cache'   => true,
							'version' => '4.3.1',
						),
						'isotope-js'                => array(
							'id'      => 'isotope-js',
							'type'    => 'script',
							'src'     => ARTS_THEME_URL . '/js/isotope.pkgd.min.js',
							'cache'   => true,
							'version' => '3.0.6',
						),
						'circletype-js'             => array(
							'id'      => 'circletype-js',
							'type'    => 'script',
							'src'     => ARTS_THEME_URL . '/js/circletype.min.js',
							'cache'   => true,
							'version' => '2.3.1',
						),
						'pjax-js'                   => array(
							'id'      => 'pjax-js',
							'type'    => 'script',
							'src'     => ARTS_THEME_URL . '/modules/PJAX/PJAX.min.js',
							'cache'   => true,
							'version' => ARTS_THEME_VERSION,
						),
						'cursor-js'                 => array(
							'id'      => 'cursor-js',
							'type'    => 'script',
							'src'     => ARTS_THEME_URL . '/modules/cursor/cursor.min.js',
							'cache'   => true,
							'version' => ARTS_THEME_VERSION,
						),
						'cursor-css'                => array(
							'id'      => 'cursor-css',
							'type'    => 'style',
							'src'     => ARTS_THEME_URL . '/modules/cursor/cursor.min.css',
							'cache'   => true,
							'version' => ARTS_THEME_VERSION,
						),
						'smooth-scrolling-js'       => array(
							'id'      => 'smooth-scrolling-js',
							'type'    => 'script',
							'src'     => ARTS_THEME_URL . '/modules/smoothScroll/smoothScroll.min.js',
							'cache'   => true,
							'version' => ARTS_THEME_VERSION,
						),
						'smooth-scrolling-lenis-js' => array(
							'id'      => 'smooth-scrolling-lenis-js',
							'type'    => 'script',
							'src'     => ARTS_THEME_URL . '/modules/smoothScrollLenis/smoothScrollLenis.min.js',
							'cache'   => true,
							'version' => ARTS_THEME_VERSION,
						),
						'section-masthead-js'       => array(
							'id'      => 'section-masthead-js',
							'type'    => 'script',
							'src'     => ARTS_THEME_URL . '/modules/sectionMasthead/sectionMasthead.min.js',
							'cache'   => true,
							'version' => ARTS_THEME_VERSION,
						),
						'section-nav-projects-js'   => array(
							'id'      => 'section-nav-projects-js',
							'type'    => 'script',
							'src'     => ARTS_THEME_URL . '/modules/sectionNavProjects/sectionNavProjects.min.js',
							'cache'   => true,
							'version' => ARTS_THEME_VERSION,
						),
						'section-nav-projects-css'  => array(
							'id'      => 'section-nav-projects-css',
							'type'    => 'style',
							'src'     => ARTS_THEME_URL . '/modules/sectionNavProjects/sectionNavProjects.min.css',
							'cache'   => true,
							'version' => ARTS_THEME_VERSION,
						),
						'scroll-down-js'            => array(
							'id'      => 'scroll-down-js',
							'type'    => 'script',
							'src'     => ARTS_THEME_URL . '/modules/scrollDown/scrollDown.min.js',
							'cache'   => true,
							'version' => ARTS_THEME_VERSION,
						),
						'change-text-hover-js'      => array(
							'id'      => 'change-text-hover-js',
							'type'    => 'script',
							'src'     => ARTS_THEME_URL . '/modules/changeTextHover/changeTextHover.min.js',
							'cache'   => true,
							'version' => ARTS_THEME_VERSION,
						),
						'change-text-hover-css'     => array(
							'id'      => 'change-text-hover-css',
							'type'    => 'style',
							'src'     => ARTS_THEME_URL . '/modules/changeTextHover/changeTextHover.min.css',
							'cache'   => true,
							'version' => ARTS_THEME_VERSION,
						),
					),
				),
			)
		);
	}
}

add_action( 'customize_preview_init', 'arts_customize_preview_script' );
if ( ! function_exists( 'arts_customize_preview_script' ) ) {
	/**
	 * Enqueue script for WP Customizer live preview
	 *
	 * @return void
	 */
	function arts_customize_preview_script() {
		wp_enqueue_script( 'rhye-customizer-preview', ARTS_THEME_URL . '/js/customizer.min.js', array(), ARTS_THEME_VERSION, true );
	}
}

add_filter( 'autoptimize_filter_js_exclude', 'arts_ao_override_jsexclude', 10, 1 );
if ( ! function_exists( 'arts_ao_override_jsexclude' ) ) {
	/**
	 * Exclude certain JS from the aggregation
	 * function of Autoptimize plugin
	 *
	 * @param string $exclude The current value of JS exclude setting.
	 * @return string The modified value of JS exclude setting.
	 */
	function arts_ao_override_jsexclude( $exclude ) {
		return $exclude . ', outdated-browser-rework, elementor-gallery, lottie';
	}
}

add_filter( 'autoptimize_filter_html_noptimize', 'arts_ao_disable_html_minification_ajax', 10, 1 );
if ( ! function_exists( 'arts_ao_disable_html_minification_ajax' ) ) {
	/**
	 * Force disable HTML minification for Autoptimize plugin
	 * if AJAX transitions are enabled to avoid incorrect
	 * page rendering.
	 *
	 * @param bool $value The current value of HTML minification setting.
	 * @return bool The modified value of HTML minification setting.
	 */
	function arts_ao_disable_html_minification_ajax( $value ) {
		$ajax_enabled = get_theme_mod( 'ajax_enabled', false );

		if ( $ajax_enabled ) {
			$value = true;
		}

		return $value;
	}
}

add_action( 'wp_head', 'arts_add_noscript_styles' );
if ( ! function_exists( 'arts_add_noscript_styles' ) ) {
	/**
	 * SEO-friendly page rendering for noscript styles
	 *
	 * @return void
	 */
	function arts_add_noscript_styles() {
		?>
		<noscript><style>.header__col,.section-masthead[data-arts-os-animation]:not([data-arts-os-animation=animated])>*,[data-arts-os-animation]:not([data-arts-os-animation=animated]):not(.section-masthead){opacity:inherit!important;visibility:inherit!important}.section-masthead[data-arts-os-animation] .section-masthead__meta .post-meta li:not(:last-child):after{opacity:inherit!important}.circle-button__icon-touch,.preloader,img[data-src],video[data-src]{display:none!important}</style></noscript>
		<?php
	}
}
