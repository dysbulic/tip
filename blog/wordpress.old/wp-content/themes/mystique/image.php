<?php
/**
 * The template for displaying an attachment image.
 *
 * @package WordPress
 * @subpackage Mystique
 */
get_header(); ?>

 			<div id="content-container">
	 			<div id="content">
					<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

						<div <?php post_class( 'post-wrapper clear-block' ); ?>>

							<h2 class="single-title"><?php the_title(); ?></h2>
							<div class="post-date">
								<p class="day"><?php the_time( __( 'M j', 'mystique' ) ); ?></p>
							</div><!-- .post-date -->

							<div class="post-info clear-block">
								<p class="author alignleft">
									<?php
										if ( ! empty( $post->post_parent ) ) :

											$metadata = wp_get_attachment_metadata();
											printf( __( '<span class="meta-prep meta-prep-entry-date">Published </span> at <a href="%1$s" title="Link to full-size image">%2$s &times; %3$s</a> in <a href="%4$s" title="Return to %5$s" rel="gallery">%5$s</a>', 'mystique' ),
												wp_get_attachment_url(),
												$metadata['width'],
												$metadata['height'],
												get_permalink( $post->post_parent ),
												get_the_title( $post->post_parent )
											);
										?>
									<?php
									else :

										$metadata = wp_get_attachment_metadata();
										printf( __( '<span class="meta-prep meta-prep-entry-date">Uploaded </span> at <a href="%1$s" title="Link to full-size image">%2$s &times; %3$s</a>', 'mystique' ),
											wp_get_attachment_url(),
											$metadata['width'],
											$metadata['height']
										);
									?>
									<?php endif; ?>
									<?php edit_post_link( __( 'Edit', 'mystique' ), '<span class="edit-link"> &#124; ', '</span>' ); ?>
								</p>
							</div><!-- .post-info clear-block" -->

							<div id="image-navigation">
								<span class="previous-image"><?php previous_image_link( false, __( '&larr; Previous Image', 'mystique' ) ); ?></span>
								<span class="next-image"><?php next_image_link( false, __( 'Next Image &rarr;', 'mystique' ) ); ?></span>
							</div><!-- #image-navigation -->

							<div class="entry">
								<div class="entry-attachment">
									<div class="attachment">
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
											echo wp_get_attachment_image( $post->ID, array( 788, 788 ) ); // filterable image width with, essentially, no limit for image height.
										?></a>
									</div><!-- .attachment -->

									<?php if ( ! empty( $post->post_excerpt ) ) : ?>
									<div class="entry-caption">
										<?php the_excerpt(); ?>
									</div>
									<?php endif; ?>
								</div><!-- .entry-attachment -->

								<?php the_content(); ?>
								<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'mystique' ), 'after' => '</div>' ) ); ?>
							</div><!-- .entry -->

							<div class="post-utility">
								<p class="details">
									<?php mystique_post_meta(); ?>
									<?php comments_popup_link( __( 'Leave a comment', 'mystique' ), __( '1 Comment', 'mystique' ), __( '% Comments', 'mystique' ) ); ?><?php _e( '.', 'mystique' ); ?>
								</p>
							</div><!-- .post-utility -->

						</div><!-- #post-## -->

						<?php comments_template(); ?>

					<?php endwhile; ?>

				</div><!-- #content -->
			</div><!-- #content-container -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>