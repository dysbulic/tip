<?php
/**
 * @package WordPress
 * @subpackage Manifest
 */
?>

</div><!-- #site-wrapper -->

<div id="footer">

	<?php
		/* A sidebar in the footer? Yep. You can can customize
		 * your footer with three columns of widgets.
		 */
		get_sidebar( 'footer' );
	?>

	<!-- Search Field -->
	<div class="footer-content">
		<form method="get" id="searchform" action="<?php echo home_url(); ?>/">
			<div id="search">
				<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" />
				<input type="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'manifest' ); ?>" />
			</div>
		</form>
		<p>
			<a href="http://wordpress.org/" rel="generator">Proudly powered by WordPress</a>
			<?php printf( __( 'Theme: %1$s by %2$s.', 'manifest' ), 'Manifest', '<a href="http://jimbarraud.com/" rel="designer">Jim Barraud</a>' ); ?>
		</p>
	</div>
</div><!-- #footer -->

<?php wp_footer(); ?>

</body>
</html>