<?php
/**
* @package WordPress
* @subpackage AutoFocus
*/
get_header(); ?>

<div id="content">

	<?php while ( have_posts() ) : the_post(); ?>

		<div id="nav-above">
			<h1 class="assistive-text">
				<?php _e( 'Image navigation', 'autofocus' ); ?>
			</h1><!-- .assistive-text-->
			<div class="nav-previous">
				<?php previous_image_link( false, __( '&laquo;' , 'autofocus' ) ); ?>
			</div><!-- .nav-previous -->
			<div class="nav-next">
				<?php next_image_link( false, __( '&raquo;' , 'autofocus' ) ); ?>
			</div><!-- .nav-next -->
		</div><!-- #nav-above -->
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div id="post-thumbnail">
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
				<a href="<?php echo esc_url( $next_attachment_url ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment">
					<?php echo wp_get_attachment_image( $post->ID, 'autofocus-800x1200' ); ?>
				</a>
				<?php if ( ! empty( $post->post_excerpt ) ) : ?>
				<div id="attachment-caption">
					<?php the_excerpt(); // Image Caption ?>
				</div>
				<?php endif; ?>
			</div><!-- #post-thumbnail -->
			<h2 class="entry-title">
				<?php the_title(); ?>
			</h2><!-- .entry-title -->
			<div id="entry-content">
				<?php the_content(); // Image Description ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'autofocus' ), 'after' => '</div>' ) ); ?>
				<?php edit_post_link( __( 'Edit', 'autofocus' ), '<span class="edit-link">', '</span>' ); ?>
			</div><!-- #entry-content -->
			<div id="entry-meta">
				<span class="entry-date">
					<?php the_time( 'd M' ); ?>
				</span><!-- .entry-date -->
				<?php
					$metadata = wp_get_attachment_metadata();
					printf( __( '<span class="meta-prep meta-prep-entry-date">Published </span> at <a href="%1$s" title="Link to full-size image">%2$s &times; %3$s</a> in <a href="%4$s" title="Return to %5$s" rel="gallery">%5$s</a>.', 'autofocus' ),
						esc_url( wp_get_attachment_url() ),
						$metadata['width'],
						$metadata['height'],
						esc_url( get_permalink( $post->post_parent ) ),
						get_the_title( $post->post_parent )
					);
				?>
				<span class="bookmark-permalink">
					<?php printf( __( 'Bookmark the <a href="%1$s" title="Permalink to %2$s" rel="bookmark">permalink</a>.', 'autofocus' ), esc_url( get_permalink() ), esc_attr__( the_title_attribute( 'echo=0' ) ) ); ?>
				</span>
				<span class="comments-rss-link">
					<?php printf( __( 'Follow any comments here with the <a href="%1$s" title="RSS feed for %2$s">RSS feed for this post</a>.', 'autofocus' ), get_post_comments_feed_link(), esc_attr__( the_title_attribute( 'echo=0' ) ) ); ?>
				</span><!-- .comments-rss-link -->
			</div><!-- #entry-meta -->
		</div><!-- #post-<?php the_ID(); ?> -->

		<?php comments_template(); ?>

	<?php endwhile; ?>

</div><!-- #content -->

<?php get_footer(); ?>