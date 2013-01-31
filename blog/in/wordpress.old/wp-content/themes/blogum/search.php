<?php
/**
 * @package WordPress
 * @subpackage Blogum
 */
?>

<?php get_header(); ?>

<div id="content" role="main">

	<header class="archive-title clear">
		<hgroup>
			<div class="archive-title-meta"><h2><?php _e( 'Search Results', 'blogum' ); ?></h2></div>
			<div class="archive-title-name"><h1><?php the_search_query(); ?></h1></div>
		</hgroup>
	</header>

	<?php if ( have_posts() ) : ?>
	
		<?php while ( have_posts() ) : the_post(); ?>
		
			<?php get_template_part( 'content', get_post_format() ); ?>
		
		<?php endwhile; ?>
		
		<?php blogum_content_nav( 'nav-below' ); ?>
		
	<?php else :?>

		<article id="post-0" class="post no-results not-found">
			<header class="post-meta"><h1><?php _e( 'Nothing Found', 'blogum' ); ?></h1></header>
			<div class="post-content">
				<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'blogum' ); ?></p>
			</div>
		</archive>

	<?php endif; ?>

</div><!-- #content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>