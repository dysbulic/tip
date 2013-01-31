<?php
/**
 * @package Adventure_Journal
 */

get_header(); ?>

	<div id="content" class="clearfix">
		<div id="main-content">
			<?php if ( have_posts() ) : ?>
				<h1 class="entry-title"><?php printf( __( 'Search Results for: %s', 'adventurejournal' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
				<?php get_template_part( 'loop', 'search' ); ?>
			<?php else : ?>
				<div id="post-0" class="post no-results not-found">
					<h2><?php _e( 'Nothing Found', 'adventurejournal' ); ?></h2>
					<div class="entry-content">
						<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'adventurejournal' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</div><!-- #post-0 -->
			<?php endif; ?>
		</div><!-- #main-content -->

		<?php get_sidebar(); ?>
	</div><!-- #content -->

<?php get_footer(); ?>