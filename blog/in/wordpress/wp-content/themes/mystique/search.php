<?php
/**
 * The template for displaying Search result pages
 *
 * @package WordPress
 * @subpackage Mystique
 */

get_header(); ?>

 			<div id="content-container">
	 			<div id="content">
				<?php if ( have_posts() ) : ?>
					<h1 class="archive-title"><?php printf( __( 'Search Results for %s', 'mystique' ), '<span class="alt-text">' . get_search_query() . '</span>' ); ?></h1>
					<?php get_template_part( 'loop', 'search' ); ?>
				<?php else : ?>
					<div id="post-0" class="post no-results not-found">
						<h2 class="archive-title"><?php _e( 'Nothing Found', 'mystique' ); ?></h2>
						<div class="entry">
							<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'mystique' ); ?></p>
							<?php get_search_form(); ?>
						</div><!-- .entry -->
					</div><!-- #post-0 -->
				<?php endif; ?>
				</div><!-- #content -->
			</div><!-- #content-container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>