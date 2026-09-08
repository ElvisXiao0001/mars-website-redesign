<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'arts_get_footer_columns' ) ) {
	/**
	 * Retrieve the column classes for the footer based on the given option.
	 *
	 * @param string $option The option for the footer columns layout. Possible values:
	 *                       '7_5' - First column 7 units, second column 5 units.
	 *                       '6_6' - Both columns 6 units each.
	 *                       '5_7' - First column 5 units, second column 7 units.
	 *                       Any other value will default to both columns being 12 units.
	 *
	 * @return array An array containing the column classes for the footer.
	 */
	function arts_get_footer_columns( $option ) {
		$columns = array();

		switch ( $option ) {
			case '7_5':
				$columns[0] = 'col-lg-7';
				$columns[1] = 'col-lg-5';
				break;
			case '6_6':
				$columns[0] = 'col-lg-6';
				$columns[1] = 'col-lg-6';
				break;
			case '5_7':
				$columns[0] = 'col-lg-5';
				$columns[1] = 'col-lg-7';
				break;
			default:
				$columns[0] = 'col-12';
				$columns[1] = 'col-12';
				break;
		}

		return $columns;
	}
}
