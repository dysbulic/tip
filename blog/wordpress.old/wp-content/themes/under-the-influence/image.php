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
			<?php previous_post_link('&laquo; %link') ?>&nbsp;&nbsp;&#124;&nbsp;&nbsp;<?php next_post_link('%link &raquo;') ?>
	</div><!--.navigation-->
	<div id="content" class="singlepage">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
			<h2>
				<a href="<?php echo get_permalink($post->post_parent); ?>" rev="attachment">
					<?php echo get_the_title($post->post_parent); ?>
				</a> &raquo;
				<?php the_title(); ?>
			</h2>
			<div class="entry">
				<div class="centered">
					<a href="<?php echo wp_get_attachment_url($post->ID); ?>">
						<?php echo wp_get_attachment_image( $post->ID, 'large' ); ?>
					</a>
				</div>
				<div class="caption">
					<?php if ( !empty($post->post_excerpt) ) the_excerpt(); // this is the "caption" ?>
				</div>
				<?php the_content(__('<div class="read_more">read more &raquo;</div>', 'uti_theme')); ?>
				<br class="clear" />

				<div class="alignleft">
					<?php previous_image_link() ?>
				</div>

				<div class="alignright">
					<?php next_image_link() ?>
				</div>
				<br class="clear" />

				<p class="postmetadata">
					<small>
						<?php printf( __( 'Posted on %1$s at %2$s in', 'uti_theme' ), get_the_date(), get_the_time() ); echo ' '; the_category( ', ' ); ?>
						&nbsp;&nbsp;&#124;&nbsp;&nbsp;<?php post_comments_feed_link(_e('RSS feed', 'uti_theme')); ?>
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
							<?php _e('Comments are currently closed, but you can', 'uti_theme')?>
							<a href="<?php trackback_url(); ?> " rel="trackback">
								<?php _e('trackback','uti_theme')?>
							</a>
							<?php _e(' from your own site.','uti_theme')?>

						<?php } elseif ( comments_open() && !pings_open() ) {
							// Comments are open, Pings are not ?>
							<?php _e( 'Pinging is disabled. But you can skip to the end and leave a', 'uti_theme' ); ?>&nbsp;
							<a href="#respond">
								<?php _e( 'response.','uti_theme' ); ?>
							</a>

						<?php } elseif ( !comments_open() && !pings_open() ) {
							// Neither Comments, nor Pings are open ?>
							<?php _e('Comments and pings are currently closed.', 'uti_theme')?>
						<?php } edit_post_link(__('|  Edit this entry', 'uti_theme'),'','.'); ?>
					</small>
				</p>
			</div><!--.entry-->

			<?php comments_template(); ?>
		</div><!--.post-->
		<?php endwhile; else: ?>
			<p>
				<?php _e('Sorry, no attachments matched your criteria.', 'uti_theme')?>
			</p>
		<?php endif; ?>
	</div><!--#content-->
</div><!--#content_container-->

<?php get_footer(); ?>