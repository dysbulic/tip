	</div>
	<!-- main END -->

	<?php
		$options = get_option('inove_options');
		global $inove_nosidebar;
		if(!$options['nosidebar'] && !$inove_nosidebar) {
			get_sidebar();
		}
	?>
	<div class="fixed"></div>
</div>
<!-- content END -->

<!-- footer START -->
<div id="footer">
	<a id="gotop" href="#" onclick="MGJS.goTop();return false;"><?php _e('Top', 'inove'); ?></a>
	<a id="powered" href="http://wordpress.com/">WordPress</a>
	<div id="copyright">
		<?php
			$copyright = wp_cache_get('inove-copyright', 'theme');
			if ( false == $copyright ) {
				global $wpdb;
				$post_datetimes = $wpdb->get_results("SELECT YEAR(min(post_date_gmt)) AS firstyear, YEAR(max(post_date_gmt)) AS lastyear FROM $wpdb->posts WHERE post_date_gmt > 1970");
				if ($post_datetimes) {
					$firstpost_year = $post_datetimes[0]->firstyear;
					$lastpost_year = $post_datetimes[0]->lastyear;

					$copyright = __('Copyright &copy; ', 'inove') . $firstpost_year;
					if($firstpost_year != $lastpost_year) {
						$copyright .= '-'. $lastpost_year;
					}
					$copyright .= ' ';

				}

				wp_cache_set('inove-copyright', $copyright, 'theme', 21600); //cache for 6 hours
			}

			echo $copyright;
			bloginfo('name');
		?>
	</div>
	<div id="themeinfo">
		<a href="http://wordpress.com/" rel="generator">Get a free blog at WordPress.com</a>. <?php printf( __( 'Theme: %1$s by %2$s.', 'inove' ), 'INove', '<a href="http://www.neoease.com/" rel="designer">NeoEase</a>' ); ?>
	</div>
</div>
<!-- footer END -->

</div>
<!-- container END -->
</div>
<!-- wrap END -->

<?php wp_footer(); ?>

</body>
</html>
