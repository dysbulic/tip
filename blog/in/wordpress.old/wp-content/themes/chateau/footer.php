<?php
/**
 * The template for displaying the footer.
 *
 * @package WordPress
 * @subpackage Chateau
 */
?>

		</div><!-- end #main -->
	</div><!-- end #page-inner -->

	<footer id="colophon" role="contentinfo">
		<div id="footer">
			<div id="footer-inner">
				<div id="footer-inner-inner">
					<?php
						/* A sidebar in the footer? Yep. You can can customize
						 * your footer with three columns of widgets.
						 */
						get_sidebar( 'footer' );
					?>
				</div><!-- end #footer-inner-inner -->
			</div><!-- end #footer-inner -->
		</div>
		<div id="copyright">
			<div id="copyright-inner">
					<div id="copyright-inner-inner">
					<p>
						<a href="http://wordpress.org/" rel="generator">Proudly powered by WordPress</a>
		<?php printf( __( 'Theme: %1$s by %2$s.', 'chateau' ), 'Chateau', '<a href="http://ignacioricci.com/" rel="designer">Ignacio Ricci</a>' ); ?>
					</p>
				</div><!-- end #copyright-inner-inner -->
			</div><!-- end #copyright-inner -->
		</div><!-- end #copyright -->
	</footer><!-- end #footer -->

	<?php wp_footer(); ?>
</div><!-- end #page -->
</body>
</html>