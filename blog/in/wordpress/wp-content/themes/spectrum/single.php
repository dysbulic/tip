<?php
/**
 * @package Spectrum
 */

get_header(); ?>

	<?php if ( have_posts() ) : while (have_posts()) : the_post(); ?>

	<?php get_template_part( 'content', 'single' ); ?>

	<?php comments_template(); ?>

	<?php endwhile; else: ?>

	<h3><?php _e( 'Not Found', 'spectrum' ); ?></h3>
	<p><?php _e( 'Sorry, no posts matched your criteria.', 'spectrum' ); ?></p>

	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>