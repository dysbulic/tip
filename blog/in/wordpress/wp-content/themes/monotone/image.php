<?php
/**
 * @package WordPress
 * @subpackage Monotone
 */
get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<div class="image">
		<div class="nav prev"><?php previous_image_link( false ); ?></div>
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
			$attachment_size = apply_filters( 'monotone_attachment_size', 840 );
			echo wp_get_attachment_image( $post->ID, array( $attachment_size, $attachment_size ) );
		?>
		<div class="nav next"><?php next_image_link( false ); ?></div>
	</div><!-- #image -->
	<div id="container" <?php post_class(); ?>>
		<h2><?php the_title(); ?></h2>

		<div id="postmetadata">
			<div class="sleeve">
				<p>
				<?php
					$metadata = wp_get_attachment_metadata();
					printf( __( '%1$s at <a href="%2$s" title="Link to full-size image">%3$s &times; %4$s</a> in <a href="%5$s" title="Return to %6$s" rel="gallery">%6$s</a>', 'monotone' ),
						get_the_date(),
						wp_get_attachment_url(),
						$metadata['width'],
						$metadata['height'],
						get_permalink( $post->post_parent ),
						get_the_title( $post->post_parent )
					);
				?>
				</p>
				<?php edit_post_link( __( 'Edit', 'monotone' ), '<p>', '</p>' ); ?>
				<p><?php comments_popup_link( __( 'Leave a Comment &#187;', 'monotone' ), __( '1 Comment &#187;', 'monotone' ), __( '% Comments &#187;', 'monotone' ) ); ?></p>
			</div><!-- .sleeve -->
		</div><!-- #postmetadata -->

		<div id="post">
			<div class="sleeve">
				<?php the_content( __( 'Read the rest of this entry &raquo;', 'monotone' ) ); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'monotone' ), 'after' => '</div>' ) ); ?>
			</div><!-- .sleeve -->
		</div><!-- #post -->

		<div class="navigation">
			<div class="prev"><?php previous_image_link( false, '&lsaquo;' ); ?></div>
			<div class="next"><?php next_image_link( false, '&rsaquo;' ); ?></div>
		</div><!-- #navigation -->

		<?php comments_template(); ?>
	</div><!-- #container -->
<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>