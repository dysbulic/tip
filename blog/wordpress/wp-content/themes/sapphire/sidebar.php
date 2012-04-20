	<div id="sidebar">
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>
			<?php include (TEMPLATEPATH . '/searchform.php'); ?>
			
			<?php /* If this is a category archive */ if (is_category()) { ?>
			<p><?php printf(__('You are currently browsing the archives for the %s category', 'sapphire'), single_cat_title('', false)); ?></p>
			
			<?php /* If this is a yearly archive */ } elseif (is_day()) { ?>
			<p><?php printf(__('You are currently browsing the <a href="%s">%s</a> weblog archives for the day %s', 'sapphire'), get_settings('siteurl'), get_bloginfo('name'), get_the_time(get_option('date_format'))); ?>.</p>
			
			<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
			<p><?php printf(__('You are currently browsing the <a href="%s">%s</a> weblog archives for %s', 'sapphire'), get_settings('siteurl'), get_bloginfo('name'), get_the_time('F Y')); ?>.</p>

      <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
			<p><?php printf(__('You are currently browsing the <a href="%s">%s</a> weblog archives for %s', 'sapphire'), get_settings('siteurl'), get_bloginfo('name'), get_the_time('Y')); ?>.</p>
			
		 <?php /* If this is a monthly archive */ } elseif (is_search()) { ?>
			<?php printf(__('You have searched the <a href="%s">%s</a> weblog archives for <strong>"%s"</strong>. If you are unable to find anything in these search results, you can try one of these links.', 'sapphire'), get_settings('siteurl'), get_bloginfo('name'), wp_specialchars($s)); ?>
			
			<?php /* If this is a monthly archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
			<p><?php printf(__('You are currently browsing the <a href="%s">%s</a> weblog archives', 'sapphire'), get_settings('siteurl'), get_bloginfo('name')); ?>.</p>

			<?php } ?>
		
		<ul>
			<li class="page_item"><a href="<?php bloginfo('url'); ?>">Home</a></li>
			<?php wp_list_pages('title_li='); ?>
		</ul>
		
		<h2><?php _e('Subscribe', 'sapphire'); ?></h2>
			<p><?php printf(__('%s syndicates its <a href="%s">weblog posts</a>
		and <a href="%s">Comments</a> using a technology called 
		RSS (Real Simple Syndication). You can use a service like <a href="http://bloglines.com/">Bloglines</a> to get
		notified when there are new posts to this weblog.', 'sapphire'), get_bloginfo('name'), get_bloginfo('rss2_url'), get_bloginfo('comments_rss2_url')); ?></p>

		<h2><?php _e('Archives', 'sapphire'); ?></h2>
				<ul>
				<?php wp_get_archives('type=monthly'); ?>
				</ul>
		

		<h2><?php _e('Categories', 'sapphire'); ?></h2>
				<ul>
				<?php list_cats(0, '', 'name', 'asc', '', 1, 0, 1, 1, 1, 1, 0,'','','','','') ?>
				</ul>
		

			<?php /* If this is the frontpage */ if ( is_home() || is_page() ) { ?>				
				<ul>
				<?php wp_list_bookmarks(); ?>
				</ul>
				
		<h2><?php _e('Meta', 'sapphire'); ?></h2>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<li><a href="http://validator.w3.org/check/referer" title="<?php _e('This page validates as XHTML 1.0 Transitional', 'sapphire'); ?>"><?php _e('Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr>', 'sapphire'); ?></a></li>
					<li><a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a></li>
					<li><a href="http://wordpress.com/" title="<?php _e('Powered by WordPress, state-of-the-art semantic personal publishing platform.', 'sapphire'); ?>">WordPress.com</a></li>
					<?php wp_meta(); ?>
				</ul>
		
			<?php } ?>
<?php endif; ?>
	</div>

