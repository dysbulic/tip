<div id="sidebar-left" class="sidebar">

<ul class="menu">

<?php /* WordPress Widget Support */ if (function_exists('dynamic_sidebar') and dynamic_sidebar('left-sidebar')) { } else { ?>
<li class="widget widget_pages">
	<h3>Pages</h3>
	<ul>
		<li><a href="<?php echo home_url( '/' ); ?>"><?php _e('Home'); ?></a></li>
		<?php wp_list_pages( 'title_li=&depth=1' ); ?>
	</ul>
</li>

<li class="widget widget_categories categories">
	<h3><?php _e( 'Categories', 'garland' ); ?></h3>
	<ul>
		<?php wp_list_categories( array( 'optioncount' => 1, 'hierarchical' => 0, 'title_li' => '' ) ); ?>
	</ul>
</li>

<?php } ?>
</ul>

</div>