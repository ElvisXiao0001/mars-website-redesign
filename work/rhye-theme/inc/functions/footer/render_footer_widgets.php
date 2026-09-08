<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'arts_render_footer_widgets' ) ) {
	/**
	 * Render the footer widgets based on the specified suffix and column configuration.
	 *
	 * @param string $suffix          The suffix to identify the footer section (default: 'upper').
	 * @param int    $default_columns The default number of columns to display (default: 4).
	 *
	 * @return void
	 */
	function arts_render_footer_widgets( $suffix = 'upper', $default_columns = 4 ) {
		$footer_columns         = get_theme_mod( 'footer_columns_' . $suffix, $default_columns );
		$text_align_enabled     = get_theme_mod( 'text_align_enabled_' . $suffix, false );
		$increase_width_enabled = get_theme_mod( 'increase_width_enabled_' . $suffix, true ); // increase width of the 2nd column in 3 columns layout
		$class_col              = '';

		switch ( $footer_columns ) {
			case 1: {
				$class_col = 'col-12 text-center';
				break;
			}
			case 2: {
				$class_col = 'col-lg-6 col-sm-6 col-12';
				break;
			}
			case 3: {
				$class_col = 'col-lg-4 col-12';
				break;
			}
			default: {
				$class_col = 'col-lg-3 col-sm-6 col-12';
				break;
			}
		}

		for ( $i = 1; $i <= $footer_columns; $i++ ) {

			if ( is_active_sidebar( 'footer-sidebar-' . $suffix . $i ) ) {

				$class_col_order = ' order-lg-' . $i;

				// 2 columns layout: 1st column
				if ( $footer_columns == 2 && $i == 1 ) {
					$class_col  = 'col-lg-6';
					$class_col .= $text_align_enabled ? ' text-center text-lg-left' : '';
				}

				// 2 columns layout: 2nd column
				if ( $footer_columns == 2 && $i == 2 ) {
					$class_col  = 'col-lg-6';
					$class_col .= $text_align_enabled ? ' text-center text-lg-right' : '';
				}

				// 3 columns layout: 1st column
				if ( $footer_columns == 3 && $i == 1 ) {
					$class_col  = $increase_width_enabled ? 'col-lg-3' : 'col-lg-4';
					$class_col .= $text_align_enabled ? ' text-center text-lg-left' : '';
				}

				// 3 columns layout: 2nd column
				if ( $footer_columns == 3 && $i == 2 ) {
					$class_col  = $increase_width_enabled ? 'col-lg-6' : 'col-lg-4';
					$class_col .= $text_align_enabled ? ' text-center' : '';
				}

				// 3 columns layout: 3rd column
				if ( $footer_columns == 3 && $i == 3 ) {
					$class_col  = $increase_width_enabled ? 'col-lg-3' : 'col-lg-4';
					$class_col .= $text_align_enabled ? ' text-center text-lg-right' : '';
				}

				// column order classes
				if ( get_theme_mod( 'order_column_' . $i . '_' . $suffix ) > 1 ) {
					$order           = get_theme_mod( 'order_column_' . $i . '_' . $suffix );
					$class_col_order = ' order-lg-' . $i . ' order-' . $order;
				}

				?>
				<div class="<?php echo esc_attr( $class_col . $class_col_order ); ?> footer__column">
					<?php dynamic_sidebar( 'footer-sidebar-' . $suffix . $i ); ?>
				</div>
				<?php

			}
		}
	}
}
