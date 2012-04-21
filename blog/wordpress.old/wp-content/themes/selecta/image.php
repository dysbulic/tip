<?php
/**
 * The single image template file.
 * @package WordPress
 * @subpackage Selecta
 */
get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

<div id="single-header">
	<div class="single-title-wrap">
		<h1 class="single-title"><?php the_title(); ?></h1>
		<div class="attachment-meta">
			<?php
				$metadata = wp_get_attachment_metadata();
				printf( __( '<span class="meta-prep meta-prep-entry-date">Published </span> <span class="entry-date"><abbr class="published" title="%1$s">%2$s</abbr></span> at <a href="%3$s" title="Link to full-size image">%4$s &times; %5$s</a> in <a href="%6$s" title="Return to %7$s" rel="gallery">%7$s</a>', 'selecta' ),
					esc_attr( get_the_time() ),
					get_the_date(),
					esc_url( wp_get_attachment_url() ),
					$metadata['width'],
					$metadata['height'],
					esc_url( get_permalink( $post->post_parent ) ),
					get_the_title( $post->post_parent )
				);
			?>
		</div><!-- .attachment-meta-->
	</div><!-- .single-title-wrap" -->
</div><!-- #single-header -->
<?php endwhile; // end of the loop. ?>

<?php rewind_posts(); ?>

<div id="main" class="clearfix">
	<div id="content" class="full-width image-attachment" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<div id="post-<?php the_ID(); ?>" <?php post_class( 'post-wrapper' ); ?>>

				<div class="entry-wrapper clearfix">

					<div class="entry">

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
							$attachment_size = apply_filters( 'selecta_attachment_size', 785 );
							echo wp_get_attachment_image( $post->ID, array( $attachment_size, 785 ) ); // filterable image width with 785px limit for image height.
							?></a>

							<?php if ( ! empty( $post->post_excerpt ) ) : ?>
								<div class="entry-caption">
									<?php the_excerpt(); ?>
								</div>
							<?php endif; ?>
						</div><!-- .attachment -->
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'selecta' ) . '</span>', 'after' => '</div>' ) ); ?>

					</div><!-- .entry -->

					<div id="nav-image" class="clearfix">
						<h3 class="assistive-text"><?php _e( 'Image navigation', 'selecta' ); ?></h3>
						<span class="nav-previous"><?php previous_image_link(); ?></span>
						<span class="nav-next"><?php next_image_link(); ?></span>
					</div><!-- #nav-single -->

				<div class="post-info clearfix">
					<p class="post-meta"><?php edit_post_link( __( 'Edit this Entry', 'selecta' ), '<span class="edit-link">', '</span>' ); ?></p>
					<p class="comment-link"><?php comments_popup_link( __( 'Leave a Comment', 'selecta' ), __( '1 Comment', 'selecta' ), __( '% Comments', 'selecta' ) ); ?></p>
				</div><!-- .post-info -->
			</div><!-- .entry-wrapper -->

		</div><!-- #post .post-wrapper -->
		<?php comments_template( '', true ); ?>

		<?php endwhile; // end of the loop. ?>

	</div><!-- #content -->
</div><!-- #main -->

<?php get_footer(); ?>