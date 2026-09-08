<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<?php if ( is_active_sidebar( 'blog-sidebar' ) ) : ?>
	<aside class="sidebar sidebar_no-margin-last-widget widget-area">
		<?php	dynamic_sidebar( 'blog-sidebar' ); ?>
	</aside>
<?php endif; ?>
