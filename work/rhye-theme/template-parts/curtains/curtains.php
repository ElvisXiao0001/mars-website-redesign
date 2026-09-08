<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$ajax_enabled = get_theme_mod( 'ajax_enabled', false );

?>

<!-- page curtain AJAX transition -->
<div class="curtain transition-curtain" id="js-page-transition-curtain">
	<div class="curtain__wrapper-svg">
		<?php get_template_part( 'template-parts/svg/svg', 'curtain' ); ?>
	</div>
</div>
<!-- - page curtain AJAX transition -->

<?php if ( $ajax_enabled ) : ?>
	<!-- header curtain AJAX transition -->
	<div class="header-curtain header-curtain_transition curtain" id="js-header-curtain-transition">
		<div class="curtain__wrapper-svg">
			<?php get_template_part( 'template-parts/svg/svg', 'curtain' ); ?>
		</div>
	</div>
	<!-- - header curtain AJAX transition -->
<?php endif; ?>

<!-- header curtain show/hide -->
<div class="header-curtain curtain" id="js-header-curtain">
	<div class="curtain__wrapper-svg">
		<?php get_template_part( 'template-parts/svg/svg', 'curtain' ); ?>
	</div>
</div>
<!-- - header curtain show/hide -->
