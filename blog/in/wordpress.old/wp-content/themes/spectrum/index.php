<?php
/**
 * @package Spectrum
 */

get_header(); ?>

<?php if ( have_posts() ) : ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', get_post_format() ); ?>

	<?php endwhile; ?>

	<?php else : ?>

	<h3><?php _e( 'Not Found', 'spectrum' ); ?></h3>
	<p><?php _e( "Sorry, but you are looking for something that isn't here.", "spectrum" ); ?></p>

	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>

<div id="navigation">
	<p id="prev-page"><?php next_posts_link( __( 'Older Posts', 'spectrum' ) ); ?></p>
	<p id="next-page"><?php previous_posts_link( __( 'Newer Posts', 'spectrum' ) ); ?></p>
</div>


<?php get_footer(); ?>