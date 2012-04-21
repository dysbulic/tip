<?php
/**
 * The template for displaying an attachment image.
 *
 * @package WordPress
 * @subpackage Matala
 */
get_header(); ?>

	<div id="primary" class="image-attachment">
		<div id="attachment-wrapper">
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<header class="attachment-header">
							<h1 class="attachment-title"><?php the_title(); ?></h1>

							<div class="attachment-meta">
								<?php
									$metadata = wp_get_attachment_metadata();
									printf( __( '<span class="meta-prep meta-prep-entry-date">Published </span> <span class="entry-date"><abbr class="published" title="%1$s">%2$s</abbr></span> at <a href="%3$s" title="Link to full-size image">%4$s &times; %5$s</a> in <a href="%6$s" title="Return to %7$s" rel="gallery">%7$s</a>', 'matala' ),
										esc_attr( get_the_time() ),
										get_the_date(),
										esc_url( wp_get_attachment_url() ),
										$metadata['width'],
										$metadata['height'],
										esc_url( get_permalink( $post->post_parent ) ),
										get_the_title( $post->post_parent )
									);
								?>
								<?php edit_post_link( __( 'Edit', 'matala' ), '<span class="edit-link">', '</span>' ); ?>
							</div><!-- .attachment-meta-->

						</header><!-- .attachment-header -->

						<div class="entry-content">

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
			$prev_attachment_url = wp_get_attachment_url();
		}
	?>
									<a href="<?php echo esc_url( $next_attachment_url ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php
									$attachment_size = apply_filters( 'matala_attachment_size', 848 );
									echo wp_get_attachment_image( $post->ID, array( $attachment_size, 1024 ) ); // filterable image width with 1024px limit for image height.
									?></a>

								</div><!-- .attachment -->

								<?php if ( ! empty( $post->post_excerpt ) ) : ?>
								<div class="entry-caption">
									<?php the_excerpt(); ?>
								</div>
								<?php endif; ?>

							</div><!-- .entry-attachment -->

							<div class="entry-description">
								<?php the_content(); ?>
								<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'matala' ) . '</span>', 'after' => '</div>' ) ); ?>
							</div><!-- .entry-description -->

						</div><!-- .entry-content -->

					</article><!-- #post-<?php the_ID(); ?> -->

					<nav id="nav-single">
						<h3 class="assistive-text"><?php _e( 'Image navigation', 'matala' ); ?></h3>
						<div class="thumbnail-nav">
							<div class="prev-thumb"><?php previous_image_link(); ?></div>
							<div class="next-thumb"><?php next_image_link(); ?></div>
						</div><!-- .thumbnail-nav -->
						<span class="nav-previous"><?php previous_image_link( false, __( 'Previous Image' , 'matala' ) ); ?></span>
						<span class="nav-next"><?php next_image_link( false, __( 'Next Image' , 'matala' ) ); ?></span>
					</nav><!-- #nav-single -->

					<?php comments_template(); ?>

					<?php endwhile; // end of the loop. ?>

					<?php $options = get_option( 'matala_theme_options' ); ?>
					<?php if ( $options['show_random_photos'] != '' ) : ?>
					<div id="random-gallery">
						<h3 class="random-gallery-title"><?php echo $options['random_photos_header']; ?></h3>
						<?php
							$posts = get_posts( array( 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'rand', 'numberposts' => 3 ) );

						foreach( $posts as $post ) : ?>

							<dl class="gallery-item">
								<dt class="gallery-icon">
									<div class="random-photo">
										<?php echo wp_get_attachment_link( $post->ID, array( 200, 200), true ); ?>
									</div><!-- .random-photo -->
								</dt><!-- .gallery-icon -->
							</dl><!-- .gallery-item -->
						<?php endforeach; ?>
					</div><!-- .random-gallery -->
					<?php endif; ?>

			 </div><!-- #content -->
		</div><!-- #attachment-wrapper -->
		<div id="primary-bottom"></div>
	</div><!-- #primary -->

<?php get_footer(); ?>