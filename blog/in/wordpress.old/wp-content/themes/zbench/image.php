<?php
/**
 * @package WordPress
 * @subpackage zBench
 *
 * Image template
 */
?>
<?php get_header(); ?>

	<div id="maincontent">

	<?php
	/* Main loop - displays posts */
	if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<h2 class="title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'zbench' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

			<div class="post-info">
				<div>
					<span class="comments-meta"><a href="<?php the_permalink(); ?>#respond"><?php comments_number(__( 'Leave a Comment', 'zbench' ), __( '1 Comment', 'zbench' ), __( '% Comments', 'zbench' )); ?></a></span>
					<?php
						$metadata = wp_get_attachment_metadata();
						printf( __( '<span class="meta-prep meta-prep-entry-date">Published </span> <span class="entry-date"><abbr class="published" title="%1$s">%2$s</abbr></span>  at <a href="%3$s" title="Link to full-size image">%4$s &times; %5$s</a> in <a href="%6$s" title="Return to %7$s" rel="gallery">%7$s</a>', 'zbench' ),
							esc_attr( get_the_time() ),
							get_the_date(),
							wp_get_attachment_url(),
							$metadata['width'],
							$metadata['height'],
							get_permalink( $post->post_parent ),
							get_the_title( $post->post_parent )
						);
					?>
					<?php edit_post_link( __( 'Edit', 'zbench' ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>
				</div>
			</div>

			<div class="content">
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

					<a href="<?php echo $next_attachment_url; ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment">
						<?php echo wp_get_attachment_image( $post->ID, array( 950, 950 ) ); ?>
					</a>
				</div>

				<?php if ( ! empty( $post->post_excerpt ) ) : ?>
				<div class="entry-caption">
					<?php the_excerpt(); ?>
				</div>
				<?php endif; ?>

				<?php the_content(); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'themename' ), 'after' => '</div>' ) ); ?>

			</div>

			<div class="sep"></div>
		</div>
		
		<div class="next-prev-links">
			<span class="nav-previous"><?php previous_image_link( '%link', '&larr; Previous' ); ?></span>
			<span class="nav-next"><?php next_image_link( '%link', 'Next &rarr;' ); ?></span>
		</div>

	<?php endwhile; ?>
	<?php comments_template(); // Load comments


	/* If no posts, then serve error message */
	else: ?>

		<div class="post">
			<h2><?php _e( 'Not Found', 'zbench' ); ?></h2>
			<p><?php _e( 'Sorry, but you are looking for something that isn\'t here.', 'zbench' ); ?></p>
		</div>
	<?php endif; ?>

	</div>

<?php get_footer(); ?>