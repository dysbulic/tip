<?php
/**
 * @package WordPress
 * @subpackage Spectrum
 */

get_header(); ?>

	<?php if ( have_posts() ) : while (have_posts()) : the_post(); ?>

		<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
			<div class="main-title">
				<h3><a href="<?php echo get_permalink( $post->post_parent ); ?>" rev="attachment"><?php echo get_the_title( $post->post_parent ); ?></a> &raquo; <?php the_title(); ?></h3>
				<div class="post-date">
					<?php spectrum_date(); ?>
				</div>
			</div>
			<div class="post-meta post-author-and-comments">
				<p class="post-author"><?php printf( __( 'Published by <strong>%1$s</strong>', 'spectrum' ), get_the_author() ); ?></p>
				<p class="comment-number"><?php comments_popup_link( __( 'Leave a comment', 'spectrum' ), __( '<strong>1</strong> Comment', 'spectrum' ), __( '<strong>%</strong> Comments', 'spectrum' ) ); ?></p>
			</div>
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
					<a href="<?php echo $next_attachment_url; ?>" title="<?php the_title_attribute(); ?>" rel="attachment"><?php
						$attachment_size = apply_filters( 'theme_attachment_size',  878 );
						echo wp_get_attachment_image( $post->ID, array( $attachment_size, $attachment_size ) );
					?></a>
				<?php the_content( 'Read the rest of this entry &raquo;' ); ?>
			</div>
			<div class="post-meta post-nav">
				<p class="prev-post"><?php previous_image_link(); ?></p>
				<p class="next-post"><?php next_image_link(); ?></p>
			</div>
		</div>

	<?php comments_template(); ?>

	<?php endwhile; else: ?>
	
	<h3><?php _e( 'Not Found', 'spectrum' ); ?></h3>
	<p><?php _e( 'Sorry, no posts matched your criteria.', 'spectrum' ); ?></p>

	<?php endif; ?>

	</div>

<?php get_footer(); ?>