<?php
/**
 * @package WordPress
 * @subpackage Pink Touch 2
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php get_template_part( 'content' ); ?>

	<?php comments_template( '', true ); ?>

<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>