<?php
/**
 * @package WordPress
 * @subpackage Under_the_Influence
 */

get_header(); ?>

<div id="content_container">
<?php get_sidebar(); ?>
	<div id="content" class="singlepage">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
				<h2>
					<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php esc_attr_e( 'Permalink to', 'uti_theme'); echo ' '; the_title_attribute(); ?>">
						<?php the_title(); ?>
					</a>
				</h2>
				<div class="entry">
					<?php the_content(__('<div class="read_more">read more &raquo;</div>', 'uti_theme')); ?>

					<?php wp_link_pages( array( 'before' => __( '<div class="navigation"><p>Pages:', 'uti_theme' ) . ' ', 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>

					<?php edit_post_link(__('Edit this entry.', 'uti_theme'), '<br><p>', '</p>'); ?>

					<p class="postmetadata"></p>
				</div><!--.entry-->
				<?php comments_template(); ?>
			</div><!--.post-->
		<?php endwhile; endif; ?>

	</div><!--#content-->
</div><!--#content_container-->


<?php get_footer(); ?>