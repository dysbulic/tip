<?php
/*
 * The Template for displaying image attachments.
 *
 * @package WordPress
 * @subpackage Skeptical
*/
?>

<?php get_header(); ?>

	<div id="content" class="page col-full">
		<div id="main" class="fullwidth image-attachment">
			<?php while ( have_posts() ) : the_post(); ?>

			<div class="post page">
				<h1 class="title"><?php the_title(); ?></h1>
				<div class="attachment-data">
				<?php
					$metadata = wp_get_attachment_metadata();
					printf( __( '<span class="meta-prep meta-prep-entry-date">Published </span> <span class="entry-date"><abbr class="published" title="%1$s">%2$s</abbr></span> at <a href="%3$s" title="Link to full-size image">%4$s &times; %5$s</a> in <a href="%6$s" title="Return to %7$s" rel="gallery">%7$s</a>', 'woothemes' ),
						esc_attr( get_the_time() ),
						get_the_date(),
						esc_url( wp_get_attachment_url() ),
						$metadata['width'],
						$metadata['height'],
						esc_url( get_permalink( $post->post_parent ) ),
						get_the_title( $post->post_parent )
					);
				?>
				<span class="comments-num"> | <?php comments_popup_link( __( 'Leave a Comment', 'woothemes' ), __( '1 Comment', 'woothemes' ), __( '% Comments', 'woothemes' ) ); ?></span>
				</div><!-- .post-data -->
				<div class="entry">
					<div class="attachment">
						<div class="nav-attachment">
							<div class="fl"><?php previous_image_link( false, __( '&laquo; Previous' , 'woothemes' ) ); ?></div>
							<div class="fr"><?php next_image_link( false, __( 'Next &raquo;' , 'woothemes' ) ); ?></div>
							<div class="fix"></div>
						</div>
					<?php
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
						<a href="<?php echo esc_url( $next_attachment_url ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php
						$attachment_size = apply_filters( 'skeptical_attachment_size', 899 );
						echo wp_get_attachment_image( $post->ID, array( $attachment_size, 899 ) );
						?></a>

						<?php if ( ! empty( $post->post_excerpt ) ) : ?>
							<div class="entry-caption">
								<?php the_excerpt(); ?>
							</div>
						<?php endif; ?>
					</div><!-- .attachment -->
					<?php the_content( __( 'Read More...', 'woothemes' ) ); ?>
					<?php edit_post_link( __( '{ Edit }', 'woothemes' ), '<p>', '</p>' ); ?>
				</div>
			</div><!-- /.post -->

			<?php comments_template( '', true ); ?>

			<?php endwhile; // end of the loop. ?>
		</div><!-- /#main -->
	</div><!-- /#content -->

<?php get_footer(); ?>