<?php
/**
 * @package WordPress
 * @subpackage Monotone
 */
?>
			<div id="footer" class="clearfix">
				<?php get_sidebar( 'footer' ); ?>
				<!-- If you'd like to support WordPress, having the "powered by" link somewhere on your blog is the best way; it's our only promotion or advertising. -->
				<p class="info">
					<a href="<?php bloginfo( 'rss2_url' ); ?>" title="<?php esc_attr_e( 'RSS Feed', 'monotone' ); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/rss.png" alt="RSS" /></a>
					<a href="http://wordpress.org/" rel="generator">Proudly powered by WordPress</a>
					<?php printf( __( 'Theme: %1$s by %2$s.', 'monotone' ), 'Monotone', '<a href="http://automattic.com/" rel="designer">Automattic</a>' ); ?>
				</p>
				<?php get_search_form(); ?>
			</div><!-- #footer -->
		</div><!-- #sleeve -->
	</div><!-- #content -->
</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>