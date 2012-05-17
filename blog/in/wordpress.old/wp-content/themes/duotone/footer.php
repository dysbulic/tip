			<?php if( !is_archive() ) :?>
				<div id="sidebar">
				<ul>
					<?php 	/* Widgetized sidebar, if you have the plugin installed. */
							if (!function_exists('dynamic_sidebar') || !dynamic_sidebar() ) { } ?>
				</ul>
			</div>
			<?php endif; ?>
			<div id="footer">
			<?php get_sidebar(); ?>
			<!-- If you'd like to support WordPress, having the "powered by" link somewhere on your blog is the best way; it's our only promotion or advertising. -->
			<p class="info">
					<a href="<?php bloginfo( 'rss2_url' ); ?>"><img src="<?php bloginfo( 'stylesheet_directory' ); ?>/images/rss.png" alt="RSS" /></a>
					<a href="http://wordpress.org/" rel="generator">Proudly powered by WordPress</a> <?php printf( __( 'Theme: %1$s by %2$s.', 'duotone' ), 'Duotone', '<a href="http://automattic.com/" rel="designer">Automattic</a>' ); ?>
			</p>
			<?php get_search_form(); ?>
			</div>

		</div>
	</div>
</div>

<?php wp_footer(); ?>
</body>
</html>