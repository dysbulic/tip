<?php if ( is_active_sidebar( 'upper-footer-widget-area' ) ) : ?>
	<div id="upper-footer-widgets" class="clear-fix">
		<?php dynamic_sidebar( 'upper-footer-widget-area' ); ?>
	</div><!-- end #about-info -->
<?php endif; ?>

<?php
	/*
	 * If none of the sidebars have widgets, then let's bail early.
	 */
	if (   ! is_active_sidebar( 'first-footer-widget-area' )
		&& ! is_active_sidebar( 'second-footer-widget-area' )
		&& ! is_active_sidebar( 'third-footer-widget-area' )
		&& ! is_active_sidebar( 'fourth-footer-widget-area' )
	)
		return;
	// If we get this far, we have widgets. Let do this.
?>
<div id="footer-widgets-holder">
	<div id="footer-widgets" class="clear-fix">
		<div class="footer-widget-item">
			<?php
				if ( is_active_sidebar( 'first-footer-widget-area' ) ) :
					dynamic_sidebar( 'first-footer-widget-area' );
				endif;
			?>
		</div>
		<div class="footer-widget-item">
			<?php
				if ( is_active_sidebar( 'second-footer-widget-area' ) ) :
					dynamic_sidebar( 'second-footer-widget-area' );
				endif;
			?>
		</div>
		<div class="footer-widget-item">
			<?php
				if ( is_active_sidebar( 'third-footer-widget-area' ) ) :
					dynamic_sidebar( 'third-footer-widget-area' );
				endif;
			?>
		</div>
		<div class="footer-widget-item">
			<?php
				if ( is_active_sidebar( 'fourth-footer-widget-area' ) ) :
					dynamic_sidebar( 'fourth-footer-widget-area' );
				endif;
			?>
		</div>
	</div><!-- end #footer-widgets -->
</div><!-- end #footer-widgets-holder -->