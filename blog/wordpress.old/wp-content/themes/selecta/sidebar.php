<?php
/**
 * The template for displaying the sidebars.
 *
 * @package WordPress
 * @subpackage Selecta
 */
?>

	<div id="sidebar" class="widget-area" role="complementary">
		<ul class="xoxo">

		<?php // The primary sidebar used in all layouts
		if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>

			<li id="search" class="widget widget_search">
				<?php get_search_form(); ?>
			</li>

			<li class="widget widget_category">
				<h3 class="widget-title"><span><?php _e( 'Categories', 'selecta' ); ?></span></h3>
				<ul>
					<?php wp_list_categories( 'show_count=1&title_li=' ); ?>
				</ul>
			</li>

		<?php endif; // end widget area ?>

		</ul>
	</div><!-- #sidebar .widget-area -->