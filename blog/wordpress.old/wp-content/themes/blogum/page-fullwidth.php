<?php
/**
 * Template Name: Full width, no sidebar Template
 *
 * @package WordPress
 * @subpackage Blogum
 */
?>

<?php get_header(); ?>

<?php $content_width = 785; ?>

<div id="content" class="full-width" role="main">

	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', 'page' ); ?>

	<?php endwhile; // end of the loop. ?>

</div><!-- #content -->

<?php comments_template( '', true ); ?>

<?php get_footer(); ?>