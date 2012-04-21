<?php
/**
 * @package Vertigo
 */

get_header(); ?>

<div id="content" role="main">

<?php if ( have_posts() ) : ?>

	<header class="page-header">
		<div class="pagetype"><?php printf( __( '<p><span>Search Results for: "%s".</span></p>', 'vertigo' ), get_search_query() ); ?></div>
	</header>

	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', get_post_format() ); ?>

	<?php endwhile; ?>

<?php else : ?>

	<article id="post-0" class="post no-results not-found">
		<header class="page-header">
			<h1 class="pagetype"><?php _e( 'Nothing Found', 'vertigo' ); ?></h1>
		</header>

		<div class="entry-content">
			<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'vertigo' ); ?></p>
			<?php get_search_form(); ?>
		</div><!-- .entry-content -->
	</article><!-- #post-0 -->

<?php endif; ?>

</div><!-- #content -->

<?php get_footer(); ?>