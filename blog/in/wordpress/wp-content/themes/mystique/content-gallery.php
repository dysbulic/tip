<?php
/**
 * The portion of the loop that shows the "gallery" post format.
 *
 * @package WordPress
 * @subpackage Mystique
 */
?>
<div <?php post_class( 'post-wrapper clear-block' ); ?>>

	<?php if ( ! is_single() && get_the_title() != '' ) : ?>
		<h2 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'mystique' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2>
	<?php else: ?>
		<h1 class="single-title"><?php the_title(); ?></h1>
	<?php endif; ?>

	<div class="post-date">
		<p class="day"><?php the_time( __( 'M j', 'mystique' ) ); ?></p>
	</div><!-- .post-date -->

	<div class="post-info clear-block">
		<p class="author alignleft"><?php _e( 'Posted by', 'mystique' ); ?> <?php the_author_posts_link(); ?> <?php edit_post_link( __( 'Edit', 'mystique' ), '<span class="edit-link"> &#124; ', '</span>' ); ?></p>
	</div><!-- .post-info clear-block" -->

	<div class="entry">
		<?php if ( is_single() ) : ?>
			<?php the_content(); ?>
		<?php else : ?>
			<div class="gallery-wrap">
			<?php
				$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
				if ( $images ) :
					$total_images = count( $images );
					$image = array_shift( $images );
					$image_img_tag = wp_get_attachment_image( $image->ID, 'thumbnail' );
			?>
					<div class="gallery-thumb">
						<a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a>
					</div><!-- .gallery-thumb -->
					<p><strong><em><?php printf( _n( 'This gallery contains <a %1$s>%2$s photo</a>.', 'This gallery contains <a %1$s>%2$s photos</a>.', $total_images, 'mystique' ),
										'href="' . get_permalink() . '" title="' . sprintf( esc_attr__( 'Permalink to %s', 'mystique' ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark"',
										number_format_i18n( $total_images ) ); ?>
					</em></strong></p>
				<?php endif; ?>
				<?php the_excerpt(); ?>
			</div><!-- .gallery-wrap-->
		<?php endif; ?>

	</div><!-- .entry -->

	<?php if ( ! is_single() ) : ?>
		<div class="post-meta">
			<p class="post-catgories"><?php printf( __( 'Posted in %s.', 'mystique' ), get_the_category_list( ', ' ) ); ?></p>
			<?php the_tags( '<p class="post-tags">' . __( 'Tags:', 'mystique' ) . ' ', ', ', '</p>' ); ?>
		</div><!-- .post-meta -->
	<?php endif; ?>
</div><!-- .post-wrapper -->