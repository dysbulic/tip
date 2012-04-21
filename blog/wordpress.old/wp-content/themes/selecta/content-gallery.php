<?php
/**
 * The portion of the loop that shows the "gallery" post format.
 *
 * @package WordPress
 * @subpackage Selecta
 */
?>
<div id="post-<?php the_ID(); ?>" <?php post_class( 'post-wrapper clearfix' ); ?>>

	<div class="entry-header">
		<?php selecta_entry_date(); ?>

		<?php if ( ! is_single() && get_the_title() != '' ) : ?>
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'selecta' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2>
		<?php else: ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php endif; ?>
	</div><!-- .entry-header -->

	<div class="entry-wrapper clearfix">

		<div class="entry">
			<?php if ( post_password_required() || is_singular() ) : ?>
				<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'selecta' ) ); ?>

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

				<p class="gallery-info"><em><?php printf( _n( 'This gallery contains <a %1$s>%2$s photo</a>.', 'This gallery contains <a %1$s>%2$s photos</a>.', $total_images, 'selecta' ),
						'href="' . get_permalink() . '" title="' . sprintf( esc_attr__( 'Permalink to %s', 'selecta' ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark"',
						number_format_i18n( $total_images )
					); ?></em></p>
				<?php endif; ?>
				<?php the_excerpt(); ?>
			<?php endif; ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'selecta' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry -->

		<div class="post-info clearfix">
			<p class="post-meta">
				<?php selecta_post_meta(); ?>
				<?php edit_post_link( __( 'Edit this Entry', 'selecta' ), '<span class="edit-link">', '</span>' ); ?>
			</p>
			<p class="comment-link"><?php comments_popup_link( __( 'Leave a Comment', 'selecta' ), __( '1 Comment', 'selecta' ), __( '% Comments', 'selecta' ) ); ?></p>
		</div><!-- .post-info -->

	</div><!-- .entry-wrapper -->
</div><!-- .post-wrapper -->