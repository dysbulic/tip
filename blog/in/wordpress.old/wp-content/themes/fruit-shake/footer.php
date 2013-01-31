<?php
/**
 * @package WordPress
 * @subpackage Fruit Shake
 */
?>
	<footer id="colophon" role="contentinfo">
		<div id="site-info">
			<p><a href="http://wordpress.org/" rel="generator">Proudly powered by WordPress</a></p>
			<p><?php printf( __( 'Theme: %1$s by %2$s.', 'fruit-shake' ), 'Fruit Shake', '<a href="http://automattic.com/" rel="designer">Automattic</a>' ); ?></p>
		</div>
	</footer><!-- #colophon -->	

	<?php get_template_part( 'post-navigation', 'footer' ); ?>
	
	<?php
		/* A sidebar in the footer? Yep. You can can customize
		 * your footer with three columns of widgets.
		 */
		if ( ! is_404() )
			get_sidebar( 'footer' );
	?>	

	</div><!-- #main -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>