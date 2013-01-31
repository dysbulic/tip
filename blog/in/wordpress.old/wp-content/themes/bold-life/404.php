<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Bold_Life
 */

get_header(); ?>

	<div class="post">
		<div class="entry">
			<div class="entry-inner">
				<h2 class="post-title"><?php _e( '404 Page Not Found', 'bold-life' ); ?></h2>
				<p>
					<?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching, or one of the links below, can help.', 'bold-life' ); ?>
				</p>
				<?php get_search_form(); ?>
				<?php the_widget( 'WP_Widget_Recent_Posts', array( 'number' => 10 ), array( 'widget_id' => '404' ) ); ?>
				<div class="widget">
					<h2 class="widgettitle">
						<?php _e( 'Most Used Categories', 'bold-life' ); ?>
					</h2>
					<ul>
						<?php wp_list_categories( array( 'orderby' => 'count', 'order' => 'DESC', 'show_count' => 1, 'title_li' => '', 'number' => 10 ) ); ?>
					</ul>
				</div>
				<?php the_widget( 'WP_Widget_Tag_Cloud' ); ?>
			</div><!-- .entry-inner -->
		</div><!-- .entry -->
	</div><!-- .post -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>