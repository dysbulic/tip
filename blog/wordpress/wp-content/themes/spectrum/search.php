<?php
/**
 * @package WordPress
 * @subpackage Spectrum
 */

get_header(); ?>

	<?php if ( have_posts() ) : ?>

		<div class="main-title"><h3><?php printf( __( 'Search Results for &lsquo;%s&rsquo;', 'spectrum' ), get_search_query() ); ?></h3></div>

		<?php while ( have_posts() ) : the_post(); ?>

			<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
				<div class="entry">
					<h3 class="result"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
				</div>
				<div class="post-meta post-category">
					<p class="post-category-title"><strong><?php _e( 'Category:', 'spectrum' ); ?></strong></p>
					<p class="post-category-elements"><?php the_category( ', ' ); ?></p>
				</div>
				<?php the_tags( '<div class="post-meta post-tags"><p><strong>' . __('Tagged with:', 'spectrum') . '</strong></p><ul><li>','</li><li>','</li></ul></div>' ); ?>
		</div>

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