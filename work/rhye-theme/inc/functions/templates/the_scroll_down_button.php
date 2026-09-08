<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'arts_the_scroll_down_button' ) ) {
	/**
	 * Print the scroll down button HTML markup.
	 *
	 * @param array $args {
	 *  Optional. An array of arguments.
	 *
	 *  @type string $label     The label of the button. Default is 'Scroll Down'.
	 *  @type array  $cursor    Cursor settings.
	 *  @type bool   $animation Whether to animate the button. Default is true.
	 *  @type bool   $enabled   Whether to display the button. Default is true.
	 * }
	 */
	function arts_the_scroll_down_button( $args ) {
		$defaults = array(
			'label'     => esc_html__( 'Scroll Down', 'rhye' ),
			'cursor'    => array(
				'enabled'          => true,
				'hideNativeCursor' => 'true',
				'scale'            => '0',
			),
			'animation' => true,
			'enabled'   => true,
		);

		$attrs_button                   = '';
		$attrs_circle                   = '';
		$animations_scroll_down_enabled = get_theme_mod( 'animations_scroll_down_enabled', true );

		$args = wp_parse_args( $args, $defaults );

		if ( $animations_scroll_down_enabled && $args['animation'] ) {
			$attrs_button = 'data-arts-os-animation=true';
		}

		if ( $args['enabled'] ) {
			$attrs_circle = 'data-arts-scroll-down=true';
		}

		?>
		<div class="circle-button js-circle-button" <?php echo esc_attr( $attrs_button ); ?>>
			<!-- curved label -->
			<div class="circle-button__outer">
				<?php if ( ! empty( $args['label'] ) ) : ?>
					<div class="circle-button__wrapper-label">
						<div class="circle-button__label subheading"><?php echo esc_html( $args['label'] ); ?></div>
					</div>
				<?php endif; ?>
			</div>
			<!-- - curved label -->
			<!-- geometry wrapper -->
			<div class="circle-button__inner">
				<div class="circle-button__circle" <?php echo esc_attr( $attrs_circle ); ?> data-arts-cursor="true" data-arts-cursor-hide-native="true" data-arts-cursor-scale="0">
					<?php get_template_part( 'template-parts/svg/svg', 'circle' ); ?>
				</div>
				<!-- browsers WITH touch support -->
				<div class="circle-button__icon circle-button__icon-touch">
					<?php get_template_part( 'template-parts/svg/svg', 'arrow-down' ); ?>
				</div>
				<!-- - browsers WITH touch support -->
				<!-- - browsers WITHOUT touch support -->
				<div class="circle-button__icon circle-button__icon-mouse">
					<?php get_template_part( 'template-parts/svg/svg', 'mouse' ); ?>
				</div>
				<!-- - browsers WITHOUT touch support -->
			</div>
			<!-- - geometry wrapper -->
		</div>
		<?php
	}
}