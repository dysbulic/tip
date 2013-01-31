<div id="footer">
<div id="footer_box">
	<p><a href="http://wordpress.com/" rel="generator">Get a free blog at WordPress.com</a></p>
	<p><?php printf( __( 'Theme: %1$s by %2$s.', 'depo-squared' ), 'DePo Square', '<a href="http://powazek.com" rel="designer">Derek Powazek</a>' ); ?></p>
	<p class="rss">
		<a href="<?php bloginfo('rss2_url'); ?>"><img src="<?php bloginfo('template_directory'); ?>/i/depo-rss.png" alt="rss" /></a><a href="<?php bloginfo('rss2_url'); ?>"><?php _e('RSS Feed', 'depo-squared') ?></a>
	</p>
</div>
<p class="archives">
<?php
	_e('View more by category:');
	$variable = wp_list_categories('echo=0&show_count=1&title_li=&style=none&number=10&orderby=count&order=desc');
	$variable = str_replace('<br />', ', ', trim($variable));
	echo ' ' . rtrim($variable,', ') . '. ';
	$last_post_date = get_lastpostdate('blog'); ?>.
</p>

</div>
<!-- content -->
</div>
<!-- rap -->
</div>
<?php wp_footer(); ?>
</body>
</html>