<?php
/**
 * @package WordPress
 * @subpackage Under_the_Influence
 */

	get_header();
?>

<div id="content_container">
	<?php get_sidebar(); ?>
	<div class="navigation">
		<?php previous_post_link('&laquo; %link') ?>&nbsp;&nbsp;&#124;&nbsp;&nbsp;
		<?php next_post_link('%link &raquo;') ?>
	</div><!--.navigation-->

	<div id="content" class="singlepage">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<h2><?php the_title(); ?></h2>
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
				<?php wp_link_pages( array( 'before' => __( '<div class="navigation"><p>Pages:', 'uti_theme' ) . ' ', 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>

				<p class="postmetadata">
					<small>
						<?php printf( __( 'Posted on %1$s at %2$s in', 'uti_theme' ), get_the_date(), get_the_time() ); echo ' '; the_category( ', ' ); ?>
						&nbsp;&nbsp;&#124;&nbsp;&nbsp;<?php post_comments_feed_link(__('RSS feed', 'uti_theme')); ?>

						<?php if ( comments_open() && pings_open() ) {
							// Both Comments and Pings are open ?>
							&#124;&nbsp;&nbsp;<a href="#respond">
								<?php _e('Respond','uti_theme') ?>
							</a>&nbsp;&nbsp;&#124;&nbsp;&nbsp;
							<a href="<?php trackback_url(); ?>" rel="trackback">
								<?php _e('Trackback URL','uti_theme')?>
							</a>

						<?php } elseif ( !comments_open() && pings_open() ) {
							// Only Pings are Open ?>
							<?php _e('You can', 'uti_theme')?>
							<a href="<?php trackback_url(); ?> " rel="trackback">
								<?php _e('trackback','uti_theme')?>
							</a>
							<?php _e(' from your own site.','uti_theme')?>

						<?php } elseif ( comments_open() && !pings_open() ) {
							// Comments are open, Pings are not ?>
							<?php _e( 'You can skip to the end and leave a', 'uti_theme' ); ?>&nbsp;
							<a href="#respond">
								<?php _e( 'response.', 'uti_theme' ); ?>
							</a>

						<?php }
							edit_post_link(__('|  Edit this entry', 'uti_theme'),'','.');
						?>
					</small>
				</p>

				<div class="tags">
					<small><?php the_tags( __( 'Tags:', 'uti_theme' ) . ' ', ', ', '<br />' ); ?></small>
				</div><!--.tags-->
			</div><!--.entry-->
			<?php comments_template(); ?>
		</div><!--.post-->
	<?php endwhile; else: ?>
		<p><?php _e('Sorry, no posts matched your criteria.', 'uti_theme')?></p>
	<?php endif; ?>
	</div><!--#content-->
</div><!--#content_container-->

<?php get_footer(); ?>