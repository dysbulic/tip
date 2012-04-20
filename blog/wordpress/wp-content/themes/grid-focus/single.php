<?php
/**
 *	@package WordPress
 *	@subpackage Grid_Focus
 */
get_header();
?>
<div id="filler" class="fix">
	<div id="mainColumn" class="fix"><a name="main"></a>
		<div id="paginateIndex" class="fix">
			<p><span class="left"><?php next_post_link( '%link', __( '&laquo; Newer', 'grid-focus' ) ); ?></span> <span class="right"><?php previous_post_link( '%link', __( 'Older &raquo;', 'grid-focus' ) ); ?></span></p>
		</div>
		
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="postMeta fix">
				<p class="container">
					<span class="date"><?php the_date(); echo ' &bull; '; the_time() ?><?php edit_post_link( __( ' (Edit)', 'grid-focus' ), '', '' ); ?></span>
				</p>
			</div>
			
			<h2><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title() ?></a></h2>
			<div class="entry">
				<?php the_content(); ?>
				<?php wp_link_pages(); ?>
				<p><?php _e( 'Filed under: ', 'grid-focus' ); the_category(', '); echo ' '; the_tags(', ',', ',''); ?></p>
			</div>
		</div>
		<div id="commentsContainer">
			<?php comments_template(); ?>
		</div>
		<?php endwhile; else: ?>
		<div class="post">
			<h2><?php _e( 'No matching results', 'grid-focus' ); ?></h2>
			<div class="entry">
				<p><?php _e( 'You seem to have found a mis-linked page or search query with no associated results. Please trying your search again. If you feel that you should be staring at something a little more concrete, feel free to email the author of this site or browse the archives.', 'grid-focus' ); ?></p>
			</div>
		</div>
		<?php endif; ?>
	</div>
	<?php include (TEMPLATEPATH . '/second.column.post.php'); ?>
	<?php include (TEMPLATEPATH . '/third.column.shared.php'); ?>
</div>
<?php get_footer(); ?>