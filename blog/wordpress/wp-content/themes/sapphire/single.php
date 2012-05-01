<?php get_header(); ?>

	<div id="content" class="widecolumn">
				
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
		<div class="navigation">
			<div class="alignleft"><?php previous_post('&laquo; %','','yes') ?></div>
			<div class="alignright"><?php next_post(' % &raquo;','','yes') ?></div>
		</div>
	
		<div <?php post_class(); ?>>
			<h2 id="post-<?php the_ID(); ?>"><a href="<?php echo get_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link to', 'sapphire'); ?> <?php the_title(); ?>"><?php the_title(); ?></a></h2>

			<div class="entrytext">
				<?php the_content('<p class="serif">' . __('Read the rest of this entry &raquo;', 'sapphire') . '</p>'); ?> 
				<strong><?php _e('Explore posts in the same categories:', 'sapphire'); ?></strong> <?php the_category(', ') ?></small> 
				<?php link_pages('<p><strong>' . __('Pages:', 'sapphire') . '</strong> ', '</p>', 'number'); ?>
	
				<p class="postmetadata alt">
					<small>
						<?php printf(__('This entry was posted on %s at %s and is filed under %s. You can subscribe via <a href="%s">RSS 2.0</a> feed to this post\'s comments.', 'sapphire'), get_the_time(get_option('date_format')), get_the_time(), get_the_category_list(', '), get_post_comments_feed_link('RSS 2.0')); ?> 
						<?php the_tags( '<p><strong>' . __('Tags:', 'sapphire') . '</strong> ', ', ', '</p>'); ?>												
						<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
							// Both Comments and Pings are open ?>
                            <?php printf(__('You can <a href="#respond">comment below</a>, or <a href="%s">link to this permanent URL</a>  from your own site.', 'sapphire'), trackback_url(false)); ?> 
						
						<?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
							// Only Pings are Open ?>
							<?php printf(__('Responses are currently closed, but you can <a href="#respond">comment below</a>, or <a href="%s">link to this permanent URL</a>  from your own site.', 'sapphire'), trackback_url(false)); ?>
						
						<?php } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
							// Comments are open, Pings are not ?>
							<?php _e('You can skip to the end and leave a response. Pinging is currently not allowed.', 'sapphire'); ?>
			
						<?php } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
							// Neither Comments, nor Pings are open ?>
							<?php _e('Both comments and pings are currently closed.', 'sapphire'); ?>			
						
						<?php } edit_post_link(__('Edit this entry.', 'sapphire'),'',''); ?>
						
					</small>
				</p>
	
			</div>
		</div>
		
	<?php comments_template(); ?>
	
	<?php endwhile; else: ?>
	
		<p><?php _e('Sorry, no posts matched your criteria.', 'sapphire'); ?></p>
	
<?php endif; ?>
	
	</div>

<?php get_footer(); ?>
