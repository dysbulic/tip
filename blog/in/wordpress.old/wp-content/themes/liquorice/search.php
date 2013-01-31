<?php
/**
 * The template for displaying Search result pages.
 *
 * @package WordPress
 * @subpackage Liquorice
 */

get_header(); ?>

	<div id="primary-content">
			<?php if ( have_posts() ) : ?>
				<h1 class="archive-title"><?php printf( __( 'Search Results for: %s', 'liquorice' ), '<span>' . get_search_query() . '</span>' );	?></h1>
				<?php get_template_part( 'loop', 'search' ); ?>
			<?php else : ?>
				<div id="post-0" class="post no-results not-found">
					<h2 class="archive-title"><?php _e( 'Nothing Found', 'liquorice' ); ?></h2>
					<div class="entry">
						<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'liquorice' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry -->
				</div><!-- #post-0 -->
			<?php endif; ?>
	</div><!-- #primary-content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>