<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package WordPress
 * @subpackage iTheme2
 * @since iTheme2 1.1-wpcom
 */
?>

	</div><!-- #main -->

	<footer id="colophon" role="contentinfo">
		<div id="site-generator">
			<?php do_action( 'itheme2_credits' ); ?>
			<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'itheme2' ) ); ?>" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'itheme2' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'itheme2' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( __( 'Theme: %1$s by %2$s.', 'itheme2' ), 'iTheme2', '<a href="http://themify.me/" rel="designer">Themify</a>' ); ?>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>