<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \Arts\Utilities\Utilities;

$ajax_enabled              = get_theme_mod( 'ajax_enabled', false );
$outdated_browsers_enabled = get_theme_mod( 'outdated_browsers_enabled', false );

$footer_has_upper_large_section = is_active_sidebar( 'footer-sidebar-upper-large' );
$footer_has_upper_section       = arts_footer_has_active_sidebars( 'upper' );
$footer_has_lower_section       = arts_footer_has_active_sidebars( 'lower' );
$footer_hide                    = Utilities::get_overridden_document_option( 'footer_hide', 'page_footer_settings_overridden' );

?>
		<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) : ?>
			<?php if ( function_exists( 'hfe_render_footer' ) && arts_hfe_footer_enabled() && ! $footer_hide ) : ?>
				<?php arts_hfe_render_footer(); ?>
			<?php else : ?>
				<?php if ( ! $footer_hide && ! is_404() && ( $footer_has_upper_large_section || $footer_has_upper_section || $footer_has_lower_section ) ) : ?>
				<!-- PAGE FOOTER -->
					<?php get_template_part( 'template-parts/footer/footer' ); ?>
				<!-- - PAGE FOOTER -->
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>
		</main>
	</div>
	<!-- - PAGE MAIN -->
	<?php if ( $ajax_enabled ) : ?>
		<!-- Curtain Cursor Blocking -->
		<div class="blocking-curtain" id="js-page-blocking-curtain"></div>
		<!-- - Curtain Cursor Blocking -->
		</div>
	<?php endif; ?>
	<?php if ( $outdated_browsers_enabled ) : ?>
		<div id="outdated"></div>
	<?php endif; ?>
	<?php wp_footer(); ?>
</body>
</html>
