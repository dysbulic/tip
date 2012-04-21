<?php
/**
 * @package WordPress
 * @subpackage Retro MacOS
 */
?>

	<?php
		/* A sidebar in the footer? Yep. You can can customize
		 * your footer with three columns of widgets.
		 */
		get_sidebar( 'footer' );
	?>

	<div id="footer">
		<div id="site-generator">
			<a href="http://wordpress.org/" rel="generator">Proudly powered by WordPress</a><span class="sep"> | </span><?php printf( __( 'Theme: %1$s by %2$s.', 'retro' ), 'Retro MacOS', '<a href="http://stua.rtbrown.org" rel="designer">Stuart Brown</a>' ); ?>
		</div>
	</div>
</div>

<?php wp_footer(); ?>

</body>
</html>