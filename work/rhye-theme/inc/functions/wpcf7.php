<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Unwrap Form Fields from <p> tags
 */
add_filter( 'wpcf7_autop_or_not', '__return_false' );
