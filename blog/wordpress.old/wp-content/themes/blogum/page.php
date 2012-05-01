<?php
/**
 * @package WordPress
 * @subpackage Blogum
 */
?>

<?php get_header(); ?>

<div id="content" role="main">

	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', 'page' ); ?>

	<?php endwhile; // end of the loop. ?>

</div><!-- #content -->

<?php get_sidebar(); ?>

<?php comments_template( '', true ); ?>

<?php get_footer(); ?>