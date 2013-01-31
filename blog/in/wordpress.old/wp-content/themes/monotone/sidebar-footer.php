<?php
/**
 * @package WordPress
 * @subpackage Monotone
 */
?>

<?php
	/* The footer widget area is triggered if any of the areas
	 * have widgets. So let's check that first.
	 *
	 * If none of the sidebars have widgets, then let's bail early.
	 */
	if ( ! is_active_sidebar( 'sidebar-1' ) )
		return;
	// If we get this far, we have widgets. Let do this.
?>
<div id="widgets">
	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
	<div class="widget-area clearfix">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</div><!-- #first .widget-area -->
	<?php endif; ?>
</div><!-- #widgets -->