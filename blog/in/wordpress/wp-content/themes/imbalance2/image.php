<?php
/**
 * @package Imbalance 2
 */
?>
<?php
$metadata = wp_get_attachment_metadata();
$caption = get_post_field( 'post_excerpt', get_the_id() );
$parent_ID = absint( get_post_field( 'post_parent', get_the_ID() ) );
?>
<?php get_header(); ?>

<div id="container" class="single-attachment">
	<div id="content" role="main">

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>

		<?php if ( ! empty( $parent_ID ) ) : ?>
			<p class="page-title"><a href="<?php echo esc_url( get_permalink( $parent_ID ) ); ?>" title="<?php echo esc_attr( sprintf( __( 'Return to %s', 'imbalance2' ), strip_tags( get_the_title( $parent_ID ) ) ) ); ?>"><?php printf( __( '<span class="meta-nav">&larr;</span> %s', 'imbalance2' ), get_the_title( $post->post_parent ) ); ?></a></p>
		<?php endif; ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="post_title clear-fix">
				<h1 class="entry-title"><?php the_title(); ?></h1>

				<div id="nav-above" class="navigation">
					<div class="nav-previous">
						<?php previous_image_link( false, __( '&laquo; Previous Image' , 'imbalance2' ) ); ?>
					</div>
					<span class="main-separator">/</span>
					<div class="nav-next">
						<?php next_image_link( false, __( 'Next Image &raquo;' , 'imbalance2' ) ); ?>
					</div>
				</div><!-- #nav-above -->

				<div class="entry-meta">
					<?php imbalance2_posted_by(); ?>

					<?php imbalance2_posted_on(); ?>

					<?php if ( $metadata ) : ?>
						<?php
							printf( '<a href="%1$s" title="%2$s">' . __( '%3$d &times; %4$d pixels', 'imbalance2' ) . '</a>',
								esc_url( wp_get_attachment_url() ),
								esc_attr__( 'Link to full-size image', 'imbalance2' ),
								absint( $metadata['width'] ),
								absint( $metadata['height'] )
						); ?>
					<?php endif; ?>

					<?php if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
						<span class="main-separator"> / </span><?php echo comments_popup_link( __( 'Leave a comment', 'imbalance2' ), __( 'One Comment', 'imbalance2' ), __( '% Comments', 'imbalance2' ) ); ?>
					<?php endif; ?>

					<?php edit_post_link( __( 'Edit', 'imbalance2' ), '<span class="main-separator"> / </span><span class="edit-link">', '</span>' ); ?>
				</div><!-- .entry-meta -->
			</div><!-- .post_title -->

			<div class="entry-content">
				<div class="entry-attachment">
				<?php
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
					<p><a href="<?php echo $next_attachment_url; ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php
					$attachment_size = apply_filters( 'imbalance2_attachment_size', 710 );
					echo wp_get_attachment_image( $post->ID, array( $attachment_size, $attachment_size ) ); // filterable image width with, essentially, no limit for image height.
					?></a></p>
				</div><!-- .entry-attachment -->

				<?php if ( ! empty( $caption ) ) : ?>
					<div class="entry-caption">
						<?php the_excerpt(); ?>
					</div>
				<?php endif; ?>

				<?php the_content(); ?>

				<div class="entry-utility">
					<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'imbalance2' ), 'after' => '</div>' ) ); ?>
				</div><!-- .entry-utility -->

			</div><!-- .entry-content -->

		</div><!-- #post-<?php the_ID(); ?> -->

		<?php comments_template(); ?>

		<?php endwhile; ?>

	<?php else : ?>

		<div id="post-0" class="post no-results not-found">
			<h1 class="entry-title"><?php _e( 'Nothing Found', 'imbalance2' ); ?></h1>
			<div class="entry-content">
				<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'imbalance2' ); ?></p>
				<div id="page_search">
				<?php get_search_form(); ?>
				</div>
			</div><!-- .entry-content -->
		</div><!-- #post-0 -->

	<?php endif; ?>

	</div><!-- #content -->
</div><!-- #container -->

<?php get_footer(); ?>