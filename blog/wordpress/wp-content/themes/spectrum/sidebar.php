<?php
/**
 * @package WordPress
 * @subpackage Spectrum
 */
?>

	<div id="sidebar">

		<?php if ( ! dynamic_sidebar( 'primary-widget-area' ) ) : ?>

		<div class="sidebar-box" id="searchBox">
			<div class="sidebar-title">
				<h4><?php _e( 'Search', 'spectrum' ); ?></h4>
			</div>
			<?php get_search_form(); ?>
		</div>
		<div class="sidebar-box widget_categories">
			<div class="sidebar-title">
				<h4><?php _e( 'Categories', 'spectrum' ); ?></h4>
			</div>
			<ul>
				<?php wp_list_categories( 'show_count=0&title_li=' ); ?>
			</ul>
		</div>
		<div class="sidebar-box widget_recent_entries">
			<div class="sidebar-title">
				<h4><?php _e( 'Recent Posts', 'spectrum' ); ?></h4>
			</div>
			<ul>
				<?php wp_get_archives( 'title_li=&type=postbypost&limit=3' ); ?>
			</ul>
		</div>
		<div class="sidebar-box widget_archive">
			<div class="sidebar-title">
				<h4><?php _e( 'Archives', 'spectrum' ); ?></h4>
			</div>
			<ul>
				<?php wp_get_archives( 'type=monthly' ); ?>
			</ul>
		</div>
		<div class="sidebar-box" id="blogrollBox">
			<div class="sidebar-title">
				<h4><?php _e( 'Blogroll', 'spectrum' ); ?></h4>
			</div>
			<ul>
				<?php wp_list_bookmarks( '&categorize=0&title_li=' ); ?>
			</ul>
		</div>

		<?php endif; ?>

	</div>