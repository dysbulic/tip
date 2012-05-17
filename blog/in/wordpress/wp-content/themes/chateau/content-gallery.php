<?php
/**
 * The template for displaying content in the single.php template
 *
 * @package WordPress
 * @subpackage Chateau
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="post-title">
		<h1><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'chateau' ), the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a></h1>
		<?php chateau_post_info(); ?>
	</header><!-- end .post-title -->

	<div class="post-content clear-fix">
		<?php chateau_post_extra(); ?>

		<div class="post-entry clear-fix">
			<?php if ( post_password_required() ) : ?>
				<?php the_content( __( 'Continue reading &raquo;', 'chateau' ) ); ?>
			<?php else : ?>
				<?php
					$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
					if ( $images ) :
						$total_images = count( $images );
						$image = array_shift( $images );
						$image_img_tag = wp_get_attachment_image( $image->ID, 'thumbnail' );
				?>
				<div class="gallery-thumb">
					<a href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a>
				</div><!-- .gallery-thumb -->

				<p><em><?php printf( _n( 'This gallery contains <a %1$s>%2$s photo</a>.', 'This gallery contains <a %1$s>%2$s photos</a>.', $total_images, 'chateau' ),
						'href="' . esc_url( get_permalink() ) . '" title="' . sprintf( esc_attr__( 'Permalink to %s', 'chateau' ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark"',
						number_format_i18n( $total_images )
					); ?></em></p>
				<?php endif; ?>
			<?php endif; ?>
			<?php the_excerpt(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'chateau' ) . '</span>', 'after' => '</div>' ) ); ?>
		</div><!-- end .post-entry -->
	</div><!-- end .post-content -->
</article><!-- #post-<?php the_ID(); ?> -->