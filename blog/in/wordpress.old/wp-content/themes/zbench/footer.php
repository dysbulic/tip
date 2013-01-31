<?php
/**
 * @package WordPress
 * @subpackage zBench
 *
 * Footer
 */


/* Footer Widget area */
if ( is_active_sidebar( 'first-footer-widget-area' ) OR is_active_sidebar( 'second-footer-widget-area' ) OR is_active_sidebar( 'third-footer-widget-area' ) OR is_active_sidebar( 'fourth-footer-widget-area' ) ) : ?>
<div id="sub-footer">

	<div class="sidebar">
		<div class="sidebar-border <?php if ( is_active_sidebar( 'first-footer-widget-area' ) ) echo 'active'; ?>">
			<div class="sidebar-inner">
				<?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
			</div>
		</div>
	</div>
	
	<div class="sidebar">
		<div class="sidebar-border <?php if ( is_active_sidebar( 'second-footer-widget-area' ) ) echo 'active'; ?>">
			<div class="sidebar-inner">
				<?php dynamic_sidebar( 'second-footer-widget-area' ); ?>
			</div>
		</div>
	</div>

	<div class="sidebar">
		<div class="sidebar-border <?php if ( is_active_sidebar( 'third-footer-widget-area' ) ) echo 'active'; ?>">
			<div class="sidebar-inner">
				<?php dynamic_sidebar( 'third-footer-widget-area' ); ?>
			</div>
		</div>
	</div>

	<div class="sidebar">
		<div class="sidebar-border <?php if ( is_active_sidebar( 'fourth-footer-widget-area' ) ) echo 'active'; ?>">
			<div class="sidebar-inner">
				<?php dynamic_sidebar( 'fourth-footer-widget-area' ); ?>
			</div>
		</div>
	</div>

	</div>
<?php endif; ?>

</div><?php /* closing #content */ ?>

<?php /* Footer */ ?>
<div id="footer">
	<div>
		<a href="#wrapper" id="top-link"><?php _e( '&uarr; Top', 'zbench' ); ?></a>
		<a href="http://wordpress.com/" rel="generator">Get a free blog at WordPress.com</a>
		<?php printf( __( 'Theme: %1$s by %2$s.', 'zbench' ), 'zBench', '<a href="http://zww.me/" rel="designer">zwwooooo</a>' ); ?>
	</div>
</div>

</div><?php /* closing #wrapper */ ?>

<?php wp_footer(); ?>
</body>
</html>