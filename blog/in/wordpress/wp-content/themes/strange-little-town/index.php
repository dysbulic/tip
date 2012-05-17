<?php
/**
 * @package WordPress
 * @subpackage StrangeLittleTown
 */
?>

<?php get_header(); ?>

<div id="content">

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', get_post_format() ); ?>

		<?php endwhile; ?>

			<?php get_template_part( 'nav', 'posts' ); ?>

	<?php else : ?>

		<?php get_template_part( 'content', '404' ); ?>

	<?php endif; ?>

</div><!-- content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>