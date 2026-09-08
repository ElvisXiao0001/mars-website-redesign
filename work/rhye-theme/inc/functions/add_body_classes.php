<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

add_filter( 'body_class', 'arts_add_body_classes' );
if ( ! function_exists( 'arts_add_body_classes' ) ) {
	/**
	 * Add additional classes for <body> element.
	 *
	 * @param array $classes An array of existing body classes.
	 *
	 * @return array Modified array of body classes.
	 */
	function arts_add_body_classes( $classes ) {
		$page_ajax_to_enabled   = Utilities::get_document_option( 'page_ajax_to_enabled', null, 'yes' );
		$page_ajax_from_enabled = Utilities::get_document_option( 'page_ajax_from_enabled', null, 'yes' );
		$body_classes           = array();

		// Disable AJAX transitions TO this page
		if ( ! $page_ajax_to_enabled ) {
			$body_classes[] = 'page-no-ajax';
		}

		// Disable AJAX transitions FROM this page
		if ( ! $page_ajax_from_enabled ) {
			$body_classes[] = 'no-ajax';
		}

		$classes = array_merge( $classes, $body_classes );

		return $classes;
	}
}
