<div id="sidebar">
<ul>
<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(1) ) : ?>
</ul>
<?php get_footer(); ?>
</div>
<?php return; endif; ?><h2><?php _e( 'Pages', 'jentri' ); ?></h2>
<ul>
<li><a href="<?php echo home_url( '/' ); ?>"><?php _e( 'Home', 'jentri' ); ?></a></li>
<?php wp_list_pages('title_li='); ?>
</ul>
<h2><?php _e( 'Categories', 'jentri' ); ?></h2>
<ul>
<?php wp_list_cats( 'sort_column=name&optioncount=1&hierarchical=0' ); ?>
</ul>
<h2><?php _e( 'Archives', 'jentri' ); ?></h2>
<ul>
 <?php wp_get_archives( 'type=monthly' ); ?>
</ul>
<ul>
<?php wp_list_bookmarks(); ?>
</ul>
<?php get_footer(); ?>
</div>
