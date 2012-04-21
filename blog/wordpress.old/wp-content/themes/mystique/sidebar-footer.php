<?php
/**
 * @package WordPress
 * @subpackage Mystique
 */
?>

<?php
	/* The footer widget area is triggered if any of the areas
	 * have widgets. So let's check that first.
	 *
	 * If none of the sidebars have widgets, then let's bail early.
	 */
	if ( ! is_active_sidebar( 'first-footer-widget-area' )
		&& ! is_active_sidebar( 'second-footer-widget-area' )
		&& ! is_active_sidebar( 'third-footer-widget-area' )
		&& ! is_active_sidebar( 'fourth-footer-widget-area' )
	)
		return;
	// If we get this far, we have widgets. Let's do this.
?>

			<div id="footer-widget-area" role="complementary">

				<div id="first" class="widget-area">
					<?php if ( is_active_sidebar( 'first-footer-widget-area' ) ) : ?>
						<ul class="xoxo">
							<?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
						</ul>
					<?php endif; ?>
				</div><!-- #first .widget-area -->
	
				<div id="second" class="widget-area">
					<?php if ( is_active_sidebar( 'second-footer-widget-area' ) ) : ?>
						<ul class="xoxo">
							<?php dynamic_sidebar( 'second-footer-widget-area' ); ?>
						</ul>
					<?php endif; ?>
				</div><!-- #second .widget-area -->
	
				<div id="third" class="widget-area">
					<?php if ( is_active_sidebar( 'third-footer-widget-area' ) ) : ?>
						<ul class="xoxo">
							<?php dynamic_sidebar( 'third-footer-widget-area' ); ?>
						</ul>
					<?php endif; ?>
				</div><!-- #third .widget-area -->
	
				<div id="fourth" class="widget-area">
					<?php if ( is_active_sidebar( 'fourth-footer-widget-area' ) ) : ?>
						<ul class="xoxo">
							<?php dynamic_sidebar( 'fourth-footer-widget-area' ); ?>
						</ul>
					<?php endif; ?>
				</div><!-- #fourth .widget-area -->

			</div><!-- #footer-widget-area -->