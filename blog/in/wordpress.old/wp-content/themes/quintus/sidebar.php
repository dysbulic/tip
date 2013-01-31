<?php
/**
 * @package WordPress
 * @subpackage Quintus
 */
?>

		<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		<div id="secondary" class="widget-area" role="complementary">
			<?php do_action( 'before_sidebar' ); ?>
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</div><!-- #secondary .widget-area -->
		<?php endif; ?>