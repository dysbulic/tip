<?php
/**
 * The template for displaying the footer.
 *
 * @package WordPress
 * @subpackage The Morning After
 */
?>
	<div id="footer" class="column full-width">
		<div id="copyright" class="column first">
			<p>
				<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					<?php bloginfo( 'name' ); ?>
				</a>
			</p>
		</div>
		<div id="credit" class="column last">
			<p><a href="http://wordpress.org/" rel="generator">Proudly powered by WordPress</a>. <?php printf( __( 'Theme: %1$s by %2$s.', 'woothemes' ), 'The Morning After', '<a href="http://www.woothemes.com/" rel="designer">WooThemes</a>' ); ?>
</p>
		</div>
	</div><!-- end footer -->
</div><!-- end container -->

<?php wp_footer(); ?>

</body>

</html>