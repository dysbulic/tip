<?php
/**
 * @package Vertigo
 */

get_header(); ?>

<div id="content" role="main">

<?php while ( have_posts() ) : the_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="container">

			<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>

				<nav id="image-navigation" class="clear-fix">
					<span class="previous-image"><?php previous_image_link( false, __( '&larr; Previous', 'vertigo' ) ); ?></span>
					<span class="next-image"><?php next_image_link( false, __( 'Next &rarr;', 'vertigo' ) ); ?></span>
				</nav><!-- #image-navigation -->
			</header><!-- .entry-header -->

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
									$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID ); // get the URL of the next image attachment
								else
									$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID ); // or get the URL of the first image attachment
							} else {
								// or, if there's only 1 image, get the URL of the image
								$next_attachment_url = wp_get_attachment_url();
							}
						?>
						<a href="<?php echo $next_attachment_url; ?>" title="<?php the_title_attribute(); ?>" rel="attachment"><?php echo wp_get_attachment_image( $post->ID, array( 800, 800 ) ); ?></a>
					</div><!-- .attachment -->

					<?php if ( ! empty( $post->post_excerpt ) ) : ?>
					<div class="entry-content">
						<?php the_excerpt(); ?>
					</div>
					<?php endif; ?>
				</div><!-- .entry-attachment -->

				<?php the_content(); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'vertigo' ), 'after' => '</div>' ) ); ?>

			</div><!-- .entry-content -->

			<div class="entry-utility">
				<?php if ( comments_open() && pings_open() ) : // Comments and trackbacks open ?>
					<?php printf( __( '<a class="comment-link" href="#respond" title="Post a comment">Post a comment</a> or leave a trackback: <a class="trackback-link" href="%s" title="Trackback URL for your post" rel="trackback">Trackback URL</a>.', 'vertigo' ), get_trackback_url() ); ?>
				<?php elseif ( ! comments_open() && pings_open() ) : // Only trackbacks open ?>
					<?php printf( __( 'Comments are closed, but you can leave a trackback: <a class="trackback-link" href="%s" title="Trackback URL for your post" rel="trackback">Trackback URL</a>.', 'vertigo' ), get_trackback_url() ); ?>
				<?php elseif ( comments_open() && ! pings_open() ) : // Only comments open ?>
					<?php _e( 'Trackbacks are closed, but you can <a class="comment-link" href="#respond" title="Post a comment">post a comment</a>.', 'vertigo' ); ?>
				<?php elseif ( ! comments_open() && ! pings_open() ) : // Comments and trackbacks closed ?>
					<?php _e( 'Both comments and trackbacks are currently closed.', 'vertigo' ); ?>
				<?php endif; ?>
				<?php edit_post_link( __( 'edit', 'vertigo' ), '<br /><span class="edit-link">', '</span>' ); ?>
			</div><!-- .entry-utility -->

			<footer class="entry-info">
				<p class="permalink"><a href="<?php echo get_permalink( $post->post_parent ); ?>">*</a></p>
				<div class="data">
					<?php
						$metadata = wp_get_attachment_metadata();
						printf( __( 'posted %1$s ago<br /> at <a href="%2$s" title="Link to full-size image">%3$s &times; %4$s</a> in <a href="%5$s" title="Return to %6$s" rel="gallery">%6$s</a>', 'vertigo' ),
							human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ),
							wp_get_attachment_url(),
							$metadata['width'],
							$metadata['height'],
							get_permalink( $post->post_parent ),
							get_the_title( $post->post_parent )
						);
					?>
				</div>
			</footer><!-- .entry-info -->

		</div><!-- .container -->
	</article><!-- #post-## -->

	<?php comments_template(); ?>

<?php endwhile; // end of the loop. ?>

</div><!-- #content -->

<?php get_footer(); ?>