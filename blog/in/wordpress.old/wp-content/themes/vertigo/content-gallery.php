<?php
/**
 * @package Vertigo
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="container">

		<header class="entry-header hitchcock">
			<h1 class="entry-title">
			<?php if ( ! is_single() ) : ?>
				<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'vertigo' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
			<?php else : ?>
				<?php the_title(); ?>
			<?php endif; ?>
			</h1>
		</header><!-- .entry-header -->

		<?php if ( is_single() ) : ?>
			<div class="entry-content">
				<?php the_content( __( 'Read more', 'vertigo' ) ); ?>
			</div><!-- .entry-content -->
		<?php else : ?>
			<div class="entry-content clear-fix">
				<?php if ( post_password_required() ) : ?>
					<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'vertigo' ) ); ?>

					<?php else : ?>
						<?php
							$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
							if ( $images ) :
								$total_images = count( $images );
								$image = array_shift( $images );
								$image_img_tag = wp_get_attachment_image( $image->ID, 'thumbnail' );
						?>

						<figure class="gallery-thumb">
							<a href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a>
						</figure><!-- .gallery-thumb -->

						<p><em><?php printf( _n( 'This gallery contains <a %1$s>%2$s photo</a>.', 'This gallery contains <a %1$s>%2$s photos</a>.', $total_images, 'vertigo' ),
								'href="' . get_permalink() . '" title="' . sprintf( esc_attr__( 'Permalink to %s', 'vertigo' ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark"',
								number_format_i18n( $total_images )
							); ?></em></p>
					<?php endif; ?>
					<?php the_excerpt(); ?>
				<?php endif; ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'vertigo' ), 'after' => '</div>' ) ); ?>
			</div><!-- .entry-content -->

			<?php vertigo_entry_meta(); ?>
		<?php endif; ?>

		<?php vertigo_entry_info(); ?>

	</div><!-- .container -->
</article><!-- #post-## -->