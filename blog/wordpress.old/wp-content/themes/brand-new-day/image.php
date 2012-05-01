<?php
/**
 * @package WordPress
 * @subpackage Brand New Day
 */

get_header();
?>

	<div id="content" class="content image-attachment">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
				<div class="navigation">
					<div class="alignleft"><?php previous_image_link( false, __( '&laquo; Previous' , 'brand-new-day' ) ); ?></div>
					<div class="alignright"><?php next_image_link( false, __( 'Next &raquo;' , 'brand-new-day' ) ); ?></div>
				</div>
			<h2 class="page_title"><?php the_title(); ?></h2>
			<small>
				<?php
					$metadata = wp_get_attachment_metadata();
					printf( __( 'Published <span class="entry-date"><abbr class="published" title="%1$s">%2$s</abbr></span> at <a href="%3$s" title="Link to full-size image">%4$s &times; %5$s</a> in <a href="%6$s" title="Return to %7$s" rel="gallery">%7$s</a>', 'brand-new-day' ),
						esc_attr( get_the_time() ),
						get_the_date(),
						wp_get_attachment_url(),
						$metadata['width'],
						$metadata['height'],
						get_permalink( $post->post_parent ),
						get_the_title( $post->post_parent )
					);
				?>
			</small>

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
				
				<p><a href="<?php echo $next_attachment_url; ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php
				$attachment_size = apply_filters( 'brand_new_day_attachment_size', 1000 );
				echo wp_get_attachment_image( $post->ID, array( $attachment_size, $attachment_size ) ); // filterable image width with, essentially, no limit for image height.
				?></a></p>
				
				<?php if ( ! empty( $post->post_excerpt ) ) : ?>
				<div class="entry-caption wp-caption-text gallery-caption">
					<?php the_excerpt(); ?>
				</div>
				<?php endif; ?>
				
				<?php the_content(); ?>

				<?php wp_link_pages(array('before' => '<p class="clear"><strong>' . __( 'Pages:' , 'brand-new-day' ) . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

				<div class="edit-link"><?php edit_post_link( __( 'Edit this entry', 'brand-new-day' ) , '', ''); ?></div>

				<?php comments_template(); ?>

			</div>

		</div>



	<?php endwhile; else: ?>

		<h2 class="page_title"><?php _e( 'Not Found' , 'brand-new-day' ) ?></h2>
		<p class="aligncenter"><?php _e( 'Sorry, no posts matched your criteria.', 'brand-new-day' ) ?></p>
		<?php get_search_form(); ?>

<?php endif; ?>

	</div>

<?php get_footer(); ?>