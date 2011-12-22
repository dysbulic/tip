<?php
/**
 * @package WordPress
 * @subpackage Retro MacOS
 */
get_header(); ?>

	<div id="content" class="widecolumn">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" class="attachment">
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'retro' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

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
				<a href="<?php echo $next_attachment_url; ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php
						$attachment_size = apply_filters( 'theme_attachment_size', 678 );
						echo wp_get_attachment_image( $post->ID, array( $attachment_size, 700 ) ); // filterable image width with, essentially, no limit for image height.
					?></a>

			<?php if ( ! empty( $post->post_excerpt ) ) : ?>
			<div class="entry-caption">
				<?php the_excerpt(); ?>
			</div>
			<?php endif; ?>

			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number' ) ); ?>

			<div class="entry-meta">
				<?php
					$metadata = wp_get_attachment_metadata();
						printf( __( '<span class="meta-prep meta-prep-entry-date">Published </span> <span class="image-date"><abbr class="published" title="%1$s">%2$s</abbr></span> at <a href="%3$s" title="Link to full-size image">%4$s &times; %5$s</a> in <a href="%6$s" title="Return to %7$s" rel="gallery">%7$s</a>', 'retro' ),
							esc_attr( get_the_time() ),
							get_the_date(),
							wp_get_attachment_url(),
							$metadata['width'],
							$metadata['height'],
							get_permalink( $post->post_parent ),
							get_the_title( $post->post_parent )
						);
				?>
				<?php edit_post_link( __( 'Edit', 'retro' ), '<span class="sep">|</span> <span class="edit-link">', '</span>' ); ?>
			</div><!-- .entry-meta -->

			</div>
		</div>

		<nav id="image-navigation">
			<span class="previous-image"><?php previous_image_link( false, __( '&larr; Previous' , 'retro' ) ); ?></span>
			<span class="next-image"><?php next_image_link( false, __( 'Next &rarr;' , 'retro' ) ); ?></span>
		</nav><!-- #image-navigation -->

		<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p><?php _e( 'Sorry, no attachments matched your criteria.', 'retro' ); ?></p>

	<?php endif; ?>

	</div>

<?php get_footer(); ?>