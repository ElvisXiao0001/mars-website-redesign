<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'admin_init', 'arts_hicpo_fix_capabilities' );
if ( ! function_exists( 'arts_hicpo_fix_capabilities' ) ) {
	/**
	 * Fixes incorrect capabilities for the Intuitive Custom Post Order plugin.
	 *
	 * This function addresses the issue described in the following GitHub issue:
	 * https://github.com/hijiriworld/intuitive-custom-post-order/issues/66
	 *
	 * It adds the 'hicpo_load_script_css' capability to the 'administrator' and 'editor' roles.
	 *
	 * @return void
	 */
	function arts_hicpo_fix_capabilities() {
		$administrator = get_role( 'administrator' );
		if ( $administrator ) {
			$administrator->add_cap( 'hicpo_load_script_css' );
		}

		$editor = get_role( 'editor' );
		if ( $editor ) {
			$editor->add_cap( 'hicpo_load_script_css' );
		}
	}
}
