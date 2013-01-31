<?php
/**
 * @package WordPress
 * @subpackage Under_the_Influence
 */

get_header(); ?>
<div id="content_container">
<?php get_sidebar(); ?>
	<?php if (have_posts()) : ?>
		<h2 class="pagetitle">
			<?php _e('Search Results for &ldquo;'.get_search_query().'&rdquo;', 'uti_theme')?>
		</h2>

		<div class="navigation top">
			<?php next_posts_link( __( '&laquo; Older Entries', 'uti_theme' ) ); ?>&nbsp;&nbsp;&#124;&nbsp;&nbsp;
			<?php previous_posts_link( __( 'Newer Entries &raquo;', 'uti_theme' ) ); ?>
		</div><!--.navigation-->

		<div id="content" class="mainpage">
			<?php while (have_posts()) : the_post(); ?>
				<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
					<div class="date">
						<?php the_time( get_option( 'date_format' ) ); ?>
					</div>
					<h2>
						<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php esc_attr_e( 'Permalink to', 'uti_theme'); echo ' '; the_title_attribute(); ?>">
							<?php the_title(); ?>
						</a>
					</h2>
					<?php
						/* If author is shown */
						if ($author == "on"){
					?>
						<span class="author"><?php _e( 'by', 'uti_theme' ); echo ' '; the_author(); ?></span>
					<?php
						}
					?>
					<div class="entry">
						<?php the_content(__('<div class="read_more">read more &raquo;</div>', 'uti_theme')); ?>
					</div><!--.entry-->
					<p class="postmetadata">
						<?php _e( 'Posted in', 'uti_theme' ); echo ' '; the_category( ', ' ); ?> |
						<?php edit_post_link(__('Edit', 'uti_theme'), '', ' | '); ?>
						<?php comments_popup_link(__('Leave a Comment &#187;', 'uti_theme'), __('1 Comment &#187;', 'uti_theme'), __('% Comments &#187;', 'uti_theme')); ?>
					</p>
					<div class="tags">
						<?php the_tags( __( 'Tags:', 'uti_theme' ) . ' ', ', ', '<br />' ); ?>
					</div>
				</div><!--post-->
			<?php endwhile; ?>
		</div><!--#content-->

		<div class="navigation_box">
			<div class="navigation bottom">
				<?php next_posts_link( __( '&laquo; Older Entries', 'uti_theme' ) ); ?>&nbsp;&nbsp;&#124;&nbsp;&nbsp;
				<?php previous_posts_link( __( 'Newer Entries &raquo;', 'uti_theme' ) ); ?>
			</div><!--.navigation-->
		</div><!--.navigation_box-->
	<?php else : ?>

		<div class="navigation"></div><!--.navigation-->

		<div class="center">
			<h2>
				<?php _e('No posts found for &ldquo;'.get_search_query().'&rdquo;. Try a different search?', 'uti_theme')?>
			</h2>
			<?php get_search_form(); ?>
		</div><!--.center-->
	<?php endif; ?>
</div><!--#content_container-->

<?php get_footer(); ?>