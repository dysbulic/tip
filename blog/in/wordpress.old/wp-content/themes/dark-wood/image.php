<?php
/**
 * @package WordPress
 * @subpackage Dark Wood
 */
?>
<?php get_header(); ?>

<div id="container">

	<div id="content">

		<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>

		<div <?php post_class( 'post' ); ?>>

			<h2 class="post-title"><a href="<?php echo get_permalink( $post->post_parent ); ?>" rev="attachment"><?php echo get_the_title( $post->post_parent ); ?></a> &raquo; <?php the_title(); ?></h2>

			<div class="post-content">
				<p class="attachment"><a href="<?php echo wp_get_attachment_url( $post->ID ); ?>"><?php echo wp_get_attachment_image( $post->ID, 'auto' ); ?></a></p>
				<?php the_content( __( 'Read more&hellip;' ) ); ?>
				<div class="navigation image clear">
					<div class="alignleft"><?php previous_image_link(); ?></div>
					<div class="alignright"><?php next_image_link(); ?></div>
				</div>
			</div>

			<div class="postmeta">
				<span class="date"><img src="<?php bloginfo( 'template_url' ); ?>/images/calendaricon.png" alt="" />&nbsp;<?php the_time( get_option( 'date_format' ) ); ?></span>
				<span class="author"><img src="<?php bloginfo( 'template_url' ); ?>/images/authoricon.png" alt="" />&nbsp;<?php the_author(); ?></span>
				<?php edit_post_link( __( 'Edit this' ), '<span class="edit">', '</span>' ); ?>
			</div><!-- /postmeta -->

		</div><!-- /post -->

		<?php comments_template( '', true ); ?>

		<?php endwhile; ?>

		<?php else : ?>

		<h2><?php _e('404 - Page not found'); ?></h2>
		<p><?php _e( 'Oops! I cannot find what you are looking for. Please try again with a different keyword.', 'darkwood' ); ?></p>

		<?php endif; ?>

	</div><!-- /content -->

	<?php get_sidebar(); ?>

</div><!-- /container -->

<?php get_footer(); ?>