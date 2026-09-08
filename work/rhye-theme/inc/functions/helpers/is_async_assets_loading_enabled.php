<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'arts_is_async_assets_loading_enabled' ) ) {
	/**
	 * Check the theme compatibility with async assets loading mode and if it's actually enabled
	 *
	 * @return bool
	 * @deprecated 2.4.0
	 */
	function arts_is_async_assets_loading_enabled() {
		return true;
	}
}
