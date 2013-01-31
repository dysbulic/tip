<!-- begin sidebar -->
	<div id="dooncha_sidebar">
	<?php if ( !dynamic_sidebar('sidebar') ) { ?>
		<!-- something about author, you can enter the description under user option -->
		<p class="side_title"><?php _e('About author', 'supposedly-clean'); ?></p>
		<p id="author_talk"><?php mytheme_about() ?></p>
	
		<!--search form-->
		<p class="side_title"><?php _e('Search', 'supposedly-clean'); ?></p>
	
		<form action="<?php bloginfo('url'); ?>/" id="searchform" method="get">
			<p><input value="<?php the_search_query(); ?>" name="s" id="s" />
			<input type="submit" value="search" id="searchbutton" name="searchbutton" /></p>
		</form>
		<hr class="sidebar_line" />
		
		<!--main navigation-->
		<p class="side_title"><?php _e('Navigation', 'supposedly-clean'); ?></p>
			<ul class="nonnavigational">
			<li><a href="<?php bloginfo('url'); ?>" title="home">Home</a></li>
                        <?php wp_list_pages('title_li='); ?> 
		        </ul>
			
		<!--categories-->
		<p class="side_title"><?php _e('Categories:', 'supposedly-clean'); ?></p>
			<ul class="nonnavigational">
			<?php wp_list_cats('sort_column=name&hide_empty=0'); ?>
			</ul>
			
		<!--links-->
		<p class="side_title"><?php _e('Links:', 'supposedly-clean'); ?></p>
			<ul class="nonnavigational">
			<?php get_links('-1', '<li>', '</li>', '', 0, 'name', 0, 0, -1, 0); ?>
			</ul>
			
		<!--archives-->
		<p class="side_title"><?php _e('Archives:', 'supposedly-clean'); ?></p>
			<ul class="nonnavigational">
			<?php wp_get_archives('type=monthly'); ?>
			</ul>
			
		<!--meta-->
		<p class="side_title"><?php _e('Feeds', 'supposedly-clean'); ?></p>
			<ul class="nonnavigational">
			<li><a href="<?php bloginfo('rss2_url'); ?>" title="<?php esc_attr_e( 'Syndicate this site using RSS', 'supposedly-clean' ); ?>"><?php _e('<abbr title="Really Simple Syndication">RSS</abbr>', 'supposedly-clean'); ?></a></li>
			<li><a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php esc_attr_e( 'The latest comments to all posts in RSS', 'supposedly-clean' ); ?>"><?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>', 'supposedly-clean'); ?></a></li>
			</ul>								
	<?php } ?>			
	</div><!--close dooncha sidebar-->