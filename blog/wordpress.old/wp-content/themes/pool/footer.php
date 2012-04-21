<!-- begin footer -->

<?php get_sidebar(); ?>

		<p id="credits">
			<a href="http://wordpress.com/" rel="generator">Get a free blog at WordPress.com</a> | <?php printf( __( 'Theme: %1$s by %2$s.' ), 'Pool', '<a href="http://www.lamateporunyogur.net/" rel="designer">Borja Fernandez</a>' ); ?><br />
			<a href="<?php bloginfo('rss2_url'); ?>"><?php _e( 'Entries', 'pool' ); ?></a> <?php _e( 'and', 'pool' );?> <a href="<?php bloginfo('comments_rss2_url'); ?>"><?php _e( 'comments', 'pool' ); ?></a> <?php _e( 'feeds.', 'pool' ); ?>
		</p>

	</div><!-- #bloque -->

<?php wp_footer(); ?>
</body>
</html>