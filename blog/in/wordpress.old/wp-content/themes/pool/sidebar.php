<!-- begin sidebar -->
	<div id="sidebar">
		<?php do_action( 'before_sidebar' ); ?>
		<ul>
		<?php if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar() ) : ?>
			<div id="categories">
				<h3><?php _e( 'Categories:', 'pool' ); ?></h3>
				<ul>
					<?php wp_list_cats( 'sort_column=name&optioncount=1&feed=rss' ); ?>
				</ul>
			</div><!-- #categories -->

			<div id="archives">
				<h3><?php _e( 'Archives:', 'pool' ); ?></h3>
				<ul>
					<?php wp_get_archives( 'type=monthly&show_post_count=1' ); ?>
				</ul>
			</div><!-- #archives -->

			<div id="blogroll">
				<h3><?php _e( 'Blogroll', 'pool' ); ?></h3>
				<ul>
					<?php wp_list_bookmarks( 'title_li=&categorize=0' ); ?>
				</ul>
			</div><!-- #blogroll -->

			<div id="meta">
				<h3><?php _e( 'Meta:', 'pool' ); ?></h3>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<li><a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network"><?php _e( 'XFN', 'pool' ); ?></abbr></a></li>
					<li><a href="http://wordpress.com/"><?php _e( 'Get a blog at WordPress.com', 'pool' ); ?></a></li>
					<?php wp_meta(); ?>
				</ul>
			</div><!-- #meta -->
		<?php endif; ?>
		</ul>
	</div><!-- #sidebar -->

	<div class="both"></div>

</div><!-- #bloque -->
<!-- end sidebar -->