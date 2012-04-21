<?php
/**
 * The template for displaying the footer.
 *
 * @package WordPress
 * @subpackage Nishita
 *
 * A sidebar in the footer? Yep. You can can customize
 * your footer with three columns of widgets.
 */
get_sidebar( 'footer' ); ?>

	<div id="footer">
		<p>
			<a href="http://wordpress.org/" rel="generator">Proudly powered by WordPress</a>
			<?php printf( __( 'Theme: %1$s by %2$s.', 'nishita' ), 'Nishita', '<a href="http://brajeshwar.com/" rel="designer">Brajeshwar</a>' ); ?>
		</p>
	</div><!-- #footer -->
</div><!-- #container -->

<?php wp_footer(); ?>

</body>
</html>