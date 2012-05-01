<?php
/**
 * @package WordPress
 * @subpackage Koi
 */
?>

	<div id="secondary">

		<div id="footer1" class="widget-container">
			<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
				<?php dynamic_sidebar( 'footer-1' ); ?>
			<?php endif; ?>
		</div>

		<div id="footer2" class="widget-container">
			<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
				<?php dynamic_sidebar( 'footer-2' ); ?>
			<?php endif; ?>
		</div>

		<div id="footer3" class="widget-container">
			<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
				<?php dynamic_sidebar( 'footer-3' ); ?>
			<?php endif; ?>
		</div>

	</div>

	<div id="footer">

		<p class="credits"><a href="http://wordpress.com/" rel="generator">Get a free blog at WordPress.com</a> <span>&bull;</span> <?php printf( __( 'Theme: %1$s by %2$s.', 'ndesignthemes' ), 'Koi', '<a href="http://www.ndesign-studio.com" rel="designer">N.Design</a>' ); ?></p>

	</div>
	<!--/footer -->

</div>
<!--/wrapper -->
<?php wp_footer(); ?>
</body>
</html>