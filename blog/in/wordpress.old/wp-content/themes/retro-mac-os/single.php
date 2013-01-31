<?php
/**
 * @package WordPress
 * @subpackage Retro MacOS
 */
get_header(); ?>

	<div id="content" class="narrowcolumn">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', 'single' ); ?>

		<div class="navigation">
			<div class="alignleft"><?php previous_post_link( '<div class="navigation_icon">%link</div>' ); ?></div>
			<div class="alignright"><?php next_post_link( '<div class="navigation_icon">%link</div>' ); ?></div>
		</div>

		<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p><?php _e( 'Sorry, no posts matched your criteria.', 'retro' ); ?></p>

	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>