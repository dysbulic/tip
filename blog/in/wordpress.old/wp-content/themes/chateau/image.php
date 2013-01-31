<?php
/**
 * The Template for displaying image attachments.
 *
 * @package WordPress
 * @subpackage Chateau
 */
get_header(); ?>

<div id="primary">
	<div id="content" class="image-attachment clear-fix" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="post-title">
				<h1>
					<?php
						printf( __( '<a href="%1$s" title="Return to %2$s" rel="gallery">%2$s</a>' ),
							get_permalink( $post->post_parent ),
							get_the_title( $post->post_parent )
						 );
					?>
				</h1>
				<p class="post-date">
					<strong><?php the_time( 'd' ); ?></strong>
					<em><?php the_time( 'l' ); ?></em>
					<span><?php the_time( 'M Y' ); ?></span>
				</p>
				<div class="post-info clear-fix">
					<?php
						$metadata = wp_get_attachment_metadata();
						printf( __( '<p>Original size at <a href="%1$s" title="Link to original size image">%2$s &times; %3$s</a></p>', 'chateau' ),
							esc_url( wp_get_attachment_url() ),
							$metadata['width'],
							$metadata['height']
						);
					?>
					<p class="post-com-count">
						<strong>&asymp; <?php comments_popup_link( __( 'Leave a Comment', 'chateau' ), __( '1 Comment', 'chateau' ), __( '% Comments', 'chateau' ) ); ?></strong>
					</p>
				</div>
			</header><!-- .entry-header -->
			<div class="post-content clear-fix">
				<div class="post-extras">
					<?php edit_post_link( __( 'Edit', 'chateau' ), '<p>[', ']</p>' ); ?>
					<?php the_tags( '<p><strong>' . __( 'Tags','chateau' ) . '</strong></p><p>', ', ', '</p>' ); ?>
				</div><!-- end .post-extras -->
				<div class="post-entry">
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
							<a href="<?php echo esc_url( $next_attachment_url ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php
							$attachment_size = apply_filters( 'chateau_attachment_size', 762 );
							echo wp_get_attachment_image( $post->ID, array( $attachment_size, 1024 ) ); // filterable image width with 1024px limit for image height.
							?></a>

							<?php if ( ! empty( $post->post_excerpt ) ) : ?>
								<div class="entry-caption">
									<?php the_excerpt(); ?>
								</div>
							<?php endif; ?>
						</div><!-- .attachment -->
					</div><!-- .entry-attachment -->

					<div class="entry-description">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'chateau' ) . '</span>', 'after' => '</div>' ) ); ?>
					</div><!-- .entry-description -->

				</div><!-- end .post-entry -->
			</div><!-- .post-content -->
		</article><!-- #post-<?php the_ID(); ?> -->

		<nav id="nav-below" class="clear-fix">
			<span class="nav-previous"><?php previous_image_link( false, __( '&larr; Previous' , 'chateau' ) ); ?></span>
			<span class="nav-next"><?php next_image_link( false, __( 'Next &rarr;' , 'chateau' ) ); ?></span>
		</nav><!-- #nav-below -->

		<?php comments_template(); ?>

		<?php endwhile; // end of the loop. ?>

	</div><!-- end #content -->
</div><!-- end #primary -->
<?php get_footer(); ?>