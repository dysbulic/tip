<?php
/**
 * @package WordPress
 * @subpackage Retro MacOS
 */
get_header(); ?>

	<div id="content" class="narrowcolumn">

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'page' ); ?>

		<?php endwhile; endif; ?>

	</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>