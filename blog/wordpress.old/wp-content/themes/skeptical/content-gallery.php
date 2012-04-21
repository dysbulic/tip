<?php
/**
 * @package WordPress
 * @subpackage Skeptical
 */
?>
<?php
	// Access global variable directly to set content_width
	if ( isset( $GLOBALS['content_width'] ) )
		$GLOBALS['content_width'] = 479;
?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-meta col-left">
		<?php skeptical_post_meta(); ?>
	</div><!-- /.meta -->

	<div class="middle col-left clearfix">
		<h1 class="title">
		<?php if ( ! is_single() ) : ?>
			<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'woothemes' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
		<?php else : ?>
			<?php the_title(); ?>
		<?php endif; ?>
		</h1>

		<?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'skeptical-featured-image', array( 'class' => 'thumbnail main-image' ) ); endif; ?>

		<div class="entry clearfix">
			<?php if ( post_password_required() || is_singular() ) : ?>
				<?php the_content( __( 'Read More...', 'woothemes' ) ); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'woothemes' ) . '</span>', 'after' => '</div>' ) ); ?>
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

				<p class="gallery-info"><em><?php printf( _n( 'This gallery contains <a %1$s>%2$s photo</a>.', 'This gallery contains <a %1$s>%2$s photos</a>.', $total_images, 'woothemes' ),
						'href="' . get_permalink() . '" title="' . sprintf( esc_attr__( 'Permalink to %s', 'woothemes' ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark"',
						number_format_i18n( $total_images )
					); ?></em></p>
				<?php endif; ?>
				<?php the_excerpt(); ?>
			<?php endif; ?>
		</div>
		<?php if ( is_singular() ) the_tags( '<p class="tags">' . __( 'Tags: ', 'woothemes' ), ', ', '</p>' ); ?>
		<?php if ( get_the_author_meta( 'description' ) && is_singular() ) skeptical_author_info(); ?>
	</div><!-- /.middle -->
	<div class="fix"></div>
</div><!-- /.post -->