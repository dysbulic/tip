<?php
/**
 * The template for displaying the sidebars.
 *
 * @package WordPress
 * @subpackage Mystique
 */
?>

	<div id="sidebar" class="widget-area" role="complementary">
		<ul class="xoxo">

		<?php // The primary sidebar used in all layouts
		if ( ! dynamic_sidebar( 'primary-widget-area' ) ) : ?>

			<li id="search" class="widget-container widget_search">
				<?php get_search_form(); ?>
			</li>

			<li class="widget-container">
				<h3 class="widget-title"><span><?php _e( 'Categories', 'mystique' ); ?></span></h3>
				<ul>
					<?php wp_list_categories( 'show_count=1&title_li=' ); ?>
				</ul>
			</li>

		<?php endif; // end widget area ?>

		</ul>
	</div><!-- #sidebar .widget-area -->

	<?php // The secondary sidebar, displayed in 3-column layouts.

		$current_layout = mystique_layout_type();
		$secondary_widget_area_layouts = array( 'sidebar-content-sidebar', 'sidebar-sidebar-content', 'content-sidebar-sidebar' );

		if ( in_array( $current_layout, $secondary_widget_area_layouts ) ) :
	?>
	<div id="sidebar2" class="widget-area" role="complementary">
		<ul class="xoxo">

		<?php if ( ! dynamic_sidebar( 'secondary-widget-area' ) ) : ?>

				<li class="widget-container widget_links">
					<h3 class="widget-title"><span><?php _e( 'Meta', 'mystique' ); ?></span></h3>
					<ul>
						<?php wp_register(); ?>
						<li><?php wp_loginout(); ?></li>
						<?php wp_meta(); ?>
					</ul>
				</li>

		<?php endif; // end widget area ?>

		</ul>
	</div><!-- #sidebar2 .widget-area -->
	<?php endif; ?>