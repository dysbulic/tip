<?php get_header(); ?>

	<div id="content" class="widecolumn">
				
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
		<div class="navigation">
			<div class="alignleft">&nbsp;</div>
			<div class="alignright">&nbsp;</div>
		</div>
		
<?php $attachment_link = get_the_attachment_link($post->ID, true, array(450, 800)); // This also populates the iconsize for the next line ?>
<?php $_post = &get_post($post->ID); $classname = ($_post->iconsize[0] <= 128 ? 'small' : '') . 'attachment'; // This lets us style narrow icons specially ?>

		<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
			<h2><a href="<?php echo get_permalink($post->post_parent); ?>" rev="attachment"><?php echo get_the_title($post->post_parent); ?></a> &raquo; <a href="<?php echo get_permalink() ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link: %s', 'sunburn' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2>
			<div class="entrytext">
				<p class="<?php echo $classname; ?>"><?php echo $attachment_link; ?><br /><?php echo basename($post->guid); ?></p>

				<?php the_content( '<p class="serif">' . __( 'Read the rest of this entry &raquo;', 'sunburn' ) . '</p>' ); ?>
	
				<?php link_pages( '<p><strong>' . __( 'Pages:', 'sunburn' ) . '</strong> ', '</p>', 'number'); ?>
	
				<p class="postmetadata alt">
					<small>
						<?php
							printf( __( 'This entry was posted on %1$s at %2$s. You can follow any responses to this entry through the %3$s feed.', 'sunburn' ),
								get_the_time( get_option( 'date_format' ) ),
								get_the_time(),
								'<a href=""' . get_post_comments_feed_link( 'RSS' ) . '">RSS</a>'
							);
						?>
						
						<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
							// Both Comments and Pings are open ?>
							<?php printf( __( 'You can <a href="#respond">leave a response</a>, or <a href="%s" rel="trackback">trackback</a> from your own site.', 'sunburn' ), trackback_url( false ) ); ?>
							
						<?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
							// Only Pings are Open ?>
							<?php printf( __( 'Responses are currently closed, but you can <a href="%s" rel="trackback">trackback</a> from your own site.', 'sunburn' ), trackback_url( false ) ); ?>

						<?php } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
							// Comments are open, Pings are not ?>
							<?php _e('You can skip to the end and leave a response. Pinging is currently not allowed.', 'sunburn'); ?>
			
						<?php } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
							// Neither Comments, nor Pings are open ?>
							<?php _e('Both comments and pings are currently closed.', 'sunburn'); ?>
						
						<?php } edit_post_link( __( 'Edit this entry.', 'sunburn' ), '', '' ); ?>
						
					</small>
				</p>
	
			</div>
		</div>
		
	<?php comments_template(); ?>
	
	<?php endwhile; else: ?>
	
		<p><?php _e( 'Sorry, no attachments matched your criteria.', 'sunburn' ); ?></p>
	
<?php endif; ?>
	
	</div>

<?php get_footer(); ?>
