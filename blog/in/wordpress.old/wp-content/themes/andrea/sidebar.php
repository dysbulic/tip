<?php
/**
 * @package WordPress
 * @subpackage Andrea
 */
?>
<div id="sidebar">

	<ul>
	<?php if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar() ) : ?>
		<li><?php get_search_form(); ?></li>

		<li class="widget widget_archives">
			<h3><?php _e( 'Archives', 'andrea' ); ?></h3>
			<ul>
				<?php wp_get_archives( 'type=monthly' ); ?>
			</ul>
		</li>

		<li class="widget widget_categories">
			<h3><?php _e( 'Categories', 'andrea' ); ?></h3>
			<ul>
				<?php wp_list_categories( 'title_li=' ); ?>
			</ul>
		</li>

		<li class="widget widget_links">
			<h3><?php _e( 'Links', 'andrea' ); ?></h3>
			<ul>
				<?php wp_list_bookmarks( 'categorize=0&title_li=' ); ?>
			</ul>
		</li>
	<?php endif; ?>
	</ul>

</div><!-- /#sidebar -->