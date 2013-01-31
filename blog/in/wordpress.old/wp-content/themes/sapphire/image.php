<?php get_header(); ?>

	<div id="content" class="widecolumn">
				
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div <?php post_class(); ?>>
			<h2 id="post-<?php the_ID(); ?>"><a href="<?php echo get_permalink($post->post_parent); ?>" rev="attachment"><?php echo get_the_title($post->post_parent); ?></a> &raquo; <?php the_title(); ?></h2>

			<div class="entrytext">
				<p class="attachment"><a href="<?php echo wp_get_attachment_url($post->ID); ?>"><?php echo wp_get_attachment_image( $post->ID, 'auto' ); ?></a></p>
				<div class="caption"><?php if ( !empty($post->post_excerpt) ) the_excerpt(); ?></div>
				<div class="image-description"><?php if ( !empty($post->post_content) ) the_content(); ?></div>

				<div class="navigation">
					<div class="alignleft"><?php previous_image_link() ?></div>
					<div class="alignright"><?php next_image_link() ?></div>
				</div>

				<p class="postmetadata alt">
					<small>
						<?php printf( __( "You can subscribe via <a href='%s'>RSS</a> feed to this post&rsquo;s comments.", "sapphire"), get_post_comments_feed_link()); ?>
												
						<?php if ( ( 'open' == $post->comment_status ) && ( 'open' == $post->ping_status ) ) {
							// Both Comments and Pings are open ?>
							<?php printf( __( 'You can <a href="#respond">comment below</a>, or <a href="%s" rel="trackback">link to this permanent URL</a> from your own site.', 'sapphire' ), trackback_url( false ) ); ?>
						
						<?php } elseif ( ! ( 'open' == $post->comment_status ) && ( 'open' == $post->ping_status) ) {
							// Only Pings are Open ?>
							<?php printf( __( 'Responses are currently closed, but you can <a href="%s" rel="trackback">trackback</a> from your own site.', 'sapphire' ), trackback_url( false ) ); ?>
						
						<?php } elseif ( ( 'open' == $post->comment_status ) && ! ( 'open' == $post->ping_status ) ) {
							// Comments are open, Pings are not ?>
							<?php _e( 'You can skip to the end and leave a response. Pinging is currently not allowed.', 'sapphire' ); ?>
			
						<?php } elseif ( ! ( 'open' == $post->comment_status ) && ! ( 'open' == $post->ping_status ) ) {
							// Neither Comments, nor Pings are open ?>
							<?php _e( 'Both comments and pings are currently closed.', 'sapphire' ); ?>
						
						<?php } edit_post_link( __( 'Edit this entry', 'sapphire' ), '', '.' ); ?>
						
					</small>
				</p>
	
			</div>
		</div>
		
	<?php comments_template(); ?>
	
	<?php endwhile; else: ?>
	
		<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
	
<?php endif; ?>
	
	</div>

<?php get_footer(); ?>
