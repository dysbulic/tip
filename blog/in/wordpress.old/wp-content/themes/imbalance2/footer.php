<?php
/**
 * @package Imbalance 2
 */
?>
	</div><!-- #main -->

	<div id="footer" class="clear-fix">
		<div id="site-info">
			<a href="http://wordpress.org/" rel="generator">Proudly powered by WordPress</a><span class="sep"> | </span><?php printf( __( 'Theme: %1$s by %2$s.', 'imbalance2' ), 'Imbalance 2', '<a href="http://wpshower.com/" rel="designer">WPShower</a>' ); ?>
		</div><!-- #site-info -->
		<div id="footer-right">
		<?php if ( is_active_sidebar( 'sidebar-3' ) ) : ?>
			<div class="widget-area">
				<?php dynamic_sidebar( 'sidebar-3' ); ?>
			</div><!-- .widget-area -->
		<?php endif; ?>
		</div>
		<div id="footer-left">
		<?php if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
			<div class="widget-area">
				<?php dynamic_sidebar( 'sidebar-2' ); ?>
			</div><!-- .widget-area -->
		<?php endif; ?>
		</div>
	</div><!-- #footer -->

</div><!-- #wrapper -->
<?php wp_footer(); ?>
</body>
</html>