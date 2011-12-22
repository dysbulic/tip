<ul id="dysb">
<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(1) ) : ?>
</ul>
<?php else : ?>
<h2>Pages</h2>
<ul>
<?php wp_list_pages('title_li=0' ); ?>
</ul>
<h2><?php _e('Categories:'); ?></h2>
	<ul><?php wp_list_cats('optioncount=1');    ?></ul>

<h2><label for="s"><?php _e('Search:'); ?></label></h2>
	<ul>
		<li>
			<form id="searchform" method="get" action="<?php bloginfo('url'); ?>/" style="text-align:center">
					<p><input type="text" name="s" id="s" size="15" /></p>
					<p><input type="submit" name="submit" value="<?php esc_attr_e( 'Search' ); ?>" /></p>
			</form>
		</li>
	</ul>
<h2><?php _e('Monthly:'); ?></h2>
	<ul><?php wp_get_archives('type=monthly&show_post_count=true'); ?></ul>

<h2><?php _e('RSS Feeds:'); ?></h2>
	<ul>
		<li>
			<a title="<?php esc_attr_e( 'RSS2 Feed for Posts' ); ?>" href="<?php bloginfo('rss2_url'); ?>"><?php _e('Posts'); ?></a> | <a title="<?php esc_attr_e( 'RSS2 Feed for Comments' ); ?>" href="<?php bloginfo('comments_rss2_url'); ?>">Comments</a></li>	
	</ul>	
<?php endif; ?>