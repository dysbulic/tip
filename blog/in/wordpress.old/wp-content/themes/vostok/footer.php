<?php
/**
 * @package WordPress
 * @subpackage Vostok
 */
?>
	</div><!-- #main -->

	<div id="footer" role="contentinfo">
		<div id="colophon">
			<div id="site-generator">
				<p><a href="http://wordpress.org/" rel="generator">Proudly powered by WordPress</a></p>
				<p><?php printf( __( 'Theme: %1$s by %2$s.' ), 'Vostok', '<a href="http://www.vostoktheme.com/" rel="designer">Vostok</a>' ); ?></p>
			</div>
		</div><!-- #colophon -->

		<div id="page-menu">
			<?php wp_nav_menu( array( 'container_class' => 'menu-footer', 'theme_location' => 'footer' ) ); ?>
		</div>

	</div><!-- #footer -->

</div><!-- #wrapper -->

<?php wp_footer(); ?>

</body>
</html>
