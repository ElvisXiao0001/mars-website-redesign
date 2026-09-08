<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$blog_read_more_label               = get_theme_mod( 'blog_read_more_label', esc_html__( 'Read More', 'rhye' ) );
$blog_ajax_image_transition_enabled = get_theme_mod( 'blog_ajax_image_transition_enabled', true );

$sr_html_content = sprintf(
	/* translators: %s: Post title. */
	esc_html__( ' about "%s"', 'rhye' ),
	get_the_title()
);

?>

<a href="<?php the_permalink(); ?>" class="button button_bordered bg-dark-1" data-hover="<?php echo esc_attr( $blog_read_more_label ); ?>"<?php if ( $blog_ajax_image_transition_enabled ) : ?> data-pjax-link="flyingImage"<?php endif; ?>><span class="button__label-hover"><?php echo esc_html( $blog_read_more_label ); ?><span class="screen-reader-text"><?php echo esc_html( $sr_html_content ); ?></span></span></a>
