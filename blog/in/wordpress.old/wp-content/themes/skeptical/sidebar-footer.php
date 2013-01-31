<?php
/**
 * @package WordPress
 * @subpackage Skeptical
 */
?>
<?php
	// If none of the sidebars have widgets, then let's bail early.
	if (   ! is_active_sidebar( 'first-footer-widget-area' )
		&& ! is_active_sidebar( 'second-footer-widget-area' )
		&& ! is_active_sidebar( 'third-footer-widget-area' )
		&& ! is_active_sidebar( 'fourth-footer-widget-area' )
	)
		return;
	// If we get this far, we have widgets. Let do this.
?>
<div id="footer-widgets">
	<div class="col-full">
		<div class="block">
			<?php
				if ( is_active_sidebar( 'first-footer-widget-area' ) ) :
					dynamic_sidebar( 'first-footer-widget-area' );
				endif;
			?>
		</div>
		<div class="block">
			<?php
				if ( is_active_sidebar( 'second-footer-widget-area' ) ) :
					dynamic_sidebar( 'second-footer-widget-area' );
				endif;
			?>
		</div>
		<div class="block">
			<?php
				if ( is_active_sidebar( 'third-footer-widget-area' ) ) :
					dynamic_sidebar( 'third-footer-widget-area' );
				endif;
			?>
		</div>
		<div class="block last">
			<?php
				if ( is_active_sidebar( 'fourth-footer-widget-area' ) ) :
					dynamic_sidebar( 'fourth-footer-widget-area' );
				endif;
			?>
		</div>
		<div class="fix"></div>
	</div>
</div><!-- /#footer-widgets  -->