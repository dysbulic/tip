<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Chateau
 */
get_header(); ?>

	<div id="primary">
		<div id="content" class="clear-fix">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'single' ); ?>

			<nav id="nav-below" class="clear-fix">
				<h1 class="assistive-text"><?php _e( 'Post navigation', 'chateau' ); ?></h1>
				<span class="nav-previous"><?php previous_post_link( '%link', __( '&larr; Previous post', 'chateau' ) ); ?></span>
				<span class="nav-next"><?php next_post_link( '%link', __( 'Next post &rarr;', 'chateau' ) ); ?></span>
			</nav><!-- #nav-below -->

			<?php comments_template(); ?>

		<?php endwhile; ?>

		</div><!-- end #content -->
	</div><!-- end #primary -->

	<?php get_sidebar(); ?>

<?php get_footer(); ?>