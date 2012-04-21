<?php
/**
 * @package Shaan
 */
?>

<div id="sidebar">
	<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>
		<div class="section widget">
			<h3><?php _e( 'Search', 'shaan' ); ?></h3>
			<?php get_search_form(); ?>
		</div>

		<div class="section widget widget_categories">
			<h3><?php _e( 'Categories', 'shaan' ); ?></h3>
			<ul>
				<?php wp_list_categories( 'title_li=&hierarchical=0' ); ?>
			</ul>
		</div>
	<?php endif; ?>
</div><!--# sidebar -->