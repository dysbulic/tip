<?php
/**
 * @package WordPress
 * @subpackage Blogum
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'clear' ); ?>>
	<header class="post-meta">
		<h1><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'blogum' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

		<?php blogum_post_data(); ?>

		<?php blogum_comments_popup_link(); ?>

		<?php edit_post_link( __( 'Edit', 'blogum' ), '<div class="post-edit">', '</div>' ); ?>
	</header><!-- .post-meta -->

	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="post-content">
			<?php the_excerpt(); ?>
		</div><!-- .post-content -->
	<?php else : ?>
		<div class="post-content">
			<?php if ( post_password_required() ) : ?>
				<?php the_content( __( 'Read More', 'blogum' ) ); ?>
			<?php else : ?>
				<?php
					$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
					if ( $images ) :
						$total_images = count( $images );
						$image = array_shift( $images );
						$image_img_tag = wp_get_attachment_image( $image->ID, 'large' );
				?>
				<div class="gallery-thumb">
					<a href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a>
				</div><!-- .gallery-thumb -->
	
				<p><span class="gallery-summary"><?php printf( _n( 'This gallery contains <a %1$s>%2$s photo</a>.', 'This gallery contains <a %1$s>%2$s photos</a>.', $total_images, 'blogum' ),
						'href="' . esc_url( get_permalink() ) . '" title="' . sprintf( esc_attr__( 'Permalink to %s', 'blogum' ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark"',
						number_format_i18n( $total_images )
					); ?></span></p>
				<?php endif; ?>
				<?php the_excerpt(); ?>
			<?php endif; ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'blogum' ) . '</span>', 'after' => '</div>' ) ); ?>
		</div><!-- .post-content -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->