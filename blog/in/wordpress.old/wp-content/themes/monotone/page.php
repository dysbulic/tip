<?php
/**
 * @package WordPress
 * @subpackage Monotone
 */
get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<div class="image">
		<div class="nav prev"><?php next_post_link( '%link', '&lsaquo;' ); ?></div>
		<?php monotone_the_image(); ?>
		<div class="nav next"><?php previous_post_link( '%link', '&rsaquo;' ); ?></div>
	</div><!-- #image -->
	<?php get_template_part( 'post' ); ?>
<?php endwhile; else : ?>
	<h2><?php _e( 'Not Found', 'monotone' ); ?></h2>
	<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'monotone' ); ?></p>
	<?php get_search_form(); ?>
<?php endif; ?>

<?php get_footer(); ?>