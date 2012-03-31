<?php
/**
 * @package WordPress
 * @subpackage Mystique
 */
?>

</div><!-- #main -->

		<div id="footer" role="contentinfo">
			<?php get_sidebar( 'footer' ); ?>
				<div id="copyright">
					<p>
	 					<a href="http://wordpress.org/" rel="generator">Proudly powered by WordPress</a>.
						<?php printf( __( 'Theme: %1$s by %2$s.', 'mystique' ), 'Mystique', '<a href="http://digitalnature.ro/" rel="designer">digitalnature</a>' ); ?>
					</p>
				</div><!-- #copyright -->
			</div><!-- #footer -->
	</div><!-- #container -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>