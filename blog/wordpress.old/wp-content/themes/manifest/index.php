<?php
/**
 * @package WordPress
 * @subpackage Manifest
 */

get_header(); ?>

<div id="core-content" class="hfeed">

	<?php if ( have_posts() ) : ?>

		<?php /* Start the Loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>
		
			<?php get_template_part( 'content', get_post_format() ); ?>

	<?php endwhile; ?>

	<div class="navigation">
		<div class="prev"><?php next_posts_link( __( '&laquo; Older', 'manifest' ) ); ?></div>
		<div class="next"><?php previous_posts_link( __( 'Newer &raquo;', 'manifest' ) ); ?></div>
	</div>

	<?php else : ?>

	<h2><?php _e( 'Not Found', 'manifest' ); ?></h2>
	<p><?php _e( "Sorry, but you are looking for something that isn't here.", 'manifest' ); ?></p>

	<?php endif; ?>

</div><!-- #core-content -->

<?php get_footer(); ?>