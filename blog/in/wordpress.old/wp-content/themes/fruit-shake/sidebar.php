<?php
/**
 * @package WordPress
 * @subpackage Fruit Shake
 */
?>
		<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		<div id="secondary" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</div><!-- #secondary .widget-area -->
		<?php endif; ?>
