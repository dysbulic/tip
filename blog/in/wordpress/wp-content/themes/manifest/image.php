<?php
/**
 * @package WordPress
 * @subpackage Manifest
 */

get_header(); ?>

<div id="core-content">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<div class="post single hentry">
		<div class="post-content">
			<h3 class="entry-title"><?php the_title(); ?></h3>
			<div class="entry-content">
				<div class="gallery-image">
				<?php
					// Respect user entered $content_width value via Custom CSS
					if ( function_exists( 'wpcom_content_width' ) )
						$content_width = wpcom_content_width();
					echo wp_get_attachment_image( $post->ID, array( $content_width, $content_width ) );
				?>
				</div>
				<?php if ( ! empty( $post->post_excerpt ) ) : ?>
				<div class="entry-caption">
					<?php the_excerpt(); ?>
				</div>
				<?php endif; ?>

				<div class="entry-description">
					<?php the_content(); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'twentyeleven' ) . '</span>', 'after' => '</div>' ) ); ?>
				</div><!-- .entry-description -->

			</div>

			<div id="gallery-nav">
				<div id="gallery-nav_controls">
					<div id="gallery-nav_prev">
						<?php previous_image_link( array( 60, 60 ) ); ?>
					</div>
					<div id="gallery-nav_next">
						<?php next_image_link( array( 60, 60 ) ); ?>
					</div>
				</div>
			</div>
			<a href="<?php echo get_permalink( $post->post_parent ); ?>" class="gallery-nav_return"><?php _e( 'Back to Gallery', 'manifest' ); ?></a>
		</div>
	</div>

	<?php comments_template(); ?>

	<?php endwhile; else: ?>

	<p><?php _e( 'Sorry, no posts matched your criteria.', 'manifest' ); ?></p>

	<?php endif; ?>

</div><!-- #core-content -->

<?php get_footer(); ?>