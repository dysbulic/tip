<?php
/**
 * @package WordPress
 * @subpackage Selecta
 */
?>

<?php
	/* The footer widget area is triggered if any of the areas
	 * have widgets. So let's check that first.
	 *
	 * If none of the sidebars have widgets, then let's bail early.
	 */
	if ( ! is_active_sidebar( 'sidebar-2' )
		&& ! is_active_sidebar( 'sidebar-3' )
		&& ! is_active_sidebar( 'sidebar-4' )
	)
		return;
	// If we get this far, we have widgets. Let's do this.
?>

			<div id="footer-widget-area" class="clearfix" role="complementary">

				<div id="first" class="widget-area">
					<?php if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
						<ul class="xoxo">
							<?php dynamic_sidebar( 'sidebar-2' ); ?>
						</ul>
					<?php endif; ?>
				</div><!-- #first .widget-area -->

				<div id="second" class="widget-area">
					<?php if ( is_active_sidebar( 'sidebar-3' ) ) : ?>
						<ul class="xoxo">
							<?php dynamic_sidebar( 'sidebar-3' ); ?>
						</ul>
					<?php endif; ?>
				</div><!-- #second .widget-area -->

				<div id="third" class="widget-area">
					<?php if ( is_active_sidebar( 'sidebar-4' ) ) : ?>
						<ul class="xoxo">
							<?php dynamic_sidebar( 'sidebar-4' ); ?>
						</ul>
					<?php endif; ?>
				</div><!-- #third .widget-area -->

			</div><!-- #footer-widget-area -->