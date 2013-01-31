<?php
/**
 * The portion of the loop that shows the "gallery" post format.
 *
 * @package WordPress
 * @subpackage Matala
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-wrapper' ); ?>>

	<?php matala_post_date(); ?>

	<header class="entry-header">
		<?php if ( ! is_single() && get_the_title() != '' ) : ?>
			<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'matala' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
		<?php else: ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php endif; ?>

		<div class="entry-info">
			<?php matala_posted_on(); ?>
		</div><!-- .entry-info -->

	</header><!-- .entry-header -->
		<div class="post-format-icon"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'matala' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></div>

	<div class="entry-content">
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
					<p><strong><em><?php printf( _n( 'This gallery contains <a %1$s>%2$s photo</a>.', 'This gallery contains <a %1$s>%2$s photos</a>.', $total_images, 'matala' ),
										'href="' . get_permalink() . '" title="' . sprintf( esc_attr__( 'Permalink to %s', 'matala' ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark"',
										number_format_i18n( $total_images ) ); ?>
					</em></strong></p>
				<?php endif; ?>
				<?php the_excerpt(); ?>
				</div><!-- .gallery-wrap-->
		<?php endif; ?>
		</div><!-- .entry-content -->

	<footer class="entry-footer">

		<div class="entry-meta">
			<?php matala_posted_in(); ?>
			<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'matala' ), __( '1 Comment', 'matala' ), __( '% Comments', 'matala' ) ); ?></span>
			<?php edit_post_link( __( 'Edit', 'matala' ), '<span class="sep">|</span> <span class="edit-link">', '</span>' ); ?>
		</div><!-- .entry-meta -->

	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> .post-wrapper -->