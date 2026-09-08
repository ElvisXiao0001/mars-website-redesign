<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$class_col_next                       = 'col-12 text-center';
$class_col_prev                       = 'col-12 text-center';
$post_prev_next_nav_next_first_mobile = get_theme_mod( 'post_prev_next_nav_next_first_mobile', true );
$post_prev_next_heading_preset        = get_theme_mod( 'post_prev_next_heading_preset', 'h4' );

$next_post = get_previous_post();
$next_link  = null;
$next_title = null;
$next_label = get_theme_mod( 'post_prev_next_nav_next_title', esc_html__( 'Next', 'rhye' ) );

$prev_post = get_next_post();
$prev_link  = null;
$prev_title = null;
$prev_label = get_theme_mod( 'post_prev_next_nav_prev_title', esc_html__( 'Prev', 'rhye' ) );

if ( $next_post ) {
	$next_link  = get_permalink( $next_post );
	$next_title = $next_post->post_title;
}

if ( $prev_post ) {
	$prev_link  = get_permalink( $prev_post );
	$prev_title = $prev_post->post_title;
}

if ( $prev_post && $next_post ) {
	$class_col_next = 'col-lg-5';
	$class_col_prev = 'col-lg-5';

	if ( $post_prev_next_nav_next_first_mobile ) {
		$class_col_next .= ' order-lg-2';
		$class_col_prev .= ' order-lg-1';
	}
}

?>

<?php if ( $next_post || $prev_post ) : ?>
	<div class="posts-navigation py-small mt-small">
		<div class="row row-gutters justify-content-between">
			<?php if ( $next_post ) : ?>
				<div class="posts-navigation__item posts-navigation__item_next col-gutters <?php echo esc_attr( $class_col_next ); ?>">
					<a class="posts-navigation__link <?php echo esc_attr( $post_prev_next_heading_preset ); ?>" href="<?php echo esc_attr( $next_link ); ?> ">
						<?php if ( ! empty( $next_label ) ) : ?>
							<div class="subheading mb-0-5"><?php echo esc_html( $next_label ); ?></div>
						<?php endif; ?>
						<?php echo esc_html( $next_title ); ?>
					</a>
				</div>
			<?php endif; ?>
			<?php if ( $prev_post ) : ?>
				<div class="posts-navigation__item posts-navigation__item_prev col-gutters <?php echo esc_attr( $class_col_prev ); ?>">
					<?php if ( ! empty( $prev_label ) ) : ?>
						<div class="subheading mb-0-5"><?php echo esc_html( $prev_label ); ?></div>
					<?php endif; ?>
					<a class="posts-navigation__link <?php echo esc_attr( $post_prev_next_heading_preset ); ?>" href="<?php echo esc_attr( $prev_link ); ?> ">
						<?php echo esc_html( $prev_title ); ?>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>
