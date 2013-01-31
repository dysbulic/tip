<?php
/**
 * @package WordPress
 * @subpackage Notepad
 */
?>
	<div id="sidebar">

<?php if ( !dynamic_sidebar() ) : ?>

		 <?php the_widget( 'WP_Widget_Search' ); ?>

		<div class="widget">
			<h4>
				<?php _e( 'Pages','notepad-theme' ); ?>
			</h4>
			<ul>
				<?php wp_list_pages( 'title_li=' ); ?>
			</ul>
		</div>

		<div class="widget">
			<h4>
				<?php _e( 'Category','notepad-theme' ); ?>
			</h4>
			<ul>
				<?php wp_list_categories( 'show_count=1&title_li=' ); ?>
			</ul>
		</div>

		<div class="widget">
			<h4>
				<?php _e( 'Archives','notepad-theme' ); ?>
			</h4>
			<ul>
				<?php wp_get_archives( 'type=monthly' ); ?>
			</ul>
		</div>

<?php endif; ?>

	</div>
	<!--/sidebar -->