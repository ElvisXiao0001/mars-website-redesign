<?php

namespace Arts\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Utilities Class
 *
 * A comprehensive collection of utility methods that can be used during WordPress theme
 * or plugin development. This class uses traits to organize functionality into logical groups.
 *
 * @package Arts\Utilities
 * @since 1.0.0
 */
class Utilities {
	use Traits\ACF;
	use Traits\Blog;
	use Traits\Conditions;
	use Traits\ContactForm7;
	use Traits\Elementor;
	use Traits\Fonts;
	use Traits\Frontend;
	use Traits\Images;
	use Traits\LoopedPosts;
	use Traits\Markup;
	use Traits\Plugin;
	use Traits\Query;
	use Traits\Shortcodes;
	use Traits\Strings;
	use Traits\Taxonomies;
	use Traits\Theme;
	use Traits\TypeGuards;
	use Traits\URL;
	use Traits\WooCommerce;
}
