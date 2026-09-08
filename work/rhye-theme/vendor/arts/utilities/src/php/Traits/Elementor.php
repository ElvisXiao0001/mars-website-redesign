<?php

namespace Arts\Utilities\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Trait
 *
 * Acts as a container for all Elementor-related traits, providing
 * a single entry point for Elementor functionality.
 *
 * @package Arts\Utilities\Traits
 * @since 1.0.0
 */
trait Elementor {
	use Elementor\Controls;
	use Elementor\Document;
	use Elementor\Kit;
	use Elementor\Plugin;
	use Elementor\ResponsiveControls;
	use Elementor\ThemeBuilder;
}
