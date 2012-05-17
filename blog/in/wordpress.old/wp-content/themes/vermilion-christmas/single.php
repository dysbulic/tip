<?php get_header(); ?>

<!-- BEGIN SINGLE.PHP -->
<div id="content">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<div class="post-single">
		<h2 class="post-title"><?php the_title(); ?></h2>
		<div class="entry">
			<?php the_content( __( 'Read more &raquo;', 'vermilionchristmas' ) ); ?>
			<?php link_pages( '<p><strong>' . __( 'Pages:', 'vermilionchristmas' ) . '</strong>', '</p>', 'number' ); ?>

			<p class="postmetadata">
			<?php printf( __( 'Posted by %1$s on %2$s at %3$s', 'vermilionchristmas' ), get_the_author(), get_the_time( get_option( 'date_format' ) ), get_the_time() ); ?><br />
			<?php printf( __( 'Filed under %1$s', 'vermilionchristmas' ), get_the_category_list( ', ' ) ); ?> &nbsp|&nbsp;
			<?php the_tags( __( 'Tags: ', 'vermilionchristmas' ), ', ', ' &nbsp|&nbsp; ' ); ?>

<?php if (( 'open' == $post-> comment_status) && ( 'open' == $post->ping_status)) {
	// Both Comments and Pings are open ?>
			<a href="#respond" class="commentlink"><?php _e( 'Leave a comment', 'vermilionchristmas' ); ?></a> &nbsp;|&nbsp; <a href="<?php trackback_url(true); ?>" rel="trackback" class="trackback"><?php _e( 'Trackback URI', 'vermilionchristmas' ); ?></a>

<?php } elseif ( !( 'open' == $post-> comment_status ) && ( 'open' == $post->ping_status ) ) {
	// Only Pings are Open ?>
			Responses are currently closed, but you can <a href="<?php trackback_url( true ); ?> " rel="trackback">trackback</a> from your own site.

<?php } elseif ( ( 'open' == $post-> comment_status ) && !( 'open' == $post->ping_status ) ) {
	// Comments are open, Pings are not ?>
			<?php _e( 'You can skip to the end and leave a response. Pinging is currently not allowed.', 'vermilionchristmas' ); ?>

<?php } elseif (!( 'open' == $post-> comment_status) && !( 'open' == $post->ping_status)) {
	// Neither Comments, nor Pings are open ?>
			<?php _e( 'Both comments and pings are currently closed.', 'vermilionchristmas' ); ?>

<?php } edit_post_link( 'edit','<div class="edit">[',']</div>' ); ?>
			</p>
		</div>
	</div>

	<div class="navigation-single">
		<?php previous_post_link( '<span class="previous-single">Previous Entry: %link</span>' ); ?>
		<?php next_post_link( '<br /><span class="next-single">Next Entry: %link</span>' ); ?>
	</div>

<?php comments_template(); ?>

<?php endwhile; else: ?>

	<p><?php _e( 'Sorry, no posts matched your criteria.', 'vermilionchristmas' ); ?></p>

<?php endif; ?>
</div>
<!-- END SINGLE.PHP -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>