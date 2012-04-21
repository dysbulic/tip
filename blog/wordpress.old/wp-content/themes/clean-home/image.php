<?php
/**
 * @package WordPress
 * @subpackage Clean Home
 */
?>
<?php get_header(); ?>

	<div class="content">
	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>

		<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
			<h1><?php the_title(); ?></h1>
			<small class="post-meta">
			<?php
				if ( ! empty( $post->post_parent ) ) :

				$metadata = wp_get_attachment_metadata();
				printf( __( '<b>Posted:</b> %1$s | <b>Dimensions:</b> <a href="%2$s" title="Link to full-size image">%3$s &times; %4$s</a> | <b>Gallery:</b> <a href="%5$s" title="Return to %6$s" rel="gallery">%6$s</a>', 'cleanhome' ),
					esc_attr( get_the_time() ),
					wp_get_attachment_url(),
					$metadata['width'],
					$metadata['height'],
					get_permalink( $post->post_parent ),
					get_the_title( $post->post_parent )
				);
			?> | <?php comments_popup_link( __( 'Leave a comment &#187;', 'cleanhome' ), __( '<strong>1</strong> Comment &#187;', 'cleanhome' ), __( '<strong>%</strong> Comments &#187;', 'cleanhome' ) ); ?>

			<?php
				else :

				$metadata = wp_get_attachment_metadata();
				printf( __( '<b>Dimensions:</b> <a href="%1$s" title="Link to full-size image">%2$s &times; %3$s</a>', 'cleanhome' ),
					wp_get_attachment_url(),
					$metadata['width'],
					$metadata['height']
				);
			?> | <?php comments_popup_link( __( 'Leave a comment &#187;', 'cleanhome' ), __( '<strong>1</strong> Comment &#187;', 'cleanhome' ), __( '<strong>%</strong> Comments &#187;', 'cleanhome' ) ); ?>
			<?php endif; ?>
			</small>

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
						$attachment_size = apply_filters( 'theme_attachment_size',  900 );
						echo wp_get_attachment_image( $post->ID, array( $attachment_size, $attachment_size ) );
					?></a>

			<?php the_content( 'Read the rest of this entry &raquo;' ); ?>
			<?php wp_link_pages( array( 'before' => '<p>Page: ', 'after' => '</p>', 'next_or_number' => 'number' ) ); ?>
			<hr/>
		</div>

		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php previous_image_link(); ?></div>
			<div class="alignright"><?php next_image_link(); ?></div>
		</div>

		<?php comments_template(); ?>

	<?php else : ?>

		<h2 class="center"><?php _e( 'Not found', 'cleanhome' ); ?></h2>
		<p class="center"><?php _e( "Sorry, but you are looking for something that isn't here.", 'cleanhome' ); ?></p>

	<?php endif; ?>

	</div>

<?php get_footer(); ?>
