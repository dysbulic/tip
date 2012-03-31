<?php
/**
 * The Footer widget areas.
 *
 * @package WordPress
 * @subpackage Nishita
 */
?>

<?php
	/* The footer widget area is triggered if any of the areas
	 * have widgets. So let's check that first.
	 *
	 * If none of the sidebars have widgets, then let's bail early.
	 */
	if (   ! is_active_sidebar( 'footer-area-one'  )
		&& ! is_active_sidebar( 'footer-area-two' )
		&& ! is_active_sidebar( 'footer-area-three'  )
	)
		return;
	// If we get this far, we have widgets. Let do this.
?>
<div id="sidebar-footer-wrapper">
	<div id="sidebar-footer" class="widget-area">
		<?php if ( is_active_sidebar( 'footer-area-one' ) ) : ?>
		<div id="first" role="complementary">
			<ul>
				<?php dynamic_sidebar( 'footer-area-one' ); ?>
			</ul>
		</div><!-- #first -->
		<?php endif; ?>
	
		<?php if ( is_active_sidebar( 'footer-area-two' ) ) : ?>
		<div id="second" role="complementary">
			<ul>
				<?php dynamic_sidebar( 'footer-area-two' ); ?>
			</ul>
		</div><!-- #second -->
		<?php endif; ?>
	
		<?php if ( is_active_sidebar( 'footer-area-three' ) ) : ?>
		<div id="third" role="complementary">
			<ul>
				<?php dynamic_sidebar( 'footer-area-three' ); ?>
			</ul>
		</div><!-- #third -->
		<?php endif; ?>
	</div><!-- #sidebar-footer -->
</div><!-- #sidebar-footer-wrapper -->