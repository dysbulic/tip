<ul id="sidebar">
<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar() ) : else : ?>
	<h2><?php _e( 'Pages', 'simpla' ); ?></h2>
	<ul>
		<li><a href="<?php echo home_url( '/' ); ?>"><?php _e( 'Home', 'simpla' ); ?></a></li>
		<?php wp_list_pages( 'title_li=' ); ?> 
	</ul>
	<h2><?php _e( 'Categories', 'simpla' ); ?></h2>
	<ul>
		<?php wp_list_cats( 'sort_column=name&optioncount=1&hierarchical=0' ); ?>
	</ul>
	<ul>
		<?php wp_list_bookmarks(); ?>
	</ul>
<?php endif; ?>
</ul>