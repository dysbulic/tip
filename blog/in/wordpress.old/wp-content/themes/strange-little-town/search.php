<?php
/**
 * @package WordPress
 * @subpackage StrangeLittleTown
 */
?>

<?php get_header(); ?>

<div id="content">

	<header id="introduction" class="contain">
		<h1 id="page-title"><?php _e( 'Search Results', 'strange-little-town' ); ?></h1>
		<?php if ( have_posts() ) : ?>
			<h2 id="page-tagline"><?php printf( __( 'Search Results for: %1$s', 'strange-little-town' ), '<span>' . get_search_query() . '</span>' ); ?></h2>
		<?php else: ?>
			<h2 id="page-tagline"><?php _e( 'Sorry, but nothing matched your search. Please try again with some different keywords.', 'strange-little-town' ); ?></h2>
		<?php endif; ?>
		<?php get_search_form(); ?>
	</header>

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', get_post_format() ); ?>

		<?php endwhile; ?>

			<?php get_template_part( 'nav', 'posts' ); ?>

	<?php endif; ?>

</div><!-- content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>