</div>
</div>

<div id="feed_bar">
 	<p><?php _e( 'Liked it here?', 'fadtastic' ); ?><br />
	<span class="small"><?php _e( 'Why not try sites on the blogroll...', 'fadtastic' ); ?></span></p>
</div>

<div id="footer">
	<div id="footer_wrapper">
		<div class="content_padding">
			 <div class="footer_links">
				   <ul class="blogroll_list">
						<?php wp_list_bookmarks(array(
							'title_before' => '<h4>',
							'title_after' => '</h4>',
							'before' => '<li>',
							'after' => '</li>',
							'show_images'=>true)
							); ?>
				   </ul>
			</div>
			<div class="footer_meta">
				<p><a href="http://wordpress.com/" rel="generator">Get a free blog at WordPress.com</a> <?php printf( __( 'Theme: %1$s by %2$s.' ), 'Fadtastic', '<a href="http://fadtastic.net/theme/" rel="designer">Andrew Faulkner</a>' ); ?></p>
			</div>

		</div>
	</div>
</div>

<?php wp_footer(); ?>
</body>
</html>
