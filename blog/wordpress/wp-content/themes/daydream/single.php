<?php get_header(); ?>

	<div id="content">

  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
			<h2 class="single"><?php the_title(); ?></h2>

			<div class="entry">
				<?php the_content('<p class="serif">' . __('Read the rest of this entry &raquo;', 'daydream') . '</p>'); ?>

				<?php link_pages('<p><strong>'.__('Pages','daydream').':</strong> ', '</p>', 'number'); ?>

			</div>
		</div>

		<p id="single" class="postmetadata">
			<small>
				<?php printf(__('This entry was posted %1$s on %2$s at %3$s and is filed under %4$s.', 'daydream'), $time_since, get_the_time(__('l, F jS, Y', 'daydream')), get_the_time(), get_the_nice_category(', ', ' ' . __('and', 'daydream') . ' ')); ?> <?php the_tags( __( 'Tagged' ) . ': ', ', ', '. '); ?>

				<?php if (get_option('dd_tags_cats') == "both" && function_exists('UTW_ShowTagsForCurrentPost')) { ?>

				 <?php _e('Tagged with', 'daydream'); ?> <?php UTW_ShowTagsForCurrentPost("commalist") ?>.

				<?php } ?>

				<?php printf(__('You can <a href="%s">feed</a> this entry.', 'daydream'), get_post_comments_feed_link()); ?>

				<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
					// Both Comments and Pings are open ?>
					<?php printf(__('You can <a href="#respond">leave a response</a>, or <a href="%s" rel="trackback">trackback</a> from your own site.', 'daydream'), get_trackback_url()); ?>

				<?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
					// Only Pings are Open ?>
					<?php printf(__('Responses are currently closed, but you can <a href="%s" rel="trackback">trackback</a> from your own site.', 'daydream'), get_trackback_url()); ?>

				<?php } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
					// Comments are open, Pings are not ?>
					<?php _e('You can skip to the end and leave a response. Pinging is currently not allowed.', 'daydream'); ?>

				<?php } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
					// Neither Comments, nor Pings are open ?>
					<?php _e('Both comments and pings are currently closed.', 'daydream'); ?>

				<?php } edit_post_link(__('Edit this entry.', 'daydream'),'',''); ?>

			</small>
		</p>

	<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p><?php _e('Sorry, no posts matched your criteria.','daydream'); ?></p>

<?php endif; ?>

	<div class="navigation">
		<div class="alignleft"><?php previous_post_link('&laquo; %link') ?></div>
		<div class="alignright"><?php next_post_link('%link &raquo;') ?></div>
	</div>

	</div>

<?php get_footer(); ?>
