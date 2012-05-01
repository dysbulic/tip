
<hr />
<div id="footer">
	<p>
		<?php printf( __( 'Theme: %s', 'kubrick' ), '<a href="http://theme.wordpress.com/themes/default/">Kubrick</a>' ); ?>. <a href="http://wordpress.com/" rel="generator">Get a free blog at WordPress.com</a>
		<br /><?php printf(__('%1$s and %2$s.', 'kubrick'), '<a href="' . get_bloginfo('rss2_url') . '">' . __('Entries (RSS)', 'kubrick') . '</a>', '<a href="' . get_bloginfo('comments_rss2_url') . '">' . __('Comments (RSS)', 'kubrick') . '</a>'); ?>
	</p>
</div>
</div>

<?php wp_footer(); ?>
</body>
</html>