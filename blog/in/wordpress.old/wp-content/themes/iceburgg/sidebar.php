<div id="middle">
<div class="left">
<ul class="sidebar">
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(1) ) : ?>

<h2><?php _e( 'Categories', 'iceburgg' ); ?></h2>
<p><?php _e( 'Find more posts by selecting categories.', 'iceburgg' ); ?></p>
<ul>
<?php wp_list_cats(); ?>
</ul>
<?php endif; ?>
</ul>
</div>
<div class="mid">
<ul class="sidebar">
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(2) ) : ?>
<h2><?php _e( 'Search', 'iceburgg' ); ?></h2>
<p><?php _e( 'Search through our entire archives, and find the articles of your dreams.
Use keywords, tags, or the post title if you happen to know it or parts
of it. Chances are, we&rsquo;ll find something for you.', 'iceburgg' ); ?></p>
<div class="sf">
<form method="get" action="/">
<input class="searchfield" type="text" name="s" id="s" value="<?php esc_attr_e( 'Enter Search', 'iceburgg' ); ?>" size="20" />
<input type="image" src="<?php bloginfo('template_directory'); ?>/imgs/search.gif" value="<?php esc_attr_e( 'Search', 'iceburgg' ); ?>" class="searchButton" alt="<?php esc_attr_e( 'Search', 'iceburgg' ); ?>" title="<?php esc_attr_e( 'Search', 'iceburgg' ); ?>" />
</form>
</div>
<?php endif; ?>
</ul>
</div>
<div class="right">
<ul class="sidebar">
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(3) ) : ?>
<h2><?php _e( 'About this blog', 'iceburgg' ); ?></h2>
<p>
<?php bloginfo('description'); ?>
</p>
<?php endif; ?>
</ul>
</div>
<div style="clear: both"></div>
</div>