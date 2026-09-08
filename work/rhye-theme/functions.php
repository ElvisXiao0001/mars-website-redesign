<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once __DIR__ . '/vendor/autoload.php';

use \Arts\Utilities\Utilities;

/**
 * Theme Constants
 */
$theme_version = Utilities::get_parent_theme_version();

define( 'ARTS_THEME_SLUG', 'rhye' );
define( 'ARTS_THEME_PATH', get_template_directory() );
define( 'ARTS_THEME_URL', get_template_directory_uri() );
define( 'ARTS_THEME_VERSION', $theme_version );

require_once ARTS_THEME_PATH . '/inc/functions/constants.php';
require_once ARTS_THEME_PATH . '/inc/classes/class-arts-walker-nav-menu-overlay.php';
require_once ARTS_THEME_PATH . '/inc/classes/class-arts-walker-comment.php';

/**
 * Polyfills
 */
require_once ARTS_THEME_PATH . '/inc/functions/polyfills/get_page_by_title.php';

/**
 * Additional body classes
 */
require_once ARTS_THEME_PATH . '/inc/functions/add_body_classes.php';

/**
 * ACF: Registered Fields & Helpers
 */
require_once ARTS_THEME_PATH . '/inc/functions/acf/acf_fields.php';
require_once ARTS_THEME_PATH . '/inc/functions/acf/acf_helpers.php';

/**
 * Blog
 */
require_once ARTS_THEME_PATH . '/inc/functions/blog/add_pingback_url.php';
require_once ARTS_THEME_PATH . '/inc/functions/blog/comments.php';
require_once ARTS_THEME_PATH . '/inc/functions/blog/get_post_author.php';
require_once ARTS_THEME_PATH . '/inc/functions/blog/get_posts_categories.php';
require_once ARTS_THEME_PATH . '/inc/functions/blog/pagination.php';
require_once ARTS_THEME_PATH . '/inc/functions/blog/password_form.php';
require_once ARTS_THEME_PATH . '/inc/functions/blog/wrap_category_archive_count.php';

/**
 * Header Footer Elementor plugin
 */
require_once ARTS_THEME_PATH . '/inc/functions/hfe/hfe_get_header_attributes.php';
require_once ARTS_THEME_PATH . '/inc/functions/hfe/hfe_get_footer_attributes.php';
require_once ARTS_THEME_PATH . '/inc/functions/hfe/hfe_helpers.php';
require_once ARTS_THEME_PATH . '/inc/functions/hfe/hfe_render_header.php';
require_once ARTS_THEME_PATH . '/inc/functions/hfe/hfe_render_footer.php';

/**
 * Elementor Helpers
 */
require_once ARTS_THEME_PATH . '/inc/functions/elementor/elementor_canvas_template.php';
require_once ARTS_THEME_PATH . '/inc/functions/elementor/elementor_compatibility.php';
require_once ARTS_THEME_PATH . '/inc/functions/elementor/elementor_custom_icons.php';
require_once ARTS_THEME_PATH . '/inc/functions/elementor/elementor_helpers.php';

/**
 * Adobe Fonts (Typekit) & Self Hosted Fonts Support
 */
require_once ARTS_THEME_PATH . '/inc/functions/fonts/fonts.php';
require_once ARTS_THEME_PATH . '/inc/classes/class-arts-add-custom-fonts.php';

/**
 * Footer Widgets
 */
require_once ARTS_THEME_PATH . '/inc/functions/footer/footer_has_active_sidebars.php';
require_once ARTS_THEME_PATH . '/inc/functions/footer/get_footer_columns.php';
require_once ARTS_THEME_PATH . '/inc/functions/footer/render_footer_widgets.php';

/**
 * Theme Helpers & Enhancements
 */
require_once ARTS_THEME_PATH . '/inc/functions/helpers/get_gmap_key.php';
require_once ARTS_THEME_PATH . '/inc/functions/helpers/print_attributes.php';
require_once ARTS_THEME_PATH . '/inc/functions/helpers/ajax_get_pswp_gallery.php';
require_once ARTS_THEME_PATH . '/inc/functions/helpers/body_open.php';
require_once ARTS_THEME_PATH . '/inc/functions/helpers/get_all_image_sizes.php';
require_once ARTS_THEME_PATH . '/inc/functions/helpers/get_taxonomy_term_names.php';
require_once ARTS_THEME_PATH . '/inc/functions/helpers/get_element_cursor_attributes.php';
require_once ARTS_THEME_PATH . '/inc/functions/helpers/get_post_looped.php';
require_once ARTS_THEME_PATH . '/inc/functions/helpers/get_post_looped_overridden.php';
require_once ARTS_THEME_PATH . '/inc/functions/helpers/get_woocommerce_urls.php';
require_once ARTS_THEME_PATH . '/inc/functions/helpers/is_async_assets_loading_enabled.php';
require_once ARTS_THEME_PATH . '/inc/functions/helpers/is_referer_from_same_domain.php';
require_once ARTS_THEME_PATH . '/inc/functions/helpers/is_smooth_scroll.php';
require_once ARTS_THEME_PATH . '/inc/functions/helpers/is_preloader_enabled.php';
require_once ARTS_THEME_PATH . '/inc/functions/helpers/set_page_title.php';

/**
 * Functional Template Parts
 */
require_once ARTS_THEME_PATH . '/inc/functions/templates/the_arrow.php';
require_once ARTS_THEME_PATH . '/inc/functions/templates/the_lazy_image.php';
require_once ARTS_THEME_PATH . '/inc/functions/templates/the_scroll_down_button.php';

/**
 * Frontend Styles & Scripts
 */
require_once ARTS_THEME_PATH . '/inc/functions/add_preload_prefetch_links.php';
require_once ARTS_THEME_PATH . '/inc/functions/frontend.php';

/**
 * Get Main Container Attributes/Classes
 */
require_once ARTS_THEME_PATH . '/inc/functions/get_container_attributes.php';

/**
 * Nav Menu
 */
require_once ARTS_THEME_PATH . '/inc/functions/nav.php';

/**
 * Theme Support Features
 */
require_once ARTS_THEME_PATH . '/inc/functions/theme_support.php';

/**
 * Widget Areas
 */
require_once ARTS_THEME_PATH . '/inc/functions/widget_areas.php';

/**
 * WP Contact Form 7: Don't Wrap Form Fields Into </p>
 */
require_once ARTS_THEME_PATH . '/inc/functions/wpcf7.php';

/**
 * WPForms: Force enable "Load Assets Globally" option if AJAX is on
 */
require_once ARTS_THEME_PATH . '/inc/functions/wpforms.php';

/**
 * Customizer Panels
 */
require_once ARTS_THEME_PATH . '/inc/customizer/customizer.php';

/**
 * Remove rendering of SVG duotone filters
 */
require_once ARTS_THEME_PATH . '/inc/functions/remove_duotone_filters.php';

/**
 * Fix for Intuitive CPO plugin
 */
require_once ARTS_THEME_PATH . '/inc/functions/intuitive-cpo/fix_capabilities.php';

/**
 * Load Required Plugins
 */
require_once ARTS_THEME_PATH . '/inc/tgm/load_plugins.php';

/**
 * Demo Content Importer
 */
require_once ARTS_THEME_PATH . '/inc/importer/importer.php';

/**
 * Theme Updater
 */
require_once ARTS_THEME_PATH . '/inc/updater/updater.php';
