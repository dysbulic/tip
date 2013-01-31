<?php
/**
 * @package WordPress
 * @subpackage Fusion
 */
?>
<?php get_header(); ?>

<div id="mid-content">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<h3 class="title"><a href="<?php echo get_permalink( $post->post_parent ); ?>" rev="attachment"><?php echo get_the_title( $post->post_parent ); ?></a> &raquo; <?php the_title(); ?></h3>

				<p class="attachment-entry">

<?php
	/**
	 * Grab the IDs of all the image attachments in a gallery so we can get the URL of the next adjacent image in a gallery,
	 * or the first image (if we're looking at the last image in a gallery), or, in a gallery of one, just the link to that image file
	 */
	$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
	foreach ( $attachments as $k => $attachment ) {
		if ( $attachment->ID == $post->ID )
			break;
	}
	$k++;
	// If there is more than 1 attachment in a gallery
	if ( count( $attachments ) > 1 ) {
		if ( isset( $attachments[ $k ] ) )
			// get the URL of the next image attachment
			$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
		else
			// or get the URL of the first image attachment
			$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
	} else {
		// or, if there's only 1 image, get the URL of the image
		$next_attachment_url = wp_get_attachment_url();
	}
?>
					<a href="<?php echo $next_attachment_url; ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php
						$attachment_size = apply_filters( 'theme_attachment_size',  attachment_width() );
						echo wp_get_attachment_image( $post->ID, array( $attachment_size, $attachment_size ) );
					?></a>

	  				<div class="caption"><?php if ( !empty( $post->post_excerpt ) ) the_excerpt(); // this is the "caption" ?>
  						<?php the_content( __( 'Read the rest of this entry &raquo;', 'fusion' ) ); ?>
					</div>

 				</div>

  		 	</div>
  			<!-- /post -->

			<div class="navigation">
		 		<div class="alignleft"><?php previous_image_link(); ?></div>
				<div class="alignright"><?php next_image_link(); ?></div>

				<div class="clear"></div>
		 	</div>

			<p class="postmetadata alt">

  			<small>
  				<?php
  					printf( __( 'This image was posted on %s. You can follow any responses to this entry through %s.', 'fusion' ), get_the_time( get_option( 'date_format' ).', '.get_option( 'time_format' ) ), '<a href="'.get_post_comments_feed_link( $post->ID ).'" title="RSS 2.0">RSS 2.0</a>' );  ?>

  				<?php if (( 'open' == $post-> comment_status) && ( 'open' == $post->ping_status) ) {
 				  // Both Comments and Pings are open
  					printf( __( 'You can <a href="#respond">leave a response</a>, or <a href="%s" rel="trackback">trackback</a> from your own site.', 'fusion' ), trackback_url( '',false) );

 				 } elseif (!( 'open' == $post-> comment_status) && ( 'open' == $post->ping_status) ) {
 				  // Only Pings are Open
  					printf( __( 'Responses are currently closed, but you can <a href="%s" rel="trackback">trackback</a> from your own site.', 'fusion' ), trackback_url( '',false) );

 				 } elseif (( 'open' == $post-> comment_status) && !( 'open' == $post->ping_status) ) {
 				  // Comments are open, Pings are not
 				  _e( 'You can skip to the end and leave a response. Pinging is currently not allowed.', 'fusion' );

 					 } elseif (!( 'open' == $post-> comment_status) && !( 'open' == $post->ping_status) ) {
				  // Neither Comments, nor Pings are open
				  _e( 'Both comments and pings are currently closed.', 'fusion' );
				} ?>

				<?php edit_post_link( __( 'Edit this entry', 'fusion' ) ); ?>
			</small>

 			</p>

	<?php comments_template(); ?>

<?php endwhile; else: ?>

	<p><?php _e( 'Sorry, no attachments matched your criteria.', 'fusion' ); ?></p>

<?php endif; ?>

</div>
<!-- mid content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>