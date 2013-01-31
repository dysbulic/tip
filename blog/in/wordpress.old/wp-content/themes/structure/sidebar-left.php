<?php
/**
 * @package WordPress
 * @subpackage Structure
 */
?>
<!-- begin r_sidebar -->

<div id="sidebar_left">
 
	<ul> 
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Left Sidebar') ) : ?>
			<li class="widget">
				<h4><?php _e( 'Categories', 'structuretheme' ); ?></h4>
				<ul>
					<?php wp_list_categories( 'sort_column=name&title_li=' ); ?>
				</ul>
			</li>
        <?php endif; ?>
	</ul>
			
</div>