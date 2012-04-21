<div id="footer">
	<p>
	<a href="http://wordpress.com/" rel="generator">Get a free blog at WordPress.com</a>
		<br /><?php printf( __( 'Theme: %1$s.' ), 'Neat!' ); ?>
		<a href="<?php bloginfo('rss2_url'); ?>"><?php _e('Entries (RSS)'); ?></a>
		and <a href="<?php bloginfo('comments_rss2_url'); ?>"><?php _e('Comments (RSS)'); ?></a>.
	</p>
</div>

</div>
		<?php wp_footer(); ?>
<br /><br /><br />
</body>
</html>
