	<div id="primary" class="sidebar">
		<ul class="xoxo">
<?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar(1) ) : // begin primary sidebar widgets ?>
			<li id="pages">
				<h3><?php _e( 'Pages', 'notesil' ); ?></h3>
				<ul>
<?php wp_list_pages( 'title_li=&sort_column=menu_order' ); ?>
				</ul>
			</li>

			<li id="categories">
				<h3><?php _e( 'Categories', 'notesil' ); ?></h3>
				<ul>
<?php wp_list_categories( 'title_li=&show_count=0&hierarchical=1' ); ?>

				</ul>
			</li>

			<li id="archives">
				<h3><?php _e( 'Archives', 'notesil' ); ?></h3>
				<ul>
<?php wp_get_archives( 'type=monthly' ); ?>

				</ul>
			</li>
				<li id="search">
					<h3><label for="s"><?php _e( 'Search', 'notesil' ); ?></label></h3>
					<form id="searchform" class="blog-search" method="get" action="<?php echo home_url(); ?>">
						<div>
							<input id="s" name="s" type="text" class="text" value="<?php the_search_query(); ?>" size="10" tabindex="1" />
							<input type="submit" class="button" value="<?php esc_attr_e( 'Find', 'notesil' ); ?>" tabindex="2" />
						</div>
					</form>
				</li>

	<?php wp_list_bookmarks( 'title_before=<h3>&title_after=</h3>&show_images=1' ); ?>

				<li id="rss-links">
					<h3><?php _e( 'RSS Feeds', 'notesil' ); ?></h3>
					<ul>
						<li><a href="<?php bloginfo( 'rss2_url' ); ?>" title="<?php echo esc_attr( sprintf( __( '%s latest posts', 'notesil' ), get_bloginfo( 'name', 'display' ) ) ); ?>" rel="alternate" type="application/rss+xml"><?php _e( 'All posts', 'notesil' ); ?></a></li>
						<li><a href="<?php bloginfo( 'comments_rss2_url' ); ?>" title="<?php echo esc_attr( sprintf( __( '%s latest comments', 'notesil' ), get_bloginfo( 'name', 'display' ) ) ); ?>" rel="alternate" type="application/rss+xml"><?php _e( 'All comments', 'notesil' ); ?></a></li>
					</ul>
				</li>

				<li id="meta">
					<h3><?php _e( 'Meta', 'notesil' ); ?></h3>
					<ul>
						<?php wp_register(); ?>

						<li><?php wp_loginout(); ?></li>
						<?php wp_meta(); ?>

					</ul>
				</li>
<?php endif; // end primary sidebar widgets  ?>
		</ul>
	</div><!-- #primary .sidebar -->