<?php
/**
 * @package WordPress
 * @subpackage Oulipo
 */
?>
<?php get_header(); ?>

<div id="content">

	<div id="entry-content">

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h2><?php the_title(); ?></h2>
				<p class="date"><?php the_time( 'F jS, Y' ); ?> <?php comments_popup_link( '&sect; <span class="commentcount">' . __( 'Leave a Comment', 'oulipo' ) . '</span>', '&sect; <span class="commentcount">' . __( '1 Comment', 'oulipo' ) . '</span>', '&sect; <span class="commentcount">' . __( '% Comments', 'oulipo' ) . '</span>' ); ?></p>


				<div class="entry">
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
								$attachment_size = apply_filters( 'theme_attachment_size',  480 );
								echo wp_get_attachment_image( $post->ID, array( $attachment_size, $attachment_size ) );
								?></a>
					
					<?php the_content( __( '&raquo; Read the rest of this entry &laquo;', 'oulipo' ) ); ?>
					<p class="tags"><?php the_tags( '<strong>Tagged:</strong> ', ', ', '' ); ?></p>
					
					<div class="navigation">
						<p class="alignleft"><?php previous_image_link( '', __( '&laquo;Previous Image', 'oulipo' ) ); ?></p>
						<p class="alignright"><?php next_image_link( '', __( 'Next Image &raquo;', 'oulipo' ) ); ?></p>
					</div>
					
					<?php comments_template(); ?>

				</div>
			</div>

		<?php endwhile; ?>

	<?php else : ?>

		<div class="entry">
			<span class="error"><img src="<?php bloginfo( 'template_directory' ); ?>/images/mal.png" alt="error duck" /></span>
			<p><?php _e( 'Hmmm, seems like what you were looking for isn&rsquo;t here. You might want to give it another try.', 'oulipo' ); ?></p>
		</div>

	<?php endif; ?>

</div> <!-- close entry-content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>