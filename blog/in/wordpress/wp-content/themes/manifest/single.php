<?php
/**
 * @package WordPress
 * @subpackage Manifest
 */

get_header(); ?>

<div id="core-content">

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<?php get_template_part( 'content', 'single' ); ?>

	<?php comments_template(); ?>

	<div class="navigation">
		<div class="prev"><?php previous_post_link( '%link', __( '&laquo; Previous Post', 'manifest' ) ); ?></div>
		<div class="next"><?php next_post_link( '%link', __( 'Next Post &raquo;', 'manifest' ) ); ?></div>
	</div>

<?php endwhile; else: ?>

	<p><?php _e( 'Sorry, no posts matched your criteria.', 'manifest' ); ?></p>

<?php endif; ?>

</div><!-- #core-content -->

<?php get_footer(); ?>