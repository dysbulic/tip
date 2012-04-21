<?php
/**
 * Template Name: Full-width
 * Description: A full-width template with no side elements
 *
 * @package WordPress
 * @subpackage Retro MacOS
 */

get_header(); ?>

	<div id="content" class="widecolumn">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'page' ); ?>

			<?php comments_template( '', true ); ?>

		<?php endwhile; ?>

	</div><!-- #content -->

<?php get_footer(); ?>