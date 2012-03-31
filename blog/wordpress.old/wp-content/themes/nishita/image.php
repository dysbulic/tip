<?php
/**
 * @package WordPress
 * @subpackage Nishita
 */
get_header(); ?>

<div class="main single-no-sidebar">

	<?php while ( have_posts() ) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<?php if ( get_the_title() ) { ?>
				<h2 class="photo-title"><span class="content-title"><?php the_title(); ?></span></h2>
			<?php } ?>

			<div class="photo">
				<div class="photo-inner">

					<?php the_content(); ?>

					<div class="entry-attachment">

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
						<a href="<?php echo esc_url( $next_attachment_url ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php
						$attachment_size = apply_filters( 'nishita_attachment_size', 1024 );
						echo wp_get_attachment_image( $post->ID, array( $attachment_size, 1024 ) ); // filterable image width with 1024px limit for image height.
						?></a>

						<?php if ( ! empty( $post->post_excerpt ) ) : ?>
						<div class="entry-caption">
							<?php the_excerpt(); ?>
						</div><!-- .entry-caption -->
						<?php endif; ?>

						<div class="attachment-navigate">
							<div class="previous"><?php previous_image_link( 'thumbnail' ); ?></div>
							<div class="next"><?php next_image_link( 'thumbnail' ); ?></div>
						</div><!-- .attachment-navigate -->

					</div><!-- .entry-attachment -->

					<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'nishita' ) . '</span>', 'after' => '</div>' ) ); ?>
					<?php if ( comments_open() ) comments_template( '', true ); ?>
					<div class="clear"></div>

				</div><!-- .photo-inner -->
			</div><!-- .photo -->

			<div class="photo-meta">
				<div class="photo-meta-inner">
					<ul>
						<li class="first last">
						<?php
							$metadata = wp_get_attachment_metadata();
							printf( __( '<span class="meta-prep meta-prep-entry-date">Published </span> <span class="entry-date"><abbr class="published" title="%1$s">%2$s</abbr></span> at <a href="%3$s" title="Link to full-size image">%4$s &times; %5$s</a> in <a href="%6$s" title="Return to %7$s" rel="gallery">%7$s</a>', 'nishita' ),
								esc_attr( get_the_time() ),
								get_the_date(),
								esc_url( wp_get_attachment_url() ),
								$metadata['width'],
								$metadata['height'],
								esc_url( get_permalink( $post->post_parent ) ),
								get_the_title( $post->post_parent )
							);
						?>
						</li>
					</ul>
				</div><!-- .photo-meta-inner -->
			</div><!-- .photo-meta -->

		</div><!-- #post-<?php the_ID(); ?> -->

	<?php endwhile; // end of the loop. ?>

</div><!-- .main -->

<?php get_footer(); ?>