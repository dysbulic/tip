<?php
/**
 * The template for displaying single posts.
 *
 * @package WordPress
 * @subpackage Liquorice
 */

get_header(); ?>

	<div id="primary-content">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<div class="post-navigation">
				<div class="nav-previous">
					<?php previous_post_link( '%link', '<span class="meta-nav"> ' . _x( '&larr;', 'Previous post link', 'liquorice' ) . '</span> %title' ); ?>
				</div>
				<div class="nav-next">
					<?php next_post_link( '%link', '%title <span class="meta-nav"> ' . _x( '&rarr;', 'Next post link', 'liquorice' ) . '</span>' ); ?>
				</div>
			</div><!-- .post-navigation -->

			<?php get_template_part( 'content', get_post_format() ); ?>

			<div class="post-navigation">
				<div class="nav-previous">
					<?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '', 'Previous post link', 'liquorice' ) . '</span> %title' ); ?>
				</div>
				<div class="nav-next">
					<?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '', 'Next post link', 'liquorice' ) . '</span>' ); ?>
				</div>
			</div><!-- .post-navigation -->

			<?php comments_template(); ?>

		<?php endwhile; else: ?>
			<p><?php _e( 'Sorry, but nothing matched your search criteria.', 'liquorice' ); ?></p>

		<?php endif; ?>
	</div><!-- #primary-content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>