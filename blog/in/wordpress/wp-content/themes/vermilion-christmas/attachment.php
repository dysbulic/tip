<?php get_header(); ?>

<!-- BEGIN ATTACHMENT.PHP -->
<div id="wide-page">
<div id="wide-content">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<?php $attachment_link = get_the_attachment_link($post->ID, true, array(450, 800)); // This also populates the iconsize for the next line ?>
<?php $_post = &get_post($post->ID); $classname = ($_post->iconsize[0] <= 128 ? 'small' : '') . 'attachment'; // This lets us style narrow icons specially ?>
      <h2 align="center" class="post-title"><a href="<?php echo get_permalink($post->post_parent); ?>" rev="attachment"><?php echo get_the_title($post->post_parent); ?></a> &raquo; <a href="<?php echo get_permalink() ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link: %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2>
      <div class="entry justify">
	  
	  <p class="<?php echo $classname; ?>"><?php echo $attachment_link; ?><br /><?php echo basename($post->guid); ?></p>

				<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>

				<?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>
	  
	  <p class="postmetadata">

<?php _e( 'Published:', 'theme-slug' ); ?> <?php the_time(get_option('date_format')) ?><br />

<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
	// Both Comments and Pings are open ?>
<a href="#respond"><?php _e( 'Leave a response', 'vermilionchristmas' ); ?></a> | <a href="<?php trackback_url(true); ?>" rel="trackback"><?php _e( 'Trackback', 'vermilionchristmas' ); ?></a>

<?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
	// Only Pings are Open ?>
	<?php printf( __( 'Responses are currently closed, but you can <a href="%s" rel="trackback">trackback</a> from your own site.', 'vermilionchristmas'), trackback_url( false ) ); ?>

<?php } elseif ( ( 'open' == $post->comment_status ) && ! ( 'open' == $post->ping_status ) ) {
	// Comments are open, Pings are not ?>
	<?php _e( 'You can skip to the end and leave a response. Pinging is currently not allowed.', 'vermilionchristmas' ); ?>

<?php } elseif ( ! ( 'open' == $post->comment_status ) && ! ( 'open' == $post->ping_status ) ) {
	// Neither Comments, nor Pings are open ?>
	<?php _e( 'Both comments and pings are currently closed.', 'vermilionchristmas' ); ?>

<?php } edit_post_link( __( 'Edit this entry.', 'vermilionchristmas' ), '| ', '' ); ?>

</p>
	  
	  </div>

<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p><?php _e( 'Sorry, no attachments matched your criteria.', 'vermilionchristmas' ); ?></p>

<?php endif; ?>

</div>
</div>
<!-- END ATTACHMENT.PHP -->

<?php get_footer(); ?>
