<?php
/**
 * @package Spectrum
 */
?>
<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<div class="main-title">
		<h3><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
		<div class="post-date">
			<?php spectrum_date(); ?>
		</div>
	</div>
	<?php if ( is_multi_author() ) { ?>
		<div class="post-meta post-author-and-comments">
			<p class="post-author"><?php printf( __( 'Written by <strong>%1$s</strong>', 'spectrum' ), get_the_author() ); ?></p>
			<p class="comment-number"><?php comments_popup_link( __( 'Leave a comment', 'spectrum' ), __( '<strong>1</strong> Comment', 'spectrum' ), __( '<strong>%</strong> Comments', 'spectrum' ) ); ?></p>
		</div>
	<?php } ?>
	<div class="entry">
	<?php if ( post_password_required() ) : ?>
		<?php the_content(); ?>
	<?php else : ?>
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
				<p><em><?php printf( _n( 'This gallery contains <a %1$s>%2$s photo</a>.', 'This gallery contains <a %1$s>%2$s photos</a>.', $total_images, 'toolbox' ),
						'href="' . get_permalink() . '" title="' . sprintf( esc_attr__( 'Permalink to %s', 'spectrum' ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark"',
						number_format_i18n( $total_images )
					); ?></em></p>
		<?php endif; ?>
			<?php the_excerpt(); ?>
	<?php endif; ?>
	</div>
	<div class="post-meta post-category">
		<p class="post-category-title"><strong><?php _e( 'Category:', 'spectrum' ); ?></strong></p>
		<p class="post-category-elements"><?php the_category( ', ' ); ?></p>
		<?php if ( ! is_multi_author() ) { ?>
			<p class="comment-number"><?php comments_popup_link( __( 'Leave a comment', 'spectrum' ), __( '<strong>1</strong> Comment', 'spectrum' ), __( '<strong>%</strong> Comments', 'spectrum' ) ); ?></p>
		<?php } ?>
	</div>
	<?php the_tags( '<div class="post-meta post-tags"><p><strong>' . __('Tagged with:', 'spectrum') . '</strong></p><ul><li>','</li><li>','</li></ul></div>' ); ?>
</div>