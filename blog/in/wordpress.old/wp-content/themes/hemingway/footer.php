
<hr class="hide" />
	<div id="footer">
		<div class="inside">
			<?php
				// You are not required to keep this link back to Warpspire, but if you wouldn't mind, leaving it in would make my day.
			?>
			<p class="copyright"><a href="http://wordpress.com/" rel="generator">Get a free blog at WordPress.com</a> | <?php printf( __( 'Theme: %1$s by %2$s.', 'hemingway' ), 'Hemingway', '<a href="http://warpspire.com/hemingway" rel="designer">warpspire</a>' ); ?></p>
			<p class="attributes"><a href="<?php bloginfo('rss2_url'); ?>"><?php _e('Entries RSS', 'hemingway'); ?></a> <a href="<?php bloginfo('comments_rss2_url'); ?>"><?php _e('Comments RSS', 'hemingway'); ?></a></p>
		</div>
	</div>
	<!-- [END] #footer -->

	<?php wp_footer(); ?>
</body>
</html>