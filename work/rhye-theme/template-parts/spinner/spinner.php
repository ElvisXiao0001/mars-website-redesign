<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$ajax_spinner_desktop_enabled = get_theme_mod( 'ajax_spinner_desktop_enabled', false );
$ajax_spinner_mobile_enabled  = get_theme_mod( 'ajax_spinner_mobile_enabled', true );
$class_spinner                = '';

if ( $ajax_spinner_desktop_enabled ) {
	$class_spinner .= ' d-lg-block';
} else {
	$class_spinner .= ' d-lg-none';
}

if ( $ajax_spinner_mobile_enabled ) {
	$class_spinner .= ' d-block';
} else {
	$class_spinner .= ' d-none';
}

?>

<svg class="spinner <?php echo esc_attr( trim( $class_spinner ) ); ?>" id="js-spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
	<circle class="spinner__path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
</svg>
