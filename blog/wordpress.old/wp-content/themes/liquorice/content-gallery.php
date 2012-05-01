<?php
/**
 * The portion of the loop that shows the "gallery" post format.
 *
 * @package WordPress
 * @subpackage Liquorice
 */
?>
<div <?php post_class( 'post-wrapper' ); ?>>

	<?php if ( ! is_single() && get_the_title() != '' ) : ?>
		<h2 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'liquorice' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2>
	<?php else: ?>
		<h1 class="single-title"><?php the_title(); ?></h1>
	<?php endif; ?>

	<div class="date">
		<small><?php liquorice_posted_on(); ?></small>
	</div><!-- .date -->

	<div class="entry">
		<?php if ( is_single() ) : ?>
			<?php the_content(); ?>
		<?php else : ?>
			<?php
				$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
				if ( $images ) :
					$total_images = count( $images );
					$image = array_shift( $images );
					$image_img_tag = wp_get_attachment_image( $image->ID, 'thumbnail' );
				?>
				<div class="gallery-wrap">
					<div class="gallery-thumb">
						<a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a>
					</div><!-- .gallery-thumb -->
					<p><strong><em><?php printf( _n( 'This gallery contains <a %1$s>%2$s photo</a>.', 'This gallery contains <a %1$s>%2$s photos</a>.', $total_images, 'liquorice' ),
										'href="' . get_permalink() . '" title="' . sprintf( esc_attr__( 'Permalink to %s', 'liquorice' ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark"',
										number_format_i18n( $total_images ) ); ?>
					</em></strong></p>
				<?php endif; ?>
				<?php the_excerpt(); ?>
				</div><!-- .gallery-wrap-->
		<?php endif; ?>
		<?php edit_post_link( __( '(Edit)', 'liquorice' ), '<span class="edit-link">', '</span>' ); ?>
	</div><!-- .entry -->

	<?php if ( is_single() ) : ?>
		<div class="post-meta">
			<p class="comments-num"><?php comments_popup_link( __( 'Leave a comment', 'liquorice' ), __( '1 Comment', 'liquorice' ), __( '% Comments', 'liquorice' ) ); ?></p>
			<?php liquorice_posted_in(); ?>
		</div><!-- .meta -->
	<?php endif; ?>

</div><!-- .post-wrapper -->