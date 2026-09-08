<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'arts_get_element_cursor_attributes' ) ) {
	function arts_get_element_cursor_attributes( $args ) {
		$defaults = array(
			'enabled'     => false,
			'scale'       => 1.0,
			'magnetic'    => false,
			'hide_native' => false,
			'label'       => false,
			'icon_class'  => false,
			'color'       => false,
			'return'      => 'string',
		);

		$cursor_interactive_enabled = get_theme_mod( 'cursor_interactive_enabled', true );
		$args                       = wp_parse_args( $args, $defaults );
		$attrs                      = '';
		$attrs_arr                  = array();

		if ( ! $cursor_interactive_enabled || ! $args['enabled'] ) {
			return $args['return'] === 'string' ? $attrs : $attrs_arr;
		}

		if ( $args['enabled'] ) {
			$attrs     .= ' data-arts-cursor=true';
			$attrs_arr += array( 'data-arts-cursor' => 'true' );
		}
		if ( isset( $args['scale'] ) ) {
			$attrs     .= ' data-arts-cursor-scale=' . floatval( $args['scale'] );
			$attrs_arr += array( 'data-arts-cursor-scale' => floatval( $args['scale'] ) );
		}

		if ( $args['magnetic'] ) {
			$attrs     .= ' data-arts-cursor-magnetic=true';
			$attrs_arr += array( 'data-arts-cursor-magnetic' => 'true' );
		}

		if ( $args['hide_native'] ) {
			$attrs     .= ' data-arts-cursor-hide-native=true';
			$attrs_arr += array( 'data-arts-cursor-hide-native' => 'true' );
		}

		if ( $args['label'] ) {
			$attrs     .= ' data-arts-cursor-label=' . $args['label'];
			$attrs_arr += array( 'data-arts-cursor-label' => $args['label'] );
		}

		if ( $args['color'] ) {
			$attrs     .= ' data-arts-cursor-color=' . $args['color'];
			$attrs_arr += array( 'data-arts-cursor-color' => $args['color'] );
		}

		if ( $args['icon_class'] ) {
			$attrs     .= ' data-arts-cursor-icon=' . $args['icon_class'];
			$attrs_arr += array( 'data-arts-cursor-icon' => $args['icon_class'] );
		}

		return $args['return'] === 'string' ? trim( $attrs ) : $attrs_arr;
	}
}
