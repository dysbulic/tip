<?php
/**
 * @package WordPress
 * @subpackage Blogum
 */

get_header(); ?>

<div id="content" role="main">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', get_post_format() ); ?>

		<?php endwhile; ?>

		<?php blogum_content_nav( 'nav-below' ); ?>

	<?php else : ?>

		<article id="post-0" class="post no-results not-found">
			<header class="post-meta">
				<h1><?php _e( 'Nothing Found', 'blogum' ); ?></h1>
			</header><!-- .post-meta -->

			<div class="post-content">
				<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'blogum' ); ?></p>
				<?php get_search_form(); ?>
			</div><!-- .entry-content -->
		</article><!-- #post-0 -->

	<?php endif; ?>
</div><!-- #content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>