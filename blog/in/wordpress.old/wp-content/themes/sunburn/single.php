<?php get_header(); ?>
	<div id="content" class="widecolumn">
<?php is_tag(); ?>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
			<h2><?php the_title(); ?></h2>

			<div class="entrytext">
				<?php the_content( '<p class="serif">' . __( 'Read the rest of this entry &raquo;', 'sunburn' ) . '</p>' ); ?>

				<?php link_pages('<p><strong>' . __( 'Pages:', 'sunburn' ) . '</strong> ', '</p>', 'number'); ?>

			</div>
		</div>

	<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p><?php _e( 'Sorry, no posts matched your criteria.', 'sunburn' ); ?></p>

<?php endif; ?>

	</div>

	<div id="sidebar" style="color: #ccc; font-size: 0.8em;">

		<p><a href="<?php bloginfo('url'); ?>"><?php _e( 'Home', 'sunburn' ); ?></a></p>

		<p class="postmeta_single">
				<?php
					printf( __( 'This entry was posted on %1$s at %2$s and filed under %4$s. You can follow any responses to this entry through the %3$s feed.', 'sunburn' ),
						get_the_time( get_option( 'date_format' ) ),
						get_the_time(),
						'<a href=""' . get_post_comments_feed_link( 'RSS' ) . '">RSS</a>',
						get_the_category_list( ',' )
					);
				?>
				<?php print get_the_term_list( $post->ID, 'post_tag', '<p>' . __( 'Tags:', 'sunburn' ) . ' ', ', ', '</p>'); ?>

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
		</p>


		<div class="navigation">
			<div class="alignright"><?php next_post_link( __( '%link &raquo;', 'sunburn' ) ); ?></div>
			<div class="alignleft"><?php previous_post_link( __( '&laquo; %link', 'sunburn' ) ); ?></div>
		</div>

	</div>

<?php get_footer(); ?>
