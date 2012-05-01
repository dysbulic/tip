<?php
/**
 *	@package WordPress
 *	@subpackage Grid_Focus
 */
?>
<div class="navStripWrapper">
	
	<ul class="nav fix">
		<li><a href="<?php echo home_url( '/' ); ?>" title="<?php esc_attr_e( 'Return to the the frontpage', 'grid-focus' ); ?>"><?php _e( 'Frontpage', 'grid-focus' ); ?><br /><span><?php _e( 'Return home', 'grid-focus' ); ?></span></a></li>
		<li><a id="triggerCatID" href="#" title="<?php esc_attr_e( 'Show categories', 'grid-focus' ); ?>"><?php _e( 'Browse', 'grid-focus' ); ?><br /><span><?php _e( 'By topic', 'grid-focus' ); ?></span></a></li>
		<li class="last"><a href="<?php bloginfo('rss2_url'); ?>" title="<?php esc_attr_e( 'Subscribe to the main feed via RSS', 'grid-focus' ); ?>"><?php _e( 'Subscribe', 'grid-focus' ); ?><br /><span><?php _e( 'RSS feed', 'grid-focus' ); ?></span></a></li>
		<li id="searchBar">
			<?php get_search_form(); ?>
		</li>
	</ul>

	<div id="headerStrip" class="toggleCategories fix" style="display: none;"> 
		<ul class="fix">
		<?php wp_list_cats('sort_column=name&optioncount=0&exclude=10, 15'); ?>
		</ul>
	</div>
	
</div>