<?php
/**
 * The Template for displaying image attachments.
 *
 * @package WordPress
 * @subpackage Blogum
 */
get_header(); ?>

	<div id="content" class="full-width image-attachment clear" role="main">

		<?php  while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="post-meta">
				<h1>
					<?php
						printf( __( '<a href="%1$s" title="Return to %2$s" rel="gallery">%2$s</a>', 'blogum' ),
							get_permalink( $post->post_parent ),
							get_the_title( $post->post_parent )
						 );
					?>
				</h1>

				<div class="post-data">
					<?php
						$metadata = wp_get_attachment_metadata();
						printf( __( '<p>Original size at <a href="%1$s" title="Link to original size image">%2$s &times; %3$s</a></p>', 'blogum' ),
							esc_url( wp_get_attachment_url() ),
							$metadata['width'],
							$metadata['height']
						);
					?>
				</div><!-- .post-data -->

				<?php blogum_comments_popup_link(); ?>

				<?php edit_post_link( __( 'Edit', 'blogum' ), '<div class="post-edit">', '</div>' ); ?>
			</header><!-- .post-meta -->

			<div class="post-content clear">

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
					$attachment_size = apply_filters( 'blogum_attachment_size', 785 );
					echo wp_get_attachment_image( $post->ID, array( $attachment_size, 785 ) ); // filterable image width with 785px limit for image height.
					?></a>

					<?php if ( ! empty( $post->post_excerpt ) ) : ?>
						<div class="entry-caption">
							<?php the_excerpt(); ?>
						</div>
					<?php endif; ?>
				</div><!-- .attachment -->

				<div class="entry-description">
					<?php the_content(); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'blogum' ) . '</span>', 'after' => '</div>' ) ); ?>
				</div><!-- .entry-description -->

			</div><!-- .post-content -->
		</article><!-- #post-<?php the_ID(); ?> -->

		<nav id="nav-below" class="clear">
			<span class="nav-previous"><?php previous_image_link( false, __( '&larr; Previous' , 'blogum' ) ); ?></span>
			<span class="nav-next"><?php next_image_link( false, __( 'Next &rarr;' , 'blogum' ) ); ?></span>
		</nav><!-- #nav-below -->

		<?php comments_template(); ?>

		<?php endwhile; // end of the loop. ?>

	</div><!-- end #content -->

<?php get_footer(); ?>