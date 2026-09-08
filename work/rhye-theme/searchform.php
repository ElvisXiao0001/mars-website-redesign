<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$input_id = uniqid( 'search-form-' );
$label_id = uniqid( 'label-' );

?>

<form method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="input-float input-search js-input-float">
		<input type="search" id="<?php echo esc_attr( $input_id ); ?>" class="input-float__input input-search__input" value="<?php echo get_search_query(); ?>" name="s" aria-labelledby="<?php echo esc_attr( $label_id ); ?>"/><span id="<?php echo esc_attr( $label_id ); ?>" class="input-float__label"><?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'rhye' ); ?></span>
		<button type="submit" class="input-search__submit"><i class="material-icons">search</i></button>
	</div>
</form>
