<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

if ( ! function_exists( 'arts_is_preloader_enabled' ) ) {
	function arts_is_preloader_enabled() {
		$preloader_enabled   = get_theme_mod( 'preloader_enabled', false );
		$preloader_show_once = get_theme_mod( 'preloader_show_once', false );

		if ( isset( $_GET['preloader'] ) ) {
			$force_preloader_enabled = preg_replace( '/[^-a-zA-Z0-9_]/', '', $_GET['preloader'] );

			if ( $force_preloader_enabled === 'yes' ) {
				return true;
			} elseif ( $force_preloader_enabled === 'no' ) {
				return false;
			}
		}

		if ( $preloader_enabled && ! Utilities::is_elementor_editor_active() ) {
			if ( $preloader_show_once ) {
				return ! Utilities::is_referer_from_same_domain();
			}

			return true;
		}
	}
}
