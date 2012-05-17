<?php
/**
 * @package WordPress
 * @subpackage StrangeLittleTown
 */

$content_width = 900;
?>

<?php get_header(); ?>

<div id="content">

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="contain">
					<?php the_title( '<h1>', '</h1>' ); ?>

					<span class="about-this-post"><?php
						edit_post_link( __( 'Edit', 'strange-little-town' ) );

						/* translators: %1$s is the date. %2$s is the author's name. */
						printf( __( 'Uploaded %1$s by %2$s', 'strange-little-town' ), '<span class="date-published">' . esc_html( get_the_time( get_option( 'date_format' ) ) ) . '</span>', '<span>' . esc_html( get_the_author() ) . '</span>' );
					?></span>
				</header>

				<div id="image"><a href="<?php echo wp_get_attachment_url( get_the_ID() ); ?>"><?php echo wp_get_attachment_image( $post->ID, array( $content_width, $content_width ) ); ?></a></div>

				<?php if ( ! empty( $post->post_excerpt ) ) : ?>
					<div class="wp-caption-text"><?php the_excerpt(); ?></div>
				<?php endif; ?>

				<?php the_content(); ?>

			</article>

			<nav id="nav-image" class="paged-navigation contain">
				<h1 class="assistive-text"><?php _e( 'Image navigation', 'strange-little-town' ); ?></h1>
				<div class="nav-older"><?php previous_image_link( false, __( '&larr; Previous Image', 'strange-little-town' ) ); ?></div>
				<div class="nav-newer"><?php next_image_link( false, __( 'Next Image &rarr;', 'strange-little-town' ) ); ?></div>
			</nav>

			<?php comments_template(); ?>

		<?php endwhile; ?>

	<?php else: ?>

		<?php get_template_part( 'content', '404' ); ?>

	<?php endif; ?>

</div><!-- content -->
<?php get_footer(); ?>