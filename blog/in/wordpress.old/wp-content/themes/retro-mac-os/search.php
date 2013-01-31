<?php
/**
 * @package WordPress
 * @subpackage Retro MacOS
 */
get_header(); ?>

	<div id="content" class="narrowcolumn">

	<?php if ( have_posts() ) : ?>

		<h2 class="pagetitle">
			<?php printf( __( 'Search Results for: %s', 'retro' ), '<span>' . get_search_query() . '</span>' ); ?>
		</h2>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content' ); ?>

		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link( __( '&laquo; Previous Entries', 'retro' ) ); ?></div>
			<div class="alignright"><?php previous_posts_link( __( 'Next Entries &raquo;', 'retro' ) ); ?></div>
		</div>

	<?php else : ?>

		<h2 class="pagetitle"><?php _e( 'No posts found. Try a different search?', 'retro' ); ?></h2>
		<?php get_search_form(); ?>

	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>