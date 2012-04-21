<?php
/**
 * The template for displaying the sidebar.
 *
 * @package WordPress
 * @subpackage Liquorice
 */
?>
	<div id="secondary-content" class="widget-area" role="complementary">
		<ul class="xoxo">

		<?php // The primary sidebar used in all layouts
		if ( ! dynamic_sidebar( 'primary' ) ) : ?>

			<li id="search" class="widget-container widget_search">
				<h3 class="widget-title"><?php _e( 'Search', 'liquorice' ); ?></h3>
				<?php get_search_form(); ?>
			</li>

			<li class="widget-container">
				<h3 class="widget-title"><?php _e( 'Links', 'liquorice' ); ?></h3>
					<ul>
						<?php wp_list_bookmarks( array( 'title_li' => '', 'categorize' => 0 ) ); ?>
					</ul>
			</li>

		<?php endif; // end widget area ?>

		</ul>
	</div><!-- #secondary-content .widget-area -->