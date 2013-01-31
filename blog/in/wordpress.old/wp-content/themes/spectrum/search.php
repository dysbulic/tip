<?php
/**
 * @package Spectrum
 */

get_header(); ?>

	<?php if ( have_posts() ) : ?>

		<div class="main-title"><h3><?php printf( __( 'Search Results for &lsquo;%s&rsquo;', 'spectrum' ), get_search_query() ); ?></h3></div>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'archive' ); ?>

		<?php endwhile; ?>

	<?php else : ?>

		<div class="main-title"><h3><?php _e( 'No posts found. Please try a different search.', 'spectrum' ); ?></h3></div>

	<?php endif; ?>

</div>

<?php get_sidebar(); ?>

<div id="navigation">
	<p id="prev-page"><?php next_posts_link( __( 'Older Posts', 'spectrum' ) ); ?></p>
	<p id="next-page"><?php previous_posts_link( __( 'Newer Posts', 'spectrum' ) ); ?></p>
</div>

<?php get_footer(); ?>